<?php

namespace Stellar\Vortex\Adapters;

use Stellar\Vortex\Adapter;
use Stellar\Vortex\Cosmo\Commands\RouteList;

class VortexCore extends Adapter
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