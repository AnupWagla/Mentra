<?php require 'auth.php'; ?>
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
                <tr><th>#</th><th>Project Title</th><th>Student</th><th>Supervisor</th><th>Department</th><th>Status</th></tr>
            </thead>
            <tbody>
                <tr><td colspan="6" style="text-align:center;color:#b8cde8;">No projects found.</td></tr>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
