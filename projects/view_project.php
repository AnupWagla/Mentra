<?php
require_once "../Mentra/backend/auth_check.php";
require_once "../Mentra/backenddb.php";
/** @var mysqli $conn */

if(!isset($_GET['id'])){
    header("Location: ../dashboard/dashboard.php");
    exit();
}

$project_id = intval($_GET['id']);
$student_id = $_SESSION['user_id'];

//Get project
$sql = "SELECT * FROM projects where id = ? AND student_id = ?";
$stmt = mysqli_prepare($conn,$sql);
mysqli_stmt_bind_param($stmt,"ii",$project_id,$student_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
if(mysqli_num_rows($result) !== 1){
    die("Project not found");
}

$project = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

//Get feedback
$feedback_sql = "SELECT feedback.*, users.full_name FROM feedback JOIN users ON feedback.supervisor_id = users.id WHERE feedback.project_id = ? ORDER BY feedback.created_at DESC";
$feedback_stmt = mysqli_prepare($conn, $feedback_sql);
mysqli_stmt_bind_param($feedback_stmt, "i", $project_id);
mysqli_stmt_execute($feedback_stmt);

$feedback_result = mysqli_stmt_get_result($feedback_stmt);

$version_sql = "SELECT * from project_versions WHERE project_id = ? order by version_number DESC";
$version_stmt = mysqli_prepare($conn, $version_sql);
mysqli_stmt_bind_param($version_stmt,"i",$project_id);
mysqli_stmt_execute($version_stmt);

$version_result = mysqli_stmt_get_result($version_stmt);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($project['title']); ?></title>
    <link rel="stylesheet" href="project.css">
</head>
<body>
    <header class="header">
        <h2>Mentra</h2>
        <div>
            <span>
                <?php echo htmlspecialchars($_SESSION['name']); ?>
            </span>
                <a href="../dashboard/dashboard.php">Dashboard</a>
                <a href="../backend/logout.php">Logout</a>
        </div>
    </header>
    <main class="container">
        <section class="project-details">

            <h1>
                <?php echo htmlspecialchars($project['title']) ?>
            </h1>

            <div class="status">
                Status:
                <strong>
                    <?php echo htmlspecialchars($project['status']); ?>
                </strong>
            </div>

            <p>
                <strong>Category:</strong>
                <?php echo htmlspecialchars($project['category']); ?>
            </p>

            <p>
                <strong>Submitted</strong>
                <?php echo date("d M Y", strtotime($project['created_at'])); ?>
            </p>

            <h3>Project Description</h3>
            <p class="description">
                <?php echo nl2br(htmlspecialchars($project['description'])); ?>
            </p>
        </section>

    <section class="section">

        <h2>Supervisor Feedback</h2>

        <?php if (mysqli_num_rows($feedback_result) > 0): ?>

            <?php while ($feedback = mysqli_fetch_assoc($feedback_result)): ?>

                <div class="feedback-card">

                    <h3>
                         <?php echo htmlspecialchars( $feedback['full_name']); ?>
                    </h3>

                    <p>
                        <?php echo nl2br( htmlspecialchars( $feedback['feedback'] )); ?>
                    </p>

                    <span>
                        <?php echo htmlspecialchars($feedback['status'] ); ?>
                    </span>

                    <small>
                        <?php echo date("d M Y",strtotime($feedback['created_at']));?>
                    </small>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <p>No feedback has been given yet. </p>

        <?php endif; ?>

    </section>

    <section class="section">
        <h2>
            Proposal Version History
        </h2>

        <?php if (mysqli_num_rows($version_result) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Version</th>
                        <th>Status</th>
                        <th>Submitted</th>
                    </tr>
                </thead>
                <tbody>

                <?php while ($version = mysqli_fetch_assoc($version_result)): ?>
                    <tr>
                        <td>Version
                            <?php echo $version['version_number'];?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars( $version['status']); ?>
                        </td>

                        <td>
                            <?php echo date( "d M Y",strtotime($version['submitted_at']) );?>
                        </td>

                    </tr>

                <?php endwhile; ?>

                </tbody>

            </table>

        <?php endif; ?>

    </main>
</body>
</html>
