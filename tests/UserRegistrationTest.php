<?php 

use PHPUnit\Framework\TestCase;

class UserRegistrationTest extends TestCase
{
    public function testEmptyFields()
    {
        $_POST = [
            'name' => '',
            'email' => '',
            'password' => '',
        ];

        ob_start();
        include 'public/users_registration.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Name is required', $output);
        $this->assertStringContainsString('Email is required', $output);
        $this->assertStringContainsString('Password is required', $output);
    }

    public function testValidRegistration()
    {
        $_POST = [
            'name' => 'test 5',
            'email' => 'test5@gmail.com',
            'password' => '123',
            'submit' => true
        ];

        ob_start();
        include 'public/users_registration.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Registration successful', $output);
    }
}
