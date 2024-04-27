<?php

namespace Stellar\Vortex\Navigation\Enums;

enum ElementTypes: string
{
 case DIR = 'Directory';
 case FILE = 'File';
 case SYMLINK = 'Symbolic Link';
}
