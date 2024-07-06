<?php

namespace Stellar\Services;

use Core\Contracts\RequestInterface;
use Core\Contracts\RouteInterface;
use Stellar\RouteDriver;

class ControllerRouteService extends AbstractRouteService
{
    public function discover(RequestInterface $request): RouteInterface
    {
        RouteDriver::discover($request);
        return RouteDriver::getRoute();
    }
}