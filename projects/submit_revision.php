
<?php

require_once __DIR__ . "/../backend/auth_check.php";
require_once __DIR__ . "/../backend/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: view_project.php");
    exit();
}

if ($_SESSION["role"] !== "student") {
    die("Access denied.");
}

$student_id = $_SESSION["user_id"];
$project_id = intval($_POST["project_id"] ?? 0);
$description = trim($_POST["description"] ?? "");

if ($project_id <= 0 || $description === "") {
    die("Please enter a valid project description.");
}

// Check that this project belongs to the logged-in student
$sql = "SELECT id, status
        FROM projects
        WHERE id = ? AND student_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $project_id, $student_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$project = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$project) {
    die("Project not found or access denied.");
}

if ($project["status"] !== "Revision Required") {
    die("This project does not currently require revision.");
}

// Find the next version number
$sql = "SELECT COALESCE(MAX(version_number), 0) + 1 AS next_version
        FROM project_versions
        WHERE project_id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $project_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
$version_number = (int) $row["next_version"];

mysqli_stmt_close($stmt);

// Save the revised proposal and its new version
mysqli_begin_transaction($conn);

try {
    $sql = "INSERT INTO project_versions
            (project_id, version_number, description, status)
            VALUES (?, ?, ?, 'Under Review')";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "iis",
        $project_id,
        $version_number,
        $description
    );
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $sql = "UPDATE projects
            SET description = ?, status = 'Under Review'
            WHERE id = ? AND student_id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param(
        $stmt,
        "sii",
        $description,
        $project_id,
        $student_id
    );
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    mysqli_commit($conn);

    header("Location: view_project.php?id=" . $project_id);
    exit();

} catch (Throwable $e) {
    mysqli_rollback($conn);
    error_log($e->getMessage());
    die("Unable to submit revision. Please try again.");
}
?>
