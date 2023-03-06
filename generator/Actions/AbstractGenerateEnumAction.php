<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use MallardDuck\MtgCardsSdk\Generator\Events;
use MallardDuck\MtgCardsSdk\Generator\HooksEmitter\HooksEmitter;
use Nette\PhpGenerator\EnumType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PsrPrinter;
use function Symfony\Component\String\u;

abstract class AbstractGenerateEnumAction extends AbstractRenderAction
{
    protected ?string $subNamespace = 'Enums';

    abstract public static function getEnumMainColumn(): string;

    public function __invoke(): void {
        $emitter = HooksEmitter::getInstance();
        $this->query();
        $enumDetails = [];
        while ($row = $this->getResultsRowArray()) {
            $skipRowTarget = Events::PreEnumFormatSkip->eventSuffixedKey($this->getBasename());
            if ($emitter->hasFilter($skipRowTarget)) {
                $skipRow = $emitter->applyFilters(
                    $skipRowTarget,
                    $row
                ) ?? false;
                if ($skipRow) continue;
            }
            $prepareEnumTarget = Events::PreEnumFormat->eventSuffixedKey($this->getBasename());
            if ($emitter->hasAction($prepareEnumTarget)) {
                $prepared = $emitter->applyFilters(
                    $prepareEnumTarget,
                    $row
                );
                if ($row !== $prepared) {
                    $enumDetails[] = $prepared;
                } else {
                    $enumDetails[] = $this->defaultEnumPrepare($row);
                }
            } else {
                $enumDetails[] = $this->defaultEnumPrepare($row);
            }
        }
        usort(
            $enumDetails,
            static fn ($a, $b) => $a <=> $b,
        );
        $this->save($this->renderEnum($enumDetails));
    }

    protected function defaultEnumPrepare(array $value): array
    {
        return [
            'name' => u($value[$this->getEnumMainColumn()])->camel()->title()->toString(),
            'label'=> $value[$this->getEnumMainColumn()],
            'value' => u($value[$this->getEnumMainColumn()])->snake()->toString(),
        ];
    }

    /**
     * @param array{name: string, label: string, value: string}[] $details
     * @return string
     */
    public function renderEnum(array $details): string
    {
        $emitter = HooksEmitter::getInstance();
        $enum = new EnumType($this->rendersClass);
        try {
            $enum->addMethod('tryFromLabel')
                ->setStatic()
                ->setReturnType('self')
                ->setBody("return {$this->rendersClass}::tryFrom(u(\$label)->snake()->toString());")
                ->addParameter('label')
                ->setType('string')
            ;
            $enum->addMethod('label')
                ->setReturnType('string')
                ->setBody("return {$this->rendersClass}::getLabel(\$this);");
            $getLabelMethod = $enum->addMethod('getLabel')
                ->setStatic(true)
                ->setReturnType('string');
            $getLabelMethod->addParameter('value')
                ->setType('self');
            $matchCases = [];
            foreach ($details as $case) {
                $enum->addCase($case['name'], $case['value']);
                // Also push this into set body stack.
                $matchCases[sprintf('%s::%s', $this->rendersClass, $case['name'])] = $case['label'];
            }
            $getLabelMethod->setBody(sprintf(
                'return %s;',
                parent::render('match.twig', [
                    'cases' => $matchCases,
                ])
            ));
            $enum->addComment("@see \\" . static::class);
            $preEnumInsert = Events::PreEnumInserted->eventSuffixedKey($this->getBasename());
            if ($emitter->hasAction($preEnumInsert)) {
                $emitter->doAction(
                    $preEnumInsert,
                    $enum,
                    $details,
                );
            }
            $namespace = $this->getNamespace();
            $namespace->addUseFunction('Symfony\Component\String\u');
            $namespace->add($enum);
        } catch (\Throwable $throwable) {
            dd($throwable, $this->getBasename(),);
        }

        $file = new PhpFile;
        $file->setStrictTypes();
        $file->addNamespace($namespace);

        return (new PsrPrinter)->printFile($file);
    }
}