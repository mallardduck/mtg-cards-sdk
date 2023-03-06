<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use SQLite3;
use SQLite3Result;
use function Symfony\Component\String\u;

/**
 * @see \MallardDuck\MtgCardsSdk\Enums\CardType
 */
class GenerateCardSupertypeAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'CardSupertype';

    protected SQLite3Result $results;

    public function __construct(
        SQLite3 $db,
    ) {
        $this->results = $db->query(<<<HERE
SELECT DISTINCT
    substr(supertypes, 0, instr(supertypes || ',', ',')) AS type_value
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