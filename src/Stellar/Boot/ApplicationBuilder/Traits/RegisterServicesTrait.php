<?php

namespace Stellar\Boot\ApplicationBuilder\Traits;

use Core\Contracts\ServiceInterface;
use Stellar\Boot\ApplicationBuilder;
use Stellar\Navigation\File;
use Stellar\Navigation\File\Exceptions\FailedOnGetFileContent;
use Stellar\Navigation\Path;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Services\AbstractRouteService;
use Stellar\Setting;
use Stellar\Settings\Exceptions\InvalidSettingException;
use Stellar\Throwable\Exceptions\Generics\InvalidClassProvidedException;

trait RegisterServicesTrait
{
    private AbstractRouteService $abstractRouteService;
    private array $services = [];

    /**
     * @return ApplicationBuilder
     * @throws FailedOnGetFileContent
     * @throws InvalidClassProvidedException
     */
    private function registerServices(): ApplicationBuilder
    {
        $this->registerPackagesServices();
        $this->registerApplicationServices();

        return $this;
    }

    private function addService(string $base_class, string $service): static
    {
        $this->services[$base_class] = $service;

        return $this;
    }

    /**
     * @param array $services
     * @return void
     * @throws InvalidClassProvidedException
     */
    private function loadServices(array $services): void
    {
        foreach ($services as $base_class => $service) {
            if (!($service::getInstance() instanceof ServiceInterface)) {
                throw new InvalidClassProvidedException($service, ServiceInterface::class);
            }

            $this->addService($base_class, $service);
        }
    }

    /**
     * @return void
     * @throws FailedOnGetFileContent
     * @throws InvalidClassProvidedException
     */
    private function registerPackagesServices(): void
    {
        try {
            $services = File::get(storage_path(Path::mountPath(['internals', 'cache', 'packages', 'services.php'])));
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
}