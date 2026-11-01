<?php
require_once "admin_check.php";
require_once "../db.php";
/** @var mysqli $conn */

$supervisor_id = intval($_GET['id'] ?? 0);
if ($supervisor_id <= 0) {
    header("Location: ../admin/supervisors.php");
    exit();
}

// Verify supervisor exists and role is supervisor
$check_sql = "SELECT id FROM users WHERE id = ? AND role = 'supervisor'";
$check_stmt = mysqli_prepare($conn, $check_sql);
mysqli_stmt_bind_param($check_stmt, "i", $supervisor_id);
mysqli_stmt_execute($check_stmt);
$check_res = mysqli_stmt_get_result($check_stmt);
if (mysqli_num_rows($check_res) !== 1) {
    // Not found or not a supervisor
    mysqli_stmt_close($check_stmt);
    header("Location: ../admin/supervisors.php");
    exit();
}
mysqli_stmt_close($check_stmt);

// Delete assignments first to maintain referential integrity
$del_assign_sql = "DELETE FROM supervisor_assignments WHERE supervisor_id = ?";
$del_assign_stmt = mysqli_prepare($conn, $del_assign_sql);
mysqli_stmt_bind_param($del_assign_stmt, "i", $supervisor_id);
mysqli_stmt_execute($del_assign_stmt);
mysqli_stmt_close($del_assign_stmt);

// Delete the supervisor user record
$del_user_sql = "DELETE FROM users WHERE id = ?";
$del_user_stmt = mysqli_prepare($conn, $del_user_sql);
mysqli_stmt_bind_param($del_user_stmt, "i", $supervisor_id);
mysqli_stmt_execute($del_user_stmt);
mysqli_stmt_close($del_user_stmt);

header("Location: ../admin/supervisors.php?deleted=1");
exit();
?>
