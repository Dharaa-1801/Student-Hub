<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

startSecureSession();

if (!restoreRememberedSession($pdo)) {
    header('Location: login.php?error=' . urlencode('Please log in to continue.'));
    exit();
}

if (!isset($_SESSION['last_activity']) ||
    time() - (int) $_SESSION['last_activity'] > SESSION_TIMEOUT) {
    $_SESSION = [];
    session_destroy();
    clearRememberMeCookie();
    header('Location: login.php?error=' . urlencode('Your session expired. Please log in again.'));
    exit();
}

$_SESSION['last_activity'] = time();

header('Location: ' . (($_SESSION['role'] ?? '') === 'admin'
    ? 'admin_dashboard.php'
    : 'student_dashboard.php'));
exit();
