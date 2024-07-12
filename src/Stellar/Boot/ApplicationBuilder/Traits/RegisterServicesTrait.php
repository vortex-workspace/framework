<?php

namespace Stellar\Boot\ApplicationBuilder\Traits;

use Core\Contracts\ServiceInterface;
use Stellar\Boot\ApplicationBuilder;
use Stellar\Navigation\File;
use Stellar\Navigation\Path;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Navigation\ProjectPath;
use Stellar\Setting;
use Stellar\Settings\Exceptions\InvalidSettingException;
use Stellar\Throwable\Exceptions\Generics\InvalidClassProvidedException;

trait RegisterServicesTrait
{
    private array $services = [];

    /**
     * @return ApplicationBuilder
     * @throws InvalidClassProvidedException
     * @throws PathNotFound
     */
    private function registerServices(): ApplicationBuilder
    {
        $this->registerPackagesServices();
        $this->registerApplicationServices();

        return $this;
    }

    private function addService(string $service): static
    {
        $this->services[get_parent_class($service)] = $service;

        return $this;
    }

    /**
     * @param array $services
     * @return void
     * @throws InvalidClassProvidedException
     */
    private function loadServices(array $services): void
    {
        foreach ($services as $service) {
            if (!($implements = class_implements($service)) || !in_array(ServiceInterface::class, $implements)) {
                throw new InvalidClassProvidedException($service, ServiceInterface::class);
            }

            $this->addService($service);
        }
    }

    /**
     * @return void
     * @throws InvalidClassProvidedException
     */
    private function registerPackagesServices(): void
    {
        try {
            $services = require_once storage_path(Path::mountPath(['internals', 'cache', 'packages', 'services.php']));
        } catch (PathNotFound) {
            $services = [];
        }

        foreach ($services as $package_services) {
            $this->loadServices($package_services);
        }
    }

    /**
     * @return void
     * @throws InvalidClassProvidedException
     */
    private function registerApplicationServices(): void
    {
        try {
            $services = Setting::get('app.services');
        } catch (InvalidSettingException) {
            $services = [];
        }

        $this->loadServices($services);
    }

    public function getServices(): array
    {
        return $this->services;
    }

    public function getService(string $base_service, ?string $default = null): ?string
    {
        return $this->services[$base_service] ?? $default;
    }
}