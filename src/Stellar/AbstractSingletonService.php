<?php

namespace Stellar;

use Core\Contracts\Boot\ApplicationInterface;
use Core\Contracts\RequestInterface;
use Core\Contracts\ServiceInterface;

abstract class AbstractSingletonService implements ServiceInterface
{
    protected static ServiceInterface $instance;

    abstract protected function __construct(RequestInterface $request, ApplicationInterface $application);

    final public static function getInstance(
        ?RequestInterface     $request = null,
        ?ApplicationInterface $application = null
    ): ServiceInterface
    {
        if (!isset(self::$instance)) {
            self::$instance = new static($request, $application);
        }

        return self::$instance;
    }
}