<?php

namespace Stellar\Services\Request;

use Core\Contracts\Boot\ApplicationInterface;
use Core\Contracts\RequestInterface;
use Core\Contracts\ServiceInterface;
use Stellar\AbstractService;

abstract class AbstractRequestService extends AbstractService
{
    protected RequestInterface $request;

    abstract protected function mount(): RequestInterface;

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    protected function __construct(ApplicationInterface $application)
    {
        parent::__construct($this->mount(), $application);
    }

    public static function getInstance(
        ?RequestInterface $request = null,
        ?ApplicationInterface $application = null
    ): ServiceInterface
    {
        return new static($application);
    }
}