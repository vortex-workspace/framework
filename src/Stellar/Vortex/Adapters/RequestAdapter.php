<?php

namespace Stellar\Vortex\Adapters;

use Stellar\Core\Contracts\RequestInterface;
use Stellar\Vortex\Adapter;
use Stellar\Vortex\Request;

class RequestAdapter extends Adapter
{
    public static function relatedInterface(): string
    {
        return RequestInterface::class;
    }

    public static function defaultClass(): string
    {
        return Request::class;
    }
}