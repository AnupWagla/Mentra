<?php

include "../db.php";

$id = $_POST['id'];
$title = $_POST['title'];
$description = $_POST['description'];

$sql = "UPDATE projects
        SET title = ?, description = ?
        WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssi",
    $title,
    $description,
    $id
);

if (mysqli_stmt_execute($stmt)) {
    echo "Project updated.";
} else {
    echo "Update failed.";
}

?>