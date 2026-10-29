
<?php
require_once __DIR__ . "/../backend/admin_check.php";
require_once __DIR__ . "/../backend/db.php";
/** @var mysqli $conn */

if (!isset($_GET['id']) || intval($_GET['id']) <= 0) {
    header("Location: projects.php");
    exit();
}

$project_id = intval($_GET['id']);

// Get project, student, and current supervisor
$sql = "SELECT
            p.*,
            student.full_name AS student_name,
            supervisor.full_name AS supervisor_name
        FROM projects p
        JOIN users student ON p.student_id = student.id
        LEFT JOIN supervisor_assignments sa ON p.id = sa.project_id
        LEFT JOIN users supervisor ON sa.supervisor_id = supervisor.id
        WHERE p.id = ?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("Unable to load project information.");
}

mysqli_stmt_bind_param($stmt, "i", $project_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) !== 1) {
    die("Project not found.");
}

$project = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Get available supervisors
$supervisor_sql = "SELECT id, full_name, email, department
                   FROM users
                   WHERE role = 'supervisor'
                   ORDER BY full_name";

$supervisor_result = mysqli_query($conn, $supervisor_sql);

if (!$supervisor_result) {
    die("Unable to load supervisors. Please check the database.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Supervisor - Mentra</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<header class="header">
    <h2>Mentra Admin</h2>
    <a href="projects.php">Back to Projects</a>
</header>

<main class="container">
    <div class="form-card">
        <h1>Assign Supervisor</h1>

        <div class="project-info">
            <p>
                <strong>Project:</strong>
                <?php echo htmlspecialchars($project['title']); ?>
            </p>

            <p>
                <strong>Student:</strong>
                <?php echo htmlspecialchars($project['student_name']); ?>
            </p>

            <p>
                <strong>Current Supervisor:</strong>
                <?php
                echo htmlspecialchars(
                    $project['supervisor_name'] ?? 'Not assigned'
                );
                ?>
            </p>

            <p>
                <strong>Project Status:</strong>
                <?php echo htmlspecialchars($project['status']); ?>
            </p>
        </div>

        <form action="../backend/assign_supervisor_process.php" method="POST">
            <input type="hidden" name="project_id"
                   value="<?php echo $project_id; ?>">

            <label for="supervisor">Select Supervisor</label>

            <select name="supervisor_id" id="supervisor" required>
                <option value="">Select Supervisor</option>

                <?php while ($supervisor = mysqli_fetch_assoc($supervisor_result)): ?>
                    <option
                        value="<?php echo (int) $supervisor['id']; ?>"
                        <?php
                        if (
                            isset($project['supervisor_id']) &&
                            (int) $project['supervisor_id'] === (int) $supervisor['id']
                        ) {
                            echo 'selected';
                        }
                        ?>
                    >
                        <?php echo htmlspecialchars($supervisor['full_name']); ?>
                        - <?php echo htmlspecialchars($supervisor['department'] ?? ''); ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Assign Supervisor</button>
        </form>
    </div>
</main>

</body>
</html>
