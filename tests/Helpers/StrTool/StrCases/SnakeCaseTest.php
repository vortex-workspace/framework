<?php

namespace Stellar\Tests\Helpers\StrTool\StrCases;


use Stellar\Tests\Helpers\StrTool\StrCases\Contracts\BaseConversionCase;

class SnakeCaseTest extends BaseConversionCase
{
    function getStrHelperCaseMethod(): string
    {
        return 'snakeCase';
    }

    function getStrHelperMethodRelatedString(): string
    {
        return self::SNAKE_CASE_STRING_VERSION;
    }
}