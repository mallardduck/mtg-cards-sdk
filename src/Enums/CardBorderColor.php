<?php

declare(strict_types=1);

namespace MallardDuck\MtgCardsSdk\Enums;

use function Symfony\Component\String\u;

/**
 * @see \MallardDuck\MtgCardsSdk\Generator\Actions\GenerateCardBorderColorAction
 */
enum CardBorderColor: string
{
    case Black = 'black';
    case Borderless = 'borderless';
    case Gold = 'gold';
    case Silver = 'silver';
    case White = 'white';

    public static function tryFromLabel(string $label): self
    {
        return CardBorderColor::tryFrom(u($label)->snake()->toString());
    }

    public function label(): string
    {
        return CardBorderColor::getLabel($this);
    }

    public static function getLabel(self $value): string
    {
        return match ($value) {
            CardBorderColor::Black => "black",
            CardBorderColor::Borderless => "borderless",
            CardBorderColor::Gold => "gold",
            CardBorderColor::Silver => "silver",
            CardBorderColor::White => "white",
        }
        ;
    }
}
