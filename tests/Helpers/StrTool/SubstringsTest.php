<?php

namespace Stellar\Tests\Helpers\StrTool;

use PHPUnit\Framework\TestCase;
use Stellar\Core\Helpers\Typography\Enum\Typography;
use Stellar\Helpers\StrTool;
use Stellar\Tests\Helpers\StrTool\Traits\BaseConstants;

class SubstringsTest extends TestCase
{
    use BaseConstants;

    public function testRemoveIfStartWithWhereStartWith()
    {
        $this->assertTrue(
            StrTool::removeIfStartWith(
                self::BASE_SLASH_BEFORE_STRING_TEST,
                Typography::SLASH->value) === self::BASE_STRING_TEST,
            'Test StrTool removeIfStartWith() where true'
        );
    }

    public function testRemoveIfStartWithWhereNotStartWith()
    {
        $this->assertTrue(
            StrTool::removeIfStartWith(self::BASE_STRING_TEST, '/') === self::BASE_STRING_TEST,
            'Test StrTool removeIfStartWith() where false'
        );
    }

    public function testRemoveIfFinishWithWhereStartWith()
    {
        $this->assertTrue(
            StrTool::removeIfEndWith(
                self::BASE_SLASH_AFTER_STRING_TEST, Typography::SLASH->value) === self::BASE_STRING_TEST,
            'Test StrTool removeIfEndWith() where true'
        );
    }

    public function testRemoveIfFinishWithWhereNotStartWith()
    {
        $this->assertTrue(
            StrTool::removeIfEndWith(self::BASE_SLASH_BEFORE_STRING_TEST, Typography::SLASH->value) === self::BASE_SLASH_BEFORE_STRING_TEST,
            'Test StrTool removeIfEndWith() where false'
        );
    }

    public function testGetSubstringAfterAnotherWhereExist()
    {
        $this->assertTrue(
            StrTool::after(self::BASE_STRING_TEST, self::BASE_FIRST_SUBSTRING_TEST) === self::BASE_FIRST_SUBSTRING_RESULT_TEST,
        );
    }

    public function testGetSubstringAfterAnotherWhereNotExist()
    {
        $this->assertFalse(
            StrTool::after(self::BASE_STRING_TEST, self::BASE_NON_SUBSTRING_TEST) === self::BASE_STRING_TEST,
        );
    }

    public function testGetSubstringBeforeAnotherWhereExist()
    {
        $this->assertTrue(
            StrTool::before(self::BASE_STRING_TEST, self::BASE_LAST_SUBSTRING_TEST) === self::BASE_LAST_SUBSTRING_RESULT_TEST,
        );
    }

    public function testGetSubstringBeforeAnotherWhereNotExist()
    {
        $this->assertFalse(StrTool::before(self::BASE_STRING_TEST, self::BASE_NON_SUBSTRING_TEST));
    }

    public function testGetSubstringAfterIgnoringOne()
    {
        $this->assertTrue(
            StrTool::after('string_test_one_complex', '_', false, 1) === 'one_complex',
        );
    }

    public function testGetSubstringAfterIgnoringTwo()
    {
        $this->assertTrue(
            StrTool::after('string_test_one_complex', '_', false, 2) === 'complex',
        );
    }

    public function testGetSubstringAfterIgnoringThree()
    {
        $this->assertTrue(
            StrTool::after('string_test_one_complex', '_', false, 3) === '',
        );
    }

    public function testGetSubstringBeforeIgnoringOne()
    {
        $this->assertTrue(
            StrTool::before('string_test_one_complex', '_', false, 1) === 'string_test',
        );
    }

    public function testGetSubstringBeforeIgnoringTwo()
    {
        $this->assertTrue(
            StrTool::before('string_test_one_complex', '_', false, 2) === 'string_test_one',
        );
    }

    public function testGetSubstringBeforeIgnoringThree()
    {
        $this->assertTrue(
            StrTool::before('string_test_one_complex', '_', false, 3) === 'string_test_one_complex',
        );
    }
}