<?php

namespace MallardDuck\MtgCardsSdk\Generator;

enum Events: string
{
    use EnumHelpers;
    case PreEnumFormatSkip = 'pre_enum_format_value_skip';
}
