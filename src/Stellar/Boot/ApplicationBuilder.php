<?php

namespace Stellar\Boot;

use Core\Contracts\Boot\ApplicationBuilderInterface;
use Core\Contracts\Boot\ApplicationInterface;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Stellar\Boot\ApplicationBuilder\Exceptions\MissingEnvironmentFileException;
use Stellar\Boot\ApplicationBuilder\Traits\PathsDefinitionTrait;
use Stellar\Boot\ApplicationBuilder\Traits\RegisterInjectionsTrait;
use Stellar\Boot\ApplicationBuilder\Traits\RegisterServicesTrait;
use Stellar\Navigation\Directory;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Throwable\Exceptions\Generics\InvalidClassProvidedException;

final class ApplicationBuilder implements ApplicationBuilderInterface
{
    use PathsDefinitionTrait, RegisterServicesTrait, RegisterInjectionsTrait;

    private array $setting_files;

    /**
     * @param string $root_path
     * @param string|null $framework_path
     * @throws InvalidClassProvidedException
     * @throws MissingEnvironmentFileException
     * @throws PathNotFound
     */
    public function __construct(
        private readonly string  $root_path,
        private readonly ?string $framework_path = null
    )
    {
        $this->defineApplicationBasePaths()
            ->loadEnvironment()
            ->registerApplicationSettingFiles()
            ->registerServices()
            ->registerInjections();
    }

    public function createApp(): ApplicationInterface
    {
        return Application::getInstance($this);
    }

    /**
     * @return $this
     * @throws MissingEnvironmentFileException
     */
    private function loadEnvironment(): ApplicationBuilder
    {
        try {
            Dotenv::createImmutable(ROOT_PATH)->load();

            return $this;
        } catch (InvalidPathException) {
            throw new MissingEnvironmentFileException;
        }
    }

    private function registerApplicationSettingFiles(): ApplicationBuilder
    {
        try {
            $setting_files = Directory::scan(
                root_path('settings'),
                is_real_path: true,
                key_as_path: true,
                return_full_path: true
            );
        } catch (PathNotFound) {
            $setting_files = [];
        }

        $this->setting_files = $setting_files;

        return $this;
    }
}