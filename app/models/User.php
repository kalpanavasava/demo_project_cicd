<?php

namespace App\Models;

class User
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function checkIfEmailExists($email)
    {
        $email = mysqli_real_escape_string($this->conn, $email);
        $query = "SELECT 1 FROM users WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($this->conn, $query);

        // Return true if email exists, false otherwise
        return mysqli_num_rows($result) > 0;
    }

    public function registerUser($name, $email, $password, $contact, $city, $gender, $hobbies)
    {
        // Validate that the name is not empty
        if(empty($name)){
            return false; 
        }

        // Validate that the email is not empty
        if(empty($email)){
            return false; 
        }

        // Validate email format
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            return false; 
        }

        // Check if email already exists using the checkIfEmailExists method
        if($this->checkIfEmailExists($email)){
            return false; 
        }

        // Hash the password securely using PHP's built-in password_hash function
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert the user into the database
        $query = "INSERT INTO users (name, email, password, contact, city, gender, hobby) 
                VALUES ('$name', '$email', '$hashed_password', '$contact', '$city', '$gender', '$hobbies')";

        // Execute the insert query
        $result = mysqli_query($this->conn, $query);

        // Return true if the insert was successful, false otherwise
        return $result;
    }

    public function getAllUsers()
    {
        $query = "SELECT * FROM users";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }
}
