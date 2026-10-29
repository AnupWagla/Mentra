<?php

require_once "../backend/admin_check.php";
require_once "../backend/db.php";
/** @var mysqli $conn */


/* Total students */

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM users
     WHERE role = 'student'"
);

$students = mysqli_fetch_assoc($result)['total'];


/* Total supervisors */

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM users
     WHERE role = 'supervisor'"
);

$supervisors = mysqli_fetch_assoc($result)['total'];


/* Total projects */

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM projects"
);

$projects = mysqli_fetch_assoc($result)['total'];


/* Pending projects */

$result = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM projects
     WHERE status = 'Submitted'"
);

$pending = mysqli_fetch_assoc($result)['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard - Mentra</title>

    <link rel="stylesheet"
          href="admin.css">

</head>

<body>

<header class="header">

    <h2>Mentra Admin</h2>

    <div>

        <span>
            <?php
            echo htmlspecialchars($_SESSION['name']);
            ?>
        </span>

        <a href="../backend/logout.php">
            Logout
        </a>

    </div>

</header>


<main class="container">

    <h1>Admin Dashboard</h1>

    <p class="subtitle">
        Manage students, supervisors and projects.
    </p>


    <div class="stats">

        <div class="stat-card">

            <h3>Students</h3>

            <strong>
                <?php echo $students; ?>
            </strong>

        </div>


        <div class="stat-card">

            <h3>Supervisors</h3>

            <strong>
                <?php echo $supervisors; ?>
            </strong>

        </div>


        <div class="stat-card">

            <h3>Projects</h3>

            <strong>
                <?php echo $projects; ?>
            </strong>

        </div>


        <div class="stat-card">

            <h3>Pending</h3>

            <strong>
                <?php echo $pending; ?>
            </strong>

        </div>

    </div>


    <div class="admin-links">

        <a href="students.php">
            Manage Students
        </a>

        <a href="supervisors.php">
            Manage Supervisors
        </a>

        <a href="projects.php">
            Manage Projects
        </a>

    </div>

</main>

</body>
</html>