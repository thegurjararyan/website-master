<?php
// process_join_form.php

// Database configuration
$host = 'localhost'; // Your database host
$dbname = 'forms_db'; // Your database name
$username = 'Arnish'; // Your database username
$password = 'ArnishGACT'; // Your database password

try {
    // Create a PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare SQL statement
    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['formType'] == 'joinForm') {
        $name = $_POST['name'];
        $contact = $_POST['contact'];
        $email = $_POST['email'];
        $houseNumber = $_POST['houseNumber'];
        $lane = $_POST['lane'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $pincode = $_POST['pincode'];
        $interest = $_POST['interest'];
        $bloodGroup = $_POST['bloodGroup'];
        $otherInterest = $_POST['otherInterest'] ?? null;
        $profession = $_POST['profession'];

        // Combine address components for storage
        $address = "House No: $houseNumber, $lane, $city, $state - $pincode";

        // Insert data into the database
        $stmt = $pdo->prepare("INSERT INTO members (name, contact, email, address, house_number, lane, city, state, pincode, interest, blood_group, other_interest, profession) 
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([$name, $contact, $email, $address, $houseNumber, $lane, $city, $state, $pincode, $interest, $bloodGroup, $otherInterest, $profession]);

        // Return JSON response
        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        echo json_encode(['success' => false]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>
