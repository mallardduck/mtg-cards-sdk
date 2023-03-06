<?php

declare(strict_types=1);

namespace MallardDuck\MtgCardsSdk\Enums;

use function Symfony\Component\String\u;

/**
 * @see \MallardDuck\MtgCardsSdk\Generator\Actions\GenerateCardSupertypeAction
 */
enum CardSupertype: string
{
    case Basic = 'basic';
    case Host = 'host';
    case Legendary = 'legendary';
    case Ongoing = 'ongoing';
    case Snow = 'snow';
    case World = 'world';

    public static function tryFromLabel(string $label): self
    {
        return CardSupertype::tryFrom(u($label)->snake()->toString());
    }

    public function label(): string
    {
        return CardSupertype::getLabel($this);
    }

    public static function getLabel(self $value): string
    {
        return match ($value) {
            CardSupertype::Basic => "Basic",
            CardSupertype::Host => "Host",
            CardSupertype::Legendary => "Legendary",
            CardSupertype::Ongoing => "Ongoing",
            CardSupertype::Snow => "Snow",
            CardSupertype::World => "World",
        }
        ;
    }
}
