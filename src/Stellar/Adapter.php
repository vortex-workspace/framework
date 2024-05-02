<?php

namespace Stellar;

use Stellar\Boot\Application;
use Core\Contracts\AdapterInterface;

abstract class Adapter implements AdapterInterface
{
    protected static ?string $match_class;
    protected static object $match_instance;

    abstract public static function relatedInterface(): string;

    abstract public static function defaultClass(): string;

    public static function getMatchClass(): string
    {
        return self::setMatchClass();
    }

    private static function setMatchClass(array $gateways = []): string
    {
        if (!isset(static::$match_class) || static::$match_class === null) {
            if (isset($gateways[static::relatedInterface()])) {
                static::$match_class = $gateways[static::relatedInterface()];

                return static::$match_class;
            }

            if (($match_class = Application::getInstance()->getGatewayByInterface(static::relatedInterface())) !== null) {
                static::$match_class = $match_class;

                return static::$match_class;
            }

            static::$match_class = static::defaultClass();
        }

        return static::$match_class;
    }

    public static function getMatchClassObject(array $parameters = [])
    {
        if (empty($parameters) && isset(self::$match_instance)) {
            return self::$match_instance;
        }

        return new (static::setMatchClass())(...$parameters);
    }

    public static function getMatchClassObjectForProvider(array $parameters = [], array $gateways = [])
    {
        if (empty($parameters) && isset(self::$match_instance)) {
            return self::$match_instance;
        }

        return new (static::setMatchClass($gateways))(...$parameters);
    }
}