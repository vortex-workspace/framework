<?php

namespace Stellar\Boot;

use Core\Contracts\Boot\ApplicationBuilderInterface;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Stellar\Boot\ApplicationBuilder\Exceptions\MissingEnvironmentFileException;
use Stellar\Boot\ApplicationBuilder\Traits\PathsDefinitionTrait;
use Stellar\Boot\ApplicationBuilder\Traits\RegisterInjectionsTrait;
use Stellar\Boot\ApplicationBuilder\Traits\RegisterServicesTrait;
use Stellar\Navigation\Directory;
use Stellar\Navigation\File\Exceptions\FailedOnGetFileContent;
use Stellar\Navigation\Path\Exceptions\PathNotFound;
use Stellar\Throwable\Exceptions\Generics\InvalidClassProvidedException;

final class ApplicationBuilder implements ApplicationBuilderInterface
{
    use PathsDefinitionTrait, RegisterServicesTrait, RegisterInjectionsTrait;

    private array $setting_files;

    public function __construct(
        private readonly string  $root_path,
        private readonly ?string $framework_path = null
    )
    {
    }

    /**
     * @return ApplicationBuilder
     * @throws MissingEnvironmentFileException
     * @throws FailedOnGetFileContent
     * @throws InvalidClassProvidedException
     */
    public function build(): ApplicationBuilder
    {
        return $this->defineApplicationBasePaths()
            ->loadEnvironment()
            ->registerApplicationSettingFiles()
            ->registerServices()
            ->registerInjections();
    }

    public function createApp()
    {

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
        } catch (PathNotFound $exception) {
            $setting_files = [];
        }

        $this->setting_files = $setting_files;

        return $this;
    }
}