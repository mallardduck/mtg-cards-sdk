<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use MallardDuck\MtgCardsSdk\Generator\Events;
use MallardDuck\MtgCardsSdk\Generator\HooksEmitter\HooksEmitter;

/**
 * @see \MallardDuck\MtgCardsSdk\Enums\CardType
 */
class GenerateCardSubtypeAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'CardSubtype';

    public static function registerHooks(HooksEmitter $emitter): void
    {
        $emitter->addFilter(
            Events::PreEnumFormatSkip->eventSuffixedKey(static::class_basename(static::class)),
            function (array $value): bool|array {
                return ($value['value'] === 'Elemental?' || empty($value['value']));
            },
            2
        );
    }

    public function query(): void
    {
        $this->results = $this->db->query(<<<HERE
SELECT DISTINCT
    substr(subtypes, 0, instr(subtypes || ',', ',')) AS type_value
FROM
    cards
WHERE
    type_value IS NOT NULL;
HERE
        );
    }
}