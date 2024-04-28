<?php

namespace Stellar\Cosmo\Command\Enums;

enum ConsoleStyleOption: string
{
    case Blink = 'blink';
    case Bold = 'bold';
    case Conceal = 'conceal';
    case Underscore = 'underscore';
    case Reverse = 'reverse';
}
