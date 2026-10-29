<?php

require_once "../backend/admin_check.php";
require_once "../backend/db.php";
/** @var mysqli $conn */


$sql = "SELECT id, full_name, email, roll_number,
               phone, department, created_at
        FROM users
        WHERE role = 'student'
        ORDER BY created_at DESC";


$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Students - Mentra</title>

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

    <h1>Students</h1>


    <div class="table-container">

        <table>

            <thead>

                <tr>

                    <th>Name</th>
                    <th>Email</th>
                    <th>Roll Number</th>
                    <th>Department</th>
                    <th>Phone</th>

                </tr>

            </thead>


            <tbody>

            <?php while ($student = mysqli_fetch_assoc($result)): ?>

                <tr>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $student['full_name']
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $student['email']
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $student['roll_number']
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $student['department']
                        );
                        ?>
                    </td>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $student['phone']
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