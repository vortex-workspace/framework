<?php

namespace Stellar\Vortex;

use Stellar\Core\Contracts\ProviderInterface;
use Stellar\Core\Contracts\CommandInterface;
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

    public static function boot(Request $request, Application &$application): void
    {

    }

    /**
     * Return array of full path strings for settings.
     * @return array
     */
    public static function settings(): array
    {
        return [];
    }

    public static function canBoot(Request $request, Application &$application): bool
    {
        return true;
    }

    public static function afterBoot(Request $request, Application &$application): void
    {
    }

    public static function afterNotBoot(Request $request, Application &$application): void
    {
    }
}