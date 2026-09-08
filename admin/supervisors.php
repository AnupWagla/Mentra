<?php require 'auth.php'; ?>
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
                    <option value="1">BCA</option>
                    <option value="2">BIT</option>
                    <option value="3">BSc CSIT</option>
                </select>
            </div>
            <button type="submit" class="btn">Add Supervisor</button>
        </form>
    </div>

    <div class="card">
        <p class="card-title">All Supervisors</p>
        <table>
            <thead>
                <tr><th>Name</th><th>Email</th><th>Department</th></tr>
            </thead>
            <tbody>
                <tr><td colspan="3" style="text-align:center;color:#b8cde8;">No supervisors found.</td></tr>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
