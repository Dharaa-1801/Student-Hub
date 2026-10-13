<?php
require_once 'db.php'; // Using PDO connection from Practical 8

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Sanitize & Retrieve Inputs
    $fullname = trim($_POST['fullname'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm-password'] ?? '';
    $course = trim($_POST['course'] ?? '');
    $year = trim($_POST['year'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $terms = isset($_POST['terms']) ? true : false;

    // 2. Server-Side Validation
    $errors = [];

    if (empty($fullname) || empty($email) || empty($phone) || empty($password) || empty($confirm_password) || empty($course) || empty($year) || empty($gender)) {
        $errors[] = "All required fields must be filled.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address format.";
    }

    if (!preg_match('/^[6-9][0-9]{9}$/', $phone)) {
        $errors[] = "Invalid mobile number (must be 10 digits starting with 6-9).";
    }

    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (!$terms) {
        $errors[] = "You must agree to the terms and conditions.";
    }

    // 3. Duplicate Check & Database Insertion
    if (empty($errors)) {
        try {
            // Check for Duplicate Email using Prepared Statement
            $checkStmt = $pdo->prepare("SELECT id FROM students WHERE email = ?");
            $checkStmt->execute([$email]);

            if ($checkStmt->rowCount() > 0) {
                echo "<script>alert('Error: Email address is already registered!'); window.location.href='register.html';</script>";
                exit();
            }

            // Secure Password Hashing using password_hash()
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Insert into Database using Prepared Statement
            $insertStmt = $pdo->prepare("INSERT INTO students (fullname, email, password) VALUES (?, ?, ?)");
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
    header("Location: register.html");
    exit();
}
?>