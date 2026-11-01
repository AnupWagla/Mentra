<?php
require_once "../backend/admin_check.php";
require_once "../db.php";
/** @var mysqli $conn */

$supervisor_id = intval($_GET['id'] ?? 0);
if ($supervisor_id <= 0) {
    header("Location: supervisors.php");
    exit();
}

// Fetch supervisor info for confirmation display
$sql = "SELECT full_name, email FROM users WHERE id = ? AND role = 'supervisor'";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $supervisor_id);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($res) !== 1) {
    header("Location: supervisors.php");
    exit();
}
$supervisor = mysqli_fetch_assoc($res);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Supervisor – Mentra</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="page-wrapper">
    <main class="container">
        <h1>Delete Supervisor</h1>
        <p class="subtitle">Are you sure you want to permanently delete supervisor <strong><?php echo htmlspecialchars($supervisor['full_name']); ?></strong> (<?php echo htmlspecialchars($supervisor['email']); ?>)? This action cannot be undone.</p>
        <a href="../backend/delete_supervisor_process.php?id=<?php echo $supervisor_id; ?>" class="action-link action-delete" onclick="return confirm('Confirm permanent deletion of this supervisor?');">Confirm Delete</a>
        <a href="supervisors.php" class="action-link action-view" style="margin-left:12px;">Cancel</a>
    </main>
</div>
</body>
</html>
