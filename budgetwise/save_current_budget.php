<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$month_year = $_POST['month_year'];

$housing = $_POST['housing'] ?? 0;
$transportation = $_POST['transportation'] ?? 0;
$food = $_POST['food'] ?? 0;
$health = $_POST['health'] ?? 0;
$savings = $_POST['savings'] ?? 0;
$shopping = $_POST['shopping'] ?? 0;
$entertainment = $_POST['entertainment'] ?? 0;

// Check if record exists
$sql = "SELECT id FROM current_budgets WHERE user_id = ? AND month_year = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $month_year);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // UPDATE
    $sql = "UPDATE current_budgets 
            SET housing=?, transportation=?, food=?, health=?, savings=?, shopping=?, entertainment=?
            WHERE user_id=? AND month_year=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dddddddis", $housing, $transportation, $food, $health, $savings, $shopping, $entertainment, $user_id, $month_year);
    $stmt->execute();
    header("Location: current_budget.php?success=2");
} else {
    // INSERT
    $sql = "INSERT INTO current_budgets (user_id, month_year, housing, transportation, food, health, savings, shopping, entertainment)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isddddddd", $user_id, $month_year, $housing, $transportation, $food, $health, $savings, $shopping, $entertainment);
    $stmt->execute();
    header("Location: current_budget.php?success=1");
}
exit;
?>
