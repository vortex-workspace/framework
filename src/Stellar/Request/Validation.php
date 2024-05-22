<?php

namespace Stellar\Request;

use Core\Contracts\Request\ValidationInterface;
use Stellar\Request;
use Stellar\Request\Validations\Rule;
use Stellar\Request\Validations\Traits\Getters;
use Stellar\Request\Validations\Traits\ResolveRules;
use Stellar\Request\Validations\Traits\Setters;

abstract class Validation implements ValidationInterface
{
    use Getters;
    use ResolveRules;
    use Setters;

    protected ?array $custom_errors = null;

    public function __construct(protected ?Request $request = null)
    {
    }

    /**
     * @return array<string, Rule>
     */
    public function getRules(): array
    {
        return [];
    }

    /**
     * @return array<string, string>
     */
    public function getFeedbacks(): array
    {
        return [];
    }

    /**
     * - Note: return boolean for result validation or a string feedback validation message on fail.
     * @param Request $request
     * @return bool|string
     */
    public function customValidation(Request $request): bool|string
    {
        return true;
    }

    public function setRequest(Request $request): static
    {
        $this->request = $request;

        return $this;
    }

    public function fireError(string $field, string $error_key, string $feedback): static
    {
        $this->custom_errors[$field][$error_key] = $feedback;

        return $this;
    }

    public function getCustomFiredErrors(): array
    {
        return $this->custom_errors ?? [];
    }
}