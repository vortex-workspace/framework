<?php

namespace Stellar;

use Closure;
use Core\Contracts\AdapterInterface;
use Stellar\Adapter\Exceptions\MethodNotFound;
use Stellar\Boot\Application;
use Stellar\Throwable\Exceptions\Generics\MissingRequiredArgumentException;

abstract class Adapter implements AdapterInterface
{
    /** This is the default class to be called where no Adapters found. */
    abstract public static function defaultClass(): string;

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws MethodNotFound
     * @throws MissingRequiredArgumentException
     */
    public static function __callStatic(string $name, array $arguments)
    {
        if (method_exists(static::defaultClass(), $name)) {
            return static::defaultClass()::$name(...$arguments);
        }

        if (!static::hasGatewayMethod($name)) {
            throw new MethodNotFound($name);
        }

        $method = Application::getInstance()->getGatewayByAdapter(static::class, $name)->callable;

        if ($method instanceof Closure) {
            return call_user_func_array(Closure::bind($method, null, static::class), $arguments);
        }

        return call_user_func_array($method, $arguments);
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws MethodNotFound
     */
    public function __call(string $name, array $arguments)
    {
        if (method_exists($default = (new (static::defaultClass())), $name)) {
            return $default->$name(...$arguments);
        }

        if (!static::hasGatewayMethod($name, false)) {
            throw new MethodNotFound($name);
        }

        $method = Application::getInstance()->getGatewayByAdapter(static::class, $name, false)->callable;

        if ($method instanceof Closure) {
            return call_user_func_array($method->bindTo($this, static::class), $arguments);
        }

        return call_user_func_array($method, $arguments);
    }

    /**
     * @param string $name
     * @param bool $static
     * @return bool
     */
    public static function hasGatewayMethod(string $name, bool $static = true): bool
    {
        return (bool)Application::getInstance()->getGatewayByAdapter(static::class, $name, $static);
    }
}