<?php

namespace MallardDuck\MtgCardsSdk\Generator;
trait EnumHelpers
{
    public function eventSuffixedKey(string $target = null): string
    {
        if ($target === null) return $this->value;
        return sprintf('%s_%s', $this->value, $target);
    }
}
