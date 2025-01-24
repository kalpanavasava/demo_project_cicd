<?php
require_once 'config/config.php';

use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    private $conn;

    /* Setup method to initialize the connection */
    protected function setUp(): void
    {
        // Create connection
        $this->conn = mysqli_connect(CON_DB_HOST, CON_DB_USER, CON_DB_PASSWORD, CON_DB_NAME);
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
