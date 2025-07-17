<?php
/**
 * Environment Configuration Template
 * 
 * Copy this file to config.php and update with your actual database credentials
 * This file should NOT be committed to version control
 */

// Database configuration - UPDATE THESE VALUES
define('DB_HOST', 'localhost');                    // Your database host
define('DB_NAME', 'dental_pay_survey');            // Your database name  
define('DB_USER', 'your_username');                // Your database username
define('DB_PASS', 'your_password');                // Your database password
define('DB_CHARSET', 'utf8mb4');

// Application configuration
define('APP_ENV', 'development');                  // 'development' or 'production'
define('APP_DEBUG', true);                         // Set to false in production

/**
 * Example configurations for different environments:
 * 
 * LOCAL DEVELOPMENT:
 * define('DB_HOST', 'localhost');
 * define('DB_NAME', 'dental_pay_survey');
 * define('DB_USER', 'root');
 * define('DB_PASS', '');
 * 
 * HOSTINGER SHARED HOSTING:
 * define('DB_HOST', 'mysql.hostinger.com');
 * define('DB_NAME', 'u123456_dental_survey');
 * define('DB_USER', 'u123456_user');
 * define('DB_PASS', 'your_secure_password');
 * 
 * OTHER HOSTING PROVIDERS:
 * Check your hosting provider's documentation for correct values
 */

/**
 * Get database connection using PDO
 */
function getDbConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        if (APP_DEBUG) {
            error_log("Database connection error: " . $e->getMessage());
        }
        throw new Exception("Database connection failed. Please try again later.");
    }
}

/**
 * Set common headers for API responses
 */
function setApiHeaders() {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
}

/**
 * Send JSON response
 */
function sendJsonResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

/**
 * Log error for debugging
 */
function logError($message, $e = null) {
    if (APP_DEBUG) {
        $errorMsg = $message;
        if ($e) {
            $errorMsg .= " - " . $e->getMessage();
        }
        error_log($errorMsg);
    }
}
?>
