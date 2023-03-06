<?php

namespace MallardDuck\MtgCardsSdk\Generator\HooksEmitter;

enum Events: string
{
    case AfterEmitterSetup = 'after_emitter_setup';
}