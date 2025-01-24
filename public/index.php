<?php
// Include the Composer autoloader
require '../vendor/autoload.php';

// Include the database connection
include '../config/config.php';
include '../config/db_connection.php';

use App\Models\User;
use App\Controllers\UserController;

// Create instances of the models and controllers
$userModel = new User($conn); 
$userController = new UserController($conn);

// Handle registration form submission
$formErrors = $userController->handleRegistration();

// Get all users
$users = $userController->getUsers();

// Render the view
include '../app/views/users_registration.php';
