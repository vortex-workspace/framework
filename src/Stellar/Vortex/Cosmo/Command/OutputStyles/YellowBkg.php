<?php

namespace Stellar\Vortex\Cosmo\Command\OutputStyles;

use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleOption;
use Stellar\Vortex\Cosmo\Command\OutputStyle;

class YellowBkg extends OutputStyle
{
    public function background(): ?ConsoleStyleColor
    {
        return ConsoleStyleColor::Yellow;
    }

    public function foreground(): ConsoleStyleColor
    {
        return ConsoleStyleColor::White;
    }

    public function name(): string
    {
        return 'yellow_bkg';
    }

    public function options(): array
    {
        return [
            ConsoleStyleOption::Bold
        ];
    }
}
