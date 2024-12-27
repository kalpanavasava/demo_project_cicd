<?php

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function testAddition()
    {
        ob_start();
        include 'index.php';
        $output = ob_get_clean();
        
        $this->assertStringContainsString("<h1>Welcome to GitHub Actions CI/CD Demo VW</h1>", $output);
        $this->assertStringContainsString("Last deployment:", $output);
    }
}

?>
