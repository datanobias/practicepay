<?php
/**
 * Submit Survey Data
 * 
 * Handles form submission, validates data, and stores in database
 */

require_once 'config.php';

// Set headers for API response
setApiHeaders();

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(['error' => 'Method not allowed'], 405);
}

try {
    // Get and validate input data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = $_POST['role'] ?? '';
    $state = trim($_POST['state'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $practice = trim($_POST['practice'] ?? '');
    $hourly_rate = $_POST['hourly_rate'] ?? '';

    // Validation
    $errors = [];

    // Name validation
    if (empty($name)) {
        $errors[] = 'Name is required';
    } elseif (strlen($name) > 100) {
        $errors[] = 'Name must be less than 100 characters';
    }

    // Email validation
    if (empty($email)) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address';
    } elseif (strlen($email) > 150) {
        $errors[] = 'Email must be less than 150 characters';
    }

    // Role validation
    $valid_roles = ['Dentist', 'Hygienist', 'Assistant', 'Office Manager', 'Lab Tech', 'Other'];
    if (empty($role)) {
        $errors[] = 'Role is required';
    } elseif (!in_array($role, $valid_roles)) {
        $errors[] = 'Please select a valid role';
    }

    // State validation
    if (empty($state)) {
        $errors[] = 'State/Province is required';
    } elseif (strlen($state) > 50) {
        $errors[] = 'State/Province must be less than 50 characters';
    }

    // Country validation
    if (empty($country)) {
        $errors[] = 'Country is required';
    } elseif (strlen($country) > 50) {
        $errors[] = 'Country must be less than 50 characters';
    }

    // Practice validation (optional)
    if (strlen($practice) > 150) {
        $errors[] = 'Practice name must be less than 150 characters';
    }

    // Hourly rate validation
    if (empty($hourly_rate)) {
        $errors[] = 'Hourly rate is required';
    } elseif (!is_numeric($hourly_rate)) {
        $errors[] = 'Hourly rate must be a number';
    } elseif ($hourly_rate < 0) {
        $errors[] = 'Hourly rate must be positive';
    } elseif ($hourly_rate > 999.99) {
        $errors[] = 'Hourly rate must be less than $1000';
    }

    // If there are validation errors, return them
    if (!empty($errors)) {
        sendJsonResponse(['error' => 'Validation failed', 'details' => $errors], 400);
    }

    // Get database connection
    $pdo = getDbConnection();

    // Check for duplicate email (optional business rule)
    $stmt = $pdo->prepare("SELECT id FROM pay_surveys WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        sendJsonResponse(['error' => 'This email has already been used to submit a survey'], 409);
    }

    // Insert the survey data
    $sql = "INSERT INTO pay_surveys (name, email, role, state, country, practice, hourly_rate) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        $name,
        $email,
        $role,
        $state,
        $country,
        $practice ?: null, // Convert empty string to null
        number_format($hourly_rate, 2, '.', '') // Format to 2 decimal places
    ]);

    if ($result) {
        sendJsonResponse([
            'success' => true,
            'message' => 'Survey submitted successfully! Thank you for your participation.',
            'id' => $pdo->lastInsertId()
        ]);
    } else {
        sendJsonResponse(['error' => 'Failed to save survey data'], 500);
    }

} catch (Exception $e) {
    logError("Survey submission error", $e);
    sendJsonResponse(['error' => 'An error occurred while processing your submission. Please try again.'], 500);
}
?>
