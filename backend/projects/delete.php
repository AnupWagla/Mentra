<?php

include "../db.php";

$id = $_POST['id'];

$sql = "DELETE FROM projects WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    echo "Project deleted.";
} else {
    echo "Delete failed.";
}

?>