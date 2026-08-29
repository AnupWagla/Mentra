<?php

session_start();

$_SESSION = [];

session_destroy();

header("Location: ../account/login.php");
exit();

?>