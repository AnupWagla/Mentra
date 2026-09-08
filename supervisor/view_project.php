<?php

require_once "../backend/supervisor_check.php";
require_once "../backend/db.php";
/** @var mysqli $conn */


if (!isset($_GET['id'])) {

    header("Location: dashboard.php");
    exit();

}


$project_id = intval($_GET['id']);


/*
    Get project
*/

$sql = "SELECT
            projects.*,
            users.full_name,
            users.email,
            users.roll_number,
            users.department

        FROM projects

        JOIN users
        ON projects.student_id = users.id

        WHERE projects.id = ?";


$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $project_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


if (mysqli_num_rows($result) !== 1) {

    die("Project not found.");

}


$project = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);


/*
    Get feedback history
*/

$feedback_sql = "SELECT
                    feedback.*,
                    users.full_name

                 FROM feedback

                 JOIN users
                 ON feedback.supervisor_id = users.id

                 WHERE feedback.project_id = ?

                 ORDER BY feedback.created_at DESC";


$feedback_stmt = mysqli_prepare(
    $conn,
    $feedback_sql
);

mysqli_stmt_bind_param(
    $feedback_stmt,
    "i",
    $project_id
);

mysqli_stmt_execute($feedback_stmt);

$feedback_result =
    mysqli_stmt_get_result($feedback_stmt);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Review Project - Mentra
    </title>

    <link rel="stylesheet"
          href="supervisor.css">

</head>


<body>


<header class="header">

    <h2>Mentra</h2>

    <div>

        <span>
            <?php
            echo htmlspecialchars($_SESSION['name']);
            ?>
        </span>

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="../backend/logout.php">
            Logout
        </a>

    </div>

</header>


<main class="container">


    <!-- Student information -->

    <section class="card">

        <h2>Student Information</h2>

        <p>
            <strong>Name:</strong>

            <?php
            echo htmlspecialchars(
                $project['full_name']
            );
            ?>
        </p>


        <p>
            <strong>Email:</strong>

            <?php
            echo htmlspecialchars(
                $project['email']
            );
            ?>
        </p>


        <p>
            <strong>Roll Number:</strong>

            <?php
            echo htmlspecialchars(
                $project['roll_number']
            );
            ?>
        </p>


        <p>
            <strong>Department:</strong>

            <?php
            echo htmlspecialchars(
                $project['department']
            );
            ?>
        </p>

    </section>


    <!-- Project -->

    <section class="card">

        <h1>
            <?php
            echo htmlspecialchars(
                $project['title']
            );
            ?>
        </h1>


        <p>

            <strong>Category:</strong>

            <?php
            echo htmlspecialchars(
                $project['category']
            );
            ?>

        </p>


        <p>

            <strong>Status:</strong>

            <span class="status">

                <?php
                echo htmlspecialchars(
                    $project['status']
                );
                ?>

            </span>

        </p>


        <h3>
            Project Description
        </h3>


        <p class="description">

            <?php

            echo nl2br(
                htmlspecialchars(
                    $project['description']
                )
            );

            ?>

        </p>


        <a
            href="feedback.php?id=<?php
                echo $project_id;
            ?>"
            class="feedback-btn"
        >
            Give Feedback
        </a>

    </section>


    <!-- Previous feedback -->

    <section class="card">

        <h2>
            Feedback History
        </h2>


        <?php if (mysqli_num_rows($feedback_result) > 0): ?>

            <?php while ($feedback =
                mysqli_fetch_assoc($feedback_result)): ?>

                <div class="feedback-card">

                    <h3>

                        <?php
                        echo htmlspecialchars(
                            $feedback['full_name']
                        );
                        ?>

                    </h3>


                    <p>

                        <?php
                        echo nl2br(
                            htmlspecialchars(
                                $feedback['feedback']
                            )
                        );
                        ?>

                    </p>


                    <strong>

                        <?php
                        echo htmlspecialchars(
                            $feedback['status']
                        );
                        ?>

                    </strong>

                    <small>

                        <?php
                        echo date(
                            "d M Y",
                            strtotime(
                                $feedback['created_at']
                            )
                        );
                        ?>

                    </small>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <p>
                No feedback given yet.
            </p>

        <?php endif; ?>

    </section>


</main>

</body>
</html>