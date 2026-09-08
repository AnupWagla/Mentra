<?php require 'auth.php'; ?>
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
                <tr><td>BCA</td><td>Bachelor of Computer Application</td><td>0</td><td>0</td></tr>
                <tr><td>BIT</td><td>Bachelor of Information Technology</td><td>0</td><td>0</td></tr>
                <tr><td>BSc CSIT</td><td>BSc Computer Science &amp; IT</td><td>0</td><td>0</td></tr>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
