<?php
session_start();

// Unset all variables
$_SESSION = array();

// Destroy session
session_destroy();

// Redirect to homepage or login page
header("Location: ../index.php"); 
exit();
?>