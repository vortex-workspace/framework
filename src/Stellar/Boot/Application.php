<?php

namespace Stellar\Boot;

use Core\Contracts\Boot\ApplicationInterface;
use Core\Contracts\GatewayInterface;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Stellar\Boot\Application\Exceptions\InvalidGateway;
use Stellar\Boot\Application\Exceptions\InvalidProvider;
use Stellar\Boot\Application\Exceptions\TryRegisterDuplicatedGatewayMethod;
use Stellar\Boot\Application\Traits\Providers;
use Stellar\Facades\Log;
use Stellar\Gateway\Method;
use Stellar\Helpers\ArrayTool;
use Stellar\Navigation\Directory;
use Stellar\Navigation\Enums\ApplicationPath;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Route\Exceptions\RouteNameAlreadyInUse;
use Stellar\Router;
use Stellar\Router\Exceptions\PrefixIsEnabledButNotFound;
use Stellar\Setting;
use Stellar\Settings\Enum\SettingKey;
use Stellar\Settings\Exceptions\InvalidSettingException;

final class Application implements ApplicationInterface
{
    use Providers;

    private static Application $instance;
    private array $commands = [];
    private array $gateways = [];
    private Filesystem $filesystem;

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
     * @throws PathNotFound
     * @throws PrefixIsEnabledButNotFound
     * @throws RouteNameAlreadyInUse
     * @throws TryRegisterDuplicatedGatewayMethod
     */
    public static function build(string $root_path, ?string $framework_path = null): void
    {
        self::getInstance()
            ->setRootPaths($root_path, $framework_path)
            ->setOSSeparator()
            ->tryLoadEnvironment()
            ->discoverGateways()
            ->setErrorHandler()
            ->loadProvidersFromPackages()
            ->loadProviders(Setting::get(SettingKey::APP_PROVIDERS->value, []))
            ->loadApplicationRoutes()
            ->closeRoutesDoor();
    }

    /**
     * @return Application
     * @throws TryRegisterDuplicatedGatewayMethod
     * @throws InvalidGateway
     * @throws InvalidSettingException
     */
    private function discoverGateways(): Application
    {
        foreach (Setting::get(SettingKey::APP_GATEWAYS->value) as $gateway) {
            if (!((new $gateway) instanceof GatewayInterface)) {
                throw new InvalidGateway($gateway);
            }

            $this->loadGateway($gateway);
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

        $this->filesystem = new Filesystem(new LocalFilesystemAdapter($root_path));

        return $this;
    }

    /**
     * @return Application
     * @throws PathNotFound
     */
    private function tryLoadEnvironment(): Application
    {
        try {
            Dotenv::createImmutable(root_path())->load();
        } catch (InvalidPathException) {
        }

        return $this;
    }

    public function getFilesystem(?FilesystemAdapter $adapter = null): Filesystem
    {
        if ($adapter !== null) {
            return new Filesystem($adapter);
        }

        return $this->filesystem;
    }

    /**
     * @return Application
     * @throws InvalidSettingException
     * @throws PrefixIsEnabledButNotFound
     * @throws RouteNameAlreadyInUse
     * @throws PathNotFound
     */
    private function loadApplicationRoutes(): Application
    {
        $route_files = Directory::scan(root_path(ApplicationPath::Routes->value), exclude_parents: true);

        try {
            require_once root_path(ApplicationPath::Routes->additionalPath('web.php'));
        } catch (PathNotFound) {
        }

        try {
            require_once root_path(ApplicationPath::Routes->additionalPath('api.php'));
        } catch (PathNotFound) {
        }

        foreach (ArrayTool::deleteValue($route_files, ['web.php', 'api.php']) as $route_file) {
            try {
                require_once root_path(ApplicationPath::Routes->additionalPath($route_file));
            } catch (PathNotFound) {
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

    public function getGatewayByAdapter(string $interface, string $method): ?Method
    {
        if (!isset($this->gateways[$interface])) {
            return null;
        }

        return $this->gateways[$interface][$method] ?? null;
    }

    public function getGateways(): array
    {
        return $this->gateways;
    }

    /**
     * @return Application
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    private function setErrorHandler(): Application
    {
        $error_settings = Setting::get('error');

        ini_set('log_errors', $error_settings['log'] ?? true);
        ini_set('error_log', Log::getFilename(root_path() . '/storage/logs', Setting::get('logs', [])));
        error_reporting($error_settings['reporting'] ?? E_ALL);
        ini_set('display_errors', $error_settings['display'] ?? true);


        return $this;
    }
}