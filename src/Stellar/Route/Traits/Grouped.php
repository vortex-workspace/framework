<?php

namespace Stellar\Route\Traits;

use Stellar\Core\Routes\Router;

trait Grouped
{
    /**
     * @param string $prefix
     * @param callable $routes
     * @return void
     */
    public static function groupPrefix(string $prefix, callable $routes): void
    {
        Router::setClosureRoutesPrefix($prefix, $routes);
    }

    public static function groupController(string $controller, callable $routes): void
    {
        Router::setClosureRoutesController($controller, $routes);
    }

    /**
     * @param string|array $middleware
     * @param callable $routes
     * @return void
     */
    public static function groupMiddleware(string|array $middleware, callable $routes): void
    {
        Router::setClosureRoutesMiddleware($middleware, $routes);
    }

    /**
     * @param callable $routes
     * @param string|array $middleware
     * @param string|null $controller
     * @param string|null $prefix
     * @return void
     */
    public static function group(
        callable     $routes,
        string|array $middleware = [],
        ?string      $controller = null,
        ?string      $prefix = null
    ): void
    {
        Router::setClosureRoutesGroup($routes, $middleware, $controller, $prefix);
    }
}