<?php

namespace Core\Contracts;

use Core\Contracts\Boot\ApplicationInterface;

interface ServiceInterface
{
    public static function getInstance(
        ?RequestInterface     $request = null,
        ?ApplicationInterface $application = null
    ): ServiceInterface;
}