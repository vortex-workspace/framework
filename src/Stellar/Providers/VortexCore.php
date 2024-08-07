<?php

namespace Stellar\Providers;

use Stellar\AdapterAlias;
use Stellar\Commands\PublishSettings;
use Stellar\Commands\RouteList;
use Stellar\Provider;
use Stellar\Storage;

class VortexCore extends Provider
{
    public static function commands(): array
    {
        return [
            PublishSettings::class,
            RouteList::class,
        ];
    }

    public static function routes(): array
    {
        return parent::routes();
    }

    public static function adapters(): array
    {
        return [
            AdapterAlias::make('Stellar\Adapters', 'StorageAdapter', Storage::class),
        ];
    }
}