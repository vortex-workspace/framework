<?php

namespace Stellar;

use Core\Contracts\Boot\ApplicationInterface;
use Core\Contracts\RequestInterface;
use Core\Contracts\ServiceInterface;

abstract class AbstractVolatileService implements ServiceInterface
{
    protected function __construct(protected RequestInterface $request, protected ApplicationInterface $application)
    {
    }

    final public static function getInstance(
        ?RequestInterface     $request = null,
        ?ApplicationInterface $application = null
    ): ServiceInterface
    {
        return new static($request, $application);
    }
}