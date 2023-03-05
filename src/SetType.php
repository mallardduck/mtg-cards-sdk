<?php

namespace MallardDuck\MtgCardsSdk;

enum SetType: string
{
    case Masters = 'masters';
    case DraftInnovation = 'draft_innovation';
    case Expansion = 'expansion';
    case Commander = 'commander';
    case Masterpiece = 'masterpiece';
    case Promo = 'promo';
}
