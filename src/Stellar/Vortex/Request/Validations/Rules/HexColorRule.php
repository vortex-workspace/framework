<?php

namespace Stellar\Vortex\Request\Validations\Rules;

use Override;
use Stellar\Vortex\Helpers\StrTool;
use Stellar\Vortex\Request;
use Stellar\Vortex\Request\Validations\Rule;

class HexColorRule extends Rule
{
    public const string HEX_COLOR_VALIDATION = 'hex_color';

    protected array $feedback_messages = [
        self::HEX_COLOR_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be a valid Email.',
    ];

    #[Override] public function applyRule(Request $request): void
    {
        if (!ctype_xdigit(StrTool::substring($this->value, 1))) {
            $this->fireError(self::HEX_COLOR_VALIDATION);
        }
    }

    #[Override] public function getRuleKey(): string
    {
        return 'hex_color';
    }
}