<?php

namespace Stellar\Cosmo\Command\OutputStyles;

use Stellar\Cosmo\Command\Enums\ConsoleStyleColor;
use Stellar\Cosmo\Command\Enums\ConsoleStyleOption;
use Stellar\Cosmo\Command\OutputStyle;

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
