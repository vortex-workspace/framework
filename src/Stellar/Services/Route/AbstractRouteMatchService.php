<?php

namespace Stellar\Services\Route;

use Core\Contracts\RouteInterface;
use Stellar\AbstractService;

abstract class AbstractRouteMatchService extends AbstractService
{
    protected RouteInterface $route;

    abstract public function getMatchRoute(): ?RouteInterface;

}