<?php

require_once "../backend/admin_check.php";
require_once "../backend/db.php";
/** @var mysqli $conn */


$sql = "SELECT id, full_name, email,
               phone, department, created_at

        FROM users

        WHERE role = 'supervisor'

        ORDER BY full_name";


$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Supervisors - Mentra</title>

    <link rel="stylesheet"
          href="admin.css">

</head>


<body>


<header class="header">

    <h2>Mentra Admin</h2>

    <a href="dashboard.php">
        Dashboard
    </a>

</header>


<main class="container">

    <h1>Supervisors</h1>


    <div class="table-container">

        <table>

            <thead>

                <tr>

                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Phone</th>

                </tr>

            </thead>


            <tbody>

            <?php while ($supervisor = mysqli_fetch_assoc($result)): ?>

                <tr>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $supervisor['full_name']
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $supervisor['email']
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $supervisor['department']
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $supervisor['phone']
                        );
                        ?>
                    </td>

                </tr>

            <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</main>

</body>
</html>