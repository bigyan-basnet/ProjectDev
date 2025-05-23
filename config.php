<?php
$host = getenv('db_host');
$user = getenv('db_user');
$pass = getenv('db_pass');
$dbname = getenv('db_name');

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
