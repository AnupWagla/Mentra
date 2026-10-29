
<?php
require_once __DIR__ . "/../backend/auth_check.php";
require_once __DIR__ . "/../backend/db.php";
/** @var mysqli $conn */

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? '';

// Get projects according to the user's role
if ($role === 'supervisor') {

    $sql = "SELECT projects.*
            FROM projects
            JOIN supervisor_assignments
                ON projects.id = supervisor_assignments.project_id
            WHERE supervisor_assignments.supervisor_id = ?
            ORDER BY projects.created_at DESC";

} elseif ($role === 'student') {

    $sql = "SELECT *
            FROM projects
            WHERE student_id = ?
            ORDER BY created_at DESC";

} else {
    die("Access denied. Invalid user role.");
}

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentra Dashboard</title>
</head>
<body>

<header class="dashboard-header">
    <h2>Mentra</h2>
    <div>
        <span>
            <?php echo htmlspecialchars($_SESSION['name']); ?>
        </span>
        <a href="../backend/logout.php">Logout</a>
    </div>
</header>

<main class="dashboard">

    <h1>
        Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>
    </h1>

    <?php if ($role === 'student'): ?>

        <p>Manage your project proposal and track its progress.</p>

        <div class="dashboard-actions">
            <a href="../projects/submit_project.php">
                + Submit New Project
            </a>
        </div>

        <section class="projects">
            <h2>My Projects</h2>

    <?php else: ?>

        <p>Review assigned student proposals and track their progress.</p>

        <section class="projects">
            <h2>Projects Assigned to Me</h2>

    <?php endif; ?>

    <?php if (mysqli_num_rows($result) > 0): ?>

        <?php while ($project = mysqli_fetch_assoc($result)): ?>

            <div class="project-card">

                <h3>
                    <?php echo htmlspecialchars($project['title']); ?>
                </h3>

                <p>
                    <?php echo htmlspecialchars($project['description']); ?>
                </p>

                <strong>Status:</strong>
                <?php echo htmlspecialchars($project['status']); ?>

                <br><br>

                <?php if ($role === 'supervisor'): ?>
                    <a href="../supervisor/view_project.php?id=<?php echo $project['id']; ?>">
                        Review Project
                    </a>
                <?php else: ?>
                    <a href="../projects/view_project.php?id=<?php echo $project['id']; ?>">
                        View Project
                    </a>
                <?php endif; ?>

            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <?php if ($role === 'supervisor'): ?>
            <p>No projects have been assigned to you yet.</p>
        <?php else: ?>
            <p>You haven't submitted any project yet.</p>
        <?php endif; ?>

    <?php endif; ?>

    </section>

</main>

</body>
</html>
