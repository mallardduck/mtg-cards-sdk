<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use Nette\PhpGenerator\EnumType;
use SQLite3;
use SQLite3Result;
use function Symfony\Component\String\u;

abstract class AbstractGenerateEnumAction extends AbstractRenderAction
{
    protected ?string $subNamespace = 'Enums';

    protected SQLite3Result $results;

    public function __invoke() {
        $enumDetails = [];
        while ($row = $this->results->fetchArray()) {
            $enumDetails[] = [
                'name' => u($row[0])->camel()->title()->toString(),
                'label' => u($row[0])->replace('_', ' ')->title()->toString(),
                'value'=> $row[0],
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
        $namespace = $this->getNamespace();
        $enum = new EnumType($this->rendersClass);
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
        $namespace->add($enum);

        return (string) $namespace;
    }
}