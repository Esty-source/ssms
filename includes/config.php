<?php
// Site configuration
define('SITE_TITLE', 'Safe Lock Storage');
define('SITE_DESCRIPTION', 'Container-based Self-Storage Facility');
define('CONTACT_EMAIL', 'safelockstorageltd@gmail.com');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ssms_db');

// Application paths
define('BASE_URL', 'http://localhost/ssms/');
define('UPLOADS_PATH', __DIR__ . '/../uploads/');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
try {
    $conn = new PDO(
        "mysql:host=" . DB_HOST,
        DB_USER,
        DB_PASS,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
    
    // Create database if it doesn't exist
    $conn->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    $conn->exec("USE " . DB_NAME);
    
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Time zone setting
date_default_timezone_set('Europe/London');

// Security functions
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>
