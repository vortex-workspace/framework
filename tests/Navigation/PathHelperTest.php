<?php

namespace Stellar\Tests\Navigation;

use PHPUnit\Framework\TestCase;
use Stellar\Navigation\Helpers\Path;

class PathHelperTest extends TestCase
{
    public function testTryGetFullPathFromRelativePath()
    {
        $this->assertIsString(Path::fullPath(__FILE__));
    }
}