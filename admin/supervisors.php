<?php
require_once "../backend/admin_check.php";
require_once "../db.php";
/** @var mysqli $conn */

$q = trim($_GET['q'] ?? '');

if ($q !== '') {
    $search_param = '%' . $q . '%';
    $sql = "SELECT u.id, u.full_name, u.email, u.phone, u.department, u.is_active, u.created_at,
                   (SELECT COUNT(DISTINCT p.student_id)
                    FROM supervisor_assignments sa
                    JOIN projects p ON sa.project_id = p.id
                    WHERE sa.supervisor_id = u.id) AS student_count
            FROM users u
            WHERE u.role = 'supervisor'
              AND (u.full_name LIKE ? OR u.email LIKE ?)
            ORDER BY u.full_name";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        die("Database error preparing query.");
    }
    mysqli_stmt_bind_param($stmt, "ss", $search_param, $search_param);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
} else {
    $sql = "SELECT u.id, u.full_name, u.email, u.phone, u.department, u.is_active, u.created_at,
                   (SELECT COUNT(DISTINCT p.student_id)
                    FROM supervisor_assignments sa
                    JOIN projects p ON sa.project_id = p.id
                    WHERE sa.supervisor_id = u.id) AS student_count
            FROM users u
            WHERE u.role = 'supervisor'
            ORDER BY u.full_name";

    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die("Database error loading supervisors.");
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisors - Mentra</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<?php include 'sidebar.php'; ?>
<div class="page-wrapper">
<main class="container">
    <h1>Supervisors</h1>
    <p class="subtitle">Manage project supervisors and their assignments</p>

    <?php if (isset($_GET['created'])): ?>
        <div class="alert-success">
            Supervisor account created successfully.
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted'])): ?>
        <div class="alert-success">
            Supervisor deleted successfully.
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['toggled'])): ?>
        <div class="alert-success">
            Supervisor status updated successfully.
        </div>
    <?php endif; ?>

    <div class="table-toolbar">
        <form method="GET" action="supervisors.php" class="search-form">
            <input type="search" name="q" placeholder="Search by name or email..." value="<?php echo htmlspecialchars($q); ?>">
            <button type="submit" class="btn-secondary" style="margin-top: 0; width: auto; padding: 11px 18px;">Search</button>
            <?php if ($q !== ''): ?>
                <a href="supervisors.php" class="btn-secondary" style="margin-top: 0;">Clear</a>
            <?php endif; ?>
        </form>

        <a href="create_supervisor.php" class="btn-primary">+ Create Supervisor</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Phone</th>
                    <th>Students Assigned</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($supervisor = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($supervisor['full_name']); ?></strong>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($supervisor['email']); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($supervisor['department'] ?? '—'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($supervisor['phone'] ?: '—'); ?>
                        </td>
                        <td>
                            <?php echo (int) $supervisor['student_count']; ?>
                        </td>
                        <td>
                            <?php if (!empty($supervisor['is_active'])): ?>
                                <span class="status-badge status-active">Active</span>
                            <?php else: ?>
                                <span class="status-badge status-inactive">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="actions-cell">
                                <a href="supervisor_detail.php?id=<?php echo (int) $supervisor['id']; ?>" class="action-link action-view">View</a>
                                <?php if (!empty($supervisor['is_active'])): ?>
                                    <a href="../backend/toggle_supervisor_process.php?id=<?php echo (int) $supervisor['id']; ?>"
                                       class="action-link action-deactivate"
                                       onclick="return confirm('Are you sure you want to deactivate this supervisor?');">Deactivate</a>
                                <?php else: ?>
                                    <a href="../backend/toggle_supervisor_process.php?id=<?php echo (int) $supervisor['id']; ?>"
                                       class="action-link action-activate">Activate</a>
                                <?php endif; ?>
                                <a href="delete_supervisor.php?id=<?php echo (int) $supervisor['id']; ?>"
                                   class="action-link action-delete"
                                   onclick="return confirm('Are you sure you want to permanently delete this supervisor?');">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: #7a9abf; padding: 30px;">
                        No supervisors found<?php echo $q !== '' ? ' matching "' . htmlspecialchars($q) . '"' : ''; ?>.
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