<div class="sidebar">

    <a href="../index.php" class="sidebar-logo">Mentra</a>

    <div class="sidebar-section-label">Overview</div>
    <a href="dashboard.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : '' ?>">⊞ Dashboard</a>
    <a href="announcements.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'announcements.php' ? 'active' : '' ?>">📢 Announcements</a>
    <a href="projects.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'projects.php' ? 'active' : '' ?>">📋 All Projects</a>

    <div class="sidebar-section-label">User Management</div>
    <a href="students.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'students.php' ? 'active' : '' ?>">👨‍🎓 Students</a>
    <a href="supervisors.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'supervisors.php' ? 'active' : '' ?>">👨‍🏫 Supervisors</a>
    <a href="departments.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'departments.php' ? 'active' : '' ?>">🏛 Department</a>

    <div class="sidebar-section-label">Account</div>
    <a href="profile.php" class="nav-item <?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>">👤 My Profile</a>
    <a href="logout.php" class="nav-item nav-logout">⎋ Logout</a>

</div>
