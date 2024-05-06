<?php

namespace Stellar\Providers;

use Stellar\Provider;

class VortexCore extends Provider
{
    public static function commands(): array
    {
        return [];
    }

    public static function routes(): array
    {
        return parent::routes();
    }
}