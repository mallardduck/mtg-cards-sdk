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
}