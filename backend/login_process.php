<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../account/login.php');
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($email) || empty($password)) {
    die('Please enter email and password. <a href="../account/login.php">Go back</a>');
}

// ── Hardcoded users (no database needed yet) ─────────────────
$users = [
    [
        'email'    => 'mentraadmin@gmail.com',
        'password' => 'mentraadmin123',
        'name'     => 'Admin',
        'role'     => 'admin',
    ],
    [
        'email'    => 'student@mentra.local',
        'password' => 'student123',
        'name'     => 'Test Student',
        'role'     => 'student',
    ],
];

$found = false;
foreach ($users as $user) {
    if ($user['email'] === $email && $user['password'] === $password) {
        $_SESSION['name']  = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role']  = $user['role'];

        if ($user['role'] === 'admin') {
            $_SESSION['admin'] = true;
            header('Location: ../admin/dashboard.php');
        } else {
            header('Location: ../dashboard/dashboard.php');
        }
        $found = true;
        exit;
    }
}

if (!$found) {
    header('Location: ../account/login.php?error=1');
    exit;
}
?>
