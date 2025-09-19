<?php
session_start();
require 'db_connect.php'; // make sure this file connects to your database

// If the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Fetch user from database
    $stmt = $conn->prepare("SELECT id, fullname, currency, password FROM users WHERE username=? OR email=? LIMIT 1");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Login success → store session data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['currency'] = $user['currency'];

        // Redirect to dashboard
        header("Location: main_dashboard.php");
        exit;
    } else {
        // Invalid credentials → redirect back with error
        header("Location: login.php?error=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | BudgetWise</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
  <div class="form-container">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
      <p style="color: #10B981; text-align: center; font-weight: bold;">
        ✅ Registration successful! Please log in.
      </p>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
      <p style="color: #ef4444; text-align: center; font-weight: bold;">
        ❌ Invalid username or password!
      </p>
    <?php endif; ?>

    <h2>Login to BudgetWise</h2>

    <form action="login.php" method="POST">
      <label for="username">Email </label>
      <input type="text" id="username" name="username" required>

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required>

      <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="index.html">Register here</a></p>
  </div>
</body>
</html>
