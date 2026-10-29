<?php
declare(strict_types=1);

const SESSION_TIMEOUT = 300;
const REMEMBER_COOKIE = 'studenthub_remember';
const REMEMBER_DAYS = 30;

function startSecureSession(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

function clearRememberMeCookie(): void
{
    setcookie(REMEMBER_COOKIE, '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function revokeRememberMeToken(PDO $pdo): void
{
    if (empty($_COOKIE[REMEMBER_COOKIE])) {
        return;
    }

    $parts = explode(':', $_COOKIE[REMEMBER_COOKIE], 2);
    if (count($parts) === 2 && ctype_xdigit($parts[0])) {
        $statement = $pdo->prepare('DELETE FROM remember_tokens WHERE selector = :selector');
        $statement->execute([':selector' => $parts[0]]);
    }
}

function createRememberMeToken(PDO $pdo, int $userId): void
{
    $selector = bin2hex(random_bytes(12));
    $token = bin2hex(random_bytes(32));
    $expiresAt = date('Y-m-d H:i:s', time() + (REMEMBER_DAYS * 86400));

    $statement = $pdo->prepare(
        'INSERT INTO remember_tokens (selector, token_hash, user_id, expires_at)
         VALUES (:selector, :token_hash, :user_id, :expires_at)'
    );
    $statement->execute([
        ':selector' => $selector,
        ':token_hash' => hash('sha256', $token),
        ':user_id' => $userId,
        ':expires_at' => $expiresAt,
    ]);

    setcookie(REMEMBER_COOKIE, $selector . ':' . $token, [
        'expires' => time() + (REMEMBER_DAYS * 86400),
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function restoreRememberedSession(PDO $pdo): bool
{
    if (!empty($_SESSION['user_id']) || empty($_COOKIE[REMEMBER_COOKIE])) {
        return !empty($_SESSION['user_id']);
    }

    $parts = explode(':', $_COOKIE[REMEMBER_COOKIE], 2);
    if (count($parts) !== 2 || !ctype_xdigit($parts[0]) || !ctype_xdigit($parts[1])) {
        clearRememberMeCookie();
        return false;
    }

    $statement = $pdo->prepare(
        'SELECT s.id, s.fullname, s.email, s.phone, s.course, s.academic_year,
                s.gender, s.profile_photo, s.role, s.status, rt.token_hash
         FROM remember_tokens rt
         INNER JOIN students s ON s.id = rt.user_id
         WHERE rt.selector = :selector AND rt.expires_at > NOW()
         LIMIT 1'
    );
    $statement->execute([':selector' => $parts[0]]);
    $user = $statement->fetch();

    if (!$user || $user['status'] !== 'active' ||
        !hash_equals($user['token_hash'], hash('sha256', $parts[1]))) {
        clearRememberMeCookie();
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['phone'] = $user['phone'];
    $_SESSION['course'] = $user['course'];
    $_SESSION['academic_year'] = $user['academic_year'];
    $_SESSION['gender'] = $user['gender'];
    $_SESSION['profile_photo'] = $user['profile_photo'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['last_activity'] = time();
    return true;
}

function getCurrentUser(PDO $pdo): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }

    $statement = $pdo->prepare(
        'SELECT id, fullname, email, phone, course, academic_year, gender,
                profile_photo, role, status, created_at
         FROM students
         WHERE id = :id
         LIMIT 1'
    );
    $statement->execute([':id' => (int) $_SESSION['user_id']]);
    $user = $statement->fetch();

    if (!$user || $user['status'] !== 'active') {
        $_SESSION = [];
        session_destroy();
        clearRememberMeCookie();
        return null;
    }

    return $user;
}

function requireRole(PDO $pdo, string $role): void
{
    startSecureSession();

    if (!restoreRememberedSession($pdo)) {
        header('Location: login.php?error=' . urlencode('Please log in to continue.'));
        exit();
    }

    if (!isset($_SESSION['last_activity']) ||
        time() - (int) $_SESSION['last_activity'] >= SESSION_TIMEOUT) {
        $_SESSION = [];
        session_destroy();
        clearRememberMeCookie();
        header('Location: login.php?error=' . urlencode('Your session expired. Please log in again.'));
        exit();
    }

    $user = getCurrentUser($pdo);
    if (!$user) {
        header('Location: login.php?error=' . urlencode('Please log in to continue.'));
        exit();
    }

    if ($user['role'] !== $role) {
        http_response_code(403);
        echo '<!doctype html><html lang="en"><head><meta charset="utf-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1">'
            . '<link rel="stylesheet" href="style.css?v=20261029">'
            . '<title>Access restricted - StudentHub</title></head><body>'
            . '<main class="error-page"><div class="error-card">'
            . '<span class="eyebrow">ACCESS RESTRICTED</span><h1>403</h1>'
            . '<h2>This area is for administrators only.</h2>'
            . '<p>Your account does not have permission to open this page. Return to your workspace to continue.</p>'
            . '<a class="btn btn-primary" href="dashboard.php">Return to dashboard</a>'
            . '</div></main></body></html>';
        exit();
    }

    $_SESSION['fullname'] = $user['fullname'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['phone'] = $user['phone'];
    $_SESSION['course'] = $user['course'];
    $_SESSION['academic_year'] = $user['academic_year'];
    $_SESSION['gender'] = $user['gender'];
    $_SESSION['profile_photo'] = $user['profile_photo'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['last_activity'] = time();
}

function sessionTimeoutRemaining(): int
{
    return max(
        0,
        SESSION_TIMEOUT - (time() - (int) ($_SESSION['last_activity'] ?? time()))
    );
}
