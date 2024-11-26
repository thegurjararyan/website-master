<?php
header('Content-Type: application/json');

// Database configuration
$db_config = [
    'host' => 'localhost',
    'dbname' => 'forms_db',
    'username' => 'Arnish',
    'password' => 'ArnishGACT'
];

// Configure pass settings
$PASS_CONFIG = [
    'template_path' => '../assets/pass-template.png',
    'valid_until' => '2024-12-25',
    'font_path' => '../assets/fonts/Poppins-Regular.ttf'
];

function generatePassImage($data, $config) {
    // Load the template image
    $template = imagecreatefrompng($config['template_path']);
    if (!$template) {
        throw new Exception('Failed to load pass template');
    }

    // Set up text colors and fonts
    $black = imagecolorallocate($template, 0, 0, 0);
    $red = imagecolorallocate($template, 237, 31, 36); // #ed1f24

    // Add text to image
    // Note: Adjust these coordinates based on your template design
    imagettftext($template, 20, 0, 150, 100, $black, $config['font_path'], "Pass ID: " . $data['passId']);
    imagettftext($template, 20, 0, 150, 150, $black, $config['font_path'], "Name: " . $data['name']);
    imagettftext($template, 20, 0, 150, 200, $black, $config['font_path'], "Contact: " . $data['contact']);
    imagettftext($template, 20, 0, 150, 250, $black, $config['font_path'], "Email: " . $data['email']);
    imagettftext($template, 20, 0, 150, 300, $red, $config['font_path'], "Valid Until: " . $data['validUntil']);

    // Start output buffering
    ob_start();
    imagepng($template);
    $imageData = ob_get_clean();

    // Clean up
    imagedestroy($template);

    // Return base64 encoded image
    return 'data:image/png;base64,' . base64_encode($imageData);
}

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

    // Validate request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['formType']) || $_POST['formType'] !== 'freePassForm') {
        throw new Exception('Invalid request');
    }

    // Validate and sanitize inputs
    $name = filter_var(trim($_POST['name'] ?? ''), FILTER_SANITIZE_STRING);
    $contact = filter_var(trim($_POST['contact'] ?? ''), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $houseNumber = filter_var(trim($_POST['houseNumber'] ?? ''), FILTER_SANITIZE_STRING);
    $lane = filter_var(trim($_POST['lane'] ?? ''), FILTER_SANITIZE_STRING);
    $city = filter_var(trim($_POST['city'] ?? ''), FILTER_SANITIZE_STRING);
    $state = filter_var(trim($_POST['state'] ?? ''), FILTER_SANITIZE_STRING);
    $pincode = filter_var(trim($_POST['pincode'] ?? ''), FILTER_SANITIZE_STRING);

    // Additional validation
    if (!$name || !$contact || !filter_var($email, FILTER_VALIDATE_EMAIL) || 
        !$houseNumber || !$lane || !$city || !$state || !preg_match('/^[0-9]{6}$/', $pincode)) {
        throw new Exception('Please fill all fields correctly');
    }

    if (!preg_match('/^[0-9]{10}$/', $contact)) {
        throw new Exception('Invalid contact number');
    }

    // Check for duplicates
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM free_passes WHERE email = ? OR contact_number = ?");
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

    // Begin transaction
    $pdo->beginTransaction();

    try {
        // Insert into database
        $stmt = $pdo->prepare("
            INSERT INTO free_passes (
                pass_id, full_name, email, contact_number, house_number, 
                lane, city, state, pincode, valid_until
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $passId, $name, $email, $contact, $houseNumber,
            $lane, $city, $state, $pincode, $PASS_CONFIG['valid_until']
        ]);

        // Generate pass image
        $passData = [
            'passId' => $passId,
            'name' => $name,
            'contact' => $contact,
            'email' => $email,
            'validUntil' => date('d M Y', strtotime($PASS_CONFIG['valid_until']))
        ];

        $passImage = generatePassImage($passData, $PASS_CONFIG);

        // Commit transaction
        $pdo->commit();

        // Return success response with pass data and image
        echo json_encode([
            'success' => true,
            'message' => 'Free pass generated successfully!',
            'data' => [
                'passId' => $passId,
                'name' => $name,
                'contact' => $contact,
                'email' => $email,
                'validUntil' => date('d M Y', strtotime($PASS_CONFIG['valid_until'])),
                'passImage' => $passImage
            ]
        ]);

    } catch (Exception $e) {
        $pdo->rollBack();
        throw new Exception('Failed to generate pass: ' . $e->getMessage());
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}