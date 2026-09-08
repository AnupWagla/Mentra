<?php
require 'auth.php';
include '../backend/db.php';

$projects = @mysqli_query($conn, "SELECT p.id, p.title, p.status,
    u.name AS student,
    d.name AS dept,
    sv.name AS supervisor
    FROM projects p
    LEFT JOIN users u   ON p.student_id    = u.id
    LEFT JOIN users sv  ON p.supervisor_id = sv.id
    LEFT JOIN departments d ON u.dept_id   = d.id
    ORDER BY p.id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Projects - Mentra Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">

    <h1 class="page-title">All Projects</h1>
    <p class="page-sub">View and manage all student projects.</p>

    <div class="card">
        <p class="card-title">Projects List</p>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Project Title</th>
                    <th>Student</th>
                    <th>Supervisor</th>
                    <th>Department</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($projects && mysqli_num_rows($projects) > 0):
                    $i = 1;
                    while ($p = mysqli_fetch_assoc($projects)): ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= htmlspecialchars($p['title']) ?></td>
                        <td><?= htmlspecialchars($p['student'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($p['supervisor'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($p['dept'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($p['status'] ?? '—') ?></td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;color:#b8cde8;">No projects found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
