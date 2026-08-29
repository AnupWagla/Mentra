<?php

include "../db.php";
/** @var mysqli $conn */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (empty($name) || empty($email) || empty($password)) {
        die("Please fill all fields.");
    }

    // Check if email already exists
    $sql = "SELECT id FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        die("Email already exists.");
    }

    // Encrypt password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // New account will be a student
    $role = "student";

    $sql = "INSERT INTO users (name, email, password, role)
            VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $name,
        $email,
        $hashed_password,
        $role
    );

    if (mysqli_stmt_execute($stmt)) {

        header("Location: ../account/login.php");
        exit();

    } else {

        echo "Registration failed.";

    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);

?>