<?php
require_once "../Mentra/backend/supervisor_check.php";
require_once "../Mentra/backend/db.php";

$sql = "SELECT projects.id,projects.title,projects.category,projects.status,projects.created_at,users.full_name FROM projects JOIN users on projects.student_id = users.id ORDER BY projects.created_at DESC";
$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supervisor Dashboard</title>
    <link rel="stylesheet" href="supervisor.css">
</head>
<body>
    <header class="header">
        <h2>Mentra</h2>
        <div>
            <span>
                <?php echo htmlspecialchars($_SESSION['name']); ?>
            </span>
            <a href="../backend/logout.php">Logout</a>
        </div>
    </header>
    <main class="container">
        <h1>Supervisor Dashboard</h1>
        <p class="welcome">Review and manage student project proposals.</p>

        <section class="project-section">
            <h2>Submitted Projects</h2>
            <?php if(mysqli_num_rows($result) > 0): ?>
                <div class="project-list">
                    <?php while($project = mysqli_fetch_assoc($result)): ?>
                        <div class="project-card">
                            <div>
                                <h3><?php echo htmlspecialchars($project['title']); ?></h3>
                                <p>
                                    <strong>Student:</strong>
                                    <?php echo htmlspecialchars($project['full_name']); ?>
                                </p>

                                <p>
                                    <strong>Category:</strong>
                                    <?php echo htmlspecialchars($project['category']); ?>
                                </p>

                                <p>
                                    <strong>Status:</strong>
                                   <span class="status">
                                     <?php echo htmlspecialchars($project['status']); ?>
                                   </span>
                                </p>
                            </div>
                            <a class="view-btn" href="view_project.php?id=<?php echo $project['id'];?>">View Project</a>
                        </div>
                        <?php endwhile; ?>
                </div>
                <?php else: ?>
                    <p>
                        No project have been submitted yet.
                    </p>
                    <?php endif; ?>
        </section>
    </main>
</body>
</html>