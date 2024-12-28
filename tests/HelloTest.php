<?php

use PHPUnit\Framework\TestCase;
use App\Hello; 

class HelloTest extends TestCase {
    public function testSayHello() {
        $hello = new Hello();
        $this->assertEquals("Hello, world!", $hello->sayHello());
    }
}
