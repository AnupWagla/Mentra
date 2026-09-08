<?php
require 'auth.php';
include '../backend/db.php';

// Fetch counts — fall back to 0 if table doesn't exist yet
function safe_count($conn, $sql) {
    $r = @mysqli_query($conn, $sql);
    if (!$r) return 0;
    $row = mysqli_fetch_row($r);
    return $row ? (int)$row[0] : 0;
}

$total_projects    = safe_count($conn, "SELECT COUNT(*) FROM projects");
$total_students    = safe_count($conn, "SELECT COUNT(*) FROM users WHERE role='student'");
$total_supervisors = safe_count($conn, "SELECT COUNT(*) FROM users WHERE role='supervisor'");
$total_documents   = safe_count($conn, "SELECT COUNT(*) FROM documents");
$docs_pending      = safe_count($conn, "SELECT COUNT(*) FROM documents WHERE status='pending'");
$total_departments = safe_count($conn, "SELECT COUNT(*) FROM departments");
?>
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
                <span class="stat-value"><?= $total_projects ?></span>
                <span class="stat-label">Total Projects</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon">👨‍🎓</div>
            <div class="stat-card-body">
                <span class="stat-value"><?= $total_students ?></span>
                <span class="stat-label">Total Students</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon">👨‍🏫</div>
            <div class="stat-card-body">
                <span class="stat-value"><?= $total_supervisors ?></span>
                <span class="stat-label">Total Supervisors</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon">\📄</div>
            <div class="stat-card-body">
                <span class="stat-value"><?= $total_documents ?></span>
                <span class="stat-label">Total Documents</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon">⏳</div>
            <div class="stat-card-body">
                <span class="stat-value"><?= $docs_pending ?></span>
                <span class="stat-label">Docs Pending</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-icon">🏛</div>
            <div class="stat-card-body">
                <span class="stat-value"><?= $total_departments ?></span>
                <span class="stat-label">Departments</span>
            </div>
        </div>

    </div>

</div>
</body>
</html>
