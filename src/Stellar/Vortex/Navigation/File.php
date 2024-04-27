<?php

namespace Stellar\Vortex\Navigation;

use Stellar\Vortex\Navigation\Contracts\PathElement;
use Stellar\Vortex\Navigation\Enums\FOpenMode;
use Stellar\Vortex\Navigation\File\Traits\NonStatics;
use Stellar\Vortex\Navigation\File\Traits\Statics;
use Stellar\Vortex\Navigation\Helpers\Path;
use Stellar\Vortex\Navigation\Path\Exceptions\FailedOnOpenStreamException;
use Stellar\Vortex\Navigation\Path\Exceptions\PathNotFoundException;

class File extends PathElement
{
    use NonStatics;
    use Statics;

    public function __construct()
    {
    }

    /**
     * @param string $filename
     * @param string $directory_path
     * @param FOpenMode $mode
     * @param bool $is_real_path
     * @return false|resource
     * @throws FailedOnOpenStreamException
     * @throws PathNotFoundException
     */
    public static function openFile(
        string    $filename,
        string    $directory_path,
        FOpenMode $mode,
        bool      $is_real_path = false
    )
    {
        if (!$is_real_path) {
            $directory_path = Path::fullPath($directory_path);
        }

        if (($fp = fopen(self::mountFilePath($filename, $directory_path, $is_real_path), $mode->value)) === false) {
            throw new FailedOnOpenStreamException(self::mountFilePath($filename, $directory_path, $is_real_path));
        }

        return $fp;
    }

    /**
     * @param resource $stream
     * @return bool
     */
    public static function closeFile($stream): bool
    {
        return fclose($stream);
    }
}