<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
</head>

<body>

<h1>Welcome, <?php echo $_SESSION['name']; ?></h1>

<p>You are logged in successfully.</p>

<p>Role: <?php echo $_SESSION['role']; ?></p>

<a href="../backend/logout.php">Logout</a>

</body>

</html>