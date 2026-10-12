<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "complaint_management";

$conn = new mysqli($host, $user, $password);

if ($conn->connect_error) {
    die("MySQL Connection Failed: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS `$database`");
$conn->select_db($database);
$conn->set_charset("utf8mb4");
?>
