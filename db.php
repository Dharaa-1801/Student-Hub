<?php
$host = 'localhost';
$dbname = 'studenthub_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set PDO error mode to exception for proper debugging
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec(
        "ALTER TABLE students
         ADD COLUMN IF NOT EXISTS status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'"
    );
} catch (PDOException $e) {
    error_log('StudentHub database connection failed: ' . $e->getMessage());
    die('Database service is temporarily unavailable. Please try again later.');
}
?>