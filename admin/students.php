<?php
require 'auth.php';
include '../backend/db.php';

$students = @mysqli_query($conn, "SELECT u.id, u.name, u.email, d.name AS dept
    FROM users u
    LEFT JOIN departments d ON u.dept_id = d.id
    WHERE u.role='student'
    ORDER BY u.name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students - Mentra Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">

    <h1 class="page-title">Students</h1>
    <p class="page-sub">View all registered students.</p>

    <?php if (isset($_GET['success'])): ?>
        <p class="msg-success">✓ Student removed.</p>
    <?php endif; ?>

    <div class="card">
        <p class="card-title">All Students</p>
        <table>
            <thead>
                <tr><th>Name</th><th>Email</th><th>Department</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php if ($students && mysqli_num_rows($students) > 0):
                    while ($s = mysqli_fetch_assoc($students)): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['name']) ?></td>
                        <td><?= htmlspecialchars($s['email']) ?></td>
                        <td><?= htmlspecialchars($s['dept'] ?? '—') ?></td>
                        <td><a href="../backend/admin/delete_user.php?id=<?= $s['id'] ?>&back=students" onclick="return confirm('Delete this student?')" style="color:#e07;">Delete</a></td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="4" style="text-align:center;color:#b8cde8;">No students found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
