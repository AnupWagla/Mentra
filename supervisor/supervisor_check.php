<?php
require_once '../account/auth_check.php';
if($_SESSION['role'] !== 'supervisor'){
    header("Location: ../student/dashboard.php");
    exit();
}
?>