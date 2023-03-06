<?php

namespace MallardDuck\MtgCardsSdk\Foundation;

use Carbon\Carbon;
use MallardDuck\MtgCardsSdk\Enums\Block;
use MallardDuck\MtgCardsSdk\Enums\SetCode;
use MallardDuck\MtgCardsSdk\Enums\SetType;

abstract class AbstractSet
{
    public function __construct(
        readonly public string $name,
        readonly public null|Block $block,
        readonly public SetCode $code,
        readonly public bool $isFoilOnly,
        readonly public bool $isNonFoilOnly,
        readonly public bool $isOnlineOnly,
        readonly public bool $isPartialPreview,
        readonly public string $keyruneCode,
        readonly public null|string $mcmName, // cardmarket.com
        readonly public null|SetCode $parentCode,
        readonly public string $releaseDate,
        readonly public int $baseSetSize,
        readonly public int $totalSetSize,
        readonly public SetType $type,
    ) {}

    public function releaseData(): Carbon
    {
        return Carbon::parse($this->releaseDate);
    }
}