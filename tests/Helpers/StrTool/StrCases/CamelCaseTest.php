<?php

namespace Stellar\Tests\Helpers\StrTool\StrCases;


use Stellar\Tests\Helpers\StrTool\StrCases\Contracts\BaseConversionCase;

class CamelCaseTest extends BaseConversionCase
{
    function getStrHelperCaseMethod(): string
    {
        return 'camelCase';
    }

    function getStrHelperMethodRelatedString(): string
    {
        return self::CAMEL_CASE_STRING_VERSION;
    }
}