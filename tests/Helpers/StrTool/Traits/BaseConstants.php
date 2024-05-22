<?php

namespace Stellar\Tests\Helpers\StrTool\Traits;

trait BaseConstants
{
    protected const BASE_STRING_TEST = 'string_test';
    protected const BASE_FIRST_SUBSTRING_TEST = 'string_';
    protected const BASE_FIRST_SUBSTRING_RESULT_TEST = 'test';
    protected const BASE_LAST_SUBSTRING_TEST = '_test';
    protected const BASE_LAST_SUBSTRING_RESULT_TEST = 'string';
    protected const BASE_NON_SUBSTRING_TEST = '$$$$';
    protected const BASE_SLASH_AFTER_STRING_TEST = 'string_test/';
    protected const BASE_SLASH_BEFORE_STRING_TEST = '/string_test';
}