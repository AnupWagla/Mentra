<?php
require_once "../backend/admin_check.php";
require_once "../db.php";
/** @var mysqli $conn */

if (!isset($_GET['id']) || intval($_GET['id']) <= 0) {
    header("Location: supervisors.php");
    exit();
}

$supervisor_id = intval($_GET['id']);

// Fetch supervisor details
$sql = "SELECT id, full_name, email, phone, department, is_active, created_at
        FROM users
        WHERE id = ? AND role = 'supervisor'";

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
    header("Location: supervisors.php?error=notfound");
    exit();
}

// Fetch assigned projects
$proj_sql = "SELECT p.id, p.title, p.category, p.status, p.created_at,
                    u.full_name AS student_name, u.email AS student_email, u.roll_number AS student_roll
             FROM supervisor_assignments sa
             JOIN projects p ON sa.project_id = p.id
             JOIN users u ON p.student_id = u.id
             WHERE sa.supervisor_id = ?
             ORDER BY p.created_at DESC";

$proj_stmt = mysqli_prepare($conn, $proj_sql);
if (!$proj_stmt) {
    die("Database error preparing projects query.");
}

mysqli_stmt_bind_param($proj_stmt, "i", $supervisor_id);
mysqli_stmt_execute($proj_stmt);
$projects_result = mysqli_stmt_get_result($proj_stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($supervisor['full_name']); ?> - Supervisor Details</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<?php include 'sidebar.php'; ?>
<div class="page-wrapper">
<main class="container">
    <h1>Supervisor Details</h1>
    <p class="subtitle">Overview and assigned student projects</p>

    <!-- Supervisor Info Card -->
    <div class="detail-card">
        <div class="detail-grid">
            <div class="detail-item">
                <div class="label">Full Name</div>
                <div class="value"><?php echo htmlspecialchars($supervisor['full_name']); ?></div>
            </div>
            <div class="detail-item">
                <div class="label">Email Address</div>
                <div class="value"><?php echo htmlspecialchars($supervisor['email']); ?></div>
            </div>
            <div class="detail-item">
                <div class="label">Phone Number</div>
                <div class="value"><?php echo htmlspecialchars($supervisor['phone'] ?: '—'); ?></div>
            </div>
            <div class="detail-item">
                <div class="label">Department</div>
                <div class="value"><?php echo htmlspecialchars($supervisor['department'] ?? '—'); ?></div>
            </div>
            <div class="detail-item">
                <div class="label">Account Status</div>
                <div class="value">
                    <?php if (!empty($supervisor['is_active'])): ?>
                        <span class="status-badge status-active">Active</span>
                    <?php else: ?>
                        <span class="status-badge status-inactive">Inactive</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="detail-item">
                <div class="label">Member Since</div>
                <div class="value"><?php echo date('M d, Y', strtotime($supervisor['created_at'])); ?></div>
            </div>
        </div>
    </div>

    <!-- Assigned Projects Table -->
    <h2 class="section-title">
        <span>Assigned Projects</span>
        <span style="font-size: 14px; color: #7a9abf; font-weight: normal;">
            Total: <?php echo mysqli_num_rows($projects_result); ?>
        </span>
    </h2>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Project Title</th>
                    <th>Student Name</th>
                    <th>Status</th>
                    <th>Submitted Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($projects_result) > 0): ?>
                <?php while ($proj = mysqli_fetch_assoc($projects_result)): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($proj['title']); ?></strong>
                            <?php if (!empty($proj['category'])): ?>
                                <br><small style="color: #7a9abf;"><?php echo htmlspecialchars($proj['category']); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($proj['student_name']); ?>
                            <?php if (!empty($proj['student_roll'])): ?>
                                <br><small style="color: #7a9abf;">Roll: <?php echo htmlspecialchars($proj['student_roll']); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($proj['status']); ?>
                        </td>
                        <td>
                            <?php echo date('M d, Y', strtotime($proj['created_at'])); ?>
                        </td>
                        <td>
                            <a href="assign_supervisor.php?id=<?php echo (int) $proj['id']; ?>" class="assign-btn">
                                Manage Assignment
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; color: #7a9abf; padding: 30px;">
                        No projects assigned to this supervisor yet.
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
</div>

</body>
</html>
