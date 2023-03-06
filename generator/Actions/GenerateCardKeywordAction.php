<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use SQLite3;
use SQLite3Result;
use function Symfony\Component\String\u;

/**
 * @see \MallardDuck\MtgCardsSdk\Enums\CardKeyword
 */
class GenerateCardKeywordAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'CardKeyword';

    public static function getEnumMainColumn(): string
    {
        return 'type_value';
    }

    public function query(): void
    {
        $this->results = $this->db->query(<<<HERE
SELECT DISTINCT
    substr(keywords, 0, instr(keywords || ',', ',')) AS {$this->getEnumMainColumn()}
FROM
    cards
WHERE
    {$this->getEnumMainColumn()} IS NOT NULL;
HERE
        );
    }
}