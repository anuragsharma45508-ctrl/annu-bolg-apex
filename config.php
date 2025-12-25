<?php
// Rename this file to config.php and fill real values

define('APP_ENV', 'local'); // local | production

define('DB_HOST', 'localhost');
define('DB_NAME', 'blog');
define('DB_USER', 'root');
define('DB_PASS', '');

// Sessions and security
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', !empty($_SERVER['HTTPS']) ? 1 : 0);
ini_set('session.use_strict_mode', 1);
session_name('APPSESSID');
session_start();

function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}
function csrf_verify($token) {
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
}
function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Error visibility
if (APP_ENV === 'local') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
}

// Database connection (PDO)
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]
    );
} catch (PDOException $e) {
    if (APP_ENV === 'local') {
        die("DB connection failed: " . e($e->getMessage()));
    }
    http_response_code(500);
    die("Service temporarily unavailable");
}