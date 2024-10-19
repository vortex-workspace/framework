<?php

namespace Stellar\Services\Route;

use Core\Contracts\RequestInterface;
use Core\Contracts\RouteInterface;
use Stellar\RouteDriver;

class ControllerRouteMatchService extends AbstractRouteMatchService
{
    public function discover(RequestInterface $request): ?RouteInterface
    {
        RouteDriver::discover($request);

        return RouteDriver::getRoute();
    }

    public function getMatchRoute(): ?RouteInterface
    {
        if (!isset($this->route)) {
            $this->route = $this->discover($this->request);
        }

        return $this->route;
    }
}