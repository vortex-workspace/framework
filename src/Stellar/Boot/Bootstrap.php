<?php

namespace Stellar\Boot;

use Stellar\Core\Contracts\Boot\BootstrapInterface;
use Stellar\Core\Contracts\Request;
use Stellar\Navigation\Path\Exceptions\PathNotFoundException;
use Stellar\Navigation\Path\Exceptions\TypeNotMatchException;
use Stellar\Route\Exceptions\RouteNameAlreadyInUse;
use Stellar\Router\Exceptions\PrefixIsEnabledButNotFound;
use Stellar\Settings\Exceptions\InvalidSettingException;

abstract class Bootstrap implements BootstrapInterface
{
    /**
     * @param string $root_path
     * @return void
     * @throws \Stellar\Boot\Application\Exceptions\InvalidProvider
     * @throws InvalidSettingException
     * @throws PathNotFoundException
     * @throws PrefixIsEnabledButNotFound
     * @throws RouteNameAlreadyInUse
     * @throws TypeNotMatchException
     */
    final public static function boot(string $root_path): void
    {
        static::beforeBuild(Application::getInstance());

        Application::build($root_path);

        static::afterBuild(Application::getInstance(), Application::getInstance()->getRequest());
    }

    public static function afterBuild(Application $app, Request $request): void
    {

    }

    public static function beforeBuild(Application $app): void
    {

    }
}