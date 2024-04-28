<?php

namespace Stellar\Cosmo\Option\Enums;

use Symfony\Component\Console\Input\InputOption;

enum OptionMode: int
{
    case None = InputOption::VALUE_NONE;
    case Required = InputOption::VALUE_REQUIRED;
    case Optional = InputOption::VALUE_OPTIONAL;
    case IsArray = InputOption::VALUE_IS_ARRAY;
    case Negatable = InputOption::VALUE_NEGATABLE;
}
