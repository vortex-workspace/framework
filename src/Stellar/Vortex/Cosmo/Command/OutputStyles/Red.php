<?php

namespace Stellar\Vortex\Cosmo\Command\OutputStyles;

use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleOption;
use Stellar\Vortex\Cosmo\Command\OutputStyle;

class Red extends OutputStyle
{
    public function foreground(): ConsoleStyleColor
    {
        return ConsoleStyleColor::Red;
    }

    public function options(): array
    {
        return [
            ConsoleStyleOption::Bold,
        ];
    }

    public function name(): string
    {
        return 'red';
    }
}
