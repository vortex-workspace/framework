<?php

namespace Stellar\Tests\Helpers\StrTool;

use PHPUnit\Framework\TestCase;
use Stellar\Core\Helpers\Typography\Enum\Typography;
use Stellar\Tests\Helpers\StrTool\Traits\BaseConstants;
use Stellar\Vortex\Helpers\StrTool;

class TransformationsTest extends TestCase
{
    use BaseConstants;

    public function testFinishWithNonForceDelimiter()
    {
        $this->assertTrue(
            StrTool::forceFinishWith(self::BASE_STRING_TEST, Typography::SLASH->value) === self::BASE_SLASH_AFTER_STRING_TEST,
            'Test StrTool forceFinishWith() without delimiter'
        );
    }

    public function testFinishWithForceDelimiter()
    {
        $this->assertTrue(
            StrTool::forceFinishWith(self::BASE_SLASH_AFTER_STRING_TEST, Typography::SLASH->value) === self::BASE_SLASH_AFTER_STRING_TEST,
            'Test StrTool forceFinishWith() with delimiter'
        );
    }

    public function testStartWithNonForceDelimiter()
    {
        $this->assertTrue(
            StrTool::forceStartWith(self::BASE_STRING_TEST, Typography::SLASH->value) === self::BASE_SLASH_BEFORE_STRING_TEST,
            'Test StrTool forceStartWith() without delimiter'
        );
    }

    public function testStartWithForceDelimiter()
    {
        $this->assertTrue(
            StrTool::forceStartWith(self::BASE_SLASH_BEFORE_STRING_TEST, Typography::SLASH->value) === self::BASE_SLASH_BEFORE_STRING_TEST,
            'Test StrTool forceStartWith() with delimiter'
        );
    }
}