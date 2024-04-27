<?php

namespace Stellar\Vortex\Navigation;

use Stellar\Vortex\Navigation\Contracts\PathElement;
use Stellar\Vortex\Navigation\Directory\Exceptions\FailedOnScanDirectoryException;
use Stellar\Vortex\Navigation\Directory\Traits\Delete;
use Stellar\Vortex\Navigation\Directory\Traits\Scan;
use Stellar\Vortex\Navigation\Path\Exceptions\FailedOnDeleteException;
use Stellar\Vortex\Navigation\Path\Exceptions\PathNotFoundException;
use Stellar\Vortex\Navigation\Path\Exceptions\TypeNotMatchException;

class Directory extends PathElement
{
    use Delete;
    use Scan;

    /**
     * @param string $name
     * @param string $path
     * @param bool $is_real_path
     * @param bool $force_mode
     * @return bool
     * @throws FailedOnScanDirectoryException
     * @throws FailedOnDeleteException
     * @throws PathNotFoundException
     * @throws TypeNotMatchException
     */
    public static function create(
        string $name,
        string $path,
        bool   $is_real_path = false,
        bool   $force_mode = false
    ): bool
    {
        if (self::exist($directory_path = self::mountFilePath($name, $path, $is_real_path))) {
            if ($force_mode === false) {
                return false;
            }

            self::delete($directory_path, true);
        }

        return mkdir($directory_path);
    }
}