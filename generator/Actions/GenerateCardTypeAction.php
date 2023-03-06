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
        return 'types';
    }

    public static function registerHooks(HooksEmitter $emitter): void
    {
        // This filter ensure we have the extra data for later rendering...
        $emitter->addFilter(
            Events::PreEnumFormat->eventSuffixedKey(static::class_basename(static::class)),
            function (array $data): array {
                return [
                    'name' => u($data['type_value'])->camel()->title()->toString(),
                    'label' => u($data['type_value'])->replace('_', ' ')->title()->toString(),
                    'value'=> $data['type_value'],
                ];
            },
        );
    }

    public function query(): void
    {
        $this->results = $this->db->query(<<<HERE
SELECT DISTINCT
    substr(lower({$this->getEnumMainColumn()}), 0, instr({$this->getEnumMainColumn()} || ',', ',')) AS type_value
FROM
    cards
WHERE
    type_value IS NOT NULL;
HERE
        );
    }
}