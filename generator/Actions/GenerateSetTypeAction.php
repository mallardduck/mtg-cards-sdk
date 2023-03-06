<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use MallardDuck\MtgCardsSdk\Enums\SetType;
use SQLite3Result;
use function Symfony\Component\String\u;

/**
 * @see SetType
 */
class GenerateSetTypeAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'SetType';

    public function query(): void
    {
        $this->results = $this->db->query('SELECT DISTINCT type FROM sets;');
    }

    public function __invoke(): void {
        $this->query();
        $enumDetails = [];
        while ($row = $this->results->fetchArray()) {
            // TODO: Add a filter here too for custom string handling...
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
}