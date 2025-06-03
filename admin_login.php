<?php
session_start();
require_once 'config.php'; // This connects to your RDS database

if (isset($_POST['login'])) {
    $uname = $_POST['username'];
    $password = $_POST['password'];

    if (empty($uname) || empty($password)) {
        echo "You cannot leave username and password empty";
    } else {
        $sql = "SELECT * FROM user_registration WHERE username='$uname' AND is_admin = true";
        $result = mysqli_query($conn, $sql);
        $check = mysqli_fetch_assoc($result);

        if ($check && $check['password'] == $password) {
            $_SESSION['logged_in'] = true;
            $_SESSION['username'] = $uname;
            header("Location: admin_view.php");
            exit();
        } else {
            $message = "Username or Password incorrect or User is not admin";
            echo "<script>alert('$message');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="container">
        <form action="" method="post">
            <div class="title">Admin Login</div>

            <div class="input-box underline">
                <input type="text" name="username" placeholder="Enter Your Username" required />
                <div class="underline"></div>
            </div>

            <div class="input-box">
                <input type="password" name="password" placeholder="Enter Your Password" required />
                <div class="underline"></div>
            </div>

            <div class="input-box button">
                <input type="submit" name="login" value="Sign In" />
            </div>
        </form>
    </div>
</body>
</html>
