<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use SQLite3;
use SQLite3Result;
use function Symfony\Component\String\u;

/**
 * @see \MallardDuck\MtgCardsSdk\Enums\CardType
 */
class GenerateCardKeywordAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'CardKeyword';

    protected SQLite3Result $results;

    public function __construct(
        SQLite3 $db,
    ) {
        $this->results = $db->query(<<<HERE
SELECT DISTINCT
    substr(keywords, 0, instr(keywords || ',', ',')) AS type_value
FROM
    cards
WHERE
    type_value IS NOT NULL;
HERE
        );
    }
}