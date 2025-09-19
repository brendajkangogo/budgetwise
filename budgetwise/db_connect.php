<?php
// Show errors during development
ini_set('display_errors', 1);
error_reporting(E_ALL);

// CONNECT TO DATABASE
$conn = mysqli_connect("localhost", "root", "", "budget_tracker");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
