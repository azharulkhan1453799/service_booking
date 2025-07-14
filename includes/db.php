<?php
$host = 'localhost';
$db   = 'service_booking';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Database connection error: ' . $conn->connect_error);
}
?>
