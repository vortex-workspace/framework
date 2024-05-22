<?php

namespace Stellar\Request\Validations\Rules\Enum;

use Stellar\Request\Validations\Rules\BooleanRule;
use Stellar\Request\Validations\Rules\RequiredRule;
use Stellar\Request\Validations\Rules\StringRule;

enum RulesFeedbackKey: string
{
    case BOOLEAN = BooleanRule::BOOL_VALIDATION;
    case REQUIRED = RequiredRule::REQUIRED_VALIDATION;
    case STRING = StringRule::STRING_VALIDATION;
    case STRING_MAX = StringRule::MAX_LENGTH_VALIDATION;
    case STRING_MIN = StringRule::MIN_LENGTH_VALIDATION;
    case STRING_SIZE = StringRule::SIZE_VALIDATION;
}
