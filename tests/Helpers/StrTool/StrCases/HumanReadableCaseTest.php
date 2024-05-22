<?php

namespace Stellar\Tests\Helpers\StrTool\StrCases;


use Stellar\Tests\Helpers\StrTool\StrCases\Contracts\BaseConversionCase;

class HumanReadableCaseTest extends BaseConversionCase
{
    function getStrHelperCaseMethod(): string
    {
        return 'humanReadableCase';
    }

    function getStrHelperMethodRelatedString(): string
    {
        return self::HUMAN_READABLE_CASE_STRING_VERSION;
    }
}