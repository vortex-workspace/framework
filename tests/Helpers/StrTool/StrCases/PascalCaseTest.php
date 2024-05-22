<?php

namespace Stellar\Tests\Helpers\StrTool\StrCases;


use Stellar\Tests\Helpers\StrTool\StrCases\Contracts\BaseConversionCase;

class PascalCaseTest extends BaseConversionCase
{
    function getStrHelperCaseMethod(): string
    {
        return 'pascalCase';
    }

    function getStrHelperMethodRelatedString(): string
    {
        return self::PASCAL_CASE_STRING_VERSION;
    }
}