<?php
require_once "../account/auth_check.php";

$error = htmlspecialchars($_GET['error'] ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Project – Mentra</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="submit_project.css">
</head>
<body>

<header class="header">
    <div class="header-brand">
        <span class="brand-dot"></span>
        <h2>Mentra</h2>
    </div>
    <div class="header-right">
        <span class="user-name"><?php echo htmlspecialchars($_SESSION['name']); ?></span>
        <a href="../account/logout.php" class="logout-btn">Logout</a>
    </div>
</header>

<main class="container">
    <div class="form-card">

        <div class="form-header">
            <div class="form-icon">📁</div>
            <h1>Submit Project Proposal</h1>
            <p class="subtitle">Fill in all the details below to submit your project for review.</p>
        </div>

        <?php if ($error): ?>
            <div class="alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="save_project.php" method="POST" enctype="multipart/form-data" id="projectForm">

            <!-- Project Title -->
            <div class="field-group">
                <label for="title">Project Title <span class="required">*</span></label>
                <input type="text" id="title" name="title"
                       placeholder="e.g. Online Voting System"
                       required>
            </div>

            <!-- Category -->
            <div class="field-group">
                <label for="category">Project Category <span class="required">*</span></label>
                <div class="select-wrapper">
                    <select id="category" name="category" required>
                        <option value="">Select a category</option>
                        <option value="Web Development">Web Development</option>
                        <option value="Mobile Application">Mobile Application</option>
                        <option value="Desktop Application">Desktop Application</option>
                        <option value="AI / Machine Learning">AI / Machine Learning</option>
                        <option value="Cybersecurity">Cybersecurity</option>
                        <option value="Database System">Database System</option>
                        <option value="Networking">Networking</option>
                        <option value="Other">Other</option>
                    </select>
                    <span class="select-arrow">▾</span>
                </div>
            </div>

            <!-- Description -->
            <div class="field-group">
                <label for="description">Project Description <span class="required">*</span></label>
                <textarea id="description" name="description" rows="6"
                          placeholder="Describe your project goals, features, and technology stack..."
                          required></textarea>
            </div>

            <!-- Git / Repository Link -->
            <div class="field-group">
                <label for="git_link">
                    Repository / Git Link
                    <span class="optional-tag">Optional</span>
                </label>
                <div class="input-with-icon">
                    <span class="input-icon">🔗</span>
                    <input type="url" id="git_link" name="git_link"
                           placeholder="https://github.com/your-username/your-repo">
                </div>
                <p class="field-hint">Paste your GitHub, GitLab, or Bitbucket repository URL.</p>
            </div>

            <!-- File Upload -->
            <div class="field-group">
                <label for="project_file">
                    Project Document
                    <span class="optional-tag">Optional</span>
                </label>
                <div class="file-drop-area" id="fileDropArea">
                    <input type="file" id="project_file" name="project_file"
                           accept=".pdf,.doc,.docx,.zip,.rar,.txt,.ppt,.pptx"
                           class="file-input" id="fileInput">
                    <div class="file-drop-content">
                        <div class="file-icon">📎</div>
                        <p class="file-drop-text">Click to upload or drag & drop</p>
                        <p class="file-type-hint">PDF, DOC, DOCX, ZIP, PPT — max 10 MB</p>
                        <p class="file-chosen" id="fileChosen" style="display:none;"></p>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="../student/dashboard.php" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-submit" id="submitBtn">
                    <span class="btn-text">Submit Proposal</span>
                    <span class="btn-icon">→</span>
                </button>
            </div>

        </form>
    </div>
</main>

<script>
// File picker feedback
const fileInput = document.getElementById('project_file');
const fileChosen = document.getElementById('fileChosen');
const fileDropArea = document.getElementById('fileDropArea');

fileInput.addEventListener('change', () => {
    if (fileInput.files.length > 0) {
        fileChosen.textContent = '✔ ' + fileInput.files[0].name;
        fileChosen.style.display = 'block';
        fileDropArea.classList.add('has-file');
    }
});

// Drag & drop
fileDropArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    fileDropArea.classList.add('drag-over');
});
fileDropArea.addEventListener('dragleave', () => {
    fileDropArea.classList.remove('drag-over');
});
fileDropArea.addEventListener('drop', (e) => {
    e.preventDefault();
    fileDropArea.classList.remove('drag-over');
    if (e.dataTransfer.files.length > 0) {
        fileInput.files = e.dataTransfer.files;
        fileChosen.textContent = '✔ ' + e.dataTransfer.files[0].name;
        fileChosen.style.display = 'block';
        fileDropArea.classList.add('has-file');
    }
});

// Submit loading state
document.getElementById('projectForm').addEventListener('submit', () => {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.querySelector('.btn-text').textContent = 'Submitting...';
});
</script>

</body>
</html>