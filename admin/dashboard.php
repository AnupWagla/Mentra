<?php require 'auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Mentra Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">

    <h1 class="page-title">Dashboard</h1>
    <p class="page-sub">Welcome back, Admin. Here's an overview.</p>

    <div class="stats-grid">

        <div class="stat-card">
            <div class="stat-card-icon">📋</div>
            <div class="stat-card-body">
                <span class="stat-value">0</span>
                <span class="stat-label">Total Projects</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon">👨‍🎓</div>
            <div class="stat-card-body">
                <span class="stat-value">0</span>
                <span class="stat-label">Total Students</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon">👨‍🏫</div>
            <div class="stat-card-body">
                <span class="stat-value">0</span>
                <span class="stat-label">Total Supervisors</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon">📄</div>
            <div class="stat-card-body">
                <span class="stat-value">0</span>
                <span class="stat-label">Total Documents</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon">⏳</div>
            <div class="stat-card-body">
                <span class="stat-value">0</span>
                <span class="stat-label">Docs Pending</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon">🏛</div>
            <div class="stat-card-body">
                <span class="stat-value">0</span>
                <span class="stat-label">Departments</span>
            </div>
        </div>

    </div>

</div>
</body>
</html>
