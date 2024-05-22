<?php

namespace Stellar\Vortex\Request\Validations\Rules;

use Stellar\Vortex\Helpers\Typography\Enum\Typography;
use Stellar\Vortex\Request;
use Stellar\Vortex\Request\Validations\Rule;

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