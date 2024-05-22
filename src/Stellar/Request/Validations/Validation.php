<?php

namespace Stellar\Request\Validations;

use Stellar\Request;
use Stellar\Request\Validation as AbstractValidation;

class Validation extends AbstractValidation
{
    public function getRules(): array
    {
        return [];
    }

    public function getFeedbacks(): array
    {
        return [];
    }

    /**
     * - Note: Dynamic attributes are not available for custom validations
     * @param Request $request
     * @return bool
     */
    public function customValidation(Request $request): bool
    {
        return true;
    }
}