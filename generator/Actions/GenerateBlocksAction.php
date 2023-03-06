<?php

namespace MallardDuck\MtgCardsSdk\Generator\Actions;

use MallardDuck\MtgCardsSdk\Enums\Block;
use MallardDuck\MtgCardsSdk\Generator\Events;
use MallardDuck\MtgCardsSdk\Generator\HooksEmitter\HooksEmitter;
use Sabre\Event\Emitter;
use SQLite3;
use SQLite3Result;
use function Symfony\Component\String\u;

/**
 * @see Block
 */
class GenerateBlocksAction extends AbstractGenerateEnumAction
{
    protected string $rendersClass = 'Block';

    public static function registerHooks(HooksEmitter $emitter): void
    {
        $emitter->addFilter(
            Events::PreEnumFormatSkip->eventSuffixedKey(static::class_basename(static::class)),
            function (array $value): array|bool {
                return $value['value'] === null;
            },
        );
    }

    public function query(): void {
        $this->results = $this->db->query('SELECT DISTINCT block FROM sets;');
    }
}