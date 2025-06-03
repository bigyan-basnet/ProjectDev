<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'config.php';

$registration_status = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $first_name = trim($_POST['first_name']);
    $middle_name = trim($_POST['middle_name']);
    $last_name = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $is_admin = 0;

    // Validation
    if (empty($first_name) || empty($last_name) || empty($username) || empty($password) || empty($confirm_password)) {
        $registration_status = "⚠️ Please fill in all required fields.";
    } elseif ($password !== $confirm_password) {
        $registration_status = "❌ Passwords do not match.";
    } else {
        $stmt = $conn->prepare("SELECT username FROM user_registration WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $registration_status = "❌ Username already exists.";
        } else {
            $stmt->close();
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO user_registration (first_name, middle_name, last_name, username, password, is_admin) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssi", $first_name, $middle_name, $last_name, $username, $hashed_password, $is_admin);

            if ($stmt->execute()) {
                echo "<script>alert('✅ Account created successfully. Redirecting to login...'); window.location.href='login.php';</script>";
                exit();
            } else {
                $registration_status = "❌ Registration failed: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <style>
    body { font-family: Arial; padding: 20px; }
    input { display: block; margin: 10px 0; padding: 8px; width: 300px; }
    .form-box { max-width: 400px; margin: auto; }
    .status { color: red; margin: 10px 0; }
  </style>
</head>
<body>
  <div class="form-box">
    <h2>Create an Account</h2>
    <?php if (!empty($registration_status)) echo "<div class='status'>$registration_status</div>"; ?>
    <form method="POST" action="">
      <input type="text" name="first_name" placeholder="First Name" required>
      <input type="text" name="middle_name" placeholder="Middle Name">
      <input type="text" name="last_name" placeholder="Last Name" required>
      <input type="text" name="username" placeholder="Username" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="confirm_password" placeholder="Confirm Password" required>
      <input type="submit" name="submit" value="Register">
    </form>
  </div>
</body>
</html>
