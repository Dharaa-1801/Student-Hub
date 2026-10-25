<?php
declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_OFF);

$mysqli = new mysqli('localhost', 'root', '', 'studenthub_db');
if ($mysqli->connect_errno) {
    error_log('StudentHub MySQLi connection failed: ' . $mysqli->connect_error);
    die('Database service is temporarily unavailable. Please try again later.');
}

$mysqli->set_charset('utf8mb4');
