<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$current_month = date("Y-m");

// Fetch planned budget
$sql = "SELECT * FROM planned_budgets WHERE user_id = ? AND month_year = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $current_month);
$stmt->execute();
$planned = $stmt->get_result()->fetch_assoc();

// Fetch current budget
$sql2 = "SELECT * FROM current_budgets WHERE user_id = ? AND month_year = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("is", $user_id, $current_month);
$stmt2->execute();
$current = $stmt2->get_result()->fetch_assoc();

$categories = ["housing" => "Housing & Utilities", "transportation" => "Transportation",
               "food" => "Food & Groceries", "health" => "Health & Wellness",
               "savings" => "Savings", "shopping" => "Shopping & Personal Care",
               "entertainment" => "Entertainment"];

function safe_value($array, $key) {
    return isset($array[$key]) ? (float)$array[$key] : 0;
}

$planned_total = 0;
$current_total = 0;
$differences = [];
foreach ($categories as $key => $label) {
    $p = safe_value($planned, $key);
    $c = safe_value($current, $key);
    $planned_total += $p;
    $current_total += $c;
    $differences[$key] = $c - $p;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Summary | BudgetWise</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .summary-container {
            display: flex;
            flex-direction: column;
            gap: 30px;
            max-width: 900px;
            margin: 0 auto;
        }
        .top-section {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }
        .budget-table {
            flex: 1;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 15px;
        }
        .budget-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .budget-table th, .budget-table td {
            padding: 8px;
            border: 1px solid #e5e7eb;
            text-align: left;
        }
        .difference-section {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 15px;
        }
        h2 {
            text-align: center;
            margin-top: 0;
        }
        h3 {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="summary-container">
    <h1 style="text-align:center;">Budget Summary (<?= $current_month ?>)</h1>

    <div class="top-section">
        <!-- Planned Budget -->
        <div class="budget-table">
            <h2>Planned Budget</h2>
            <table>
                <tr><th>Category</th><th>Planned (<?= htmlspecialchars($_SESSION['currency']); ?>)</th></tr>
                <?php foreach ($categories as $key => $label): ?>
                    <tr>
                        <td><?= $label ?></td>
                        <td><?= number_format(safe_value($planned, $key), 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr><th>Total</th><th><?= number_format($planned_total, 2) ?></th></tr>
            </table>
        </div>

        <!-- Current Budget -->
        <div class="budget-table">
            <h2>Current Budget</h2>
            <table>
                <tr><th>Category</th><th>Actual (<?= htmlspecialchars($_SESSION['currency']); ?>)</th></tr>
                <?php foreach ($categories as $key => $label): ?>
                    <tr>
                        <td><?= $label ?></td>
                        <td><?= number_format(safe_value($current, $key), 2) ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr><th>Total</th><th><?= number_format($current_total, 2) ?></th></tr>
            </table>
        </div>
    </div>

    <!-- Differences -->
    <div class="difference-section">
        <h2>Differences</h2>
        <table width="100%">
            <tr><th>Category</th><th>Difference</th></tr>
            <?php foreach ($categories as $key => $label): ?>
                <?php 
                    $diff = $differences[$key];
                    $status = $diff > 0 ? "Over" : ($diff < 0 ? "Saved" : "On Budget");
                    $color = $diff > 0 ? "red" : ($diff < 0 ? "green" : "gray");
                ?>
                <tr>
                    <td><?= $label ?></td>
                    <td style="color:<?= $color ?>;">
                        <?= ($diff > 0 ? "+" : "") . number_format($diff, 2) ?> (<?= $status ?>)
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <th>Total</th>
                <th style="color:<?= $current_total > $planned_total ? 'red' : ($current_total < $planned_total ? 'green' : 'gray') ?>;">
                    <?= ($current_total > $planned_total ? "+" : "") . number_format($current_total - $planned_total, 2) ?>
                </th>
            </tr>
        </table>

        <h3>
            <?php if ($current_total > $planned_total): ?>
                ❌ You overspent by <?= htmlspecialchars($_SESSION['currency']) . " " . number_format($current_total - $planned_total, 2) ?> this month.
            <?php elseif ($current_total < $planned_total): ?>
                ✅ Great job! You saved <?= htmlspecialchars($_SESSION['currency']) . " " . number_format($planned_total - $current_total, 2) ?> this month.
            <?php else: ?>
                🎯 Perfect! You stayed exactly on budget this month.
            <?php endif; ?>
        </h3>
    </div>

    <p style="text-align:center;"><a href="main_dashboard.php" class="back-link">⬅ Back to Dashboard</a></p>
</div>
</body>
</html>
