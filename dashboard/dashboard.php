<?php
require_once "../backend/auth_check.php";
require_once "../backend/db.php";
/** @var mysqli $conn */

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM projects WHERE student_id = ? ORDER BY created_at DESC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt,"i",$user_id);
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
                <?php echo htmlspecialchars($_SESSION['name']);?>
            </span>
            <a href="../backend/logout.php">Logout</a>
        </div>
    </header>
    <main class="dashboard">
        <h1>
            Welcome,
            <?php echo htmlspecialchars($_SESSION['name']);?>
        </h1>
        <p>
            Manage your project proposal and track its progress.
        </p>
        <div class="dashboard-actions">
            <a href="../projects/submit_project.php">
                + Submit New Project
            </a>
        </div>
        <section class="projects">
            <h2>My Projects</h2>
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while ($projects = mysqli_fetch_assoc($result)): ?>
                    <div class="project-card">
                        <h3>
                            <?php echo htmlspecialchars($projects['title']); ?>
                        </h3>
                        <p>
                            <?php echo htmlspecialchars($projects['description']); ?>
                        </p>
                        <strong>
                            Status:
                        </strong>

                        <?php 
                        echo htmlspecialchars($projects['status']);
                        ?><br><br>

                        <a href="../projects/view_project.php?id=<?php 
                        echo $projects['id'];
                         ?>">
                            View Project
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>
                    You haven't submitted any project yet.
                </p>
                <?php endif; ?>
        </section>

    </main>
</body>
</html>