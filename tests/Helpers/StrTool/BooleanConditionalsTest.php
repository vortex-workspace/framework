<?php

namespace Stellar\Tests\Helpers\StrTool;

use PHPUnit\Framework\TestCase;
use Stellar\Helpers\StrTool;

class BooleanConditionalsTest extends TestCase
{
    public function testFinishWithNonForceDelimiter()
    {
        $this->assertTrue(
            StrTool::forceFinishWith('string_test', '/') === 'string_test/',
            'Test StrTool forceFinishWith() without delimiter'
        );
    }

    public function testFinishWithForceDelimiter()
    {
        $this->assertTrue(
            StrTool::forceFinishWith('string_test/', '/') === 'string_test/',
            'Test StrTool forceFinishWith() with delimiter'
        );
    }

    public function testStartWithNonForceDelimiter()
    {
        $this->assertTrue(
            StrTool::forceStartWith('string_test', '/') === '/string_test',
            'Test StrTool forceStartWith() without delimiter'
        );
    }

    public function testStartWithForceDelimiter()
    {
        $this->assertTrue(
            StrTool::forceStartWith('/string_test', '/') === '/string_test',
            'Test StrTool forceStartWith() with delimiter'
        );
    }


}