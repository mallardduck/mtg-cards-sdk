<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use SQLite3Result;
use function Symfony\Component\String\u;

/**
 * @see \MallardDuck\MtgCardsSdk\Enums\CardType
 */
class GenerateCardTypeAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'CardType';

    public function query(): void
    {
        $this->results = $this->db->query(<<<HERE
SELECT DISTINCT
    substr(lower(types), 0, instr(types || ',', ',')) AS type_value
FROM
    cards
WHERE
    type_value IS NOT NULL;
HERE
        );

    }

    public function __invoke(): void {
        $this->query();
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
}