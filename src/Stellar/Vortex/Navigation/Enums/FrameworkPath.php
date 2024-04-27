<?php

namespace Stellar\Vortex\Navigation\Enums;

use Stellar\Vortex\Navigation\Helpers\Path;
use Stellar\Vortex\Navigation\Path\Exceptions\PathNotFoundException;

enum FrameworkPath: string
{
    case CACHE = __DIR__ . '/../../../../../cache';
    case MIGRATIONS = __DIR__ . '/../../../../../migrations';
    case SETTINGS = __DIR__ . '/../../../../../settings';
    case ROOT = '';
    case COMMANDS = 'Vortex/Cosmo/Commands';

    /**
     * @param string $path
     * @param bool $full_path
     * @return string
     * @throws PathNotFoundException
     */
    public function additionalPath(string $path, bool $full_path = false): string
    {
        return $full_path ? Path::fullPath("$this->value/$path") : "$this->value/$path";
    }

    /**
     * @return string
     * @throws PathNotFoundException
     */
    public function getFullPath(): string
    {
        return Path::fullPath($this->value);
    }
}
