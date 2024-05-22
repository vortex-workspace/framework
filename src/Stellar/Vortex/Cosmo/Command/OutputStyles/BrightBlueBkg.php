<?php

namespace Stellar\Vortex\Cosmo\Command\OutputStyles;

use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Vortex\Cosmo\Command\OutputStyle;

class BrightBlueBkg extends OutputStyle
{
    public function background(): ?ConsoleStyleColor
    {
        return ConsoleStyleColor::BrightBlue;
    }

    public function foreground(): ConsoleStyleColor
    {
        return ConsoleStyleColor::White;
    }

    public function name(): string
    {
        return 'bright_blue_bkg';
    }
}
