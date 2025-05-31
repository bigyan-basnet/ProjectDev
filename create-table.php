<?php
require 'config.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Step 1: Create all tables
$sql = "
CREATE TABLE IF NOT EXISTS user_registration (
  ID INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(50) NOT NULL,
  middle_name VARCHAR(50),
  last_name VARCHAR(50) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  is_admin BOOLEAN NOT NULL DEFAULT 0,
  password VARCHAR(255) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS car (
  ID INT AUTO_INCREMENT PRIMARY KEY,
  car_year INT NOT NULL,
  car_model VARCHAR(80) NOT NULL,
  car_colour VARCHAR(40) NOT NULL,
  rental_price DECIMAL(10,2) NOT NULL,
  booked BOOLEAN NOT NULL DEFAULT FALSE,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS booking (
  ID INT AUTO_INCREMENT PRIMARY KEY,
  booking_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  total DECIMAL(10,2) NOT NULL,
  car_id INT NOT NULL,
  username VARCHAR(50) NOT NULL,
  no_of_days INT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (car_id) REFERENCES car(ID) ON DELETE CASCADE,
  FOREIGN KEY (username) REFERENCES user_registration(username) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS contact (
  ID INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL,
  email VARCHAR(50) NOT NULL,
  subject VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

if ($conn->multi_query($sql)) {
    echo "✅ Tables created successfully.<br>";
    while ($conn->more_results() && $conn->next_result()) {;}
} else {
    die("❌ Error creating tables: " . $conn->error);
}

// Step 2: Insert test user
$first_name = 'Test';
$middle_name = '';
$last_name = 'User';
$username = 'testuser';
$password = password_hash('test123', PASSWORD_BCRYPT);
$is_admin = 0;

$insert_sql = "INSERT INTO user_registration 
(first_name, middle_name, last_name, username, is_admin, password) 
VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($insert_sql);

if (!$stmt) {
    die("❌ Prepare failed: " . $conn->error);
}

$stmt->bind_param("ssssis", $first_name, $middle_name, $last_name, $username, $is_admin, $password);

if ($stmt->execute()) {
    echo "✅ User 'testuser' inserted successfully. You can now log in using password 'test123'.";
} else {
    echo "⚠️ User may already exist: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
