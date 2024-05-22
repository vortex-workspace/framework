<?php

namespace Stellar\Vortex\Request\Validations\Rules;

use Override;
use Stellar\Vortex\Request;
use Stellar\Vortex\Request\Validations\Rule;

class UrlRule extends Rule
{
    public const string URL_VALIDATION = 'url';

    protected array $feedback_messages = [
        self::URL_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be a valid URL.',
    ];

    public function applyRule(Request $request): void
    {
        if (!filter_var($this->value, FILTER_VALIDATE_URL)) {
            $this->fireError(self::URL_VALIDATION);
        }
    }

    public function getRuleKey(): string
    {
        return 'url';
    }
}