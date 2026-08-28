```php
<?php

include "config/db.php";

$full_name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

$password = password_hash($password, PASSWORD_DEFAULT);

$role = "student";

$sql = "INSERT INTO users (full_name, email, password, role)
        VALUES (?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "ssss", $full_name, $email, $password, $role);

if (mysqli_stmt_execute($stmt)) {

    echo "Registration successful.";
    echo "<br><a href='../login/login.php'>Login here</a>";

} else {

    echo "Registration failed: " . mysqli_stmt_error($stmt);

}

mysqli_stmt_close($stmt);
mysqli_close($conn);

?>
```
