
<?php

require_once __DIR__ . "/../backend/auth_check.php";
require_once __DIR__ . "/../backend/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: submit_project.php");
    exit();
}

$student_id = $_SESSION["user_id"];

$title = trim($_POST["title"] ?? "");
$category = trim($_POST["category"] ?? "");
$description = trim($_POST["description"] ?? "");

if ($title === "" || $category === "" || $description === "") {
    exit("Please fill in all required fields.");
}

// Insert the project.
$sql = "INSERT INTO projects
        (student_id, title, description, category)
        VALUES (?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    exit("Database error: " . mysqli_error($conn));
}

// i = integer, s = string
mysqli_stmt_bind_param(
    $stmt,
    "isss",
    $student_id,
    $title,
    $description,
    $category
);

if (mysqli_stmt_execute($stmt)) {

    $project_id = mysqli_insert_id($conn);

    // Save the first project version.
    $version_sql = "INSERT INTO project_versions
                    (project_id, version_number, description, status)
                    VALUES (?, 1, ?, 'Submitted')";

    $version_stmt = mysqli_prepare($conn, $version_sql);

    if (!$version_stmt) {
        mysqli_stmt_close($stmt);
        exit("Project was saved, but version history could not be prepared: "
            . mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $version_stmt,
        "is",
        $project_id,
        $description
    );

    if (mysqli_stmt_execute($version_stmt)) {

        mysqli_stmt_close($version_stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        header("Location: view_project.php?id=" . $project_id);
        exit();

    } else {
        $error = mysqli_stmt_error($version_stmt);

        mysqli_stmt_close($version_stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        exit("Project was saved, but version history failed: "
            . htmlspecialchars($error));
    }

} else {
    $error = mysqli_stmt_error($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    exit("Project submission failed: " . htmlspecialchars($error));
}

?>
