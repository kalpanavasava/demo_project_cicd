<?php

use PHPUnit\Framework\TestCase;
use App\Hello;

class HelloTest extends TestCase
{
    public function testGreet()
    {
        $hello = new Hello();
        $this->assertEquals("Hello, World!", $hello->greet());
    }
}
