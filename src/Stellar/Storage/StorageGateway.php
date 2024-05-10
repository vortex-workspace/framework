<?php

namespace Stellar\Storage;

use Stellar\Gateway;
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
            Gateway\Method::make(
                'test',
                function (string $name) { return "Name: $name";},
                [Gateway\Argument::make('name', 'string')->setDefault('calor')]
            ),
        ];
    }
}