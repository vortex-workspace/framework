<?php

namespace Stellar\Vortex\Cosmo\Command\OutputStyles;

use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleOption;
use Stellar\Vortex\Cosmo\Command\OutputStyle;

class Cyan extends OutputStyle
{
    public function foreground(): ConsoleStyleColor
    {
        return ConsoleStyleColor::Cyan;
    }

    public function name(): string
    {
        return 'cyan';
    }

    public function options(): array
    {
        return [
            ConsoleStyleOption::Bold,
        ];
    }
}
