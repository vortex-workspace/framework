<?php

namespace Stellar\Tests\Navigation\File;

use PHPUnit\Framework\TestCase;
use Stellar\Vortex\Navigation\Helpers\Path;

class TestBooleanFile extends TestCase
{
    public function testIfRelativePathFileIsFile()
    {
        $this->assertTrue(
            Path::isFile(__FILE__),
            'Check if file is a real file'
        );
    }

    public function testIfRelativePathDirectoryIsFile()
    {
        $this->assertFalse(
            Path::isFile(__DIR__),
            'Check if directory is a real file'
        );
    }

    public function testIfFullPathFileIsFile()
    {
        $this->assertTrue(
            Path::isFile(__FILE__, true),
            'Check if file is a real file'
        );
    }

    public function testIfFullPathDirectoryIsFile()
    {
        $this->assertFalse(
            Path::isFile(__DIR__, true),
            'Check if directory is a real file'
        );
    }
}