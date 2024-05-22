<?php

namespace Stellar\Vortex\Providers;

use Stellar\Vortex\Provider;
use Stellar\Vortex\Cosmo\Commands\RouteList;

class VortexCore extends Provider
{
    public static function commands(): array
    {
        return [
            RouteList::class,
        ];
    }

    public static function routes(): array
    {
        return parent::routes();
    }
}