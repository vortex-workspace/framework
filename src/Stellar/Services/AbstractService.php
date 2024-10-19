<?php

namespace Stellar\Services;

use Core\Contracts\Boot\ApplicationInterface;
use Core\Contracts\RequestInterface;
use Core\Contracts\ServiceInterface;

abstract class AbstractService implements ServiceInterface
{
    protected function __construct(protected RequestInterface $request, protected ApplicationInterface $application)
    {
    }

    public static function getInstance(
        ?RequestInterface     $request = null,
        ?ApplicationInterface $application = null
    ): ServiceInterface
    {
        return new static($request, $application);
    }
}