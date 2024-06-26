<?php

namespace Stellar\Request\Validations\Rules;

use Stellar\Request;
use Stellar\Request\Validations\Rule;

class SerializeRule extends Rule
{
    public const string SERIALIZE_VALIDATION = 'serialize';

    protected array $feedback_messages = [
        self::SERIALIZE_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be a valid serialize.',
    ];

    public function applyRule(Request $request): void
    {
        if (!unserialize($this->value)) {
            $this->fireError(self::SERIALIZE_VALIDATION);
        }
    }

    public function getRuleKey(): string
    {
        return 'serialize';
    }
}