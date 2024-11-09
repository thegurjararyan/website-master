<?php
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database configuration
$db_config = [
    'host' => 'localhost',
    'dbname' => 'forms_db',
    'username' => 'root',
    'password' => ''
];

try {
    // Create database connection
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8mb4",
        $db_config['username'],
        $db_config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::attr_default_fetch_mode => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

    // Validate request method and form type
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    if (!isset($_POST['formType']) || $_POST['formType'] !== 'freePassForm') {
        throw new Exception('Invalid form submission');
    }

    // Sanitize and validate inputs
    $name = filter_var(trim($_POST['name'] ?? ''), FILTER_SANITIZE_STRING);
    $contact = filter_var(trim($_POST['contact'] ?? ''), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $houseNumber = filter_var(trim($_POST['houseNumber'] ?? ''), FILTER_SANITIZE_STRING);
    $lane = filter_var(trim($_POST['lane'] ?? ''), FILTER_SANITIZE_STRING);
    $city = filter_var(trim($_POST['city'] ?? ''), FILTER_SANITIZE_STRING);
    $state = filter_var(trim($_POST['state'] ?? ''), FILTER_SANITIZE_STRING);
    $pincode = filter_var(trim($_POST['pincode'] ?? ''), FILTER_SANITIZE_STRING);

    // Validate required fields
    if (!$name || !$contact || !$email || !$houseNumber || !$lane || !$city || !$state || !$pincode) {
        throw new Exception('All fields are required');
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }

    // Validate contact number (10 digits)
    if (!preg_match('/^[0-9]{10}$/', $contact)) {
        throw new Exception('Contact number must be 10 digits');
    }

    // Validate pincode (6 digits)
    if (!preg_match('/^[0-9]{6}$/', $pincode)) {
        throw new Exception('Pincode must be 6 digits');
    }

    // Check for duplicate email or contact
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM free_passes WHERE email = ? OR contact = ?");
    $stmt->execute([$email, $contact]);
    if ($stmt->fetchColumn() > 0) {
        throw new Exception('A pass has already been generated for this email or contact number');
    }

    // Generate unique pass ID
    do {
        $passId = 'GACT' . date('Y') . mt_rand(100000, 999999);
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM free_passes WHERE pass_id = ?");
        $stmt->execute([$passId]);
    } while ($stmt->fetchColumn() > 0);

    // Prepare address
    $address = implode(', ', [
        $houseNumber,
        $lane,
        $city,
        $state,
        $pincode
    ]);

    // Set validity (end of current year)
    $validUntil = date('Y-12-31');

    // Begin transaction
    $pdo->beginTransaction();

    try {
        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO free_passes (
                pass_id, name, email, contact, address, created_at, valid_until
            ) VALUES (
                ?, ?, ?, ?, ?, NOW(), ?
            )
        ");

        $stmt->execute([
            $passId,
            $name,
            $email,
            $contact,
            $address,
            $validUntil
        ]);

        // Commit transaction
        $pdo->commit();

        // Return success response
        echo json_encode([
            'success' => true,
            'message' => 'Free pass generated successfully!',
            'data' => [
                'passId' => $passId,
                'name' => $name,
                'contact' => $contact,
                'email' => $email,
                'validUntil' => date('d M Y', strtotime($validUntil))
            ]
        ]);

    } catch (Exception $e) {
        // Rollback transaction on error
        $pdo->rollBack();
        throw new Exception('Failed to store data: ' . $e->getMessage());
    }

} catch (Exception $e) {
    // Log error (in production, use proper logging)
    error_log("Free Pass Error: " . $e->getMessage());
    
    // Return error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}