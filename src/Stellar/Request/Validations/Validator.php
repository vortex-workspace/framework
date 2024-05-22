<?php

namespace Stellar\Request\Validations;

use Core\Contracts\Request\Validation\ValidatorInterface;
use Stellar\Request;
use Stellar\Request\Validation;
use Stellar\Request\Validations\Traits\Getters;
use Stellar\Request\Validations\Traits\ResolveRules;
use Stellar\Request\Validations\Traits\Setters;

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