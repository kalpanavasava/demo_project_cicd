<?php

use PHPUnit\Framework\TestCase;

class DatabaseConnectionTest extends TestCase
{
    private $dbConfig;

    protected function setUp(): void
    {
        $this->dbConfig = [
            'host' => 'localhost',
            'username' => 'root',
            'password' => '',
            'dbname' => 'demo_project_cicd',
        ];
    }

    public function testSuccessfulConnection()
    {
        $mysqli = mysqli_connect(
            $this->dbConfig['host'],
            $this->dbConfig['username'],
            $this->dbConfig['password'],
            $this->dbConfig['dbname']
        );

        $this->assertFalse($mysqli->connect_error, "Failed to connect to database: " . $mysqli->connect_error);
        $mysqli->close();
    }

    public function testInvalidCredentials()
    {
        $invalidConfig = $this->dbConfig;
        $invalidConfig['password'] = 'wrong_password';

        $mysqli = mysqli_connect(
            $invalidConfig['host'],
            $invalidConfig['username'],
            $invalidConfig['password'],
            $invalidConfig['dbname']
        );

        $this->assertNotEmpty($mysqli->connect_error, "Connection should fail with invalid credentials");
        $mysqli->close();
    }

    public function testDatabaseUnavailability()
    {
        $unavailableConfig = $this->dbConfig;
        $unavailableConfig['host'] = 'invalid_host';

        $mysqli = mysqli_connect(
            $unavailableConfig['host'],
            $unavailableConfig['username'],
            $unavailableConfig['password'],
            $unavailableConfig['dbname']
        );

        $this->assertNotEmpty($mysqli->connect_error, "Connection should fail with an invalid host");
        $mysqli->close();
    }
}
