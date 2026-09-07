<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register — Mentra</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Inter:wght@400;500&display=swap">
    <link rel="stylesheet" href="register.css">
</head>
<body class="auth-page">

    <div class="auth-wrapper">
        
        <!-- Right panel (form) -->
        <main class="auth-form-wrap">
            <div class="auth-card">
                <div class="auth-card-header">
                    <h1>Create your account</h1>
                    <p>Already have an account? <a href="/login.php">Login in</a></p>
                </div>

                <form method="POST" action="register.php">

                    <!-- Row 1: Full Name + Email -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="full_name">Full Name <span class="req">*</span></label>
                            <input type="text"
                                   id="full_name"
                                   name="full_name"
                                   placeholder="e.g. Aarav Poudel"
                                   required
                                   autocomplete="name">
                            <span class="field-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address <span class="req">*</span></label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   placeholder="you@college.edu"
                                   required
                                   autocomplete="email">
                            <span class="field-error"></span>
                        </div>
                    </div>

                    <!-- Row 2: Roll Number + Phone -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="roll_number">Roll Number <span class="req">*</span></label>
                            <input type="text"
                                   id="roll_number"
                                   name="roll_number"
                                   placeholder="e.g. BCA-2024-001"
                                   required>
                            <span class="field-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number <span class="opt">(optional)</span></label>
                            <input type="tel"
                                   id="phone"
                                   name="phone"
                                   placeholder="e.g. 9812345678">
                        </div>
                    </div>

                    <!-- Department -->
                    <div class="form-group">
                        <label for="dept_id">Department <span class="req">*</span></label>
                        <select id="dept_id" name="dept_id" required>
                            <option value="">— Select your department —</option>
                            <option value="1">BCA</option>
                            <option value="2">BIT</option>
                            <option value="3">BSc CSIT</option>
                        </select>
                        <span class="field-error"></span>
                    </div>

                    <!-- Row 3: Password + Confirm -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password <span class="req">*</span></label>
                            <div class="input-wrap">
                                <input type="password"
                                       id="password"
                                       name="password"
                                       placeholder="Min 8 chars, 1 uppercase, 1 number"
                                       required
                                       autocomplete="new-password">
                                <button type="button" class="eye-toggle" data-target="password" aria-label="Toggle password">
                                    👁
                                </button>
                            </div>
                            <span class="field-error"></span>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password <span class="req">*</span></label>
                            <div class="input-wrap">
                                <input type="password"
                                       id="confirm_password"
                                       name="confirm_password"
                                       placeholder="Re-enter your password"
                                       required
                                       autocomplete="new-password">
                                <button type="button" class="eye-toggle" data-target="confirm_password" aria-label="Toggle password">
                                    👁
                                </button>
                            </div>
                            <span class="field-error"></span>
                        </div>
                    </div>

                    <!-- Password strength bar -->
                    <div class="strength-wrap" id="strengthWrap" style="display:none">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strengthFill"></div>
                        </div>
                        <span class="strength-label" id="strengthLabel">Weak</span>
                    </div>

                    <button type="submit" class="btn-submit" id="submitBtn">
                        Create Account →
                    </button>

                    <p class="terms">
                        This account is for <strong>students only</strong>.
                    </p>
                      <a href="/" class="back-link">← Back to Home</a>
                </form>

            </div>
        </main>
    </div>

</body>
</html>