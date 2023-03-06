<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use SQLite3Result;

/**
 * @see \MallardDuck\MtgCardsSdk\Enums\CardType
 */
class GenerateCardSupertypeAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'CardSupertype';

    public function query(): void
    {
        $this->results = $this->db->query(<<<HERE
SELECT DISTINCT
    substr(supertypes, 0, instr(supertypes || ',', ',')) AS type_value
FROM
    cards
WHERE
    type_value IS NOT NULL;
HERE
);
    }
}