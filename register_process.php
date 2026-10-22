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
    $profilePhotoPath = null;

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
            if (isset($_FILES['profile_photo']) &&
                $_FILES['profile_photo']['error'] !== UPLOAD_ERR_NO_FILE) {
                if ($_FILES['profile_photo']['error'] !== UPLOAD_ERR_OK) {
                    throw new RuntimeException('The profile photo upload failed.');
                }

                if ($_FILES['profile_photo']['size'] > 2 * 1024 * 1024) {
                    throw new RuntimeException('The profile photo must be 2 MB or smaller.');
                }

                $imageInfo = getimagesize($_FILES['profile_photo']['tmp_name']);
                $mime = $imageInfo['mime'] ?? '';
                $allowedMimes = [
                    'image/jpeg' => 'jpg',
                    'image/png' => 'png',
                    'image/webp' => 'webp',
                ];

                if ($imageInfo === false || !isset($allowedMimes[$mime])) {
                    throw new RuntimeException('Only JPG, PNG, and WEBP images are allowed.');
                }

                $uploadDirectory = __DIR__ . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'profiles';
                if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true)) {
                    throw new RuntimeException('The profile photo directory could not be created.');
                }

                $fileName = bin2hex(random_bytes(16)) . '.' . $allowedMimes[$mime];
                $targetPath = $uploadDirectory . DIRECTORY_SEPARATOR . $fileName;
                if (!move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetPath)) {
                    throw new RuntimeException('The profile photo could not be saved.');
                }

                $profilePhotoPath = 'uploads/profiles/' . $fileName;
            }

            $checkStmt = $pdo->prepare('SELECT 1 FROM students WHERE LOWER(email) = LOWER(:email) LIMIT 1');
            $checkStmt->execute([':email' => $email]);

            if ($checkStmt->fetchColumn() !== false) {
                echo "<script>alert('Error: Email address is already registered!'); window.location.href='register.php';</script>";
                exit();
            }

            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insertStmt = $pdo->prepare(
                'INSERT INTO students
                    (fullname, email, password, phone, course, academic_year, gender, profile_photo, role)
                 VALUES
                    (:fullname, :email, :password, :phone, :course, :academic_year, :gender, :profile_photo, :role)'
            );
            $insertStmt->execute([
                ':fullname' => $fullname,
                ':email' => $email,
                ':password' => $hashedPassword,
                ':phone' => $phone,
                ':course' => $course,
                ':academic_year' => $year,
                ':gender' => $gender,
                ':profile_photo' => $profilePhotoPath,
                ':role' => 'student',
            ]);

            echo "<script>alert('Registration successful! Welcome to StudentHub Portal.'); window.location.href='login.php';</script>";
        } catch (PDOException | RuntimeException $e) {
            error_log('StudentHub registration failed: ' . $e->getMessage());
            echo "<script>alert('Registration could not be completed. Please try again.'); window.location.href='register.php';</script>";
        }
    } else {
        $errorMessage = implode("\\n", $errors);
        echo "<script>alert('Validation Failed:\\n" . $errorMessage . "'); window.location.href='register.php';</script>";
    }
} else {
    header('Location: register.php');
    exit();
}
?>