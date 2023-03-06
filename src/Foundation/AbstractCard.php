<?php

namespace MallardDuck\MtgCardsSdk\Foundation;

abstract class AbstractCard
{
    public function __construct(
        readonly public string $borderColor,
        readonly public string $keywords,
        readonly public string $leadershipSkills,
        readonly public string $name,
        readonly public int $number,
        readonly public string $manaCost,
        readonly public string $colorIndicator,
        readonly public string $rarity,
        readonly public string $typeLine,
        readonly public string $types,
        readonly public string $subTypes,
        readonly public string $superType,
        readonly public string $text,
        readonly public ?int $loyalty,
        readonly public ?int $power,
        readonly public ?int $toughness,
    ) {}
}