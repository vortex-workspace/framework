<?php

namespace Stellar\Core\Contracts\Boot;

interface GatewayInterface
{
    public static function baseInterface(): string;
    public static function customClass(): string;
}