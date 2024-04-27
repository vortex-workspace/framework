<?php

namespace Stellar\Vortex\Cosmo\Command\Enums;

enum ProgressBarMode: string
{
    case NORMAL = 'normal';
    case VERBOSE = 'verbose';
    case VERY_VERBOSE = 'very_verbose';
    case DEBUG = 'debug';
    case NORMAL_NO_MAX = 'normal_nomax';
    case VERBOSE_NO_MAX = 'verbose_nomax';
    case VERY_VERBOSE_NO_MAX = 'very_verbose_nomax';
    case DEBUG_NO_MAX = 'debug_nomax';
}
