<?php

namespace Stellar\Request\Validations\Rules;

use Stellar\Request;
use Stellar\Request\Validations\Rule;

class EmailRule extends Rule
{
    public const string EMAIL_VALIDATION = 'email';

    protected array $feedback_messages = [
        self::EMAIL_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be a valid Email.',
    ];

    public function applyRule(Request $request): void
    {
        if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            $this->fireError(self::EMAIL_VALIDATION);
        }
    }

    public function getRuleKey(): string
    {
        return 'email';
    }
}