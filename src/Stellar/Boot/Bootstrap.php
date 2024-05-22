<?php

namespace Stellar\Boot;

use Core\Contracts\Boot\BootstrapInterface;
use Core\Contracts\RequestInterface;
use Stellar\Adapters\RequestAdapter;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Route\Exceptions\RouteNameAlreadyInUse;
use Stellar\Router\Exceptions\PrefixIsEnabledButNotFound;
use Stellar\Settings\Exceptions\InvalidSettingException;

abstract class Bootstrap implements BootstrapInterface
{
    /**
     * @param string $root_path
     * @return void
     * @throws Application\Exceptions\InvalidGateway
     * @throws Application\Exceptions\InvalidProvider
     * @throws InvalidSettingException
     * @throws PrefixIsEnabledButNotFound
     * @throws RouteNameAlreadyInUse
     * @throws PathNotFound
     */
    final public static function boot(string $root_path): void
    {
        static::beforeBuild(Application::getInstance());

        Application::build($root_path);

        static::afterBuild(Application::getInstance(), RequestAdapter::getMatchClassObject());
    }

    public static function afterBuild(Application $app, RequestInterface $request): void
    {

    }

    public static function beforeBuild(Application $app): void
    {

    }
}