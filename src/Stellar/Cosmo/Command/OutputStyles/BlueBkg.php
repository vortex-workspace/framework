<?php

namespace Stellar\Cosmo\Command\OutputStyles;

use Stellar\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Cosmo\Command\OutputStyle;

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
