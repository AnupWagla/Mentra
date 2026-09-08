<?php
require 'auth.php';
include '../backend/db.php';

// Load departments for dropdown
$depts = @mysqli_query($conn, "SELECT id, name FROM departments ORDER BY name");

// Load all supervisors
$supervisors = @mysqli_query($conn, "SELECT u.id, u.name, u.email, d.name AS dept FROM users u LEFT JOIN departments d ON u.dept_id = d.id WHERE u.role='supervisor' ORDER BY u.name");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisors - Mentra Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">

    <h1 class="page-title">Supervisors</h1>
    <p class="page-sub">Add and manage supervisors.</p>

    <?php if (isset($_GET['success'])): ?>
        <p class="msg-success">✓ Supervisor added successfully. Default password: <strong>supervisor123</strong></p>
    <?php endif; ?>
    <?php if (isset($_GET['error']) && $_GET['error'] === 'exists'): ?>
        <p class="msg-error">✗ Email already exists.</p>
    <?php endif; ?>

    <div class="card">
        <p class="card-title">Add Supervisor</p>
        <form method="POST" action="../backend/admin/add_supervisor.php">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" placeholder="e.g. Dr. Anita Thapa" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="supervisor@college.edu" required>
            </div>
            <div class="form-group">
                <label>Department</label>
                <select name="dept_id" required>
                    <option value="">— Select Department —</option>
                    <?php if ($depts): while ($d = mysqli_fetch_assoc($depts)): ?>
                        <option value="<?= $d['id'] ?>"><?= htmlspecialchars($d['name']) ?></option>
                    <?php endwhile; endif; ?>
                </select>
            </div>
            <button type="submit" class="btn">Add Supervisor</button>
        </form>
    </div>

    <div class="card">
        <p class="card-title">All Supervisors</p>
        <table>
            <thead>
                <tr><th>Name</th><th>Email</th><th>Department</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php if ($supervisors && mysqli_num_rows($supervisors) > 0):
                    while ($s = mysqli_fetch_assoc($supervisors)): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['name']) ?></td>
                        <td><?= htmlspecialchars($s['email']) ?></td>
                        <td><?= htmlspecialchars($s['dept'] ?? '—') ?></td>
                        <td><a href="../backend/admin/delete_user.php?id=<?= $s['id'] ?>&back=supervisors" onclick="return confirm('Delete this supervisor?')" style="color:#e07;">Delete</a></td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="4" style="text-align:center;color:#b8cde8;">No supervisors found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
