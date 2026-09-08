<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Mentra</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="register.css">
</head>

<body>

<div class="registersection">

    <a href="../index.php" class="register-logo">Mentra</a>

    <h2 class="register-title">Create Account</h2>

     <p class="info-text">Sign up to access your student dashboard</p>
     
    <form action="../backend/register_process.php" method="POST">

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Full Name <span class="required">*</span></label>
                <input type="text" id="full_name" name="full_name" class="form-control"
                       placeholder="e.g. Anup Wagle" required autocomplete="name">
            </div>

            <div class="form-group">
                <label class="form-label">Email <span class="required">*</span></label>
                <input type="email" id="email" name="email" class="form-control"
                       placeholder="mentraservice@gmail.com" required autocomplete="email">
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Roll Number <span class="required">*</span></label>
                <input type="text" id="roll_number" name="roll_number" class="form-control"
                       placeholder="e.g. Tu Registered Code" required>
            </div>

            <div class="form-group">
                <label class="form-label">Phone <span class="optional">(optional)</span></label>
                <input type="tel" id="phone" name="phone" class="form-control"
                       placeholder="e.g. 9876543210">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Department <span class="required">*</span></label>
            <select id="dept_id" name="dept_id" class="form-control" required autocomplete="tel">
                <option value="">—> Select your department <—</option>
                <option value="1">BCA</option>
                <option value="2">BIT</option>
                <option value="3">BSc CSIT</option>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Password <span class="required">*</span></label>
                <input type="password" id="password" name="password" class="form-control"
                       placeholder="Min 8 chars" required autocomplete="new-password">
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password <span class="required">*</span></label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control"
                       placeholder="Re-enter password" required autocomplete="new-password">
            </div>
        </div>

        <button type="submit" class="btn-register">Create Account →</button>

    </form>

    <div class="register-footer">
        <p>Already have an account? <a href="login.php">Sign in</a></p>
        <br>
        <p><a href="../index.php">← Back to Home</a></p>
    </div>

</div>

</body>
</html>
