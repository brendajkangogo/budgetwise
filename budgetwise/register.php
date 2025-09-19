<?php
// Show errors while testing
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 1. CONNECT TO DATABASE
$conn = mysqli_connect("localhost", "root", "", "budget_tracker");
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// 2. COLLECT & ESCAPE DATA
$fullname = mysqli_real_escape_string($conn, $_POST['fullname'] ?? '');
$username = mysqli_real_escape_string($conn, $_POST['username'] ?? '');
$email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$currency = mysqli_real_escape_string($conn, $_POST['currency'] ?? '');
$income = isset($_POST['income']) && $_POST['income'] !== '' ? (float)$_POST['income'] : NULL;

// 3. VALIDATE SERVER SIDE
if (empty($fullname) || empty($username) || empty($email) || empty($password) || empty($currency)) {
    die("Please fill all required fields.");
}

// 4. HASH PASSWORD
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 5. CHECK IF USER ALREADY EXISTS
$checkUser = mysqli_query($conn, "SELECT id FROM users WHERE email='$email' OR username='$username'");
if (mysqli_num_rows($checkUser) > 0) {
    die("Email or Username already exists!");
}

// 6. INSERT DATA
$income_sql = is_null($income) ? "NULL" : $income;
$sql = "INSERT INTO users (fullname, username, email, password, currency, monthly_income)
        VALUES ('$fullname', '$username', '$email', '$hashed_password', '$currency', $income_sql)";

if (mysqli_query($conn, $sql)) {
    // ✅ No echo/HTML here, just redirect
    header("Location: login.php?success=1");
    exit;
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
