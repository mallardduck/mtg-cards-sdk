<?php

namespace MallardDuck\MtgCardsSdk\Generator\HooksEmitter;

use MallardDuck\MtgCardsSdk\Generator\EnumHelpers;

enum Events: string
{
    use EnumHelpers;

    case AfterEmitterSetup = 'after_emitter_setup';
}