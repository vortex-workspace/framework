<?php

namespace Stellar\Boot\ApplicationBuilder\Traits;

use Core\Contracts\InjectionInterface;
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

trait RegisterInjectionsTrait
{
    private array $injections = [];

    /**
     * @return ApplicationBuilder
     * @throws FailedOnGetFileContent
     * @throws InvalidClassProvidedException
     */
    private function registerInjections(): ApplicationBuilder
    {
        $this->registerPackagesInjections();
        $this->registerApplicationInjections();

        return $this;
    }

    private function addInjection(string $injection): static
    {
        $this->injections[$injection] = $injection;

        return $this;
    }

    /**
     * @param array $injections
     * @return void
     * @throws InvalidClassProvidedException
     */
    private function loadInjections(array $injections): void
    {
        foreach ($injections as $injection) {
            if (!($injection::getInstance() instanceof InjectionInterface)) {
                throw new InvalidClassProvidedException($injection, InjectionInterface::class);
            }

            $this->addInjection($injection);
        }
    }

    /**
     * @return void
     * @throws FailedOnGetFileContent
     * @throws InvalidClassProvidedException
     */
    private function registerPackagesInjections(): void
    {
        try {
            $injections = File::get(storage_path(Path::mountPath(['internals', 'cache', 'packages', 'injections.php'])));
        } catch (PathNotFound) {
            $injections = [];
        }

        foreach ($injections as $package_injections) {
            $this->loadInjections($package_injections);
        }
    }

    /**
     * @return void
     * @throws InvalidClassProvidedException
     */
    private function registerApplicationInjections(): void
    {
        try {
            $injections = Setting::get('app.injections');
        } catch (InvalidSettingException) {
            $injections = [];
        }

        $this->loadInjections($injections);
    }
}