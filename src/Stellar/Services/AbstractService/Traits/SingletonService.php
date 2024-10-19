<?php

namespace Stellar\Services\AbstractService\Traits;

use Core\Contracts\Boot\ApplicationInterface;
use Core\Contracts\RequestInterface;
use Core\Contracts\ServiceInterface;
use Stellar\Services\AbstractService;

trait SingletonService
{
    protected static AbstractService $instance;

    public static function getInstance(
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