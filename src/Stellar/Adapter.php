<?php

namespace Stellar;

use Core\Contracts\AdapterInterface;
use Stellar\Adapter\Exceptions\MethodNotFound;
use Stellar\Boot\Application;

abstract class Adapter implements AdapterInterface
{
    /** This is the default class to be called where no Adapters found. */
//    abstract public static function defaultClass(): string;

    public static function __callStatic(string $name, array $arguments)
    {
        $method = Application::getInstance()->getGatewayByAdapter(static::class, $name);

        if ($method === null) {
            throw new MethodNotFound($name);
        }

        return $method->execute($arguments, static::class);
    }
}