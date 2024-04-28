<?php

namespace Stellar\Helpers\ArrayTool\Enum;

enum SortMode: int
{
    case REGULAR = 0;
    case NUMERIC = 1;
    case STRING = 2;
    case LOCALE_STRING = 5;
    case NATURAL = 6;
    case FLAG_CASE = 8;
}