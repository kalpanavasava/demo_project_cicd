<?php

use PHPUnit\Framework\TestCase;

class TestCases extends TestCase
{
    public function testIndex()
    {
        $response = $this->get('/app/index.php');
        $response->assertSee('<h1>Welcome to GitHub Actions CI/CD Demo VW</h1>');
    }
}

?>
