<?php

namespace Stellar\Boot;

use Core\Contracts\Boot\ApplicationInterface;
use Core\Contracts\RequestInterface;
use Core\Contracts\RouteInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use Stellar\Boot\Application\Exceptions\DuplicatedAdapter;
use Stellar\Boot\Application\Exceptions\InvalidGateway;
use Stellar\Boot\Application\Exceptions\InvalidProvider;
use Stellar\Boot\Application\Exceptions\TryRegisterDuplicatedGatewayMethod;
use Stellar\Boot\Application\Traits\Providers;
use Stellar\Facades\Log;
use Stellar\Gateway\Method;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Navigation\ProjectPath;
use Stellar\Router;
use Stellar\Services\Request\AbstractRequestService;
use Stellar\Services\Request\RequestService;
use Stellar\Services\Route\AbstractRouteFinderService;
use Stellar\Services\Route\AbstractRouteMatchService;
use Stellar\Services\Route\ControllerRouteMatchService;
use Stellar\Services\Route\RouteFinderService;
use Stellar\Setting;
use Stellar\Settings\Enum\SettingKey;
use Stellar\Settings\Exceptions\InvalidSettingException;
use Stellar\Throwable\Exceptions\Generics\MissingRequiredArgumentException;

final class Application implements ApplicationInterface
{
    use Providers;

    private static Application $instance;
    private array $commands = [];
    private array $gateways = [];
    private Filesystem $filesystem;
    private array $adapters = [];
    private array $setting_files = [];
    private RequestInterface $request;
    private RouteInterface $route;

    private function __construct(private readonly ApplicationBuilder $applicationBuilder)
    {
    }

    /**
     * @param ApplicationBuilder|null $applicationBuilder
     * @return Application
     * @throws MissingRequiredArgumentException
     */
    public static function getInstance(?ApplicationBuilder $applicationBuilder = null): Application
    {
        if (!isset(self::$instance)) {
            if ($applicationBuilder === null) {
                throw new MissingRequiredArgumentException('applicationBuilder');
            }

            self::$instance = new self($applicationBuilder);
        }

        return self::$instance;
    }

    /**
     * @return ApplicationInterface
     * @throws DuplicatedAdapter
     * @throws InvalidGateway
     * @throws InvalidProvider
     * @throws InvalidSettingException
     * @throws PathNotFound
     * @throws TryRegisterDuplicatedGatewayMethod
     */
    public function build(): ApplicationInterface
    {
        return $this->discoverGateways()
            ->configureErrorBehavior()
            ->setRequestInstanceService()
            ->loadProvidersFromPackages()
            ->loadProviders(Setting::get(SettingKey::AppProviders->value, []))
            ->loadApplicationRoutes()
            ->closeRoutesDoor();
    }

    public function run()
    {
        if (!$this->applicationBuilder->is_console) {
            $this->discoverCurrentRoute();
            return $this->route->call();
        }

        return null;
    }

    private function setRequestInstanceService(): Application
    {
        /** @var AbstractRequestService $request_class */
        $request_class = $this->applicationBuilder->getService(
            AbstractRequestService::class,
            RequestService::class
        );

        $this->request = $request_class::getInstance(application: $this)->getRequest();

        return $this;
    }

    public function discoverCurrentRoute(): Application
    {
        /** @var AbstractRouteMatchService $route_class */
        $route_class = $this->applicationBuilder->getService(
            AbstractRouteMatchService::class,
            ControllerRouteMatchService::class
        );

        $this->route = $route_class::getInstance($this->request, $this)->getMatchRoute();

        return $this;
    }

    private function discoverGateways(): Application
    {
        // TODO
//        foreach (Setting::get(SettingKey::APP_GATEWAYS->value, []) as $gateway) {
//            if (!((new $gateway) instanceof GatewayInterface)) {
//                throw new InvalidGateway($gateway);
//            }
//
//            $this->loadGateway($gateway);
//        }

        return $this;
    }

    public function getFilesystem(?FilesystemAdapter $adapter = null): Filesystem
    {
        if ($adapter !== null) {
            return new Filesystem($adapter);
        }

        return $this->filesystem;
    }

    private function loadApplicationRoutes(): Application
    {
        /** @var RouteFinderService $route_finder_Service_class */
        $route_finder_Service_class = $this->applicationBuilder->getService(
            AbstractRouteFinderService::class,
            RouteFinderService::class
        );

        $route_finder_Service_class::getInstance($this->request, $this)->findRoutes();

        return $this;
    }

    private function appendCommands(array $commands): void
    {
        $this->commands = array_merge($this->commands, $commands);
    }

    private function closeRoutesDoor(): Application
    {
        Router::getInstance()->disableEntrance();

        return $this;
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    public function getGatewayByAdapter(string $interface, string $method, bool $static = true): ?Method
    {
        if (!isset($this->gateways[$interface][$static ? 'static' : 'non_static'])) {
            return null;
        }

        return $this->gateways[$interface][$static ? 'static' : 'non_static'][$method] ?? null;
    }

    public function getGateways(): array
    {
        return $this->gateways;
    }

    public function getAdapters(): array
    {
        return $this->adapters;
    }

    public function getRoute(): RouteInterface
    {
        return $this->route;
    }

    /**
     * @return Application
     * @throws InvalidSettingException
     * @throws PathNotFound
     */
    private function configureErrorBehavior(): Application
    {
        $error_settings = Setting::get(SettingKey::Error->value, []);

        ini_set('log_errors', $error_settings['log'] ?? true);
        ini_set('error_log', Log::getFilename(ProjectPath::logsPath(), Setting::get(SettingKey::Log->value, [])));
        ini_set('display_errors', $error_settings['display'] ?? true);
        error_reporting($error_settings['reporting'] ?? E_ALL);

        return $this;
    }
}