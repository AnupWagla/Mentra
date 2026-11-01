<?php
require_once __DIR__ . "/supervisor_check.php";
require_once __DIR__ . "/../db.php";

$supervisor_id = $_SESSION["user_id"];

// Stats
function sv_count($conn, $sql, $id) {
    $s = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($s, "i", $id);
    mysqli_stmt_execute($s);
    $r = mysqli_fetch_row(mysqli_stmt_get_result($s));
    mysqli_stmt_close($s);
    return $r ? (int)$r[0] : 0;
}

$total_assigned  = sv_count($conn, "SELECT COUNT(DISTINCT projects.student_id) FROM projects JOIN supervisor_assignments sa ON projects.id = sa.project_id WHERE sa.supervisor_id = ?", $supervisor_id);
$pending_review  = sv_count($conn, "SELECT COUNT(*) FROM projects JOIN supervisor_assignments sa ON projects.id = sa.project_id WHERE sa.supervisor_id = ? AND projects.status IN ('Submitted','Under Review')", $supervisor_id);
$revision_req    = sv_count($conn, "SELECT COUNT(*) FROM projects JOIN supervisor_assignments sa ON projects.id = sa.project_id WHERE sa.supervisor_id = ? AND projects.status = 'Revision Required'", $supervisor_id);
$approved        = sv_count($conn, "SELECT COUNT(*) FROM projects JOIN supervisor_assignments sa ON projects.id = sa.project_id WHERE sa.supervisor_id = ? AND projects.status = 'Approved'", $supervisor_id);

// My assigned projects with student info + version count
$sql = "SELECT
            p.id, p.title, p.category, p.status, p.updated_at,
            u.full_name AS student_name, u.email AS student_email,
            u.roll_number, u.department, u.batch, u.semester,
            (SELECT COUNT(*) FROM project_versions pv WHERE pv.project_id = p.id) AS versions,
            (SELECT COUNT(*) FROM feedback f WHERE f.project_id = p.id AND f.supervisor_id = ?) AS feedback_count
        FROM projects p
        JOIN users u ON p.student_id = u.id
        JOIN supervisor_assignments sa ON p.id = sa.project_id
        WHERE sa.supervisor_id = ?
        ORDER BY p.updated_at DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $supervisor_id, $supervisor_id);
mysqli_stmt_execute($stmt);
$projects = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Dashboard - Mentra</title>
    <link rel="stylesheet" href="supervisor.css">
</head>
<body>

<header class="header">
    <a href="#" class="logo">Mentra</a>
    <div class="header-right">
        <span class="username"><?= htmlspecialchars($_SESSION['name']) ?></span>
        <a href="../account/logout.php" class="logout-link">Logout</a>
    </div>
</header>

<main class="container">

    <h1 class="page-title">Supervisor Dashboard</h1>
    <p class="subtitle">Review and manage your assigned student projects.</p>

    <!-- Stats -->
    <div class="stats">
        <div class="stat-card">
            <div class="stat-num"><?= $total_assigned ?></div>
            <div class="stat-label">Assigned Students</div>
        </div>
        <div class="stat-card">
            <div class="stat-num"><?= $pending_review ?></div>
            <div class="stat-label">Pending Review</div>
        </div>
        <div class="stat-card">
            <div class="stat-num"><?= $revision_req ?></div>
            <div class="stat-label">Needs Revision</div>
        </div>
        <div class="stat-card">
            <div class="stat-num"><?= $approved ?></div>
            <div class="stat-label">Approved</div>
        </div>
    </div>

    <!-- My Students -->
    <div class="section-card">
        <h2 class="section-heading">My Assigned Students</h2>

        <?php if (empty($projects)): ?>
            <p class="empty-msg">No students have been assigned to you yet.</p>
        <?php else: ?>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Department / Batch</th>
                            <th>Project</th>
                            <th>Submissions</th>
                            <th>Last Update</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($projects as $p): ?>
                        <tr>
                            <td>
                                <div class="student-name"><?= htmlspecialchars($p['student_name']) ?></div>
                                <div class="student-email"><?= htmlspecialchars($p['student_email']) ?></div>
                            </td>
                            <td>
                                <?= htmlspecialchars($p['department'] ?? '—') ?>
                                <?php if ($p['batch']): ?>
                                    <br><small><?= htmlspecialchars($p['batch']) ?> · Sem <?= htmlspecialchars($p['semester'] ?? '?') ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($p['title']) ?></strong>
                                <?php if ($p['category']): ?>
                                    <br><small><?= htmlspecialchars($p['category']) ?></small>
                                <?php endif; ?>
                            </td>
                            <td><?= $p['versions'] ?> version<?= $p['versions'] != 1 ? 's' : '' ?></td>
                            <td><?= date('d M Y', strtotime($p['updated_at'])) ?></td>
                            <td><span class="status-badge status-<?= strtolower(str_replace(' ', '-', $p['status'])) ?>"><?= htmlspecialchars($p['status']) ?></span></td>
                            <td><a href="view_project.php?id=<?= $p['id'] ?>" class="btn-review">Review</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

</main>

</body>
</html>
