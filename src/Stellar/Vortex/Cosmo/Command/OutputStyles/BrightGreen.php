<?php

namespace Stellar\Vortex\Cosmo\Command\OutputStyles;

use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleOption;
use Stellar\Vortex\Cosmo\Command\OutputStyle;

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
