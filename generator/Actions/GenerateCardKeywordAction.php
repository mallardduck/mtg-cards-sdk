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

    public function query(): void
    {
        $this->results = $this->db->query(<<<HERE
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