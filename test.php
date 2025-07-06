<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Test 1: PHP Works<br>";

// Test database
require_once 'config/database.php';
echo "Test 2: Database connected<br>";

// Test functions
require_once 'includes/functions.php';
echo "Test 3: Functions loaded<br>";

// Test session
echo "Test 4: Session ID: " . session_id() . "<br>";
echo "Test 5: User logged in: " . (isLoggedIn() ? 'Yes' : 'No') . "<br>";

if (isset($_SESSION['user_id'])) {
    echo "User ID: " . $_SESSION['user_id'] . "<br>";
    echo "Username: " . $_SESSION['username'] . "<br>";
    echo "Role: " . $_SESSION['role'] . "<br>";
}
