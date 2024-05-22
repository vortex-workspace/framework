<?php

namespace Stellar\Request\Validations\Rules;

use Stellar\Request;
use Stellar\Request\Validations\Rule;

class TimeRule extends Rule
{
    public const string BOOL_VALIDATION = 'bool';

    protected array $feedback_messages = [
        self::BOOL_VALIDATION => 'The "' . self::CUSTOM_FIELD_MARKER . '" field must be true or false.',
    ];

    public function applyRule(Request $request): void
    {
        if (!is_bool($this->value)) {
            $this->fireError(self::BOOL_VALIDATION);
        }
    }

    public function getRuleKey(): string
    {
        return 'bool';
    }
}