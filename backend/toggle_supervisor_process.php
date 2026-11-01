<?php
require_once "admin_check.php";
require_once "db.php";
/** @var mysqli $conn */

if (!isset($_GET['id']) || intval($_GET['id']) <= 0) {
    header("Location: ../admin/supervisors.php");
    exit();
}

$supervisor_id = intval($_GET['id']);

// Validate user exists and role='supervisor'
$sql = "SELECT id, is_active FROM users WHERE id = ? AND role = 'supervisor'";
$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("Database error preparing query.");
}

mysqli_stmt_bind_param($stmt, "i", $supervisor_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$supervisor = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$supervisor) {
    header("Location: ../admin/supervisors.php?error=notfound");
    exit();
}

// Toggle is_active
$new_status = ($supervisor['is_active'] == 1) ? 0 : 1;

$update_sql = "UPDATE users SET is_active = ? WHERE id = ? AND role = 'supervisor'";
$update_stmt = mysqli_prepare($conn, $update_sql);
if (!$update_stmt) {
    die("Database error preparing update.");
}

mysqli_stmt_bind_param($update_stmt, "ii", $new_status, $supervisor_id);
mysqli_stmt_execute($update_stmt);
mysqli_stmt_close($update_stmt);

header("Location: ../admin/supervisors.php?toggled=1");
exit();
