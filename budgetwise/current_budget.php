<?php
session_start();
require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$current_month = date("Y-m");

// Fetch existing current budget (if any)
$sql = "SELECT * FROM current_budgets WHERE user_id = ? AND month_year = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $user_id, $current_month);
$stmt->execute();
$result = $stmt->get_result();
$existing_current = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Current Budget | BudgetWise</title>
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
        <h1>Current Budget - <?= $current_month ?></h1>

        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <p class="success">✅ Current budget saved successfully!</p>
        <?php elseif (isset($_GET['success']) && $_GET['success'] == 2): ?>
            <p class="success" style="color: #f59e0b;">🔄 Current budget updated successfully!</p>
        <?php endif; ?>

        <form action="save_current_budget.php" method="POST" class="budget-form">
            <input type="hidden" name="month_year" value="<?= $current_month ?>">

            <div class="form-group">
                <label>Housing & Utilities</label>
                <input class="budget-input" type="number" name="housing" step="0.01" 
                    value="<?= $existing_current['housing'] ?? '' ?>" oninput="calculateTotal()">
            </div>

            <div class="form-group">
                <label>Transportation</label>
                <input class="budget-input" type="number" name="transportation" step="0.01" 
                    value="<?= $existing_current['transportation'] ?? '' ?>" oninput="calculateTotal()">
            </div>

            <div class="form-group">
                <label>Food & Groceries</label>
                <input class="budget-input" type="number" name="food" step="0.01" 
                    value="<?= $existing_current['food'] ?? '' ?>" oninput="calculateTotal()">
            </div>

            <div class="form-group">
                <label>Health & Wellness</label>
                <input class="budget-input" type="number" name="health" step="0.01" 
                    value="<?= $existing_current['health'] ?? '' ?>" oninput="calculateTotal()">
            </div>

            <div class="form-group">
                <label>Savings</label>
                <input class="budget-input" type="number" name="savings" step="0.01" 
                    value="<?= $existing_current['savings'] ?? '' ?>" oninput="calculateTotal()">
            </div>

            <div class="form-group">
                <label>Shopping & Personal Care</label>
                <input class="budget-input" type="number" name="shopping" step="0.01" 
                    value="<?= $existing_current['shopping'] ?? '' ?>" oninput="calculateTotal()">
            </div>

            <div class="form-group">
                <label>Entertainment</label>
                <input class="budget-input" type="number" name="entertainment" step="0.01" 
                    value="<?= $existing_current['entertainment'] ?? '' ?>" oninput="calculateTotal()">
            </div>

            <h3>Total: <span id="total">0.00</span></h3>

            <button type="submit" class="btn">
                <?= $existing_current ? "Update" : "Save" ?> Current Budget
            </button>
        </form>

        <p style="text-align:center; margin-top:20px;">
    <a href="main_dashboard.php" class="back-link">⬅ Back to Dashboard</a>
</p>

    </div>
</body>
</html>
