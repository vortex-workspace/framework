<?php

namespace Stellar\Tests\Helpers\StrTool\StrCases\Contracts;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use PHPUnit\Framework\TestCase;
use Stellar\Helpers\StrTool;

abstract class BaseConversionCase extends TestCase
{
    protected const LOWER_CASE_STRING_VERSION = 'test cases versions';
    protected const UPPER_CASE_STRING_VERSION = 'TEST CASES VERSIONS';
    protected const CAMEL_CASE_STRING_VERSION = 'testCasesVersions';
    protected const PASCAL_CASE_STRING_VERSION = 'TestCasesVersions';
    protected const SNAKE_CASE_STRING_VERSION = 'test_cases_versions';
    protected const TITLE_CASE_STRING_VERSION = 'Test Cases Versions';
    protected const HUMAN_READABLE_CASE_STRING_VERSION = 'Test cases versions';
    protected const SLUG_CASE_STRING_VERSION = 'test-cases-versions';
    protected const KEBAB_CASE_STRING_VERSION = 'test-cases-versions';

    protected Inflector $inflector;
    protected const STR_METHOD = '';
    protected const CLASS_STR_CONTEXT = '';

    abstract function getStrHelperCaseMethod(): string;
    abstract function getStrHelperMethodRelatedString(): string;

    public function __construct($name)
    {
        parent::__construct($name);

        $this->inflector = InflectorFactory::create()->build();
    }

    public function testFromLowerCase()
    {
        $this->assertEquals(
            static::getStrHelperMethodRelatedString(),
            StrTool::{static::getStrHelperCaseMethod()}(self::LOWER_CASE_STRING_VERSION)
        );
    }

    public function testFromUpperCase()
    {
        $this->assertEquals(
            static::getStrHelperMethodRelatedString(),
            StrTool::{static::getStrHelperCaseMethod()}(self::UPPER_CASE_STRING_VERSION)
        );
    }

    public function testFromCamelCase(): void
    {
        $this->assertEquals(
            static::getStrHelperMethodRelatedString(),
            StrTool::{static::getStrHelperCaseMethod()}(self::CAMEL_CASE_STRING_VERSION)
        );
    }

    public function testFromPascalCase(): void
    {
        $this->assertEquals(
            static::getStrHelperMethodRelatedString(),
            StrTool::{static::getStrHelperCaseMethod()}(self::PASCAL_CASE_STRING_VERSION)
        );
    }

    public function testFromSnakeCase(): void
    {
        $this->assertEquals(
            static::getStrHelperMethodRelatedString(),
            StrTool::{static::getStrHelperCaseMethod()}(self::SNAKE_CASE_STRING_VERSION)
        );
    }

    public function testFromTitleCase(): void
    {
        $this->assertEquals(
            static::getStrHelperMethodRelatedString(),
            StrTool::{static::getStrHelperCaseMethod()}(self::TITLE_CASE_STRING_VERSION)
        );
    }

    public function testFromHumanReadableCase(): void
    {
        $this->assertEquals(
            static::getStrHelperMethodRelatedString(),
            StrTool::{static::getStrHelperCaseMethod()}(self::HUMAN_READABLE_CASE_STRING_VERSION)
        );
    }

    public function testFromSlugCase(): void
    {
        $this->assertEquals(
            static::getStrHelperMethodRelatedString(),
            StrTool::{static::getStrHelperCaseMethod()}(self::SLUG_CASE_STRING_VERSION)
        );
    }

    public function testFromKebabCase(): void
    {
        $this->assertEquals(
            static::getStrHelperMethodRelatedString(),
            StrTool::{static::getStrHelperCaseMethod()}(self::KEBAB_CASE_STRING_VERSION)
        );
    }
}