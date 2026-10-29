<?php
require_once __DIR__ . '/page_session.php';

$message = htmlspecialchars(
    (string) ($_GET['error'] ?? $_GET['message'] ?? ''),
    ENT_QUOTES,
    'UTF-8'
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - StudentHub Portal</title>
    <link rel="stylesheet" href="style.css?v=20261029">
    <script src="script.js" defer></script>
</head>
<body style="font-family: Arial, sans-serif; background: #f8fafc; padding: 2rem;">
    <?php renderPortalHeader(); ?>
    <div style="max-width: 400px; margin: auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <h2 style="text-align: center; color: #3b0764;">StudentHub Login</h2>
        <p id="login-message" style="color: #b91c1c; text-align: center;"><?= $message ?></p>
        <form action="authenticate.php" method="POST">
            <div style="margin-bottom: 1rem;">
                <label for="email" style="display: block; font-weight: bold; margin-bottom: 4px;">Email Address:</label>
                <input type="email" id="email" name="email" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label for="password" style="display: block; font-weight: bold; margin-bottom: 4px;">Password:</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="font-weight: normal;"><input type="checkbox" name="remember"> Remember Me</label>
            </div>
            <button type="submit" style="width: 100%; padding: 10px; background: #06b6d4; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">Login</button>
        </form>
        <p style="text-align: center; margin-top: 1rem;">
            New user?
            <a href="register.php" style="color: #5b21b6; font-weight: bold;">Register here</a>
        </p>
    </div>
    <?php renderPortalFooter(); ?>
</body>
</html>