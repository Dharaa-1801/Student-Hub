<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/db.php';

startSecureSession();
revokeRememberMeToken($pdo);
$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 120,
        $params['path'],
        $params['domain'] ?? '',
        (bool) $params['secure'],
        (bool) $params['httponly']
    );
}

session_destroy();
clearRememberMeCookie();
header('Location: login.php?message=' . urlencode('You have been logged out successfully.'));
exit();
