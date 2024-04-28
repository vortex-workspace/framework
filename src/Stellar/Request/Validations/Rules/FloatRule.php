<?php

namespace Stellar\Request\Validations\Rules;

use Override;
use Stellar\Request;
use Stellar\Request\Validations\Rule;

class FloatRule extends Rule
{
    public const string URL_VALIDATION = 'url';

    protected array $feedback_messages = [
        self::URL_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be a valid URL.',
    ];

    #[Override] public function applyRule(Request $request): void
    {
        if (!filter_var($this->value, FILTER_VALIDATE_URL)) {
            $this->fireError(self::URL_VALIDATION);
        }
    }

    #[Override] public function getRuleKey(): string
    {
        return 'url';
    }

    public function max()
    {

    }

    public function min()
    {

    }

    public function positive()
    {

    }

    public function negative()
    {

    }

    public function range()
    {

    }
}