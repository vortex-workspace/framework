<?php

namespace Stellar;

use Core\Contracts\RequestInterface;
use Stellar\Adapters\RequestAdapter;
use Stellar\Helpers\StrTool;
use Stellar\RouteDriver\Exceptions\RouteNotFoundException;
use Stellar\Router\Exceptions\PrefixIsEnabledButNotFound;
use Stellar\Settings\Exceptions\InvalidSettingException;

class RouteDriver
{
    private static Route $route;

    public static function discover(RequestInterface $request): static
    {
        return new self($request);
    }

    /**
     * @param Request $request
     * @throws InvalidSettingException
     * @throws PrefixIsEnabledButNotFound
     * @throws RouteNotFoundException
     */
    private function __construct(protected RequestInterface $request)
    {
        $routes = Router::getInstance()->getRoutes();
        $method = $this->request->method();
        $uri = StrTool::removeIfStartAndFinishWith($this->request->uri(), '/');

        self::$route = $this->pipelineGetRoute($routes, $method, $uri);
    }

    /**
     * @param array $routes
     * @param string $method
     * @param string $uri
     * @return Route|null
     * @throws RouteNotFoundException
     */
    private function pipelineGetRoute(array $routes, string $method, string $uri): ?Route
    {
        return $this->tryGetStaticRoute($routes, $method, $uri) ??
            $this->tryGetDynamicRoute($routes, $method, $uri) ??
            $this->tryGetFallbackRoute() ??
            throw new RouteNotFoundException($method, $uri);
    }

    private function tryGetStaticRoute(array $routes, string $method, string $uri): ?Route
    {
        return $routes[$method][$uri] ?? null;
    }

    private function tryGetDynamicRoute(array $routes, string $method, string $uri): ?Route
    {
        /** @var Route $route */
        foreach ($routes[$method] as $route_uri => $route) {
            if (is_array($route_parameters = $this->routeMatch($route_uri, $uri))) {
                $route->setBindParameters($route_parameters);
                return $route;
            }
        }

        return null;
    }

    private function tryGetFallbackRoute(): ?Route
    {
        return Router::getInstance()->getFallbackRoute();
    }

    public static function getRoute(): ?Route
    {
        return self::$route ?? null;
    }

    private function routeMatch(string $route_uri, string $current_uri): bool|array
    {
        $uri_trace = explode('/', $route_uri);
        $current_uri_trace = explode('/', $current_uri);

        if (count($uri_trace) !== count($current_uri_trace)) {
            return false;
        }

        $route_parameters = [];

        foreach ($current_uri_trace as $index => $trace) {
            if (StrTool::startWith($uri_trace[$index], '{') && StrTool::finishWith($uri_trace[$index], '}')) {
                $route_parameters[StrTool::between($uri_trace[$index], '{', '}')] = $trace;
                continue;
            }

            if ($trace !== $uri_trace[$index]) {
                return false;
            }
        }

        return $route_parameters;
    }
}
