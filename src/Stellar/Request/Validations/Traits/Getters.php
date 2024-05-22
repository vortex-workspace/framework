<?php

namespace Stellar\Request\Validations\Traits;

use Stellar\Request;
use Stellar\Request\Validation;

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