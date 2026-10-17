<?php
session_start();

// 1. Check if logged in & Admin role restriction
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Unauthorized Access! Admin privileges required.'); window.location.href='login.html';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - StudentHub</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 2rem; background: #fef2f2;">
    <h2 style="color: #991b1b;">Welcome Admin Dashboard, <?= htmlspecialchars($_SESSION['fullname']) ?>!</h2>
    <p>System Administrator Control Panel</p>
    <hr>
    <a href="logout.php" style="padding: 8px 16px; background: #dc2626; color: white; text-decoration: none; border-radius: 4px;">Logout</a>
</body>
</html>