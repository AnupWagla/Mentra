
<?php
session_start();

require_once __DIR__ . '/db.php';

/** @var mysqli $conn */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../account/login.php");
    exit();
}

$email = trim($_POST["email"] ?? '');
$password = $_POST["password"] ?? '';

if ($email === '' || $password === '') {
    exit("Please enter both email and password.");
}

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    exit("Database query failed.");
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {

    if (password_verify($password, $user['password'])) {

        session_regenerate_id(true);

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["name"] = $user["full_name"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["role"] = $user["role"];
        $_SESSION["department"] = $user["department"];

        if ($user["role"] === "admin") {

    header("Location: ../admin/dashboard.php");

} elseif ($user["role"] === "student") {

    header("Location: ../dashboard/dashboard.php");

} elseif ($user["role"] === "supervisor") {

    header("Location: ../supervisor/dashboard.php");

} else {

    session_destroy();
    exit("Invalid user role.");

}

exit();

        exit();

    } else {
        echo "Wrong password.";
    }

} else {
    echo "User not found.";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
