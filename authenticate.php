<?php
session_start();
require_once 'db.php'; // Using PDO connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        echo "<script>alert('All fields are required.'); window.location.href='login.html';</script>";
        exit();
    }

    try {
        $stmt = $pdo->prepare("SELECT id, fullname, email, password, role FROM students WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Verify password using password_verify()
        if ($user && password_verify($password, $user['password'])) {
            
            // Regenerate session ID to prevent session fixation attacks
            session_regenerate_id(true);

            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role']; // 'admin' or 'student'
            $_SESSION['last_activity'] = time(); // For session timeout

            // Role-based redirection
            if ($user['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: student_dashboard.php");
            }
            exit();
        } else {
            echo "<script>alert('Invalid email or password.'); window.location.href='login.html';</script>";
            exit();
        }
    } catch (PDOException $e) {
        echo "<script>alert('Database Error: " . $e->getMessage() . "'); window.location.href='login.html';</script>";
    }
}
?>