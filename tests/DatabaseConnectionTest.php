<?php

use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    private $conn;

    /* Setup method to initialize the connection */
    protected function setUp(): void
    {
        $servername = "127.0.0.1";
        $username = "root";
        $password = "";
        $dbname = "demo_project_cicd";

        // Create connection
        $this->conn = mysqli_connect($servername, $username, $password, $dbname);
    }

    /* Test if the connection is established successfully */
    public function testConnection()
    {
        $this->assertNotFalse($this->conn, "Connection failed: " . mysqli_connect_error());
    }

    /* Cleanup after each test */
    protected function tearDown(): void
    {
        mysqli_close($this->conn);
    }
}
