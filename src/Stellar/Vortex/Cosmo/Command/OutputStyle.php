<?php

namespace Stellar\Vortex\Cosmo\Command;

use Stellar\Vortex\Cosmo\Command\Enums\ConsoleStyleColor;

abstract class OutputStyle
{
    abstract public function name(): string;
    abstract public function foreground(): ConsoleStyleColor;

    public function background(): ?ConsoleStyleColor
    {
        return null;
    }

    public function options(): array
    {
        return [];
    }
}
