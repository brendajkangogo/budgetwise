<?php
session_start();

// Destroy all session data
session_unset();
session_destroy();

// Redirect to login page (or homepage if you prefer)
header("Location: login.php");
exit;
?>
