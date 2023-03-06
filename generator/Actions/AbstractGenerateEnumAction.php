<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use MallardDuck\MtgCardsSdk\Generator\Events;
use MallardDuck\MtgCardsSdk\Generator\HooksEmitter\HooksEmitter;
use Nette\PhpGenerator\EnumType;
use function Symfony\Component\String\u;

abstract class AbstractGenerateEnumAction extends AbstractRenderAction
{
    protected ?string $subNamespace = 'Enums';

    public function __invoke(): void {
        $emitter = HooksEmitter::getInstance();
        $this->query();
        $enumDetails = [];
        while ($row = $this->results->fetchArray()) {
            if ($emitter->hasFilter(Events::PreEnumFormatSkip->value . '_' . $this->getBasename())) {
                $skipRow = $emitter->applyFilters(
                    Events::PreEnumFormatSkip->value . '_' . $this->getBasename(),
                    [
                        'context' => $this::class_basename($this),
                        'value' => $row[0],
                    ]
                ) ?? false;
                if ($skipRow) continue;
            }
            // TODO: Add a filter here too for custom string handling...
            $enumDetails[] = [
                'name' => u($row[0])->camel()->title()->toString(),
                'label'=> $row[0],
                'value' => u($row[0])->snake()->toString(),
            ];
        }
        usort(
            $enumDetails,
            static fn ($a, $b) => $a <=> $b,
        );
        $this->save($this->renderEnum($enumDetails));
    }

    /**
     * @param array{name: string, label: string, value: string}[] $details
     * @return string
     */
    public function renderEnum(array $details): string
    {
        $enum = new EnumType($this->rendersClass);
        try {
            $enum->addMethod('label')
                ->setReturnType('string')
                ->setBody('return static::getLabel($this);');
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
            $enum->addComment("@see " . $this->class_basename($this));
            $namespace = $this->getNamespace();
            $namespace->add($enum);
            $namespace->addUse(self::class);
        } catch (\Throwable $throwable) {
            dd($throwable, $this->getBasename(),);
        }

        return (string) $namespace;
    }
}