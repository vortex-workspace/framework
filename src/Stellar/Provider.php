<?php

namespace Stellar;

use Stellar\Boot\Application;
use Core\Contracts\CommandInterface;
use Core\Contracts\ProviderInterface;
use Core\Contracts\RequestInterface;

abstract class Provider implements ProviderInterface
{
    /**
     * @return class-string<CommandInterface>[]
     */
    public static function commands(): array
    {
        return [];
    }

    public static function routes(): array
    {
        return [];
    }

    public static function boot(RequestInterface $request, Application &$application): void
    {

    }

    /**
     * Store local Gateways that run only inside Provider Boot
     * @return array
     */
    public static function localGateways(): array
    {
        return [];
    }

    /**
     * Return array of full path strings for settings.
     * @return array
     */
    public static function settings(): array
    {
        return [];
    }

    public static function canBoot(RequestInterface $request, Application &$application): bool
    {
        return true;
    }

    public static function afterBoot(RequestInterface $request, Application &$application): void
    {
    }

    public static function afterNotBoot(RequestInterface $request, Application &$application): void
    {
    }
}