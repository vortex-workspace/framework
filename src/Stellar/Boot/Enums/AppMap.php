<?php

namespace Stellar\Boot\Enums;

enum AppMap: string
{
    case Logs = 'storage/logs';

    public function getFullPath(): string
    {
        return ROOT_PATH . "/$this->value";
    }
}
