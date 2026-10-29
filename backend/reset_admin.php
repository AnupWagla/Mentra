
<?php
require_once __DIR__ . '/db.php';

$email = 'admin@mentra.com';
$newPassword = 'MentraAdmin@2026';

$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

$sql = "UPDATE users SET password = ? WHERE email = ? AND role = 'admin'";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $email);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo "Admin password reset successfully.";
} else {
    echo "No password change made. Check the admin account.";
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
