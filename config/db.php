<?php
require_once __DIR__ . '/env.php';

$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASSWORD') ?: '';
$dbName = getenv('DB_NAME') ?: 'ecocare';
$dbPort = (int)(getenv('DB_PORT') ?: 3306);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $exception) {
    error_log('EcoCare database connection failed: ' . $exception->getMessage());
    http_response_code(500);
    exit('Database connection failed. Check the database settings in .env and confirm MariaDB is running.');
}
