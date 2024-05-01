<?php

namespace Stellar\Route\Traits;

use Stellar\Helpers\StrTool;
use Stellar\Route;
use Stellar\Route\Enums\HttpMethods;
use Stellar\Route\Exceptions\InvalidNumberOfArguments;
use Stellar\Router;
use Stellar\Router\Exceptions\FailedOnTryAddRoute;

trait BaseMethods
{
    /**
     * @param string $route
     * @param array|callable|string $action
     * @return Route
     * @throws FailedOnTryAddRoute
     * @throws InvalidNumberOfArguments
     */
    public static function connect(string $route, array|callable|string $action): Route
    {
        $route = new Route(HttpMethods::CONNECT, $route, $action);

        $route->setOriginGroup(self::getOriginFromBacktrace(debug_backtrace()));

        Router::addRoute($route);

        return $route;
    }

    /**
     * @param string $route
     * @param array|callable|string $action
     * @return Route
     * @throws FailedOnTryAddRoute
     * @throws InvalidNumberOfArguments
     */
    public static function delete(string $route, array|callable|string $action): Route
    {
        $route = new Route(HttpMethods::DELETE, $route, $action);

        $route->setOriginGroup(self::getOriginFromBacktrace(debug_backtrace()));

        Router::addRoute($route);

        return $route;
    }

    /**
     * @param string $route
     * @param array|callable|string $action
     * @return Route
     * @throws FailedOnTryAddRoute
     * @throws InvalidNumberOfArguments
     */
    public static function get(string $route, array|callable|string $action): Route
    {
        $route = new Route(HttpMethods::GET, $route, $action);

        $route->setOriginGroup(self::getOriginFromBacktrace(debug_backtrace()));

        Router::addRoute($route);

        return $route;
    }

    /**
     * @param string $route
     * @param array|callable|string $action
     * @return Route
     * @throws FailedOnTryAddRoute
     * @throws InvalidNumberOfArguments
     */
    public static function head(string $route, array|callable|string $action): Route
    {
        $route = new Route(HttpMethods::HEAD, $route, $action);

        $route->setOriginGroup(self::getOriginFromBacktrace(debug_backtrace()));

        Router::addRoute($route);

        return $route;
    }

    /**
     * @param string $route
     * @param array|callable|string $action
     * @return Route
     * @throws FailedOnTryAddRoute
     * @throws InvalidNumberOfArguments
     */
    public static function options(string $route, array|callable|string $action): Route
    {
        $route = new Route(HttpMethods::OPTIONS, $route, $action);

        $route->setOriginGroup(self::getOriginFromBacktrace(debug_backtrace()));

        Router::addRoute($route);

        return $route;
    }

    /**
     * @param string $route
     * @param array|callable|string $action
     * @return Route
     * @throws FailedOnTryAddRoute
     * @throws InvalidNumberOfArguments
     */
    public static function patch(string $route, array|callable|string $action): Route
    {
        $route = new Route(HttpMethods::PATCH, $route, $action);

        $route->setOriginGroup(self::getOriginFromBacktrace(debug_backtrace()));

        Router::addRoute($route);

        return $route;
    }

    /**
     * @param string $route
     * @param array|callable|string $action
     * @return Route
     * @throws FailedOnTryAddRoute
     * @throws InvalidNumberOfArguments
     */
    public static function post(string $route, array|callable|string $action): Route
    {
        $route = new Route(HttpMethods::POST, $route, $action);

        $route->setOriginGroup(self::getOriginFromBacktrace(debug_backtrace()));

        Router::addRoute($route);

        return $route;
    }

    /**
     * @param string $route
     * @param array|callable|string $action
     * @return Route
     * @throws FailedOnTryAddRoute
     * @throws InvalidNumberOfArguments
     */
    public static function put(string $route, array|callable|string $action): Route
    {
        $route = new Route(HttpMethods::PUT, $route, $action);

        $route->setOriginGroup(self::getOriginFromBacktrace(debug_backtrace()));

        Router::addRoute($route);

        return $route;
    }

    /**
     * @param string $route
     * @param array|callable|string $action
     * @return Route
     * @throws InvalidNumberOfArguments
     * @throws FailedOnTryAddRoute
     */
    public static function trace(string $route, array|callable|string $action): Route
    {
        $route = new Route(HttpMethods::TRACE, $route, $action);

        $route->setOriginGroup(self::getOriginFromBacktrace(debug_backtrace()));

        Router::addRoute($route);

        return $route;
    }

    private static function getOriginFromBacktrace(array $backtrace): string
    {
        return StrTool::of($backtrace[0]['file'])
            ->afterLast(OS_SEPARATOR)
            ->substring(0, -4)->get();
    }
}