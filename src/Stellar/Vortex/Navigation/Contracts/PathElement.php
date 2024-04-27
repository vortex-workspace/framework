<?php

namespace Stellar\Vortex\Navigation\Contracts;

use Stellar\Vortex\Helpers\StrTool;
use Stellar\Vortex\Navigation\Enums\ElementTypes;
use Stellar\Vortex\Navigation\Helpers\Path;
use Stellar\Vortex\Navigation\Path\Exceptions\PathNotFoundException;

abstract class PathElement
{
    protected string $full_path;
    protected string $path;
    protected ElementTypes $type;

    protected function getFullPath(): ?string
    {
        return Path::fullPath($this->path);
    }

    protected function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return bool
     */
    public static function exist(string $path): bool
    {
        return file_exists($path);
    }

    /**
     * @param string $filename
     * @param string $directory_path
     * @param bool $is_real_path
     * @return string
     * @throws PathNotFoundException
     */
    protected static function mountFilePath(
        string $filename,
        string $directory_path,
        bool $is_real_path = false
    ): string
    {
        return ($is_real_path ?
                StrTool::forceFinishWith($directory_path, '/') :
                StrTool::forceFinishWith(Path::fullPath($directory_path), '/')) . Path::trim($filename);
    }
}