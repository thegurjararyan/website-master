<?php
// Database connection parameters
$servername = "localhost";
$username = "Arnish";
$password = "ArnishGACT";
$dbname = "forms_db";

// Create a connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $name = $conn->real_escape_string($_POST['name']);
    $contact = $conn->real_escape_string($_POST['contact']);
    
    // Get address components
    $house_number = $conn->real_escape_string($_POST['houseNumber']);
    $lane = $conn->real_escape_string($_POST['lane']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $pincode = $conn->real_escape_string($_POST['pincode']);
    
    $category = $conn->real_escape_string($_POST['category']);
    $uniqueness = $conn->real_escape_string($_POST['uniqueness']);
    $shops = min((int)$_POST['shops'], 5); // Ensure shops don't exceed 5
    $requirements = isset($_POST['requirements']) ? $conn->real_escape_string($_POST['requirements']) : NULL;

    // Insert form data into the `stalls` table
    $sql = "INSERT INTO stalls (
        name, 
        contact, 
        house_number,
        lane,
        city,
        state,
        pincode,
        category, 
        uniqueness, 
        shops, 
        requirements,
        submission_date
    ) VALUES (
        '$name', 
        '$contact', 
        '$house_number',
        '$lane',
        '$city',
        '$state',
        '$pincode',
        '$category', 
        '$uniqueness', 
        $shops, 
        '$requirements',
        NOW()
    )";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => "Thank you for booking a stall, $name! Your application has been successfully submitted."]);
    } else {
        echo json_encode(['success' => false, 'message' => "Error: " . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => "Invalid request method."]);
}

// Close the database connection
$conn->close();
?>
