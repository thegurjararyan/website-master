<?php
declare(strict_types=1);

header('Content-Type: application/json');

try {
    // Database configuration
    $db_config = [
        'host' => 'localhost',
        'dbname' => 'your_database_name',
        'username' => 'your_username',
        'password' => 'your_password'
    ];

    // Create PDO connection
    $dsn = "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset=utf8mb4";
    $pdo = new PDO($dsn, $db_config['username'], $db_config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);

    // Validate form type
    if (!isset($_POST['formType']) || $_POST['formType'] !== 'joinForm') {
        throw new Exception('Invalid form submission.');
    }

    // Required fields validation
    $required_fields = [
        'name', 'contact', 'email', 'bloodGroup', 'profession',
        'houseNumber', 'lane', 'city', 'state', 'pincode', 'interest'
    ];
    
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("$field is required.");
        }
    }

    // Validate contact number (10 digits)
    if (!preg_match('/^[0-9]{10}$/', $_POST['contact'])) {
        throw new Exception('Invalid contact number format.');
    }

    // Validate email
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format.');
    }

    // Validate pincode (6 digits)
    if (!preg_match('/^[0-9]{6}$/', $_POST['pincode'])) {
        throw new Exception('Invalid pincode format.');
    }

    // Handle the 'others' interest case
    $interest = $_POST['interest'];
    $other_interest = null;
    if ($interest === 'others' && !empty($_POST['otherInterest'])) {
        $other_interest = $_POST['otherInterest'];
    }

    // Generate a unique member ID
    $member_id = 'GACT' . date('Y') . str_pad((string)random_int(1, 9999), 4, '0', STR_PAD_LEFT);

    // Prepare and execute the SQL query
    $sql = "INSERT INTO members (
        member_id, name, contact, email, blood_group, profession,
        house_number, lane, city, state, pincode,
        interest, other_interest
    ) VALUES (
        :member_id, :name, :contact, :email, :blood_group, :profession,
        :house_number, :lane, :city, :state, :pincode,
        :interest, :other_interest
    )";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'member_id' => $member_id,
        'name' => $_POST['name'],
        