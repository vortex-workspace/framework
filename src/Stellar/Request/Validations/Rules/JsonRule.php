<?php

namespace Stellar\Request\Validations\Rules;

use Override;
use Stellar\Request;
use Stellar\Request\Validations\Rule;

class JsonRule extends Rule
{
    public const string JSON_VALIDATION = 'json';

    protected array $feedback_messages = [
        self::JSON_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be a valid JSON.',
    ];

    #[Override] public function applyRule(Request $request): void
    {
        if (!json_validate($this->value)) {
            $this->fireError(self::JSON_VALIDATION);
        }
    }

    #[Override] public function getRuleKey(): string
    {
        return 'json';
    }
}