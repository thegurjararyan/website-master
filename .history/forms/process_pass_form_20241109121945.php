<?php
header('Content-Type: application/json');

// Database configuration
$db_config = [
    'host' => 'localhost',
    'dbname' => 'your_database',
    'username' => 'your_username',
    'password' => 'your_password'
];

try {
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8mb4",
        $db_config['username'],
        $db_config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Validate form submission
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $_POST['formType'] !== 'freePassForm') {
        throw new Exception('Invalid request');
    }

    // Validate required fields
    $required_fields = ['name', 'contact', 'email', 'houseNumber', 'lane', 'city', 'state', 'pincode'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Missing required field: {$field}");
        }
    }

    // Validate email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format');
    }

    // Validate contact number (10 digits)
    if (!preg_match('/^[0-9]{10}$/', $_POST['contact'])) {
        throw new Exception('Invalid contact number');
    }

    // Validate pincode (6 digits)
    if (!preg_match('/^[0-9]{6}$/', $_POST['pincode'])) {
        throw new Exception('Invalid pincode');
    }

    // Generate unique pass ID
    $passId = 'GACT' . date('Y') . mt_rand(100000, 999999);

    // Prepare address
    $address = implode(', ', [
        $_POST['houseNumber'],
        $_POST['lane'],
        $_POST['city'],
        $_POST['state'],
        $_POST['pincode']
    ]);

    // Set validity (e.g., end of the year)
    $validUntil = date('Y-12-31');

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
        $_POST['name'],
        $_POST['email'],
        $_POST['contact'],
        $address,
        $validUntil
    ]);

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Free pass generated successfully!',
        'data' => [
            'passId' => $passId,
            'name' => $_POST['name'],
            'contact' => $_POST['contact'],
            'email' => $_POST['email'],
            'validUntil' => date('d M Y', strtotime($validUntil))
        ]
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}