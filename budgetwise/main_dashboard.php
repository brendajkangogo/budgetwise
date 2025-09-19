<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Optional: you can fetch user data from the database again if you want the most updated info
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard | BudgetWise</title>
  <link rel="stylesheet" href="main_dashboard.css">
</head>
<body>
  <header>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?> 👋</h1>
    <p>Your default currency: <strong><?php echo htmlspecialchars($_SESSION['currency']); ?></strong></p>
    <a href="logout.php" class="logout-btn">Logout</a>
  </header>

  <main class="dashboard-container">
    <h2>Your Dashboard</h2>
    <p>Select what you’d like to do today:</p>

    <div class="card-container">
      <!-- Card 1: Planned Budget -->
      <div class="card">
        <h3>📊 Planned Budget</h3>
        <p>Create or adjust your monthly planned budget for all categories.</p>
        <a href="planned_budget.php" class="btn">Set Up / Edit Budget</a>
      </div>

      <!-- Card 2: Current Spending -->
      <div class="card">
        <h3>💸 Current Budget Activity</h3>
        <p>Enter your expenses for this month and save them for tracking.</p>
        <a href="current_budget.php" class="btn">Add Expenses</a>
      </div>

      <!-- Card 3: Summary & Comparison -->
      <div class="card">
        <h3>📑 Summary</h3>
        <p>View a comparison between your planned and actual spending.</p>
        <a href="summary.php" class="btn">View Summary</a>
      </div>
    </div>
  </main>
</body>
</html>
