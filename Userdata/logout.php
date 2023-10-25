<?php
session_start();

// Unset session variables and destroy the session
unset($_SESSION['userlogin']);
unset($_SESSION['userRole']);
session_destroy();

header('location: index.php');
?>
