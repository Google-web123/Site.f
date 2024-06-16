<?php
$host = "192.168.0.101";
$user = "root";
$pass = "root";
$db = "std";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connection successful!";
$conn->close();
?>
