<?php
declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_OFF);

$mysqli = new mysqli(
    getenv('STUDENTHUB_DB_HOST') ?: 'localhost',
    getenv('STUDENTHUB_DB_USER') ?: 'root',
    getenv('STUDENTHUB_DB_PASSWORD') ?: '',
    getenv('STUDENTHUB_DB_NAME') ?: 'studenthub_db',
    (int) (getenv('STUDENTHUB_DB_PORT') ?: 3306)
);
if ($mysqli->connect_errno) {
    error_log('StudentHub MySQLi connection failed: ' . $mysqli->connect_error);
    die('Database service is temporarily unavailable. Please try again later.');
}

$mysqli->set_charset('utf8mb4');
