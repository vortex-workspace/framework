<?php

namespace Stellar\Services\Route;

use Core\Contracts\RouteInterface;
use Stellar\Services\AbstractService;
use Stellar\Services\AbstractService\Traits\SingletonService;

abstract class AbstractRouteMatchService extends AbstractService
{
    use SingletonService;

    protected RouteInterface $route;

    abstract public function getMatchRoute(): ?RouteInterface;
}