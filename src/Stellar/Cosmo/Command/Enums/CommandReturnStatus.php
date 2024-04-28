<?php

namespace Stellar\Cosmo\Command\Enums;

enum CommandReturnStatus: int
{
    case SUCCESS = 0;
    case FAILED = 1;
    case INVALID = 2;
}
