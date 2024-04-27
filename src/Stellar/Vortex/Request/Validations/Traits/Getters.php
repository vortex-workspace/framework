<?php

namespace Stellar\Vortex\Request\Validations\Traits;

use Stellar\Vortex\Request;
use Stellar\Vortex\Request\Validation;

trait Getters
{
    public function getValidation(): Validation
    {
        return $this->validation;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}