<?php

namespace Stellar\Request\Validations\Rules;

use Stellar\Helpers\Typography\Enum\Typography;
use Stellar\Request;
use Stellar\Request\Validations\Rule;

class RequiredRule extends Rule
{
    public const string REQUIRED_VALIDATION = 'required';

    protected array $feedback_messages = [
        self::REQUIRED_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field is required.',
    ];

    public function applyRule(Request $request): void
    {
        if ($this->value === null || $this->value === Typography::EmptyString->value) {
            $this->fireError(self::REQUIRED_VALIDATION);
        }
    }

    public function getRuleKey(): string
    {
        return 'required';
    }
}