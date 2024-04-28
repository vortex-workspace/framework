<?php

namespace Stellar\Adapters;

use Stellar\Adapter;
use Stellar\Core\Contracts\RequestInterface;
use Stellar\Request;

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