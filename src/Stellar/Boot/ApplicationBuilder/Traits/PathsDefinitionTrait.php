<?php

namespace Stellar\Boot\ApplicationBuilder\Traits;

use Stellar\Navigation\Path;

trait PathsDefinitionTrait
{
    private function defineApplicationBasePaths(): static
    {
        if (!defined('DIRECTORY_SEPARATOR')) {
            define('DIRECTORY_SEPARATOR', strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? '\\' : '/');
        }

        $this->defineApplicationRootPath()->defineApplicationFrameworkPath();

        return $this;
    }

    private function defineApplicationRootPath(): static
    {
        define('ROOT_PATH', $this->root_path);

        return $this;
    }

    private function defineApplicationFrameworkPath(): static
    {
        define('FRAMEWORK_PATH', ($this->framework_path ?? Path::mountPath([ROOT_PATH, 'vendor', 'vortex-framework'])));

        return $this;
    }
}