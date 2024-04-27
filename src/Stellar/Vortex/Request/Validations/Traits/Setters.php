<?php

namespace Stellar\Vortex\Request\Validations\Traits;

use Stellar\Vortex\Request\Validation;
use Stellar\Vortex\Request\Validations\Rule;

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