<?php
require_once __DIR__ . "/../account/auth_check.php";
require_once __DIR__ . "/../db.php";
/** @var mysqli $conn */

if (!isset($_GET['id'])) {
    header("Location: ../student/dashboard.php");
    exit();
}

$project_id = intval($_GET['id']);
$student_id = $_SESSION['user_id'];

// Get project — allow admin/supervisor to view too
if ($_SESSION['role'] === 'student') {
    $sql  = "SELECT * FROM projects WHERE id = ? AND student_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $project_id, $student_id);
} else {
    $sql  = "SELECT * FROM projects WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $project_id);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) !== 1) {
    die("Project not found.");
}
$project = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Get feedback
$feedback_sql  = "SELECT f.*, u.full_name FROM feedback f JOIN users u ON f.supervisor_id = u.id WHERE f.project_id = ? ORDER BY f.created_at DESC";
$feedback_stmt = mysqli_prepare($conn, $feedback_sql);
mysqli_stmt_bind_param($feedback_stmt, "i", $project_id);
mysqli_stmt_execute($feedback_stmt);
$feedback_result = mysqli_stmt_get_result($feedback_stmt);
$feedbacks = mysqli_fetch_all($feedback_result, MYSQLI_ASSOC);
mysqli_stmt_close($feedback_stmt);

// Get versions
$version_sql  = "SELECT * FROM project_versions WHERE project_id = ? ORDER BY version_number DESC";
$version_stmt = mysqli_prepare($conn, $version_sql);
mysqli_stmt_bind_param($version_stmt, "i", $project_id);
mysqli_stmt_execute($version_stmt);
$version_result = mysqli_stmt_get_result($version_stmt);
$versions = mysqli_fetch_all($version_result, MYSQLI_ASSOC);
mysqli_stmt_close($version_stmt);

// Back URL
$back_url = "../student/dashboard.php";
if ($_SESSION['role'] === 'supervisor') $back_url = "../supervisor/dashboard.php";
if ($_SESSION['role'] === 'admin')      $back_url = "../admin/projects.php";

// Status colour mapping
$status_colors = [
    'Submitted'         => '#51e2f5',
    'Under Review'      => '#f59e0b',
    'Revision Required' => '#f87171',
    'Approved'          => '#34d399',
    'Rejected'          => '#f87171',
];
$status_color = $status_colors[$project['status']] ?? '#7a9abf';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($project['title']); ?> – Mentra</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', Arial, sans-serif;
            background: #0b1120;
            color: #d4e1f7;
            min-height: 100vh;
        }

        /* ── Header ── */
        .header {
            height: 66px;
            background: #0f1a2e;
            border-bottom: 1px solid rgba(81,226,245,0.12);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 6%;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .header-left { display: flex; align-items: center; gap: 18px; }
        .back-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #7a9abf;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            padding: 6px 12px;
            border: 1px solid rgba(81,226,245,0.12);
            border-radius: 7px;
            transition: color 0.2s, border-color 0.2s, background 0.2s;
        }
        .back-btn:hover { color: #51e2f5; border-color: rgba(81,226,245,0.35); background: rgba(81,226,245,0.06); }
        .logo-wrap { display: flex; align-items: center; gap: 9px; }
        .logo-dot { width: 9px; height: 9px; border-radius: 50%; background: #51e2f5; box-shadow: 0 0 8px #51e2f5; }
        .logo-text { font-size: 21px; font-weight: 700; color: #e8f4fd; letter-spacing: 0.5px; }
        .header-right { display: flex; align-items: center; gap: 16px; }
        .user-name { font-size: 13px; color: #7a9abf; }
        .logout-btn {
            font-size: 13px; font-weight: 500; color: #f87171;
            text-decoration: none; padding: 6px 14px;
            border: 1px solid rgba(248,113,113,0.35); border-radius: 7px;
            transition: background 0.2s;
        }
        .logout-btn:hover { background: rgba(248,113,113,0.1); }

        /* ── Container ── */
        .container { max-width: 820px; margin: 36px auto 60px; padding: 0 20px; }

        /* ── Page title row ── */
        .page-title-row {
            display: flex; align-items: flex-start;
            justify-content: space-between; gap: 16px;
            margin-bottom: 28px; flex-wrap: wrap;
        }
        .page-title-row h1 { font-size: 26px; font-weight: 700; color: #e8f4fd; line-height: 1.25; }
        .page-title-row .meta { font-size: 13px; color: #5a7fa8; margin-top: 5px; }

        /* ── Status badge ── */
        .status-badge {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 13px; font-weight: 600;
            padding: 6px 14px; border-radius: 20px;
            border: 1px solid;
            white-space: nowrap;
        }
        .status-dot { width: 7px; height: 7px; border-radius: 50%; display: inline-block; }

        /* ── Cards ── */
        .card {
            background: #111c30;
            border: 1px solid rgba(81,226,245,0.1);
            border-radius: 14px;
            padding: 28px 30px;
            margin-bottom: 20px;
        }
        .card-title {
            font-size: 13px; font-weight: 700; text-transform: uppercase;
            letter-spacing: 0.8px; color: #51e2f5; margin-bottom: 16px;
        }

        /* ── Info grid ── */
        .info-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 16px;
        }
        .info-item label {
            font-size: 11px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.6px; color: #5a7fa8; display: block; margin-bottom: 4px;
        }
        .info-item span, .info-item a {
            font-size: 14px; color: #d4e1f7;
        }
        .info-item a { color: #51e2f5; text-decoration: none; }
        .info-item a:hover { text-decoration: underline; }
        .info-full { grid-column: 1 / -1; }

        /* ── Description ── */
        .description-text {
            font-size: 14px; line-height: 1.8; color: #a0bcd8;
            white-space: pre-wrap; word-break: break-word;
        }

        /* ── Attachments ── */
        .attachment-row {
            display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
        }
        .attach-pill {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(81,226,245,0.07);
            border: 1px solid rgba(81,226,245,0.18);
            border-radius: 8px; padding: 8px 14px;
            font-size: 13px; font-weight: 500; color: #51e2f5;
            text-decoration: none; transition: background 0.2s;
        }
        .attach-pill:hover { background: rgba(81,226,245,0.14); }
        .attach-none { font-size: 13px; color: #3a5570; }

        /* ── Revision form ── */
        .revision-banner {
            background: rgba(248,113,113,0.08);
            border: 1px solid rgba(248,113,113,0.25);
            border-radius: 12px; padding: 20px 24px; margin-bottom: 20px;
        }
        .revision-banner h2 { font-size: 16px; font-weight: 700; color: #fca5a5; margin-bottom: 6px; }
        .revision-banner p  { font-size: 13px; color: #a0bcd8; margin-bottom: 16px; line-height: 1.6; }
        .revision-banner textarea {
            width: 100%; background: #0b1120;
            border: 1px solid rgba(248,113,113,0.25); border-radius: 8px;
            color: #d4e1f7; font-family: 'Inter', sans-serif; font-size: 14px;
            padding: 12px; resize: vertical; outline: none;
            transition: border-color 0.2s;
        }
        .revision-banner textarea:focus { border-color: #f87171; }
        .btn-revision {
            margin-top: 12px; padding: 11px 24px;
            background: #f87171; color: #fff; border: none;
            border-radius: 8px; font-family: 'Inter', sans-serif;
            font-size: 14px; font-weight: 600; cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-revision:hover { opacity: 0.85; }

        /* ── Feedback cards ── */
        .feedback-item {
            border-left: 3px solid #51e2f5;
            background: rgba(81,226,245,0.04);
            border-radius: 0 10px 10px 0;
            padding: 16px 18px; margin-bottom: 14px;
        }
        .feedback-item:last-child { margin-bottom: 0; }
        .feedback-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; flex-wrap: wrap; gap: 8px; }
        .feedback-author { font-size: 13px; font-weight: 700; color: #e8f4fd; }
        .feedback-date   { font-size: 12px; color: #5a7fa8; }
        .feedback-text   { font-size: 14px; color: #a0bcd8; line-height: 1.7; }
        .feedback-status {
            font-size: 11px; font-weight: 600; padding: 3px 10px;
            border-radius: 12px; border: 1px solid rgba(81,226,245,0.3);
            color: #51e2f5; margin-top: 8px; display: inline-block;
        }
        .empty-state { font-size: 14px; color: #3a5570; text-align: center; padding: 20px 0; }

        /* ── Version table ── */
        .vtable { width: 100%; border-collapse: collapse; font-size: 14px; }
        .vtable th { font-size: 11px; text-transform: uppercase; letter-spacing: 0.6px;
            color: #5a7fa8; padding: 10px 12px; border-bottom: 1px solid rgba(81,226,245,0.1); text-align: left; }
        .vtable td { padding: 12px 12px; border-bottom: 1px solid rgba(81,226,245,0.06); color: #a0bcd8; vertical-align: middle; }
        .vtable tr:last-child td { border-bottom: none; }
        .vtable tr:hover td { background: rgba(81,226,245,0.03); }
        .ver-badge {
            display: inline-flex; align-items: center; justify-content: center;
            width: 28px; height: 28px; border-radius: 50%;
            background: rgba(81,226,245,0.1); color: #51e2f5;
            font-size: 12px; font-weight: 700;
        }

        @media (max-width: 600px) {
            .info-grid { grid-template-columns: 1fr; }
            .page-title-row { flex-direction: column; }
            .card { padding: 20px 18px; }
        }
    </style>
</head>
<body>

<header class="header">
    <div class="header-left">
        <a href="<?php echo $back_url; ?>" class="back-btn">← Back</a>
        <div class="logo-wrap">
            <span class="logo-dot"></span>
            <span class="logo-text">Mentra</span>
        </div>
    </div>
    <div class="header-right">
        <span class="user-name"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
        <a href="../account/logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<main class="container">

    <!-- Title + status -->
    <div class="page-title-row">
        <div>
            <h1><?php echo htmlspecialchars($project['title']); ?></h1>
            <p class="meta">
                Submitted on <?php echo date('d M Y', strtotime($project['created_at'])); ?>
                &nbsp;·&nbsp; Category: <?php echo htmlspecialchars($project['category'] ?? '—'); ?>
            </p>
        </div>
        <span class="status-badge" style="color:<?php echo $status_color; ?>; border-color:<?php echo $status_color; ?>30;">
            <span class="status-dot" style="background:<?php echo $status_color; ?>;"></span>
            <?php echo htmlspecialchars($project['status']); ?>
        </span>
    </div>

    <!-- Revision required? Show form first -->
    <?php if ($project['status'] === 'Revision Required' && $_SESSION['role'] === 'student'): ?>
    <div class="revision-banner">
        <h2>⚠ Revision Required</h2>
        <p>Your supervisor has requested changes. Review the feedback below, update your description, and submit your revision.</p>
        <form action="submit_revision.php" method="POST">
            <input type="hidden" name="project_id" value="<?php echo $project['id']; ?>">
            <textarea name="description" rows="7" required placeholder="Update your project description here..."><?php echo htmlspecialchars($project['description']); ?></textarea>
            <button type="submit" class="btn-revision">Submit Revision</button>
        </form>
    </div>
    <?php endif; ?>

    <!-- Project info -->
    <div class="card">
        <p class="card-title">Project Details</p>
        <div class="info-grid">
            <div class="info-item">
                <label>Category</label>
                <span><?php echo htmlspecialchars($project['category'] ?? '—'); ?></span>
            </div>
            <div class="info-item">
                <label>Status</label>
                <span style="color:<?php echo $status_color; ?>; font-weight:600;"><?php echo htmlspecialchars($project['status']); ?></span>
            </div>
            <div class="info-item">
                <label>Submitted</label>
                <span><?php echo date('d M Y, g:i A', strtotime($project['created_at'])); ?></span>
            </div>
            <div class="info-item">
                <label>Last Updated</label>
                <span><?php echo date('d M Y, g:i A', strtotime($project['updated_at'])); ?></span>
            </div>

            <?php if (!empty($project['git_link'])): ?>
            <div class="info-item info-full">
                <label>Repository / Git Link</label>
                <a href="<?php echo htmlspecialchars($project['git_link']); ?>" target="_blank" rel="noopener">
                    🔗 <?php echo htmlspecialchars($project['git_link']); ?>
                </a>
            </div>
            <?php endif; ?>

            <div class="info-item info-full" style="margin-top:8px;">
                <label>Description</label>
                <p class="description-text"><?php echo htmlspecialchars($project['description']); ?></p>
            </div>
        </div>
    </div>

    <!-- Feedback -->
    <div class="card">
        <p class="card-title">Supervisor Feedback (<?php echo count($feedbacks); ?>)</p>
        <?php if (empty($feedbacks)): ?>
            <p class="empty-state">No feedback received yet.</p>
        <?php else: ?>
            <?php foreach ($feedbacks as $fb): ?>
            <div class="feedback-item">
                <div class="feedback-header">
                    <span class="feedback-author">👤 <?php echo htmlspecialchars($fb['full_name']); ?></span>
                    <span class="feedback-date"><?php echo date('d M Y', strtotime($fb['created_at'])); ?></span>
                </div>
                <p class="feedback-text"><?php echo nl2br(htmlspecialchars($fb['feedback'])); ?></p>
                <?php if (!empty($fb['status'])): ?>
                    <span class="feedback-status"><?php echo htmlspecialchars($fb['status']); ?></span>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Version history -->
    <div class="card">
        <p class="card-title">Submission History (<?php echo count($versions); ?> version<?php echo count($versions) !== 1 ? 's' : ''; ?>)</p>
        <?php if (empty($versions)): ?>
            <p class="empty-state">No versions recorded.</p>
        <?php else: ?>
        <table class="vtable">
            <thead>
                <tr>
                    <th>Ver.</th>
                    <th>Status</th>
                    <th>File</th>
                    <th>Git Link</th>
                    <th>Submitted</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($versions as $v): ?>
                <tr>
                    <td><span class="ver-badge"><?php echo $v['version_number']; ?></span></td>
                    <td style="color:<?php echo $status_colors[$v['status']] ?? '#7a9abf'; ?>; font-weight:600;">
                        <?php echo htmlspecialchars($v['status']); ?>
                    </td>
                    <td>
                        <?php if (!empty($v['file_name'])): ?>
                            <a href="../uploads/projects/<?php echo htmlspecialchars($v['file_name']); ?>"
                               class="attach-pill" download style="font-size:12px; padding:5px 10px;">
                                📎 Download
                            </a>
                        <?php else: ?>
                            <span style="color:#3a5570; font-size:12px;">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($v['git_link'])): ?>
                            <a href="<?php echo htmlspecialchars($v['git_link']); ?>" target="_blank"
                               rel="noopener" style="color:#51e2f5; font-size:12px;">🔗 View Repo</a>
                        <?php else: ?>
                            <span style="color:#3a5570; font-size:12px;">—</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo date('d M Y', strtotime($v['submitted_at'])); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

</main>
</body>
</html>
