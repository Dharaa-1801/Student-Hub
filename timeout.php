<?php
require_once __DIR__ . '/auth.php';

startSecureSession();
$_SESSION = [];
session_destroy();
clearRememberMeCookie();

header('Location: login.php?error=' . urlencode('Your session expired. Please log in again.'));
exit();
