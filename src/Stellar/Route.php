<?php

namespace Stellar;

use Closure;
use Core\Contracts\RouteInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionFunction;
use ReflectionNamedType;
use ReflectionParameter;
use Stellar\Helpers\StrTool;
use Stellar\Helpers\Typography\Enum\Typography;
use Stellar\Route\Enums\HttpMethod;
use Stellar\Route\Enums\RouteType;
use Stellar\Route\Exceptions\InvalidNumberOfArguments;
use Stellar\Route\Traits\BaseMethods;
use Stellar\Route\Traits\Getters;
use Stellar\Route\Traits\Grouped;

class Route implements RouteInterface
{
    use BaseMethods, Getters, Grouped;

    private ?string $name = null;
    private ?string $controller = null;
    private ?string $method = null;
    private string $route;
    private string $route_group;
    /** @var string[]|string|null */
    private null|array|string $middleware = null;
    private ?string $prefix = null;
    private Closure $action;
    private array $query_parameters;
    private array $bind_parameters = [];
    private bool $is_fallback;

    /**
     * @param HttpMethod $httpMethods
     * @param string $route
     * @param array|Closure|string $action
     * @param bool $is_fallback
     * @throws InvalidNumberOfArguments
     */
    private function __construct(
        private readonly HttpMethod $httpMethods,
        string                      $route,
        array|Closure|string        $action,
        bool $is_fallback = false
    )
    {
        $this->is_fallback = $is_fallback;
        $this->route = StrTool::removeIfStartAndFinishWith(
            $route,
            [Typography::Slash->value, Typography::Backslash->value]
        );

        if (is_array($action)) {
            if (count($action) === 2) {
                $this->controller($action[0]);
                $this->method($action[1]);

                return;
            }

            throw new InvalidNumberOfArguments($this->route);
        }

        if (is_string($action)) {
            $this->method = $action;

            return;
        }

        $this->action = $action;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $prefix
     * @return $this
     */
    public function prefix(string $prefix): static
    {
        $this->prefix = StrTool::of($prefix)->removeIfStartAndFinishWith(
            [Typography::Slash->value, Typography::Backslash->value]
        )->get();

        return $this;
    }

    /**
     * @param string $controller
     * @return $this
     */
    public function controller(string $controller): static
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @param string $method
     * @return $this
     */
    public function method(string $method): static
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param string[]|string $middleware
     * @return static
     */
    public function middleware(array|string $middleware): static
    {
        $this->middleware = $middleware;

        return $this;
    }

    /**
     *  - Note: Use this method in conjunct to route.query.strict_mode setting to populate request with only specific
     *  query parameters
     * @param array $parameters
     * @return $this
     */
    public function queryParameters(array $parameters): static
    {
        $this->query_parameters = $parameters;

        return $this;
    }

    public function setOriginGroup(RouteType|string $route_type = RouteType::Web->value): static
    {
        $this->route_group = $route_type;

        return $this;
    }

    public function getOriginGroup(): string
    {
        return $this->route_group;
    }

    public function getPrefixedRoute(): string
    {
        return $this->prefix !== null ? "$this->prefix/$this->route" : $this->route;
    }

    public function setBindParameters(array $bind_parameters): static
    {
        $this->bind_parameters = $bind_parameters;

        return $this;
    }

    public function getBindParameters(): array
    {
        return $this->bind_parameters;
    }

    /**
     * @throws ReflectionException
     */
    public function call()
    {
        if (isset($this->controller)) {
            $reflection = new ReflectionClass($this->controller);
            $method = $reflection->getMethod($this->method);
            $parameters = $this->mountBindParameters($method->getParameters());
            return $this->controller::{$this->method}(...$parameters);
        }

        $reflection = new ReflectionFunction($this->action);
        $parameters = $this->mountBindParameters($reflection->getParameters());
        return call_user_func_array($this->action, $parameters);
    }

    private function mountBindParameters(array $parameters): array
    {
        $bind_parameters = [];

        /** @var ReflectionParameter $parameter */
        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            $parameter_name = $parameter->getName();

            if ($type instanceof ReflectionNamedType) {
                $type_name = $type->getName();

                if ($type_name === Request::class) {
                    $bind_parameters[$parameter_name] = new Request();
                    continue;
                }
            }

            if (isset($this->bind_parameters[$parameter_name])) {
                $bind_parameters[$parameter_name] = $this->bind_parameters[$parameter_name];
            }
        }

        return $bind_parameters;
    }

    /**
     * @param string $route
     * @param array|callable|string $action
     * @return Route
     * @throws InvalidNumberOfArguments
     */
    public static function fallback(string $route, array|callable|string $action): Route
    {
        $route = new Route(HttpMethod::GET, $route, $action, true);

        $route->setOriginGroup(self::getOriginFromBacktrace(debug_backtrace()));

        Router::addFallbackRoute($route);

        return $route;
    }

    public function isFallback(): bool
    {
        return $this->is_fallback;
    }
}
