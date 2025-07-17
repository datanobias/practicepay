<?php
/**
 * Database Connection Test
 * Upload this file to your Hostinger public_html directory and visit it to test DB connection
 */

// Include the config file
require_once 'config.php';

echo "<h1>Database Connection Test</h1>";
echo "<p>Testing connection to: " . DB_NAME . "</p>";

try {
    $pdo = getDbConnection();
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test if the table exists
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'pay_surveys'");
    $stmt->execute();
    $result = $stmt->fetch();
    
    if ($result) {
        echo "<p style='color: green;'>✅ Table 'pay_surveys' exists!</p>";
        
        // Test table structure
        $stmt = $pdo->prepare("DESCRIBE pay_surveys");
        $stmt->execute();
        $columns = $stmt->fetchAll();
        
        echo "<h3>Table Structure:</h3>";
        echo "<table border='1' style='border-collapse: collapse;'>";
        echo "<tr><th>Column</th><th>Type</th><th>Null</th><th>Key</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>" . $column['Field'] . "</td>";
            echo "<td>" . $column['Type'] . "</td>";
            echo "<td>" . $column['Null'] . "</td>";
            echo "<td>" . $column['Key'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Test record count
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM pay_surveys");
        $stmt->execute();
        $count = $stmt->fetch();
        echo "<p>Records in table: " . $count['count'] . "</p>";
        
    } else {
        echo "<p style='color: red;'>❌ Table 'pay_surveys' does not exist!</p>";
        echo "<p><strong>Solution:</strong> Import the database.sql file via phpMyAdmin</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database connection failed!</p>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Solutions:</strong></p>";
    echo "<ul>";
    echo "<li>Check database credentials in config.php</li>";
    echo "<li>Verify database name: " . DB_NAME . "</li>";
    echo "<li>Verify database user: " . DB_USER . "</li>";
    echo "<li>Check if database exists in Hostinger control panel</li>";
    echo "</ul>";
}

echo "<hr>";
echo "<h3>Current Configuration:</h3>";
echo "<p><strong>DB_HOST:</strong> " . DB_HOST . "</p>";
echo "<p><strong>DB_NAME:</strong> " . DB_NAME . "</p>";
echo "<p><strong>DB_USER:</strong> " . DB_USER . "</p>";
echo "<p><strong>DB_PASS:</strong> " . (DB_PASS === 'your_password' ? 'NOT SET' : '[HIDDEN]') . "</p>";
echo "<p><strong>APP_ENV:</strong> " . APP_ENV . "</p>";
echo "<p><strong>APP_DEBUG:</strong> " . (APP_DEBUG ? 'true' : 'false') . "</p>";

echo "<hr>";
echo "<p><em>Delete this file after testing for security!</em></p>";
?>
