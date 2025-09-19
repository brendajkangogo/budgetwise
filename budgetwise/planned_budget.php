<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$current_month = date("Y-m");

// Fetch existing planned budget (if any)
$sql = "SELECT * FROM planned_budgets WHERE user_id = ? AND month_year = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $current_month);
$stmt->execute();
$result = $stmt->get_result();
$existing_budget = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planned Budget | BudgetWise</title>
    <link rel="stylesheet" href="dashboard.css">
    <script>
        function calculateTotal() {
            let fields = document.querySelectorAll(".budget-input");
            let total = 0;
            fields.forEach(f => total += parseFloat(f.value || 0));
            document.getElementById("total").innerText = total.toFixed(2);
        }
        window.onload = calculateTotal;
    </script>
</head>
<body>
    <div class="container">
        <h1>Planned Budget - <?= htmlspecialchars($current_month) ?></h1>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <p class="success">✅ Planned budget saved successfully!</p>
        <?php elseif (isset($_GET['success']) && $_GET['success'] == 2): ?>
            <p class="success">🔄 Planned budget updated successfully!</p>
        <?php endif; ?>

        <form action="save_planned_budget.php" method="POST" class="budget-form">
            <input type="hidden" name="month_year" value="<?= $current_month ?>">

            <label>Housing & Utilities</label>
            <input class="budget-input" type="number" step="0.01" name="housing" value="<?= $existing_budget['housing'] ?? '' ?>" oninput="calculateTotal()">

            <label>Transportation</label>
            <input class="budget-input" type="number" step="0.01" name="transportation" value="<?= $existing_budget['transportation'] ?? '' ?>" oninput="calculateTotal()">

            <label>Food & Groceries</label>
            <input class="budget-input" type="number" step="0.01" name="food" value="<?= $existing_budget['food'] ?? '' ?>" oninput="calculateTotal()">

            <label>Health & Wellness</label>
            <input class="budget-input" type="number" step="0.01" name="health" value="<?= $existing_budget['health'] ?? '' ?>" oninput="calculateTotal()">

            <label>Savings</label>
            <input class="budget-input" type="number" step="0.01" name="savings" value="<?= $existing_budget['savings'] ?? '' ?>" oninput="calculateTotal()">

            <label>Shopping & Personal Care</label>
            <input class="budget-input" type="number" step="0.01" name="shopping" value="<?= $existing_budget['shopping'] ?? '' ?>" oninput="calculateTotal()">

            <label>Entertainment</label>
            <input class="budget-input" type="number" step="0.01" name="entertainment" value="<?= $existing_budget['entertainment'] ?? '' ?>" oninput="calculateTotal()">

            <h3>Total: <span id="total">0.00</span></h3>

            <button type="submit" class="btn"><?= $existing_budget ? "Update" : "Save" ?> Planned Budget</button>
        </form>

        <p style="text-align:center; margin-top:20px;">
    <a href="main_dashboard.php" class="back-link">⬅ Back to Dashboard</a>
</p>

    </div>
</body>
</html>
