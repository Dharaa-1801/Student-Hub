<?php
session_start();

// 1. Check if logged in & Role check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    echo "<script>alert('Unauthorized Access! Please login as a student.'); window.location.href='login.html';</script>";
    exit();
}

// 2. Session Timeout Check (e.g., 5 minutes = 300 seconds)
$timeout_duration = 300;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout_duration) {
    session_unset();
    session_destroy();
    echo "<script>alert('Session timeout! Please login again.'); window.location.href='login.html';</script>";
    exit();
}
$_SESSION['last_activity'] = time(); // Refresh timestamp
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard - StudentHub</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 2rem; background: #f0fdf4;">
    <h2 style="color: #166534;">Welcome Student Dashboard, <?= htmlspecialchars($_SESSION['fullname']) ?>!</h2>
    <p>Email: <?= htmlspecialchars($_SESSION['email']) ?></p>
    <hr>
    <a href="logout.php" style="padding: 8px 16px; background: #dc2626; color: white; text-decoration: none; border-radius: 4px;">Logout</a>
</body>
</html>