<?php

namespace Stellar\Services\Route;

use Core\Contracts\Boot\ApplicationInterface;
use Core\Contracts\RequestInterface;
use Core\Contracts\RouteInterface;
use Stellar\AbstractSingletonService;

abstract class AbstractRouteFinderService extends AbstractSingletonService
{
    /**
     * @var RouteInterface[]
     */
    protected array $routes;

    abstract public function findRoutes(): void;

    protected function __construct(protected RequestInterface $request, protected ApplicationInterface $application)
    {
    }
}