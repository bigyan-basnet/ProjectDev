<?php
require 'config.php'; // Uses environment variables for RDS connection

$first_name = 'Test';
$middle_name = '';
$last_name = 'User';
$username = 'testuser';
$password = 'test123'; // plaintext password

// Hash the password
$hashed_password = password_hash($password, PASSWORD_BCRYPT);
$is_admin = 0;

$sql = "INSERT INTO user_registration 
        (first_name, middle_name, last_name, username, is_admin, password) 
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssis", $first_name, $middle_name, $last_name, $username, $is_admin, $hashed_password);

if ($stmt->execute()) {
    echo "✅ User 'testuser' created successfully! You can now log in using password 'test123'.";
} else {
    echo "❌ Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
