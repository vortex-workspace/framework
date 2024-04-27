<?php

namespace Stellar\Vortex\Cosmo\Command\OutputStyles;

use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Vortex\Cosmo\Command\OutputStyle;

class BlueBkg extends OutputStyle
{
    public function background(): ?ConsoleStyleColor
    {
        return ConsoleStyleColor::CustomBlue;
    }

    public function foreground(): ConsoleStyleColor
    {
        return ConsoleStyleColor::White;
    }

    public function name(): string
    {
        return 'blue_bkg';
    }
}
