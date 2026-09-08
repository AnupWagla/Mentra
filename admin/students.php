<?php require 'auth.php'; ?>
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

    <div class="card">
        <p class="card-title">All Students</p>
        <table>
            <thead>
                <tr><th>Name</th><th>Email</th><th>Roll No.</th><th>Department</th></tr>
            </thead>
            <tbody>
                <tr><td colspan="4" style="text-align:center;color:#b8cde8;">No students found.</td></tr>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
