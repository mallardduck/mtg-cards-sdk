<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use MallardDuck\MtgCardsSdk\Generator\Events;
use MallardDuck\MtgCardsSdk\Generator\HooksEmitter\HooksEmitter;

/**
 * @see \MallardDuck\MtgCardsSdk\Enums\CardSubtype
 */
class GenerateCardSubtypeAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'CardSubtype';

    public static function getEnumMainColumn(): string
    {
        return 'type_value';
    }

    public static function registerHooks(HooksEmitter $emitter): void
    {
        $emitter->addFilter(
            Events::PreEnumFormatSkip->eventSuffixedKey(static::class_basename(static::class)),
            function (array $value): bool|array {
                return ($value['type_value'] === 'Elemental?' || empty($value['type_value']));
            },
        );
    }

    public function query(): void
    {
        $this->results = $this->db->query(<<<HERE
SELECT DISTINCT
    substr(subtypes, 0, instr(subtypes || ',', ',')) AS {$this->getEnumMainColumn()}
FROM
    cards
WHERE
    {$this->getEnumMainColumn()} IS NOT NULL;
HERE
        );
    }
}