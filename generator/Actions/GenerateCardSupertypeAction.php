<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use MallardDuck\MtgCardsSdk\Generator\Events;
use MallardDuck\MtgCardsSdk\Generator\HooksEmitter\HooksEmitter;
use SQLite3Result;
use function Symfony\Component\String\u;

/**
 * @see \MallardDuck\MtgCardsSdk\Enums\CardType
 */
class GenerateCardSupertypeAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'CardSupertype';

    public static function getEnumMainColumn(): string
    {
        return 'type_value';
    }

    public function query(): void
    {
        $this->results = $this->db->query(<<<HERE
SELECT DISTINCT
    substr(supertypes, 0, instr(supertypes || ',', ',')) AS {$this->getEnumMainColumn()}
FROM
    cards
WHERE
    {$this->getEnumMainColumn()} IS NOT NULL;
HERE
);
    }
}