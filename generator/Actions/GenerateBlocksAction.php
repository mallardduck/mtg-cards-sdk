<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use MallardDuck\MtgCardsSdk\Enums\Block;
use MallardDuck\MtgCardsSdk\Generator\Events;
use MallardDuck\MtgCardsSdk\Generator\HooksEmitter\HooksEmitter;

/**
 * @see Block
 */
class GenerateBlocksAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'Block';

    public static function getEnumMainColumn(): string
    {
        return 'block';
    }

    public static function registerHooks(HooksEmitter $emitter): void
    {
        $emitter->addFilter(
            Events::PreEnumFormatSkip->eventSuffixedKey(static::class_basename(static::class)),
            function (array $row): bool {
                return $row[static::getEnumMainColumn()] === null;
            },
        );
    }

    public function query(): void {
        $this->results = $this->db->query(sprintf(
            'SELECT DISTINCT %s FROM sets;',
            $this->getEnumMainColumn()
        ));
    }
}