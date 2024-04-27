<?php

namespace Stellar\Vortex;

use Stellar\Core\Contracts\Boot\GatewayInterface;

abstract class Gateway implements GatewayInterface
{
    public static function loadBeforeProviders(): bool
    {
        return false;
    }
}