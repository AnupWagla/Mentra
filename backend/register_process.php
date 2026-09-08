<?php
session_start();
require_once "db.php";
/** @var mysqli $conn */

if($_SERVER["REQUEST_METHOD"]!== "POSt"){
    header("Locaation: ../account/register.php");
    exit();
}

$full_name=trim($_POST['full_name']);
$email = trim($_POST['email']);
$roll_num = trim($_POST['roll_num']);
$phone = trim($_POST['phone']);
$dept_id = $_POST['dept_id'];
$password = $_POST['password'];
$confirm_password =$_POST['confirm_password'];
if($password !== $confirm_password){
    die("Password doesn't match");
}

if(strlen($password) <8 ){
    die("Password must contain at least 8 character");
}

$departments = [
    1 => "BCA",
    2 => "BIT",
    3 => "BSc CSIT"
];

if(!isset($department[$dept_id])){
    die("Invalid Department");
}

$department = $departments[$dept_id];

$sql = "SELECT id FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param(
    $stmt,
    "s",
    $email
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result) > 0){
    die("Email Already Registered");
}

mysqli_stmt_close($stmt);

$sql = "SELECT id from user where roll_num = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "s",
    $roll_num
);

mysqli_stmt_execute($stmt);

if(mysqli_num_rows($result) > 0){
    die("Roll number already registered");
}
mysqli_stmt_close($stmt);

$hashed_password = password_hash(
    $password,
    PASSWORD_DEFAULT
);

$sql = "INSERT into users (full_name, email, roll_num, phone, department, password, role) VALUES (?, ?, ?, ?, ?, ?, 'student')";
$stmt = mysqli_prepare($conn, $sql);

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

if(mysqli_stmt_execute($stmt)){
    header("Location: ../account/login.php?registered=1");
    exit();
}
else{
    die("Registration failed: " . mysqli_stmt_error($stmt));
}

mysqli_stmt_close($stmt);
mysqli_close($conn);