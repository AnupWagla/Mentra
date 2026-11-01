<?php
// Detect active page for nav highlighting
$current = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">

    <div class="sidebar-logo">
        <span class="sidebar-logo-icon">M</span>
        Mentra
    </div>

    <nav class="sidebar-nav">

        <p class="sidebar-section-label">Main</p>

        <a href="dashboard.php"
           class="sidebar-link <?= $current === 'dashboard.php' ? 'active' : '' ?>">
            <span class="sidebar-icon">⊞</span> Dashboard
        </a>

        <p class="sidebar-section-label">Manage</p>

        <a href="students.php"
           class="sidebar-link <?= in_array($current, ['students.php','student_detail.php']) ? 'active' : '' ?>">
            <span class="sidebar-icon">👤</span> Students
        </a>

        <a href="supervisors.php"
           class="sidebar-link <?= in_array($current, ['supervisors.php','supervisor_detail.php','create_supervisor.php']) ? 'active' : '' ?>">
            <span class="sidebar-icon">🎓</span> Supervisors
        </a>

        <a href="projects.php"
           class="sidebar-link <?= in_array($current, ['projects.php','assign_supervisor.php']) ? 'active' : '' ?>">
            <span class="sidebar-icon">📁</span> Projects
        </a>

    </nav>

    <div class="sidebar-footer">
        <a href="logout.php" class="sidebar-logout">
            <span class="sidebar-icon">⏻</span> Logout
        </a>
    </div>

</aside>
