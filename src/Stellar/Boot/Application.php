<?php

namespace Stellar\Boot;

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Stellar\Boot\Application\Exceptions\InvalidGateway;
use Stellar\Boot\Application\Exceptions\InvalidProvider;
use Stellar\Boot\Application\Traits\Providers;
use Stellar\Core\Contracts\Boot\ApplicationInterface;
use Stellar\Core\Contracts\Boot\GatewayInterface;
use Stellar\Helpers\ArrayTool;
use Stellar\Navigation\Directory;
use Stellar\Navigation\Enums\ProjectPath;
use Stellar\Navigation\Path\Exceptions\PathNotFoundException;
use Stellar\Navigation\Path\Exceptions\TypeNotMatchException;
use Stellar\Route\Exceptions\RouteNameAlreadyInUse;
use Stellar\Router;
use Stellar\Router\Exceptions\PrefixIsEnabledButNotFound;
use Stellar\Settings\Enum\SettingKey;
use Stellar\Settings\Exceptions\InvalidSettingException;
use Stellar\Settings\Setting;

final class Application implements ApplicationInterface
{
    use Providers;

    private static Application $instance;
    private array $commands = [];
    private array $gateways = [];

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
     * @throws InvalidGateway
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
            ->discoverGateways()
            ->loadProviders()
            ->loadApplicationRoutes()
            ->closeRoutesDoor();
    }

    /**
     * @return Application
     * @throws InvalidGateway
     * @throws InvalidSettingException
     */
    private function discoverGateways(): Application
    {
        foreach (Setting::get(SettingKey::APP_GATEWAYS->value) as $gateway) {
            if (!((new $gateway) instanceof GatewayInterface)) {
                throw new InvalidGateway($gateway);
            }

            $this->gateways[$gateway::baseInterface()] = $gateway;
        }

        return $this;
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

    public function getGatewayByInterface(string $interface): ?string
    {
        if (!isset($this->gateways[$interface])) {
            return null;
        }

        return $this->gateways[$interface]::customClass();
    }
}