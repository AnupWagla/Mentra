<?php

include "../db.php";
/** @var mysqli $conn */

$sql = "SELECT * FROM projects";

$result = mysqli_query($conn, $sql);

while ($project = mysqli_fetch_assoc($result)) {

    echo "Title: " . $project['title'];
    echo "<br>";

    echo "Description: " . $project['description'];
    echo "<br><br>";
}

?>