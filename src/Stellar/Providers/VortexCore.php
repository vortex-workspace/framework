<?php

namespace Stellar\Providers;

use Stellar\Cosmo\Commands\RouteList;
use Stellar\Provider;

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