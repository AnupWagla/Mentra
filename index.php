<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentra</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</head>
<body>
    <!-- NAV BAR -->
    <nav class="navbar">
        <div class="logo">
            <img src="images/logo.png" alt="Logo">
            <h2>Mentra</h2>
        </div>
        <div class="buttons">
            <a href="account/login.php" class="login">Login</a>
            <a href="account/register.php" class="register">Register</a>
        </div>
    </nav>

    <!-- MAIN INTRODUCTION -->
    <section class="content">
        <h1>Project Supervision and Tracking System</h1>
        <p>Manage Projects. Track Progress. Collaborate Efficiently.</p>
        <p class="quote">A centralized platform for students and supervisors.</p>
        <a href="account/register.php" class="start-btn">Get Started</a>
    </section>
    
    <section class="features">
        <div class="feature">
            <i class="ti ti-folder"></i>
            <h3>Centralized</h3>
            <p>Everything is managed in one place.</p>
        </div>
        <div class="feature">
            <i class="ti ti-chart-line"></i>
            <h3>Track Progress</h3>
            <p>Monitor every milestone.</p>
        </div>
        <div class="feature">
            <i class="ti ti-messages"></i>
            <h3>Feedback</h3>
            <p>Instant supervisor comments.</p>
        </div>
    </section>

    <!-- ACCOUNT CARDS -->

    <section class="accounts">

        <!-- Login Card -->
        <a href="account/login.php" class="account">
            <i class="ti ti-lock"></i>
            <h2>Login</h2>
            <p>If already registered. Login to your account.</p>
            <span class="Abtn">Login Now</span>
        </a>
        
        <!-- Register Card -->
        <a href="account/register.php" class="account">
            <i class="ti ti-user-plus"></i>
            <h2>Register</h2>
            <p>If you are a new user, please create your account to begin.</p>
            <span class="Abtn">Create Account</span>
        </a>
    </section>
    
    <!--FOOTER -->
    <footer>
        <p>Mentra &copy; 2026</p>
    </footer>

</body>
</html>