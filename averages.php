<?php
/**
 * Averages API
 * 
 * Returns average hourly rates grouped by state and country
 */

require_once 'config.php';

// Set headers for API response
setApiHeaders();

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendJsonResponse(['error' => 'Method not allowed'], 405);
}

try {
    // Get database connection
    $pdo = getDbConnection();

    // Query to get average hourly rates by state and country
    $sql = "SELECT 
                state, 
                country, 
                ROUND(AVG(hourly_rate), 2) AS avg_rate,
                COUNT(*) as survey_count
            FROM pay_surveys 
            GROUP BY country, state 
            ORDER BY country, state";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $results = $stmt->fetchAll();
    
    // If no data found, return empty array
    if (empty($results)) {
        sendJsonResponse([
            'data' => [],
            'message' => 'No survey data available yet'
        ]);
    }
    
    // Format the data for the frontend
    $formatted_data = [];
    foreach ($results as $row) {
        $formatted_data[] = [
            'state' => $row['state'],
            'country' => $row['country'],
            'avg_rate' => floatval($row['avg_rate']),
            'survey_count' => intval($row['survey_count'])
        ];
    }
    
    // Also provide summary statistics
    $summary_sql = "SELECT 
                        COUNT(*) as total_surveys,
                        ROUND(AVG(hourly_rate), 2) as overall_avg,
                        ROUND(MIN(hourly_rate), 2) as min_rate,
                        ROUND(MAX(hourly_rate), 2) as max_rate,
                        COUNT(DISTINCT CONCAT(country, '-', state)) as locations_count
                    FROM pay_surveys";
    
    $summary_stmt = $pdo->prepare($summary_sql);
    $summary_stmt->execute();
    $summary = $summary_stmt->fetch();
    
    sendJsonResponse([
        'data' => $formatted_data,
        'summary' => [
            'total_surveys' => intval($summary['total_surveys']),
            'overall_average' => floatval($summary['overall_avg']),
            'min_rate' => floatval($summary['min_rate']),
            'max_rate' => floatval($summary['max_rate']),
            'locations_count' => intval($summary['locations_count'])
        ]
    ]);

} catch (Exception $e) {
    logError("Error fetching averages", $e);
    sendJsonResponse(['error' => 'Unable to fetch survey data. Please try again later.'], 500);
}
?>
