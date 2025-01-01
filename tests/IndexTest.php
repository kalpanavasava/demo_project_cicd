<?php

use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
{
    public function testIndex()
    {
        ob_start();
        include 'public/index.php';
        $output = ob_get_clean();
        
        $this->assertStringContainsString("<h1>Welcome to GitHub Actions CI/CD Demo VW</h1>", $output);
    }
}

?>
