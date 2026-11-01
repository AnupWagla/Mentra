<?php
require_once __DIR__ . "/../account/auth_check.php";
require_once __DIR__ . "/../db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: submit_project.php");
    exit();
}

$student_id  = $_SESSION["user_id"];
$title       = trim($_POST["title"]       ?? "");
$category    = trim($_POST["category"]    ?? "");
$description = trim($_POST["description"] ?? "");
$git_link    = trim($_POST["git_link"]    ?? "");

if ($title === "" || $category === "" || $description === "") {
    header("Location: submit_project.php?error=" . urlencode("Please fill in all required fields."));
    exit();
}

// Validate git link format if provided
if ($git_link !== "" && !filter_var($git_link, FILTER_VALIDATE_URL)) {
    header("Location: submit_project.php?error=" . urlencode("Please enter a valid URL for the repository link."));
    exit();
}

// Handle file upload (optional)
$file_name = null;
if (isset($_FILES['project_file']) && $_FILES['project_file']['error'] === UPLOAD_ERR_OK) {
    $upload_dir  = __DIR__ . "/../uploads/projects/";
    $original    = basename($_FILES['project_file']['name']);
    $ext         = strtolower(pathinfo($original, PATHINFO_EXTENSION));
    $allowed_ext = ['pdf', 'doc', 'docx', 'zip', 'rar', 'txt', 'ppt', 'pptx'];

    if (!in_array($ext, $allowed_ext)) {
        header("Location: submit_project.php?error=" . urlencode("Invalid file type. Allowed: PDF, DOC, DOCX, ZIP, RAR, TXT, PPT, PPTX."));
        exit();
    }

    $max_size = 10 * 1024 * 1024; // 10 MB
    if ($_FILES['project_file']['size'] > $max_size) {
        header("Location: submit_project.php?error=" . urlencode("File is too large. Maximum size is 10 MB."));
        exit();
    }

    // Unique safe filename
    $file_name = time() . "_" . $student_id . "_" . preg_replace('/[^a-zA-Z0-9._-]/', '_', $original);
    if (!move_uploaded_file($_FILES['project_file']['tmp_name'], $upload_dir . $file_name)) {
        header("Location: submit_project.php?error=" . urlencode("File upload failed. Please try again."));
        exit();
    }
}

// Insert project
$sql  = "INSERT INTO projects (student_id, title, description, category, git_link)
         VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    exit("Database error: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($stmt, "issss", $student_id, $title, $description, $category, $git_link);

if (!mysqli_stmt_execute($stmt)) {
    $err = mysqli_stmt_error($stmt);
    mysqli_stmt_close($stmt);
    exit("Project submission failed: " . htmlspecialchars($err));
}
$project_id = mysqli_insert_id($conn);
mysqli_stmt_close($stmt);

// Insert first version (version 1)
$ver_sql  = "INSERT INTO project_versions (project_id, version_number, description, file_name, git_link, status)
             VALUES (?, 1, ?, ?, ?, 'Submitted')";
$ver_stmt = mysqli_prepare($conn, $ver_sql);
if (!$ver_stmt) {
    exit("Project saved but version history could not be prepared: " . mysqli_error($conn));
}
mysqli_stmt_bind_param($ver_stmt, "isss", $project_id, $description, $file_name, $git_link);

if (!mysqli_stmt_execute($ver_stmt)) {
    $err = mysqli_stmt_error($ver_stmt);
    mysqli_stmt_close($ver_stmt);
    exit("Project saved but version history failed: " . htmlspecialchars($err));
}
mysqli_stmt_close($ver_stmt);
mysqli_close($conn);

header("Location: view_project.php?id=" . $project_id);
exit();
?>
