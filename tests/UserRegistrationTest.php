<?php

use PHPUnit\Framework\TestCase;
use App\Models\User;

class UserRegistrationTest extends TestCase
{
    protected $conn;

    // Setup method to initialize the database connection or mock it
    protected function setUp(): void
    {
        $this->conn = new mysqli("127.0.0.1", "root", "", "demo_project_cicd");
        if($this->conn->connect_error){
            $this->fail("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Clean up the database after each test
    protected function tearDown(): void
    {
        $this->conn->query("DELETE FROM users WHERE email = 'test_user@gmail.com'");
        $this->conn->close();
    }

    // Test for valid user registration
    public function testValidUserRegistration()
    {
        // Sample valid data for registration
        $name = 'test_user';
        $email = 'test_user@gmail.com';
        $password = '123';
        $contact = '1234567890';
        $city = 'Bardoli';
        $gender = 'male';
        $hobbies = 'Reading, Writing';

        $userModel = new User($this->conn);
        $result = $userModel->registerUser($name, $email, $password, $contact, $city, $gender, $hobbies);

        // Assert that the registration was successful
        $this->assertTrue($result);
    }

    // Test for missing name
    public function testMissingName()
    {
        $name = '';
        $email = 'test_user@gmail.com';
        $password = '123';
        $contact = '1234567890';
        $city = 'Bardoli';
        $gender = 'male';
        $hobbies = 'Reading, Writing';

        $userModel = new User($this->conn);
        $result = $userModel->registerUser($name, $email, $password, $contact, $city, $gender, $hobbies);

        // Assert that the registration failed due to missing name
        $this->assertFalse($result);
    }

    // Test for missing email
    public function testMissingEmail()
    {
        $name = 'test_user';
        $email = '';
        $password = '123';
        $contact = '1234567890';
        $city = 'Bardoli';
        $gender = 'male';
        $hobbies = 'Reading, Writing';

        $userModel = new User($this->conn);
        $result = $userModel->registerUser($name, $email, $password, $contact, $city, $gender, $hobbies);

        // Assert that the registration failed due to missing name
        $this->assertFalse($result);
    }

    // Test for invalid email format
    public function testInvalidEmailRegistration()
    {
        // Sample data with invalid email format
        $name = 'test_user';
        $email = 'invalid_email';  // Invalid email format
        $password = '123';
        $contact = '1234567890';
        $city = 'Bardoli';
        $gender = 'male';
        $hobbies = 'Reading, Writing';

        $userModel = new User($this->conn);
        $result = $userModel->registerUser($name, $email, $password, $contact, $city, $gender, $hobbies);

        // Assert that the registration failed due to invalid email
        $this->assertFalse($result);
    }

    // Test for duplicate email registration
    public function testDuplicateEmail()
    {
        $name = 'Kalpana_test';
        $email = 'kalpana_test@gmail.com';
        $password = '123';
        $contact = '1234567890';
        $city = 'Bardoli';
        $gender = 'female';
        $hobbies = 'Reading, Singing, Writing';

        // Register the first user
        $userModel = new User($this->conn);
        $result1 = $userModel->registerUser($name, $email, $password, $contact, $city, $gender, $hobbies);

        // Try registering the second user with the same email
        $result2 = $userModel->registerUser($name, $email, $password, $contact, $city, $gender, $hobbies);

        // Assert that the first registration was successful
        $this->assertTrue($result1);

        // Assert that the second registration failed due to duplicate email
        $this->assertFalse($result2);
    }
}
