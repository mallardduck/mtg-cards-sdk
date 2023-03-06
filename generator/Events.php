<?php

namespace MallardDuck\MtgCardsSdk\Generator;

enum Events: string
{
    use EnumHelpers;
    case PreEnumFormatSkip = 'pre_enum_format_value_skip';
    case PreEnumFormat = 'pre_enum_format_value';
    case PreEnumInserted = 'pre_enum_inserted';
}
