<?php

namespace Stellar\Vortex\Request\Validations\Rules;

use Override;
use Stellar\Vortex\Request;
use Stellar\Vortex\Request\Validations\Rule;

class XmlRule extends Rule
{
    public const string XML_VALIDATION = 'xml';

    protected array $feedback_messages = [
        self::XML_VALIDATION => 'The ' . self::CUSTOM_FIELD_MARKER . ' field must be a valid XML.',
    ];

    #[Override] public function applyRule(Request $request): void
    {
        $prev = libxml_use_internal_errors(true);
        $doc = simplexml_load_string($this->value);
        $errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        if ($doc === false || !empty($errors)) {
            $this->fireError(self::XML_VALIDATION);
        }
    }

    #[Override] public function getRuleKey(): string
    {
        return 'xml';
    }
}