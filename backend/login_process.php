<?php

session_start();

include "../db.php";
/** @var mysqli $conn */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        die("Please enter email and password.");
    }

    $sql = "SELECT id, name, email, password, role
            FROM users
            WHERE email = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];

            header("Location: ../dashboard/dashboard.php");
            exit();

        } else {

            echo "Wrong password.";

        }

    } else {

        echo "User not found.";

    }

    mysqli_stmt_close($stmt);
}

mysqli_close($conn);

?>