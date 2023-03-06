<?php

namespace MallardDuck\MtgCardsSdk\Generator;

use MallardDuck\MtgCardsSdk\Generator\Actions;
use MallardDuck\MtgCardsSdk\Generator\HooksEmitter\HooksEmitter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
    private const ACTIONS = [
        Actions\GenerateBlocksAction::class,
        Actions\GenerateSetTypeAction::class,
        Actions\GenerateSetCodeAction::class,
        Actions\GenerateCardTypeAction::class,
        Actions\GenerateCardSupertypeAction::class,
        Actions\GenerateCardSubtypeAction::class,
        Actions\GenerateCardKeywordAction::class,
        Actions\GenerateCardBorderColorAction::class,
        Actions\GenerateSetsAction::class,
    ];

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $emitter = HooksEmitter::getInstance();
        $printingsDb = new \SQLite3(dirname(__DIR__, 1) . '/blobs/AllPrintings.sqlite');
        $actionClasses = [];
        foreach(self::ACTIONS as $actionClass) {
            /**
             * @var Actions\AbstractRenderAction $instance
             */
            $instance = new $actionClass($printingsDb);
            $instance::registerHooks($emitter);
            $actionClasses[] = $instance;
        }
        foreach($actionClasses as $actionClass) {
            $actionClass();
        }
    }
}