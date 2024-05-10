<?php

namespace Stellar\Storage;

use Stellar\Gateway;
use Stellar\Gateway\Method;
use Stellar\Storage\Adapters\Storage;

class StorageGateway extends Gateway
{

    public static function adapterClass(): string
    {
        return Storage::class;
    }

    public static function methods(): array
    {
        return [
            Method::make(
                'test',
                function (Storage $adapter, string $p1 = 'p1', string $p2 = 'p2', string $p3 = 'p3') {
                    return "P1: $p1, P2: $p2, P3: $p3";
                },
            ),
        ];
    }
}