<?php

namespace Stellar\Vortex\Cosmo\Command\OutputStyles;

use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleOption;
use Stellar\Vortex\Cosmo\Command\OutputStyle;

class GreenBkg extends OutputStyle
{
    public function background(): ?ConsoleStyleColor
    {
        return ConsoleStyleColor::Green;
    }

    public function name(): string
    {
        return 'green_bkg';
    }

    public function foreground(): ConsoleStyleColor
    {
        return ConsoleStyleColor::White;
    }

    public function options(): array
    {
        return [
            ConsoleStyleOption::Bold,
        ];
    }
}
