<?php
declare(strict_types=1);

header('Content-Type: application/json');

try {
    // Database configuration
    $db_config = [
        'host' => 'localhost',
        'dbname' => 'forms_db',
        'username' => 'Arnish',
        'password' => 'ArnishGACT'
    ];

    // Create PDO connection
    $dsn = "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8mb4";
    $pdo = new PDO($dsn, $db_config['username'], $db_config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    // Validate form type
    if (!isset($_POST['formType']) || $_POST['formType'] !== 'passForm') {
        throw new Exception('Invalid form submission.');
    }

    // Validate and sanitize input data
    $data = [
        'name' => filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING),
        'contact' => filter_input(INPUT_POST, 'contact', FILTER_SANITIZE_STRING),
        'houseNumber' => filter_input(INPUT_POST, 'houseNumber', FILTER_SANITIZE_STRING),
        'lane' => filter_input(INPUT_POST, 'lane', FILTER_SANITIZE_STRING),
        'city' => filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING),
        'state' => filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING),
        'pincode' => filter_input(INPUT_POST, 'pincode', FILTER_SANITIZE_STRING)
    ];

    // Validate required fields
    foreach ($data as $key => $value) {
        if (empty($value)) {
            throw new Exception("$key is required.");
        }
    }

    // Validate contact number (10 digits)
    if (!preg_match('/^[0-9]{10}$/', $data['contact'])) {
        throw new Exception('Invalid contact number format.');
    }

    // Validate pincode (6 digits)
    if (!preg_match('/^[0-9]{6}$/', $data['pincode'])) {
        throw new Exception('Invalid pincode format.');
    }

    // Generate a unique pass ID
    $pass_id = 'PASS' . date('Y') . str_pad((string)random_int(1, 9999), 4, '0', STR_PAD_LEFT);

    // Prepare and execute the SQL query
    $sql = "INSERT INTO free_passes (
        pass_id, full_name, contact_number, house_number, 
        lane, city, state, pincode
    ) VALUES (
        :pass_id, :full_name, :contact_number, :house_number, 
        :lane, :city, :state, :pincode
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'pass_id' => $pass_id,
        'full_name' => $data['name'],
        'contact_number' => $data['contact'],
        'house_number' => $data['houseNumber'],
        'lane' => $data['lane'],
        'city' => $data['city'],
        'state' => $data['state'],
        'pincode' => $data['pincode']
    ]);

    // Send success response
    echo json_encode([
        'success' => true,
        'message' => 'Your request has been successfully submitted!'
    ]);

} catch (Exception $e) {
    // Send error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}