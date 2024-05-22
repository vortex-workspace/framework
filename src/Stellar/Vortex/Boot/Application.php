<?php

namespace Stellar\Vortex\Boot;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Stellar\Core\Contracts\Boot\ApplicationInterface;
use Stellar\Core\Contracts\RequestInterface;
use Stellar\Vortex\Provider;
use Stellar\Vortex\Boot\Application\InvalidProvider;
use Stellar\Vortex\Helpers\ArrayTool;
use Stellar\Vortex\Navigation\Directory;
use Stellar\Vortex\Navigation\Enums\ProjectPath;
use Stellar\Vortex\Navigation\Path\Exceptions\PathNotFoundException;
use Stellar\Vortex\Navigation\Path\Exceptions\TypeNotMatchException;
use Stellar\Vortex\Route\Exceptions\RouteNameAlreadyInUse;
use Stellar\Vortex\Router;
use Stellar\Vortex\Router\Exceptions\PrefixIsEnabledButNotFound;
use Stellar\Vortex\Settings\Enum\SettingKey;
use Stellar\Vortex\Settings\Exceptions\InvalidSettingException;
use Stellar\Vortex\Settings\Setting;

final class Application implements ApplicationInterface
{
    private RequestInterface $request;
    private static Application $instance;
    private array $commands = [];

    private function __construct()
    {
    }

    public static function getInstance(): Application
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param string $root_path
     * @param string|null $framework_path
     * @return void
     * @throws InvalidProvider
     * @throws InvalidSettingException
     * @throws PrefixIsEnabledButNotFound
     * @throws RouteNameAlreadyInUse
     * @throws TypeNotMatchException
     */
    public static function build(string $root_path, ?string $framework_path = null): void
    {
        self::getInstance()
            ->setRootPaths($root_path, $framework_path)
            ->setOSSeparator()
            ->tryLoadEnvironment()
            ->defineRequest()
            ->loadProviders()
            ->loadApplicationRoutes()
            ->closeRoutesDoor();
    }

    private function setRootPaths(string $root_path, ?string $framework_path): Application
    {
        define('ROOT_PATH', $root_path);

        if ($framework_path === null) {
            $framework_path = "$root_path/vendor/vortex-framework";
        }

        define('FRAMEWORK_PATH', $framework_path);

        return $this;
    }

    /**
     * @return Application
     * @throws InvalidSettingException
     */
    private function defineRequest(): Application
    {
        $this->request = new (Setting::get(SettingKey::APP_DEFAULT_REQUEST->value));

        return $this;
    }

    private function tryLoadEnvironment(): Application
    {
        try {
            Dotenv::createImmutable(ProjectPath::Environment->value)->load();
        } catch (InvalidPathException) {
        }

        return $this;
    }

    /**
     * @return Application
     * @throws InvalidSettingException
     * @throws RouteNameAlreadyInUse
     * @throws PrefixIsEnabledButNotFound
     * @throws TypeNotMatchException
     */
    private function loadApplicationRoutes(): Application
    {
        $route_files = Directory::scan(ProjectPath::Routes->value);

        try {
            require_once ProjectPath::Routes->additionalPath('web.php', true);
        } catch (PathNotFoundException) {
        }

        try {
            require_once ProjectPath::Routes->additionalPath('api.php', true);
        } catch (PathNotFoundException) {
        }

        foreach (ArrayTool::deleteValue($route_files, ['web.php', 'api.php']) as $route_file) {
            try {
                require_once ProjectPath::Routes->additionalPath($route_file, true);
            } catch (PathNotFoundException) {
            }
        }

        Router::getInstance()->updateRoutesWithPrefix()->loadNames();

        return $this;
    }

    /**
     * @return Application
     * @throws InvalidProvider
     * @throws InvalidSettingException
     */
    private function loadProviders(): Application
    {
        foreach (Setting::get(SettingKey::APP_PROVIDERS->value) as $provider) {
            if (!(($provider = (new $provider)) instanceof Provider)) {
                throw new InvalidProvider($provider);
            }

            foreach ($provider::settings() as $setting) {
                Setting::uploadFileSetting($setting);
            }

            // Call provider routes to register routes in Router singleton.
            $provider::routes();

            $this->appendCommands($provider::commands());

            if ($provider->canBoot($this->request, $this)) {
                $provider::boot($this->request, $this);
                $provider::afterBoot($this->request, $this);

                continue;
            }

            $provider::afterNotBoot($this->request, $this);
        }

        return $this;
    }

    private function appendCommands(array $commands): void
    {
        $this->commands = array_merge($this->commands, $commands);
    }

    private function closeRoutesDoor(): void
    {
        Router::getInstance()->disableEntrance();
    }

    private function setOSSeparator(): Application
    {
        if (!defined('OS_SEPARATOR')) {
            define('OS_SEPARATOR', strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? '\\' : '/');
        }

        return $this;
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}