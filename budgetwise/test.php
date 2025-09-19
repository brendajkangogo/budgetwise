<?php
$conn = mysqli_connect("localhost", "root", "", "budget_tracker");

if ($conn) {
    echo "Database connected successfully!";
} else {
    echo "Connection failed: " . mysqli_connect_error();
}
?>
