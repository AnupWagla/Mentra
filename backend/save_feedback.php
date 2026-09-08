<?php

require_once "supervisor_check.php";
require_once "db.php";
/** @var mysqli $conn */


if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: ../supervisor/dashboard.php");
    exit();

}


$project_id = intval($_POST['project_id']);
$feedback = trim($_POST['feedback']);
$status = $_POST['status'];

$supervisor_id = $_SESSION['user_id'];


$allowed_status = [
    "Under Review",
    "Revision Required",
    "Approved",
    "Rejected"
];


if (!in_array($status, $allowed_status)) {

    die("Invalid status.");

}


if (empty($feedback)) {

    die("Feedback cannot be empty.");

}


/*
    Insert feedback
*/

$sql = "INSERT INTO feedback
        (project_id, supervisor_id, feedback, status)
        VALUES (?, ?, ?, ?)";


$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "iiss",
    $project_id,
    $supervisor_id,
    $feedback,
    $status
);


if (!mysqli_stmt_execute($stmt)) {

    die(
        "Could not save feedback: " .
        mysqli_stmt_error($stmt)
    );

}

mysqli_stmt_close($stmt);


/*
    Update project status
*/

$update_sql = "UPDATE projects
               SET status = ?
               WHERE id = ?";


$update_stmt = mysqli_prepare(
    $conn,
    $update_sql
);

mysqli_stmt_bind_param(
    $update_stmt,
    "si",
    $status,
    $project_id
);

mysqli_stmt_execute($update_stmt);

mysqli_stmt_close($update_stmt);


header(
    "Location: ../supervisor/view_project.php?id=" .
    $project_id
);

exit();

?>