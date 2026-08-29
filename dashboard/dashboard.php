<?php

require_once "../backend/auth_check.php";

?>

<!DOCTYPE html>
<html>

<head>

    <title>Mentra Dashboard</title>

</head>

<body>

    <h1>
        Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>
    </h1>

    <p>
        Role:
        <?php echo htmlspecialchars($_SESSION["role"]); ?>
    </p>

    <a href="../backend/logout.php">
        Logout
    </a>

</body>

</html>