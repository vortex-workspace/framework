<?php

namespace Stellar\Cosmo\Command\OutputStyles;

use Stellar\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Cosmo\Command\Enums\ConsoleStyleOption;
use Stellar\Cosmo\Command\OutputStyle;

class BrightGreen extends OutputStyle
{
    public function name(): string
    {
        return 'bright_green';
    }

    public function foreground(): ConsoleStyleColor
    {
        return ConsoleStyleColor::BrightGreen;
    }

    public function options(): array
    {
        return [
            ConsoleStyleOption::Bold,
        ];
    }
}
