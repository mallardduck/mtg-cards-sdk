<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use SQLite3;
use SQLite3Result;
use function Symfony\Component\String\u;

/**
 * @see \MallardDuck\MtgCardsSdk\Enums\CardType
 */
class GenerateCardTypeAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'CardType';

    protected SQLite3Result $results;

    public function __construct(
        SQLite3 $db,
    ) {
        $this->results = $db->query(<<<HERE
SELECT DISTINCT
    substr(lower(types), 0, instr(types || ',', ',')) AS type_value
FROM
    cards
WHERE
    type_value IS NOT NULL;
HERE
        );

    }

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
}