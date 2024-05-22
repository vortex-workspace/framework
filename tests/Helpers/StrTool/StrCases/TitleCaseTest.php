<?php

namespace Stellar\Tests\Helpers\StrTool\StrCases;


use Stellar\Tests\Helpers\StrTool\StrCases\Contracts\BaseConversionCase;

class TitleCaseTest extends BaseConversionCase
{
    function getStrHelperCaseMethod(): string
    {
        return 'titleCase';
    }

    function getStrHelperMethodRelatedString(): string
    {
        return self::TITLE_CASE_STRING_VERSION;
    }
}