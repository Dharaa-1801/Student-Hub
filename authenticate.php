<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit();
}

startSecureSession();
$email = strtolower(trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';
$remember = isset($_POST['remember']);

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
    header('Location: login.php?error=' . urlencode('Invalid email or password.'));
    exit();
}

$statement = $pdo->prepare(
    'SELECT id, fullname, email, password, phone, course, academic_year,
            gender, profile_photo, role
     FROM students
     WHERE email = :email
     LIMIT 1'
);
$statement->execute([':email' => $email]);
$user = $statement->fetch();

if (!$user || !password_verify($password, $user['password'])) {
    header('Location: login.php?error=' . urlencode('Invalid email or password.'));
    exit();
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

if ($remember) {
    createRememberMeToken($pdo, (int) $user['id']);
} else {
    clearRememberMeCookie();
}

header('Location: index.php');
exit();