<?php

require_once "../backend/admin_check.php";
require_once "../backend/db.php";
/** @var mysqli $conn */


$sql = "SELECT
            projects.id,
            projects.title,
            projects.category,
            projects.status,
            projects.created_at,

            students.full_name AS student_name,

            supervisors.full_name AS supervisor_name

        FROM projects

        JOIN users AS students
        ON projects.student_id = students.id

        LEFT JOIN supervisor_assignments AS assignments
        ON projects.id = assignments.project_id

        LEFT JOIN users AS supervisors
        ON assignments.supervisor_id = supervisors.id

        ORDER BY projects.created_at DESC";


$result = mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Projects - Mentra</title>

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

    <h1>Project Management</h1>


    <div class="table-container">

        <table>

            <thead>

                <tr>

                    <th>Project</th>
                    <th>Student</th>
                    <th>Supervisor</th>
                    <th>Status</th>
                    <th>Action</th>

                </tr>

            </thead>


            <tbody>

            <?php while ($project = mysqli_fetch_assoc($result)): ?>

                <tr>

                    <td>
                        <?php
                        echo htmlspecialchars(
                            $project['title']
                        );
                        ?>
                    </td>


                    <td>
                        <?php
                        echo htmlspecialchars(
                            $project['student_name']
                        );
                        ?>
                    </td>


                    <td>

                        <?php if ($project['supervisor_name']): ?>

                            <?php
                            echo htmlspecialchars(
                                $project['supervisor_name']
                            );
                            ?>

                        <?php else: ?>

                            <span class="not-assigned">
                                Not Assigned
                            </span>

                        <?php endif; ?>

                    </td>


                    <td>

                        <?php
                        echo htmlspecialchars(
                            $project['status']
                        );
                        ?>

                    </td>


                    <td>

                        <a
                            class="assign-btn"
                            href="assign_supervisor.php?id=<?php
                                echo $project['id'];
                            ?>"
                        >
                            Assign Supervisor
                        </a>

                    </td>

                </tr>

            <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</main>

</body>
</html>