<?php

namespace Stellar\Composer;

enum OperationType: string
{
    case Install = 'install';
    case Uninstall = 'uninstall';
    case Update = 'update';
}
