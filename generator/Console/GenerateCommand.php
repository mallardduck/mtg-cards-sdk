<?php

namespace MallardDuck\MtgCardsSdk\Generator\Console;

use MallardDuck\MtgCardsSdk\Generator\Actions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $printingsDb = new \SQLite3(dirname(__DIR__, 2) . '/blobs/AllPrintings.sqlite');
        (new Actions\GenerateBlocksAction($printingsDb))();
        (new Actions\GenerateSetTypeAction($printingsDb))();
        (new Actions\GenerateCardTypeAction($printingsDb))();
        (new Actions\GenerateCardSupertypeAction($printingsDb))();
        (new Actions\GenerateCardSubtypeAction($printingsDb))();
        (new Actions\GenerateCardKeywordAction($printingsDb))();
    }
}