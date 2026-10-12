<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['username']); // Ahiya email ya ID input thase
    $password = $_POST['password'];

    // Prepared statement to check if user exists
    $stmt = $conn->prepare("SELECT id, fullname, password FROM students WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // Verify hashed password
        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['fullname'];

            echo "<script>alert('Login Successful! Welcome, " . $row['fullname'] . "'); window.location.href='dashboard.html';</script>";
        } else {
            echo "<script>alert('Invalid Password! Please try again.'); window.location.href='login.html';</script>";
        }
    } else {
        echo "<script>alert('Email not registered! Please register first.'); window.location.href='register.html';</script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: login.html");
    exit();
}
?>