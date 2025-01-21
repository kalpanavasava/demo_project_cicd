<?php

namespace App\Controllers;

use App\Models\User;

class UserController
{
    private $userModel;
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
        $this->userModel = new User($this->conn);
    }

    public function handleRegistration()
    {
        $name_error = $email_error = $password_error = '';
        $valid = true;

        if (isset($_POST['submit'])) {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $contact = $_POST['contact'] ?? '';
            $city = $_POST['city'] ?? '';
            $gender = $_POST['gender'] ?? '';
            $hobbies = isset($_POST['hobby']) ? implode(",", $_POST['hobby']) : '';

            // Validate fields
            if (empty($name)) {
                $name_error = "Name is required.";
                $valid = false;
            }

            if (empty($email)) {
                $email_error = "Email is required.";
                $valid = false;
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email_error = "Invalid email format.";
                $valid = false;
            } elseif ($this->userModel->checkIfEmailExists($email)) {
                $email_error = "Email is already registered.";
                $valid = false;
            }

            if (empty($password)) {
                $password_error = "Password is required.";
                $valid = false;
            }

            // If valid, insert user
            if ($valid) {
                if ($this->userModel->registerUser($name, $email, $password, $contact, $city, $gender, $hobbies)) {
                    echo "<h2>Data Inserted Successfully!</h2>";
                } else {
                    echo "<h2>Error: " . mysqli_error($this->conn) . "</h2>";
                }
            }
        }

        return compact('name_error', 'email_error', 'password_error');
    }

    public function getUsers()
    {
        return $this->userModel->getAllUsers();
    }
}
