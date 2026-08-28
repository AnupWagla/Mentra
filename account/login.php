<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
<link rel="stylesheet" href="../style.css">
<link rel="stylesheet" href="login.css">
</head>
<body>

<div class="loginsection">

    <a href="/" class="login-logo">Mentra</a>

    <h2 class="login-title">Welcome Back</h2>

     <form action="../backend/login_process.php" method="POST">

        <div class="form-group">
            <label class="form-label"> Email <span class="required"> * </span> </label>
            <input type="email" id="email" class="form-control" placeholder="Your@email.com" required>
        </div>

        <div class="form-group">
            <label class="form-label"> Password <span class="required"> * </span> </label>
            <input type="password" id="password" class="form-control" placeholder="Your password" required>
        </div>

            <p class="Info"> Sign in to continue to your dashboard </p>

        <button type="submit" class="btn-login"> Sign In </button>
     </form>

     <div class="Option">or</div>
     <div class="login-footer">
        <p>
            New student? <a href="register.php">Here. Create an account.</a>
        </p>
        <br>
    <p>
            <a href="/"> ← Back to Home </a>
    </p>

</div>

<script>
document.getElementById("loginForm").addEventListener("submit", function(e){
e.preventDefault();
    let email = document.getElementById("email").value.trim();
    let password = document.getElementById("password").value.trim();
    let errorMsg = document.getElementById("errorMsg");
    if(email === "" || password === ""){
        errorMsg.style.display = "block";
        errorMsg.textContent = "All fields are required.";
        return;
    }
    errorMsg.style.display = "none";
    alert("Login Successful!");
});
</script>

</body>
</html>