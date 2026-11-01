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
     
    <form action="register_process.php" method="POST">

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
            <select id="dept_id" name="dept_id" class="form-control" required>
                <option value="">—> Select your department <—</option>
                <option value="1">BCA</option>
                <option value="2">BIT</option>
                <option value="3">BSc CSIT</option>
            </select>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Batch <span class="required">*</span></label>
                <select id="batch" name="batch" class="form-control" required>
                    <option value="">—> Select Batch <—</option>
                    <option value="2020">2020</option>
                    <option value="2021">2021</option>
                    <option value="2022">2022</option>
                    <option value="2023">2023</option>
                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                    <option value="2027">2027</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Semester <span class="required">*</span></label>
                <select id="semester" name="semester" class="form-control" required>
                    <option value="">—> Select Semester <—</option>
                    <option value="1st">1st</option>
                    <option value="2nd">2nd</option>
                    <option value="3rd">3rd</option>
                    <option value="4th">4th</option>
                    <option value="5th">5th</option>
                    <option value="6th">6th</option>
                    <option value="7th">7th</option>
                    <option value="8th">8th</option>
                </select>
            </div>
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
