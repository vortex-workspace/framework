<?php

namespace Stellar\Storage;

use Stellar\Gateway;
use Stellar\Gateway\Argument;
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
                function (string $name, string $test) { return "Name: $name, Test: $test";},
                [
                    Argument::make('name', 'string')->setDefault('calor'),
                    Argument::make('test', 'string'),
                ]
            ),
        ];
    }
}