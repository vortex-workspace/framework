<?php

namespace Stellar\Storage;

use Stellar\Provider;
use Stellar\Storage\Commands\StorageDrives;
use Stellar\Storage\Commands\StoragePublish;

class StorageProvider extends Provider
{
    public static function commands(): array
    {
        return [
            StoragePublish::class,
            StorageDrives::class,
        ];
    }
}