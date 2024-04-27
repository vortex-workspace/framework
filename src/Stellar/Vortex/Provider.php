<?php

namespace Stellar\Vortex;

use Stellar\Core\Contracts\ProviderInterface;
use Stellar\Core\Contracts\CommandInterface;
use Stellar\Core\Contracts\RequestInterface;
use Stellar\Vortex\Boot\Application;

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