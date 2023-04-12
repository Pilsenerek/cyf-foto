<?php

namespace App\Tests;

use App\Service\Test;
use PHPUnit\Framework\TestCase;

class TestTest extends TestCase
{
    public function testTest(): void
    {
        $test = new Test();
        $this->assertIsString($test->test());
        $this->assertStringContainsString('Test', $test->test());
    }
}
