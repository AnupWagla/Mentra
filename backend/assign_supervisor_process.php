<?php

require_once "admin_check.php";
require_once "db.php";
/** @var mysqli $conn */


if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    header("Location: ../admin/projects.php");
    exit();

}


$project_id = intval($_POST['project_id']);
$supervisor_id = intval($_POST['supervisor_id']);


/*
    Check supervisor
*/

$sql = "SELECT id
        FROM users
        WHERE id = ?
        AND role = 'supervisor'";


$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $supervisor_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


if (mysqli_num_rows($result) !== 1) {

    die("Invalid supervisor.");

}

mysqli_stmt_close($stmt);


/*
    Check if assignment already exists
*/

$check_sql = "SELECT id
              FROM supervisor_assignments
              WHERE project_id = ?";


$check_stmt = mysqli_prepare(
    $conn,
    $check_sql
);

mysqli_stmt_bind_param(
    $check_stmt,
    "i",
    $project_id
);

mysqli_stmt_execute($check_stmt);

$check_result =
    mysqli_stmt_get_result(
        $check_stmt
    );


if (mysqli_num_rows($check_result) > 0) {

    /*
       Update existing assignment
    */

    $update_sql = "UPDATE supervisor_assignments

                   SET supervisor_id = ?

                   WHERE project_id = ?";


    $update_stmt = mysqli_prepare(
        $conn,
        $update_sql
    );

    mysqli_stmt_bind_param(
        $update_stmt,
        "ii",
        $supervisor_id,
        $project_id
    );

    mysqli_stmt_execute($update_stmt);

    mysqli_stmt_close($update_stmt);

} else {

    /*
       Create new assignment
    */

    $insert_sql = "INSERT INTO
                   supervisor_assignments
                   (project_id, supervisor_id)

                   VALUES (?, ?)";


    $insert_stmt = mysqli_prepare(
        $conn,
        $insert_sql
    );

    mysqli_stmt_bind_param(
        $insert_stmt,
        "ii",
        $project_id,
        $supervisor_id
    );

    mysqli_stmt_execute($insert_stmt);

    mysqli_stmt_close($insert_stmt);

}


mysqli_stmt_close($check_stmt);


/*
    Change project status
*/

$status_sql = "UPDATE projects
               SET status = 'Under Review'
               WHERE id = ?";


$status_stmt = mysqli_prepare(
    $conn,
    $status_sql
);

mysqli_stmt_bind_param(
    $status_stmt,
    "i",
    $project_id
);

mysqli_stmt_execute($status_stmt);

mysqli_stmt_close($status_stmt);


header(
    "Location: ../admin/projects.php"
);

exit();

?>