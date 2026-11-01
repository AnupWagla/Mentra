<?php
require_once "../backend/admin_check.php";
require_once "../db.php";
/** @var mysqli $conn */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Supervisor - Mentra</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>

<?php include 'sidebar.php'; ?>
<div class="page-wrapper">
<main class="container">
    <div class="form-card">
        <h1>Create Supervisor</h1>
        <p class="subtitle">Add a new supervisor account to Mentra</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert-error">
                <?php
                if ($_GET['error'] === 'exists') {
                    echo 'An account with this email already exists.';
                } elseif ($_GET['error'] === 'mismatch') {
                    echo 'Passwords do not match.';
                } elseif ($_GET['error'] === 'length') {
                    echo 'Password must be at least 8 characters.';
                } elseif ($_GET['error'] === 'invalid_email') {
                    echo 'Please enter a valid email address.';
                } elseif ($_GET['error'] === 'empty') {
                    echo 'Please fill in all required fields.';
                } else {
                    echo 'An error occurred while creating the account.';
                }
                ?>
            </div>
        <?php endif; ?>

        <form action="../backend/create_supervisor_process.php" method="POST">
            <div class="form-group">
                <label for="full_name">Full Name <span class="required">*</span></label>
                <input type="text" id="full_name" name="full_name" placeholder="e.g. Dr. John Doe" required autocomplete="name">
            </div>

            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" id="email" name="email" placeholder="supervisor@mentra.com" required autocomplete="email">
            </div>

            <div class="form-group">
                <label for="phone">Phone <span class="optional">(optional)</span></label>
                <input type="tel" id="phone" name="phone" placeholder="e.g. 9812345678" autocomplete="tel">
            </div>

            <div class="form-group">
                <label for="department">Department <span class="required">*</span></label>
                <select id="department" name="department" required>
                    <option value="">— Select Department —</option>
                    <option value="BCA">BCA</option>
                    <option value="BIT">BIT</option>
                    <option value="BSc CSIT">BSc CSIT</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password">Password <span class="required">*</span></label>
                <input type="password" id="password" name="password" placeholder="Min 8 characters" minlength="8" required autocomplete="new-password">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password <span class="required">*</span></label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter password" minlength="8" required autocomplete="new-password">
            </div>

            <button type="submit">Create Supervisor Account</button>
        </form>
    </div>
</main>
</div>

</body>
</html>
