<?php

namespace Stellar\Tests\Helpers\StrTool\StrCases;


use Stellar\Tests\Helpers\StrTool\StrCases\Contracts\BaseConversionCase;

class SlugCaseTest extends BaseConversionCase
{
    function getStrHelperCaseMethod(): string
    {
        return 'slugCase';
    }

    function getStrHelperMethodRelatedString(): string
    {
        return self::SLUG_CASE_STRING_VERSION;
    }
}