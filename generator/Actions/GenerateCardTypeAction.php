<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use MallardDuck\MtgCardsSdk\Generator\Events;
use MallardDuck\MtgCardsSdk\Generator\HooksEmitter\HooksEmitter;
use SQLite3Result;
use function Symfony\Component\String\u;

/**
 * @see \MallardDuck\MtgCardsSdk\Enums\CardType
 */
class GenerateCardTypeAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'CardType';

    public static function getEnumMainColumn(): string
    {
        return 'type_value';
    }

    public static function registerHooks(HooksEmitter $emitter): void
    {
        // This filter ensure we have the extra data for later rendering...
        $emitter->addFilter(
            Events::PreEnumFormat->eventSuffixedKey(static::class_basename(static::class)),
            function (array $data): array {
                return [
                    'name' => u($data[static::getEnumMainColumn()])->camel()->title()->toString(),
                    'label' => u($data[static::getEnumMainColumn()])->replace('_', ' ')->title()->toString(),
                    'value' => u($data[static::getEnumMainColumn()])->snake()->toString(),
                ];
            },
        );
    }

    public function query(): void
    {
        $this->results = $this->db->query(<<<HERE
SELECT DISTINCT
    substr(lower(types), 0, instr(types || ',', ',')) AS {$this->getEnumMainColumn()}
FROM
    cards
WHERE
    {$this->getEnumMainColumn()} IS NOT NULL;
HERE
        );
    }
}