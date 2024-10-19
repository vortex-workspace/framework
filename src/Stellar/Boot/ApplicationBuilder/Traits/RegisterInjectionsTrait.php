<?php

namespace Stellar\Boot\ApplicationBuilder\Traits;

use Core\Contracts\InjectionInterface;
use Stellar\Boot\ApplicationBuilder;
use Stellar\Navigation\File;
use Stellar\Navigation\Path;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Navigation\ProjectPath;
use Stellar\Setting;
use Stellar\Settings\Exceptions\InvalidSettingException;
use Stellar\Throwable\Exceptions\Generics\InvalidClassProvidedException;

trait RegisterInjectionsTrait
{
    private array $injections = [];

    /**
     * @return ApplicationBuilder
     * @throws InvalidClassProvidedException
     * @throws PathNotFound
     */
    private function registerInjections(): ApplicationBuilder
    {
        $this->registerPackagesInjections();
        $this->registerApplicationInjections();

        return $this;
    }

    private function addInjection(string $injection): static
    {
        $this->injections[get_parent_class($injection)] = $injection;

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
            if (($implements = class_implements($injection)) && in_array(InjectionInterface::class, $implements)) {
                throw new InvalidClassProvidedException($injection, InjectionInterface::class);
            }

            $this->addInjection($injection);
        }
    }

    /**
     * @return void
     * @throws InvalidClassProvidedException
     * @throws PathNotFound
     */
    private function registerPackagesInjections(): void
    {
        try {
            $injections = require_once storage_path(Path::mountPath(['internals', 'cache', 'packages', 'injections.php']));
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