<?php

namespace Stellar\Request\Validations;

use Stellar\Core\Contracts\Request\Validation\RuleInterface;
use Stellar\Request;

abstract class Rule implements RuleInterface
{
    private function __construct(private Request $request)
    {
    }

    public static function make(Request $request): static
    {
        return new static($request);
    }

    public const string CUSTOM_FIELD_MARKER = '$field';
    protected string $generic_feedback = 'The "' . self::CUSTOM_FIELD_MARKER . '" field is invalid.';

    /**
     * - Note: messages to be retrieved in case of validation failure, set the message keys according to our
     *   validation method
     */
    protected array $feedback_messages = [];
    protected string $field;
    protected array $error_messages = [];
    protected mixed $value;

    abstract public function getRuleKey(): string;

    final public function validate(): bool
    {
        $this->applyRule($this->request);

        return empty($this->error_messages);
    }

    abstract public function applyRule(Request $request): void;

    public function setFieldName(string $field_name): static
    {
        $this->field = $field_name;

        return $this;
    }

    public function setValue(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getErrors(): array
    {
        return $this->error_messages;
    }

    public function fireError(string $error_key, ?string $feedback_message = null): static
    {
        if ($feedback_message === null) {
            $feedback_message = $this->feedback_messages[$error_key] ?? $this->generic_feedback;
        }

        $this->error_messages[$error_key] = $feedback_message;

        return $this;
    }

    public function customAttributes(Request $request): array
    {
        return [
            '$field' => $this->field,
            '$value' => $this->value ?? 'null',
        ];
    }
}