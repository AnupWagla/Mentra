<?php
require_once "admin_check.php";
require_once "db.php";
/** @var mysqli $conn */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../admin/supervisors.php");
    exit();
}

$full_name = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$department = trim($_POST['department'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate required fields
if ($full_name === '' || $email === '' || $department === '' || $password === '' || $confirm_password === '') {
    header("Location: ../admin/create_supervisor.php?error=empty");
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../admin/create_supervisor.php?error=invalid_email");
    exit();
}

// Validate password length
if (strlen($password) < 8) {
    header("Location: ../admin/create_supervisor.php?error=length");
    exit();
}

// Validate passwords match
if ($password !== $confirm_password) {
    header("Location: ../admin/create_supervisor.php?error=mismatch");
    exit();
}

// Check email not already taken
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("Database error preparing check.");
}
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($res) > 0) {
    mysqli_stmt_close($stmt);
    header("Location: ../admin/create_supervisor.php?error=exists");
    exit();
}
mysqli_stmt_close($stmt);

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert supervisor
$sql = "INSERT INTO users (full_name, email, phone, department, password, role, is_active)
        VALUES (?, ?, ?, ?, ?, 'supervisor', 1)";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("Database error preparing insert.");
}

mysqli_stmt_bind_param($stmt, "sssss", $full_name, $email, $phone, $department, $hashed_password);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: ../admin/supervisors.php?created=1");
    exit();
} else {
    mysqli_stmt_close($stmt);
    header("Location: ../admin/create_supervisor.php?error=failed");
    exit();
}
