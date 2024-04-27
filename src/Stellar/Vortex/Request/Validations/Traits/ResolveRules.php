<?php

namespace Stellar\Vortex\Request\Validations\Traits;

use Stellar\Vortex\Helpers\StrTool;
use Stellar\Vortex\Request\Validations\Exceptions\InvalidFailsCountException;
use Stellar\Vortex\Request\Validations\Rule;

trait ResolveRules
{
    private ?array $errors = null;
    private ?array $non_validated_fields = null;
    private ?int $stop_fails_limit = null;
    private array|null $final_errors_feedbacks = null;
    private array $feedbacks_attributes = [];


    public function getErrors(): ?array
    {
        if ($this->final_errors_feedbacks === null) {
            $this->formatErrorsFeedbacks();
        }

        return $this->final_errors_feedbacks;
    }

    private function formatErrorsFeedbacks(): void
    {
        if (empty($this->errors)) {
            $this->final_errors_feedbacks = [];

            return;
        }

        $custom_feedbacks = array_merge($this->getValidation()->getFeedbacks(), $this->feedbacks);

        foreach ($this->errors as $field => $errors) {
            foreach ($errors as $key => $error) {
                $primary_key = StrTool::before($key, '.');

                if (isset($this->feedbacks_attributes[$field][$primary_key])) {
                    foreach ($this->feedbacks_attributes[$field][$primary_key] as $attribute_key => $attribute_value) {
                        $this->final_errors_feedbacks[$field][$key] = StrTool::replace(
                            ($this->final_errors_feedbacks[$field][$key] ?? $custom_feedbacks[$field][$key] ?? $error),
                            $attribute_key,
                            $attribute_value ?? ''
                        );
                    }

                    continue;
                }

                $this->final_errors_feedbacks[$field][$key] = $error;
            }
        }
    }

    /**
     * @param int|null $fail_limit
     * @return $this
     * @throws InvalidFailsCountException
     */
    public function stopOnFail(?int $fail_limit = null): static
    {
        if (is_int($fail_limit) && $fail_limit <= 0) {
            throw new InvalidFailsCountException($fail_limit);
        }

        $this->stop_fails_limit = $fail_limit;

        return $this;
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param Rule|Rule[] $rules
     * @return bool
     */
    private function resolveFieldRules(string $field, mixed $value, null|array|Rule $rules): bool
    {
        if ($rules === null) {
            $this->non_validated_fields[] = $field;

            return true;
        }

        if ($rules instanceof Rule) {
            return $this->resolveRulesObject($field, $value, $rules);
        }

        return $this->resolveRulesArray($field, $value, $rules);
    }

    private function resolveRulesObject(string $field, mixed $value, Rule $rule): bool
    {
        if (($result = $rule->setFieldName($field)->setValue($value)->validate()) === false) {
            $this->errors[$field] = array_merge($this->errors[$field] ?? [], $rule->getErrors());
            $this->feedbacks_attributes[$field][$rule->getRuleKey()] = $rule->customAttributes($this->request);
        }

        return $result;
    }

    private function resolveRulesArray(string $field, mixed $value, array $rules): bool
    {
        $result = true;

        /** @var Rule $rule */
        foreach ($rules as $rule) {
            $result = $this->resolveRulesObject($field, $value, $rule) && $rule;
        }

        return $result;
    }

    public function getNonValidatedFields(): ?array
    {
        return $this->non_validated_fields;
    }
}