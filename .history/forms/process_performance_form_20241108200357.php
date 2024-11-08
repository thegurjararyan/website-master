<?php
// Database connection parameters
$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to clean input data
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['formtype'] == 'performanceform') {
    // Collect and clean form data
    $team_name = clean_input($_POST['teamname']);
    $category = clean_input($_POST['category']);
    $contact = clean_input($_POST['contact']);
    $house_number = clean_input($_POST['houseNumber']);
    $lane = clean_input($_POST['lane']);
    $city = clean_input($_POST['city']);
    $state = clean_input($_POST['state']);
    $pincode = clean_input($_POST['pincode']);
    $team_size = !empty($_POST['teamsize']) ? (int)clean_input($_POST['teamsize']) : NULL;

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO stage_performances (team_name, category, contact, house_number, lane, city, state, pincode, team_size) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("ssssssssi", $team_name, $category, $contact, $house_number, $lane, $city, $state, $pincode, $team_size);

    // Execute the statement
    if ($stmt->execute()) {
        // Success response
        $response = [
            'status' => 'success',
            'message' => 'Performance registration submitted successfully!'
        ];
    } else {
        // Error response
        $response = [
            'status' => 'error',
            'message' => 'Error submitting registration. Please try again.'
        ];
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>