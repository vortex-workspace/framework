<?php

namespace Stellar\Tests\Helpers\StrTool\StrCases;


use Stellar\Tests\Helpers\StrTool\StrCases\Contracts\BaseConversionCase;

class KebabCaseTest extends BaseConversionCase
{
    function getStrHelperCaseMethod(): string
    {
        return 'kebabCase';
    }

    function getStrHelperMethodRelatedString(): string
    {
        return self::KEBAB_CASE_STRING_VERSION;
    }
}