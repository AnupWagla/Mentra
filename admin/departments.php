<?php
require 'auth.php';
include '../backend/db.php';

$departments = @mysqli_query($conn, "SELECT d.id, d.name, d.description,
    (SELECT COUNT(*) FROM users WHERE dept_id = d.id AND role='student') AS students,
    (SELECT COUNT(*) FROM users WHERE dept_id = d.id AND role='supervisor') AS supervisors
    FROM departments d ORDER BY d.name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departments - Mentra Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">

    <h1 class="page-title">Departments</h1>
    <p class="page-sub">Manage college departments.</p>

    <?php if (isset($_GET['success'])): ?>
        <p class="msg-success">✓ Department added successfully.</p>
    <?php endif; ?>

    <div class="card">
        <p class="card-title">Add Department</p>
        <form method="POST" action="../backend/admin/add_department.php">
            <div class="form-group">
                <label>Department Name</label>
                <input type="text" name="name" placeholder="e.g. BCA" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <input type="text" name="description" placeholder="Short description (optional)">
            </div>
            <button type="submit" class="btn">Add Department</button>
        </form>
    </div>

    <div class="card">
        <p class="card-title">All Departments</p>
        <table>
            <thead>
                <tr><th>Name</th><th>Description</th><th>Students</th><th>Supervisors</th></tr>
            </thead>
            <tbody>
                <?php if ($departments && mysqli_num_rows($departments) > 0):
                    while ($d = mysqli_fetch_assoc($departments)): ?>
                    <tr>
                        <td><?= htmlspecialchars($d['name']) ?></td>
                        <td><?= htmlspecialchars($d['description'] ?? '—') ?></td>
                        <td><?= $d['students'] ?></td>
                        <td><?= $d['supervisors'] ?></td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="4" style="text-align:center;color:#b8cde8;">No departments found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
