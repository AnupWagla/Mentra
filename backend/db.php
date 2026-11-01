<?php

$host = "localhost";
$username = "mentra_user";
$password = "MentraLocal2026!";
$database = "mentra";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

?>
