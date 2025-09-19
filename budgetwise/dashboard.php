<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

echo "<h1>Welcome, " . htmlspecialchars($_SESSION['fullname']) . "!</h1>";
echo "<p>Your default currency: " . htmlspecialchars($_SESSION['currency']) . "</p>";
echo "<a href='logout.php'>Logout</a>";
?>

