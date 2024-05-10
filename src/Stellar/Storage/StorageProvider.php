<?php

namespace Stellar\Storage;

use Stellar\Provider;
use Stellar\Storage\Commands\StorageDrives;
use Stellar\Storage\Commands\StorageLink;

class StorageProvider extends Provider
{
    public static function commands(): array
    {
        return [
            StorageLink::class,
            StorageDrives::class,
        ];
    }
}