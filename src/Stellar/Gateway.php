<?php

namespace Stellar;

use Core\Contracts\GatewayInterface;
use Stellar\Gateway\Method;

abstract class Gateway implements GatewayInterface
{
    abstract public static function adapterClass(): string;

    /**
     * @return Method[]
     */
    abstract public static function methods(): array;
}