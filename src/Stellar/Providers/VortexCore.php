<?php

namespace Stellar\Providers;

use Stellar\AdapterAlias;
use Stellar\Provider;
use Stellar\Storage;

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

    public static function adapters(): array
    {
        return [
            AdapterAlias::make('Stellar\Adapters', 'StorageAdapter', Storage::class),
        ];
    }
}