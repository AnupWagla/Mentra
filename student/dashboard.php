<?php
require_once __DIR__ . "/../account/auth_check.php";
require_once __DIR__ . "/../db.php";

if ($_SESSION['role'] !== 'student') {
    header("Location: ../account/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get student profile + assigned supervisor (via project assignment)
$student_sql = "SELECT u.*, sv.full_name AS supervisor_name, sv.email AS supervisor_email, sv.phone AS supervisor_phone
                FROM users u
                LEFT JOIN projects p ON p.student_id = u.id
                LEFT JOIN supervisor_assignments sa ON sa.project_id = p.id
                LEFT JOIN users sv ON sv.id = sa.supervisor_id
                WHERE u.id = ?
                LIMIT 1";
$stmt = mysqli_prepare($conn, $student_sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$student = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
mysqli_stmt_close($stmt);

// Get all projects
$proj_stmt = mysqli_prepare($conn, "SELECT * FROM projects WHERE student_id = ? ORDER BY created_at DESC");
mysqli_stmt_bind_param($proj_stmt, "i", $user_id);
mysqli_stmt_execute($proj_stmt);
$projects_result = mysqli_stmt_get_result($proj_stmt);
$projects = mysqli_fetch_all($projects_result, MYSQLI_ASSOC);
mysqli_stmt_close($proj_stmt);

// Stats
$total_projects    = count($projects);
$approved          = count(array_filter($projects, fn($p) => $p['status'] === 'Approved'));
$pending_review    = count(array_filter($projects, fn($p) => in_array($p['status'], ['Submitted', 'Under Review'])));
$revision_required = count(array_filter($projects, fn($p) => $p['status'] === 'Revision Required'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard - Mentra</title>
    <link rel="stylesheet" href="student.css">
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

    <div class="page-top">
        <div>
            <h1>Welcome, <?= htmlspecialchars($_SESSION['name']) ?></h1>
            <p class="subtitle">Track your project submissions and supervisor feedback.</p>
        </div>
        <a href="../projects/submit_project.php" class="btn-primary">+ Submit New Project</a>
    </div>

    <!-- Stats -->
    <div class="stats">
        <div class="stat-card">
            <div class="stat-num"><?= $total_projects ?></div>
            <div class="stat-label">Total Projects</div>
        </div>
        <div class="stat-card">
            <div class="stat-num"><?= $pending_review ?></div>
            <div class="stat-label">Under Review</div>
        </div>
        <div class="stat-card">
            <div class="stat-num"><?= $revision_required ?></div>
            <div class="stat-label">Needs Revision</div>
        </div>
        <div class="stat-card">
            <div class="stat-num"><?= $approved ?></div>
            <div class="stat-label">Approved</div>
        </div>
    </div>

    <!-- My Supervisor -->
    <div class="section-card">
        <h2 class="section-heading">My Supervisor</h2>
        <?php if ($student['supervisor_name']): ?>
            <div class="supervisor-info">
                <div class="supervisor-avatar"><?= strtoupper(substr($student['supervisor_name'], 0, 1)) ?></div>
                <div class="supervisor-details">
                    <div class="supervisor-name"><?= htmlspecialchars($student['supervisor_name']) ?></div>
                    <?php if ($student['supervisor_email']): ?>
                        <a href="mailto:<?= htmlspecialchars($student['supervisor_email']) ?>" class="supervisor-contact">
                            ✉ <?= htmlspecialchars($student['supervisor_email']) ?>
                        </a>
                    <?php endif; ?>
                    <?php if ($student['supervisor_phone']): ?>
                        <a href="tel:<?= htmlspecialchars($student['supervisor_phone']) ?>" class="supervisor-contact">
                            ☎ <?= htmlspecialchars($student['supervisor_phone']) ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <p class="empty-msg">No supervisor assigned yet. Contact admin.</p>
        <?php endif; ?>
    </div>

    <!-- My Projects -->
    <div class="section-card">
        <h2 class="section-heading">My Projects</h2>

        <?php if (empty($projects)): ?>
            <p class="empty-msg">You haven't submitted any project yet. <a href="../projects/submit_project.php">Submit one now →</a></p>
        <?php else: ?>
            <?php foreach ($projects as $project): ?>

                <?php
                // Get versions count + latest feedback for this project
                $v_stmt = mysqli_prepare($conn, "SELECT COUNT(*) AS cnt FROM project_versions WHERE project_id = ?");
                mysqli_stmt_bind_param($v_stmt, "i", $project['id']);
                mysqli_stmt_execute($v_stmt);
                $version_count = mysqli_fetch_assoc(mysqli_stmt_get_result($v_stmt))['cnt'];
                mysqli_stmt_close($v_stmt);

                $f_stmt = mysqli_prepare($conn, "SELECT f.feedback, f.status, f.created_at FROM feedback f WHERE f.project_id = ? ORDER BY f.created_at DESC LIMIT 1");
                mysqli_stmt_bind_param($f_stmt, "i", $project['id']);
                mysqli_stmt_execute($f_stmt);
                $latest_feedback = mysqli_fetch_assoc(mysqli_stmt_get_result($f_stmt));
                mysqli_stmt_close($f_stmt);
                ?>

                <div class="project-card">
                    <div class="project-card-top">
                        <div>
                            <h3 class="project-title"><?= htmlspecialchars($project['title']) ?></h3>
                            <span class="project-category"><?= htmlspecialchars($project['category'] ?? '') ?></span>
                        </div>
                        <span class="status-badge status-<?= strtolower(str_replace(' ', '-', $project['status'])) ?>">
                            <?= htmlspecialchars($project['status']) ?>
                        </span>
                    </div>

                    <p class="project-desc"><?= htmlspecialchars($project['description']) ?></p>

                    <div class="project-meta">
                        <span>📁 <?= $version_count ?> submission<?= $version_count != 1 ? 's' : '' ?></span>
                        <span>🕐 <?= date('d M Y', strtotime($project['updated_at'])) ?></span>
                    </div>

                    <?php if ($latest_feedback): ?>
                        <div class="feedback-preview">
                            <strong>Latest Feedback:</strong>
                            <?= htmlspecialchars($latest_feedback['feedback']) ?>
                            <span class="feedback-date"><?= date('d M Y', strtotime($latest_feedback['created_at'])) ?></span>
                        </div>
                    <?php endif; ?>

                    <div class="project-actions">
                        <a href="../projects/view_project.php?id=<?= $project['id'] ?>" class="btn-outline">View Details</a>
                        <?php if ($project['status'] === 'Revision Required'): ?>
                            <a href="../projects/submit_project.php" class="btn-primary">Submit Revision</a>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</main>

</body>
</html>
