<?php

namespace Stellar;

use Core\Contracts\AdapterInterface;
use ReflectionException;
use Stellar\Adapter\Exceptions\MethodNotFound;
use Stellar\Boot\Application;

abstract class Adapter implements AdapterInterface
{
    /** This is the default class to be called where no Adapters found. */
    abstract public static function defaultClass(): string;

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws Gateway\Method\Exceptions\InvalidArgumentNameException
     * @throws Gateway\Method\Exceptions\InvalidArgumentNumberException
     * @throws Gateway\Method\Exceptions\InvalidArgumentTypeException
     * @throws Gateway\Method\Exceptions\MissingRequiredArgumentException
     * @throws MethodNotFound
     * @throws ReflectionException
     */
    public static function __callStatic(string $name, array $arguments)
    {
        $method = Application::getInstance()->getGatewayByAdapter(static::class, $name);

        if ($method === null) {
            if (method_exists(static::defaultClass(), $name)) {
                return static::defaultClass()::$name(...$arguments);
            }

            throw new MethodNotFound($name);
        }

        return $method->execute($arguments, static::class);
    }
}