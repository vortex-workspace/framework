<?php

namespace Stellar\Request\Traits;

use Stellar\Helpers\ArrayTool;
use Stellar\Request\Validation;
use Stellar\Request\Validations\Validator;

trait Validate
{
    protected Validation $validation;
    protected Validator $validator;

    /**
     * @param ?array $rules
     * @param ?array $feedbacks
     * @return array|bool
     */
    public function validate(?array $rules = null, ?array $feedbacks = null): array|bool
    {
        if (isset($this->validation)) {
            $this->validator->setValidation($this->validation);
        }

        if (!empty($rules)) {
            $this->validator->setRules($rules);
        }

        if (!empty($feedbacks)) {
            $this->validator->setFeedbacks($feedbacks);
        }

        return $this->validator->check();
    }

    /**
     * - Note: return array of errors or null if errors are not set.
     * @return array|null
     */
    public function getErrors(): ?array
    {
        return $this->validator->getErrors();
    }

    public function getFirstFieldErrors()
    {
        return ArrayTool::first($this->validator->getErrors());
    }

    public function getFirstErrorFromFields(): array
    {
        foreach ($validations = $this->validator->getErrors() as $field => $errors) {
            $validations[$field] = ArrayTool::first($errors);
        }

        return $validations;
    }

    public function getNonValidatedFields(): array
    {
        return $this->validator->getNonValidatedFields() ?? [];
    }

    public function back()
    {
        // TODO: Redirect to last route with errors
    }

    /**
     * @param Validation $validation
     * @return static
     */
    public function setValidation(Validation $validation): static
    {
        $this->validation = $validation->setRequest($this);

        return $this;
    }
}