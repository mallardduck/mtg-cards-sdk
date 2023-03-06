<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use MallardDuck\MtgCardsSdk\Enums\SetType;
use MallardDuck\MtgCardsSdk\Generator\Events;
use MallardDuck\MtgCardsSdk\Generator\HooksEmitter\HooksEmitter;
use SQLite3Result;
use function Symfony\Component\String\u;

/**
 * @see SetType
 */
class GenerateSetTypeAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'SetType';
    public static function getEnumMainColumn(): string
    {
        return 'type';
    }

    public static function registerHooks(HooksEmitter $emitter): void
    {
        $emitter->addFilter(
            Events::PreEnumFormat->eventSuffixedKey(static::class_basename(static::class)),
            function (array $value): array {
                return [
                    'name' => u($value[static::getEnumMainColumn()])->camel()->title()->toString(),
                    'label' => u($value[static::getEnumMainColumn()])->replace('_', ' ')->title()->toString(),
                    'value'=> $value[static::getEnumMainColumn()],
                ];
            },
        );
    }

    public function query(): void
    {
        $this->results = $this->db->query(<<<NOW
SELECT DISTINCT
    {$this->getEnumMainColumn()}
FROM
    sets
WHERE
    {$this->getEnumMainColumn()} IS NOT NULL;
NOW
        );
    }
}