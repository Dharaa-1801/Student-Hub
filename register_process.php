<?php
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $fullname = trim($_POST['fullname'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm-password'] ?? '';
    $course = trim($_POST['course'] ?? '');
    $year = trim($_POST['year'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $terms = isset($_POST['terms']) ? true : false;

    $errors = [];

    if (empty($fullname) || empty($email) || empty($phone) || empty($password) || empty($confirm_password) || empty($course) || empty($year) || empty($gender)) {
        $errors[] = 'All required fields must be filled.';
    }

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address format.';
    }

    if (!preg_match('/^[6-9][0-9]{9}$/', $phone)) {
        $errors[] = 'Invalid mobile number (must be 10 digits starting with 6-9).';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$terms) {
        $errors[] = 'You must agree to the terms and conditions.';
    }

    if (empty($errors)) {
        try {
            $checkStmt = $pdo->prepare('SELECT 1 FROM students WHERE LOWER(email) = LOWER(:email) LIMIT 1');
            $checkStmt->execute([':email' => $email]);

            if ($checkStmt->fetchColumn() !== false) {
                echo "<script>alert('Error: Email address is already registered!'); window.location.href='register.html';</script>";
                exit();
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insertStmt = $pdo->prepare('INSERT INTO students (fullname, email, password) VALUES (?, ?, ?)');
            $insertStmt->execute([$fullname, $email, $hashedPassword]);

            echo "<script>alert('Registration successful! Welcome to StudentHub Portal.'); window.location.href='login.html';</script>";
        } catch (PDOException $e) {
            echo "<script>alert('Database Error: " . $e->getMessage() . "'); window.location.href='register.html';</script>";
        }
    } else {
        $errorMessage = implode("\\n", $errors);
        echo "<script>alert('Validation Failed:\\n" . $errorMessage . "'); window.location.href='register.html';</script>";
    }
} else {
    header('Location: register.html');
    exit();
}
?>