<?php

namespace Stellar\Navigation\Enums;

use Stellar\Helpers\StrTool;

enum ApplicationPath: string
{
    case Environment = '.env';
    case Routes = 'routes';
    case Settings = 'settings';

    public function additionalPath(string $path): string
    {
        return $this->value . StrTool::forceStartWith($path, '/');
    }
}
