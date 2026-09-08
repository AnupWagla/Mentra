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

$sql = "SELECT *
        FROM projects
        WHERE id = ?";


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

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Give Feedback - Mentra
    </title>

    <link rel="stylesheet"
          href="supervisor.css">

</head>


<body>


<header class="header">

    <h2>Mentra</h2>

    <a href="dashboard.php">
        Dashboard
    </a>

</header>


<main class="container">

    <section class="card">

        <h1>
            Review Project
        </h1>


        <h2>
            <?php
            echo htmlspecialchars(
                $project['title']
            );
            ?>
        </h2>


        <p>
            <?php
            echo nl2br(
                htmlspecialchars(
                    $project['description']
                )
            );
            ?>
        </p>

    </section>


    <section class="card">

        <h2>
            Submit Feedback
        </h2>


        <form
            action="../backend/save_feedback.php"
            method="POST"
        >

            <input
                type="hidden"
                name="project_id"
                value="<?php
                    echo $project_id;
                ?>"
            >


            <label for="feedback">
                Feedback
            </label>


            <textarea
                id="feedback"
                name="feedback"
                rows="8"
                placeholder="Write your feedback..."
                required
            ></textarea>


            <label for="status">
                Project Status
            </label>


            <select
                name="status"
                id="status"
                required
            >

                <option value="Under Review">
                    Under Review
                </option>

                <option value="Revision Required">
                    Revision Required
                </option>

                <option value="Approved">
                    Approved
                </option>

                <option value="Rejected">
                    Rejected
                </option>

            </select>


            <button type="submit">
                Submit Feedback
            </button>

        </form>

    </section>

</main>

</body>
</html>