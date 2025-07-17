<?php
/**
 * Hostinger Production Configuration
 * 
 * Copy this file to config.php on your Hostinger server
 * Update with your actual Hostinger database credentials
 */

// Hostinger Database Configuration
define('DB_HOST', 'localhost');                    // Usually 'localhost' for Hostinger
define('DB_NAME', 'u395558639_dentalpay');        // Your actual database name
define('DB_USER', 'admin');                 // Your actual database username
define('DB_PASS', 'V7x@9l#P2g%Z3$K');         // Your actual database password
define('DB_CHARSET', 'utf8mb4');

// Production Application Configuration
define('APP_ENV', 'production');
define('APP_DEBUG', false);

/**
 * Get database connection using PDO
 * 
 * @return PDO Database connection
 * @throws Exception If connection fails
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
 * 
 * @param array $data Response data
 * @param int $status HTTP status code
 */
function sendJsonResponse($data, $status = 200) {
    http_response_code($status);
    echo json_encode($data);
    exit;
}

/**
 * Log error for debugging
 * 
 * @param string $message Error message
 * @param Exception $e Exception object
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
