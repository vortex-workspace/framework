<?php

namespace Stellar\Navigation;

use Stellar\Boot\Enums\AppMap;
use Stellar\Boot\Enums\FrameworkMap;
use Stellar\Helpers\StrTool;
use Stellar\Navigation\Path\Exceptions\PathNotFound;

class ProjectPath
{
    /**
     * @param string|null $additional_path
     * @return string
     * @throws PathNotFound
     */
    public static function rootPath(?string $additional_path = null): string
    {
        return self::mountPath($additional_path);
    }

    /**
     * @param string|null $additional_path
     * @return string
     * @throws PathNotFound
     */
    public static function storagePath(?string $additional_path = null): string
    {
        return self::mountPath($additional_path, 'storage');
    }

    /**
     * @param string|null $additional_path
     * @return string
     * @throws PathNotFound
     */
    public static function vendorPath(?string $additional_path = null): string
    {
        return self::mountPath($additional_path, 'vendor');
    }

    /**
     * @param string|null $additional_path
     * @return string
     * @throws PathNotFound
     */
    public static function frameworkPath(?string $additional_path = null): string
    {
        return self::mountPath($additional_path, base_path: FRAMEWORK_PATH);
    }

    /**
     * @param string|null $additional_path
     * @return string
     * @throws PathNotFound
     */
    public static function publicPath(?string $additional_path = null): string
    {
        return self::mountPath($additional_path, 'public');
    }

    /**
     * @param string|null $additional_path
     * @return string
     * @throws PathNotFound
     */
    public static function logsPath(?string $additional_path = null): string
    {
        return self::mountPath($additional_path, AppMap::Logs);
    }

    /**
     * @param string|null $additional_path
     * @param string|AppMap|FrameworkMap|null $root_relative_path
     * @param string $base_path
     * @return string
     * @throws PathNotFound
     */
    private static function mountPath(
        ?string                         $additional_path = null,
        null|string|AppMap|FrameworkMap $root_relative_path = null,
        string                          $base_path = ROOT_PATH
    ): string
    {
        $path = $base_path;

        if ($root_relative_path !== null) {
            $path .= StrTool::forceStartWith(
                (is_string($root_relative_path) ? $root_relative_path : $root_relative_path->value),
                DIRECTORY_SEPARATOR
            );
        }

        if ($additional_path !== null) {
            $path .= StrTool::forceStartWith($additional_path, DIRECTORY_SEPARATOR);
        }

        return Path::realPath($path);
    }
}