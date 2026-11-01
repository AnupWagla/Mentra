<?php
require_once "../backend/admin_check.php";
require_once "../db.php";
/** @var mysqli $conn */

if (!isset($_GET['id']) || intval($_GET['id']) <= 0) {
    header("Location: students.php");
    exit();
}

$student_id = intval($_GET['id']);

// Fetch full student information
$sql = "SELECT u.id, u.full_name, u.email, u.roll_number, u.phone,
               u.department, u.batch, u.semester, u.created_at,
               GROUP_CONCAT(DISTINCT sup.full_name SEPARATOR ', ') AS supervisor_name
        FROM users u
        LEFT JOIN projects p ON p.student_id = u.id
        LEFT JOIN supervisor_assignments sa ON sa.project_id = p.id
        LEFT JOIN users sup ON sa.supervisor_id = sup.id
        WHERE u.id = ? AND u.role = 'student'
        GROUP BY u.id";

$stmt = mysqli_prepare($conn, $sql);
if (!$stmt) {
    die("Database error preparing student query.");
}

mysqli_stmt_bind_param($stmt, "i", $student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$student = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$student) {
    header("Location: students.php?error=notfound");
    exit();
}

// Fetch student's projects
$proj_sql = "SELECT p.*,
                    (SELECT COUNT(*) FROM project_versions pv WHERE pv.project_id = p.id) AS version_count,
                    sup.full_name AS supervisor_name
             FROM projects p
             LEFT JOIN supervisor_assignments sa ON sa.project_id = p.id
             LEFT JOIN users sup ON sa.supervisor_id = sup.id
             WHERE p.student_id = ?
             ORDER BY p.created_at DESC";

$proj_stmt = mysqli_prepare($conn, $proj_sql);
if (!$proj_stmt) {
    die("Database error preparing projects query.");
}

mysqli_stmt_bind_param($proj_stmt, "i", $student_id);
mysqli_stmt_execute($proj_stmt);
$proj_res = mysqli_stmt_get_result($proj_stmt);

$projects = [];
while ($proj = mysqli_fetch_assoc($proj_res)) {
    $p_id = (int) $proj['id'];

    // Fetch versions for this project
    $v_sql = "SELECT id, version_number, description, file_name, status, submitted_at
              FROM project_versions
              WHERE project_id = ?
              ORDER BY version_number DESC";
    $v_stmt = mysqli_prepare($conn, $v_sql);
    mysqli_stmt_bind_param($v_stmt, "i", $p_id);
    mysqli_stmt_execute($v_stmt);
    $v_res = mysqli_stmt_get_result($v_stmt);
    $versions = [];
    while ($v = mysqli_fetch_assoc($v_res)) {
        $versions[] = $v;
    }
    mysqli_stmt_close($v_stmt);

    // Fetch feedback for this project
    $fb_sql = "SELECT f.id, f.feedback, f.status, f.created_at, sup.full_name AS supervisor_name
               FROM feedback f
               JOIN users sup ON f.supervisor_id = sup.id
               WHERE f.project_id = ?
               ORDER BY f.created_at DESC";
    $fb_stmt = mysqli_prepare($conn, $fb_sql);
    mysqli_stmt_bind_param($fb_stmt, "i", $p_id);
    mysqli_stmt_execute($fb_stmt);
    $fb_res = mysqli_stmt_get_result($fb_stmt);
    $feedbacks = [];
    while ($fb = mysqli_fetch_assoc($fb_res)) {
        $feedbacks[] = $fb;
    }
    mysqli_stmt_close($fb_stmt);

    $proj['versions'] = $versions;
    $proj['feedbacks'] = $feedbacks;
    $projects[] = $proj;
}
mysqli_stmt_close($proj_stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($student['full_name']); ?> - Student Details</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<?php include 'sidebar.php'; ?>
<div class="page-wrapper">
<main class="container">
    <h1>Student Details</h1>
    <p class="subtitle">Complete student profile, projects, and feedback history</p>

    <!-- Student Info Card -->
    <div class="detail-card">
        <div class="detail-grid">
            <div class="detail-item">
                <div class="label">Full Name</div>
                <div class="value"><?php echo htmlspecialchars($student['full_name']); ?></div>
            </div>
            <div class="detail-item">
                <div class="label">Email Address</div>
                <div class="value"><?php echo htmlspecialchars($student['email']); ?></div>
            </div>
            <div class="detail-item">
                <div class="label">Roll Number</div>
                <div class="value"><?php echo htmlspecialchars($student['roll_number'] ?: '—'); ?></div>
            </div>
            <div class="detail-item">
                <div class="label">Phone Number</div>
                <div class="value"><?php echo htmlspecialchars($student['phone'] ?: '—'); ?></div>
            </div>
            <div class="detail-item">
                <div class="label">Department</div>
                <div class="value"><?php echo htmlspecialchars($student['department'] ?? '—'); ?></div>
            </div>
            <div class="detail-item">
                <div class="label">Batch</div>
                <div class="value"><?php echo htmlspecialchars($student['batch'] ?: '—'); ?></div>
            </div>
            <div class="detail-item">
                <div class="label">Semester</div>
                <div class="value"><?php echo htmlspecialchars($student['semester'] ?: '—'); ?></div>
            </div>
            <div class="detail-item">
                <div class="label">Assigned Supervisor</div>
                <div class="value">
                    <?php if (!empty($student['supervisor_name'])): ?>
                        <?php echo htmlspecialchars($student['supervisor_name']); ?>
                    <?php else: ?>
                        <span class="not-assigned">Not Assigned</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="detail-item">
                <div class="label">Registered Date</div>
                <div class="value"><?php echo date('M d, Y', strtotime($student['created_at'])); ?></div>
            </div>
        </div>
    </div>

    <!-- Projects Section -->
    <h2 class="section-title">
        <span>Projects (<?php echo count($projects); ?>)</span>
    </h2>

    <?php if (!empty($projects)): ?>
        <?php foreach ($projects as $proj): ?>
            <div class="project-block">
                <div class="project-block-header">
                    <div>
                        <h3><?php echo htmlspecialchars($proj['title']); ?></h3>
                        <div class="project-meta-badges">
                            <?php if (!empty($proj['category'])): ?>
                                <span class="meta-pill"><?php echo htmlspecialchars($proj['category']); ?></span>
                            <?php endif; ?>
                            <span class="meta-pill" style="color: #ffffff; font-weight: 600;">Status: <?php echo htmlspecialchars($proj['status']); ?></span>
                            <span class="meta-pill"><?php echo (int) $proj['version_count']; ?> Versions</span>
                            <?php if (!empty($proj['supervisor_name'])): ?>
                                <span class="meta-pill" style="color: #2ecc71;">Supervisor: <?php echo htmlspecialchars($proj['supervisor_name']); ?></span>
                            <?php else: ?>
                                <span class="meta-pill not-assigned">Supervisor: Not Assigned</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div>
                        <a href="assign_supervisor.php?id=<?php echo (int) $proj['id']; ?>" class="assign-btn">
                            Manage Assignment
                        </a>
                    </div>
                </div>

                <?php if (!empty($proj['description'])): ?>
                    <p style="color: #b8cde8; margin-bottom: 20px; font-size: 14px; line-height: 1.5;">
                        <?php echo nl2br(htmlspecialchars($proj['description'])); ?>
                    </p>
                <?php endif; ?>

                <!-- Submissions Table -->
                <h4 style="color: #51e2f5; font-size: 14px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Submissions & Versions (<?php echo count($proj['versions']); ?>)
                </h4>

                <div class="table-container" style="margin-bottom: 24px;">
                    <table>
                        <thead>
                            <tr>
                                <th>Version</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Submitted Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($proj['versions'])): ?>
                            <?php foreach ($proj['versions'] as $v): ?>
                                <tr>
                                    <td>
                                        <strong>v<?php echo htmlspecialchars($v['version_number']); ?></strong>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($v['description'] ?: 'No description provided'); ?>
                                    </td>
                                    <td>
                                        <?php echo htmlspecialchars($v['status']); ?>
                                    </td>
                                    <td>
                                        <?php echo date('M d, Y h:i A', strtotime($v['submitted_at'])); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: #7a9abf; padding: 20px;">
                                    No submissions recorded for this project yet.
                                </td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Feedback Section -->
                <h4 style="color: #51e2f5; font-size: 14px; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                    Supervisor Feedback (<?php echo count($proj['feedbacks']); ?>)
                </h4>

                <?php if (!empty($proj['feedbacks'])): ?>
                    <?php foreach ($proj['feedbacks'] as $fb): ?>
                        <div class="feedback-card">
                            <div class="feedback-top">
                                <div>
                                    Supervisor: <strong style="color: #ffffff;"><?php echo htmlspecialchars($fb['supervisor_name']); ?></strong>
                                    <?php if (!empty($fb['status'])): ?>
                                        &bull; Verdict: <span style="color: #51e2f5; font-weight: 600;"><?php echo htmlspecialchars($fb['status']); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <?php echo date('M d, Y h:i A', strtotime($fb['created_at'])); ?>
                                </div>
                            </div>
                            <div class="feedback-body"><?php echo nl2br(htmlspecialchars($fb['feedback'])); ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div style="background: rgba(255, 255, 255, 0.02); padding: 14px 18px; border-radius: 8px; color: #7a9abf; font-size: 14px;">
                        No feedback has been recorded for this project yet.
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="detail-card" style="text-align: center; color: #7a9abf; padding: 36px;">
            No projects found for this student.
        </div>
    <?php endif; ?>
</main>
</div>

</body>
</html>
