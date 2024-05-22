<?php

namespace Stellar\Request\Validations\Traits;

use Stellar\Request\Validation;
use Stellar\Request\Validations\Rule;

trait Setters
{
    /**
     * @param array<string, Rule> $rules
     * @return static
     */
    public function setRules(array $rules): static
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @param array<string, string> $feedbacks
     * @return $this
     */
    public function setFeedbacks(array $feedbacks): static
    {
        $this->feedbacks = $feedbacks;

        return $this;
    }

    /**
     * @param Validation $validation
     * @return $this
     */
    public function setValidation(Validation $validation): static
    {
        $this->validation = $validation;

        return $this;
    }
}