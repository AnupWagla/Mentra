<?php
require 'auth.php';
include '../backend/db.php';

$announcements = @mysqli_query($conn, "SELECT * FROM announcements ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements - Mentra Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">

    <h1 class="page-title">Announcements</h1>
    <p class="page-sub">Post and manage announcements.</p>

    <?php if (isset($_GET['success'])): ?>
        <p class="msg-success">✓ Announcement posted successfully.</p>
    <?php endif; ?>

    <div class="card">
        <p class="card-title">Post New Announcement</p>
        <form method="POST" action="../backend/admin/add_announcement.php">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" placeholder="Announcement title" required>
            </div>
            <div class="form-group">
                <label>Message</label>
                <textarea name="message" rows="4" placeholder="Write your announcement..." required></textarea>
            </div>
            <div class="form-group">
                <label>Target</label>
                <select name="target">
                    <option value="all">Everyone</option>
                    <option value="students">Students Only</option>
                    <option value="supervisors">Supervisors Only</option>
                </select>
            </div>
            <button type="submit" class="btn">Post Announcement</button>
        </form>
    </div>

    <div class="card">
        <p class="card-title">Recent Announcements</p>
        <table>
            <thead>
                <tr><th>Title</th><th>Target</th><th>Date</th></tr>
            </thead>
            <tbody>
                <?php if ($announcements && mysqli_num_rows($announcements) > 0):
                    while ($a = mysqli_fetch_assoc($announcements)): ?>
                    <tr>
                        <td><?= htmlspecialchars($a['title']) ?></td>
                        <td><?= htmlspecialchars($a['target']) ?></td>
                        <td><?= $a['created_at'] ?></td>
                    </tr>
                <?php endwhile; else: ?>
                    <tr><td colspan="3" style="text-align:center;color:#b8cde8;">No announcements yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
