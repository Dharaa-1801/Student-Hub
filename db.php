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
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS events (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(150) NOT NULL,
            category VARCHAR(50) NOT NULL,
            event_date DATE NOT NULL,
            description TEXT,
            poster_path VARCHAR(255) DEFAULT NULL
        )"
    );
    $pdo->exec(
        "ALTER TABLE events
         ADD COLUMN IF NOT EXISTS poster_path VARCHAR(255) DEFAULT NULL"
    );
} catch (PDOException $e) {
    error_log('StudentHub database connection failed: ' . $e->getMessage());
    die('Database service is temporarily unavailable. Please try again later.');
}
?>