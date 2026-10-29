
<?php
session_start();

require_once __DIR__ . "/db.php";
/** @var mysqli $conn */

// Allow registration only through the form.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../account/register.php");
    exit();
}

// Get registration form data.
$full_name = trim($_POST["full_name"] ?? "");
$email = trim($_POST["email"] ?? "");
$roll_num = trim($_POST["roll_number"] ?? "");
$phone = trim($_POST["phone"] ?? "");
$dept_id = $_POST["dept_id"] ?? "";
$password = $_POST["password"] ?? "";
$confirm_password = $_POST["confirm_password"] ?? "";

// Check required fields. Phone is optional.
if (
    $full_name === "" ||
    $email === "" ||
    $roll_num === "" ||
    $dept_id === "" ||
    $password === "" ||
    $confirm_password === ""
) {
    exit("Please fill in all required fields.");
}

// Validate email.
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Please enter a valid email address.");
}

// Check passwords.
if ($password !== $confirm_password) {
    exit("Passwords do not match.");
}

if (strlen($password) < 8) {
    exit("Password must contain at least 8 characters.");
}

// Department options from register.php.
$departments = [
    "1" => "BCA",
    "2" => "BIT",
    "3" => "BSc CSIT"
];

if (!isset($departments[$dept_id])) {
    exit("Invalid department selected.");
}

$department = $departments[$dept_id];

// Check whether email already exists.
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    exit("Unable to check email. Please try again.");
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    mysqli_stmt_close($stmt);
    exit("Email already registered. Please use another email.");
}

mysqli_stmt_close($stmt);

// Check whether roll number already exists.
$sql = "SELECT id FROM users WHERE roll_number = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    exit("Unable to check roll number. Please try again.");
}

mysqli_stmt_bind_param($stmt, "s", $roll_num);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    mysqli_stmt_close($stmt);
    exit("Roll number already registered.");
}

mysqli_stmt_close($stmt);

// Hash password securely.
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Create a student account.
// Public registration cannot create admin or supervisor accounts.
$sql = "INSERT INTO users
        (full_name, email, roll_number, phone, department, password, role)
        VALUES (?, ?, ?, ?, ?, ?, 'student')";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    exit("Unable to prepare registration. Please try again.");
}

mysqli_stmt_bind_param(
    $stmt,
    "ssssss",
    $full_name,
    $email,
    $roll_num,
    $phone,
    $department,
    $hashed_password
);

// Save the student account.
if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    header("Location: ../account/login.php?registered=1");
    exit();
} else {
    $error = mysqli_stmt_error($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    exit("Registration failed: " . htmlspecialchars($error));
}
?>
