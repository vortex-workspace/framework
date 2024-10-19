<?php

namespace Stellar\Services\Route;

use Core\Contracts\RouteInterface;
use Stellar\Services\AbstractService;
use Stellar\Services\AbstractService\Traits\SingletonService;

abstract class AbstractRouteFinderService extends AbstractService
{
    use SingletonService;

    /**
     * @var RouteInterface[]
     */
    protected array $routes;

    abstract public function findRoutes(): void;
}