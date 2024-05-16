<?php

namespace Stellar\Storage\Adapters;

use Stellar\Adapter;
use Stellar\Storage as StellarStorage;

/**
 * @mixin StellarStorage
 */
class Storage extends Adapter
{
    public static function defaultClass(): string
    {
        return StellarStorage::class;
    }
}