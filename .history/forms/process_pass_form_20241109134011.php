<?php
header('Content-Type: application/json');

// Database configuration
$db_config = [
    'host' => 'localhost',
    'dbname' => 'forms_db',
    'username' => 'Arnish',
    'password' => 'ArnishGACT'
];

try {
    // Create database connection
    $pdo = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8mb4",
        $db_config['username'],
        $db_config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );

    // Validate request method and form type
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['formType']) || $_POST['formType'] !== 'freePassForm') {
        throw new Exception('Invalid request');
    }

    // Sanitize and validate inputs
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $contact = htmlspecialchars(trim($_POST['contact'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $houseNumber = htmlspecialchars(trim($_POST['houseNumber'] ?? ''));
    $lane = htmlspecialchars(trim($_POST['lane'] ?? ''));
    $city = htmlspecialchars(trim($_POST['city'] ?? ''));
    $state = htmlspecialchars(trim($_POST['state'] ?? ''));
    $pincode = htmlspecialchars(trim($_POST['pincode'] ?? ''));

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

    // Set validity date
    $validUntil = '2024-12-25';

    // Begin transaction
    $pdo->beginTransaction();

    try {
        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO free_passes (
                pass_id, name, email, contact, house_number, lane, city, state, pincode, created_at, valid_until
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?
            )
        ");

        $stmt->execute([
            $passId,
            $name,
            $email,
            $contact,
            $houseNumber,
            $lane,
            $city,
            $state,
            $pincode,
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
    // Return error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}