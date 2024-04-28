<?php

namespace Stellar\Cosmo\Command\OutputStyles;

use Stellar\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Cosmo\Command\Enums\ConsoleStyleOption;
use Stellar\Cosmo\Command\OutputStyle;

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
