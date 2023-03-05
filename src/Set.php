<?php

namespace MallardDuck\MtgCardsSdk;

use Carbon\Carbon;

class Set
{
    public function __construct(
        readonly public string $name,
        readonly public mixed $blockId,
        readonly public string $code,
        readonly public bool $isFoilOnly,
        readonly public bool $isNonFoilOnly,
        readonly public bool $isOnlineOnly,
        readonly public bool $isPartialPreview,
        readonly public string $keyruneCode,
        readonly public string $mcmName, // cardmarket.com
        readonly public string $parentCode,
        readonly public Carbon $releaseDate,
        readonly Public SetType $type,
    ) {}
}