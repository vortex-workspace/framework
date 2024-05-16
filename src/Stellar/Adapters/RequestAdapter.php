<?php

namespace Stellar\Adapters;

use Core\Contracts\RequestInterface;
use Stellar\Adapter;
use Stellar\Request;

/**
 * @mixin Request
 */
class RequestAdapter extends Adapter implements RequestInterface
{
    public static function defaultClass(): string
    {
        return Request::class;
    }
}