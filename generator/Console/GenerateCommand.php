<?php

namespace MallardDuck\MtgCardsSdk\Generator\Console;

use MallardDuck\MtgCardsSdk\Generator\Actions\GenerateSetTypeAction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $printingsDb = new \SQLite3(dirname(__DIR__, 2) . '/blobs/AllPrintings.sqlite');
        (new GenerateSetTypeAction($printingsDb))();
    }
}