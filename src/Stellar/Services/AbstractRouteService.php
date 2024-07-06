<?php

namespace Stellar\Services;

use Core\Contracts\Boot\ApplicationInterface;
use Core\Contracts\RequestInterface;
use Core\Contracts\RouteInterface;
use Stellar\AbstractSingletonService;

abstract class AbstractRouteService extends AbstractSingletonService
{
    protected RouteInterface $route;

    abstract public function discover(RequestInterface $request): RouteInterface;

    final public function getMatchRoute(): RouteInterface
    {
        if (!isset($this->route)) {
            $this->route = $this->discover($this->request);
        }

        return $this->route;
    }

    protected function __construct(protected RequestInterface $request, protected ApplicationInterface $application)
    {

    }
}