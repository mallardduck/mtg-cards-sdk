<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use SQLite3;
use SQLite3Result;
use function Symfony\Component\String\u;

/**
 * @see \MallardDuck\MtgCardsSdk\Enums\CardType
 */
class GenerateCardSubtypeAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'CardSubtype';

    protected SQLite3Result $results;

    public function __construct(
        SQLite3 $db,
    ) {
        $this->results = $db->query(<<<HERE
SELECT DISTINCT
    substr(subtypes, 0, instr(subtypes || ',', ',')) AS type_value
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
            if ($row[0] === 'Elemental?') continue;
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
}