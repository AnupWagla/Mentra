<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../account/login.php');
    exit;
}
