<?php

use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    public function testIndex()
    {
        ob_start();
        include 'public/index.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Hello Voidek Webolutions!', $output);
    }
}

?>
