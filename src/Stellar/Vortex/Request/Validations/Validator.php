<?php

namespace Stellar\Vortex\Request\Validations;

use Stellar\Core\Contracts\Request\Validation\ValidatorInterface;
use Stellar\Vortex\Request;
use Stellar\Vortex\Request\Validation;
use Stellar\Vortex\Request\Validations\Traits\Getters;
use Stellar\Vortex\Request\Validations\Traits\ResolveRules;
use Stellar\Vortex\Request\Validations\Traits\Setters;

class Validator implements ValidatorInterface
{
    use Getters;
    use ResolveRules;
    use Setters;

    private array $feedbacks = [];
    private array $rules = [];
    private Validation $validation;

    public function __construct(private readonly Request $request)
    {
    }

    public function check(): bool
    {
        $validation = $this->getValidation();

        $rules = array_merge($validation->getRules(), $this->rules);
        $validated_fail_count = 0;

        if ($this->validation->customValidation($this->request) === false) {
            $this->errors = $this->validation->getCustomFiredErrors();
        }

        foreach ($this->getRequest()->attributes() as $field => $attribute) {
            if ($this->resolveFieldRules($field, $attribute, $rules[$field] ?? null) === false) {
                $validated_fail_count++;

                if ($this->stop_fails_limit === $validated_fail_count) {
                    return false;
                }
            }
        }

        return true;
    }
}