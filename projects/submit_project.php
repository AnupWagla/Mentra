<?php
require_once "../backend/auth_check.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Project</title>
    <link rel="stylesheet" href="project.css">
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
    <div class="form-box">
        <h1>Submit Project Proposal</h1>
        <p class="subtitle">
            Enter your project details below.
        </p>

        <form action="save_project.php"
              method="POST">


            <label for="title">Project Title</label>
            <input type="text" id="title" name="title" placeholder="Enter project title" required >

            <label for="category"> Project Category</label>

            <select id="category" name="category" required >

                <option value=""> Select category</option>
                <option value="Web Development"> Web Development </option>
                <option value="Mobile Application">Mobile Application</option>
                <option value="Desktop Application"> Desktop Application</option>
                <option value="AI / Machine Learning">AI / Machine Learning</option>
                <option value="Cybersecurity">Cybersecurity</option>
                <option value="Other"> Other </option>
            </select>


            <label for="description">Project Description </label>
            <textarea id="description" name="description" rows="8" placeholder="Describe your project..." required></textarea>

            <button type="submit">Submit Proposal</button>

            <a href="../dashboard/dashboard.php" class="cancel">Cancel</a>

        </form>

    </div>

</main>

</body>
</html>