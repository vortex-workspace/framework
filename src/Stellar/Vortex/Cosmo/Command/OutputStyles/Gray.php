<?php

namespace Stellar\Vortex\Cosmo\Command\OutputStyles;

use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Vortex\Cosmo\Command\OutputStyle;

class Gray extends OutputStyle
{
    public function foreground(): ConsoleStyleColor
    {
        return ConsoleStyleColor::Gray;
    }

    public function name(): string
    {
        return 'ghost';
    }
}
