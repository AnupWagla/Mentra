<?php require 'auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - Mentra Admin</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">

    <h1 class="page-title">My Profile</h1>
    <p class="page-sub">Manage your admin account details.</p>

    <div class="card">
        <p class="card-title">Account Details</p>
        <form method="POST" action="../backend/profile_process.php">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" placeholder="Your name">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="admin@mentra.local">
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" placeholder="Leave blank to keep current">
            </div>
            <button type="submit" class="btn">Save Changes</button>
        </form>
    </div>

</div>

</body>
</html>
