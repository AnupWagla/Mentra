<?php
require_once "auth_check.php";
if($_SESSION['role'] !== 'supervisor'){
    header("Location: ../dashboard/dashboard.php");
    exit();
}
?>