<?php

session_start();
include "../db.php";
/** @var mysqli $conn */

if (!isset($_SESSION['user_id'])) {
    die("Please login first.");
}

$title = $_POST['title'];
$description = $_POST['description'];

$sql = "INSERT INTO projects (title, description)
        VALUES (?, ?)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ss",
    $title,
    $description
);

if (mysqli_stmt_execute($stmt)) {
    echo "Project created successfully.";
} else {
    echo "Failed to create project.";
}

?>