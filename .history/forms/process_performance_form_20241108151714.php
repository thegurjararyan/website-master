<?php
// Database connection parameters
$servername = "localhost";  // Usually 'localhost'
$username = "Arnish";         // MySQL username, adjust if necessary
$password = "ArnishGACT";             // MySQL password, adjust if necessary
$dbname = "forms_db";       // Database name

// Create a connection to the MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $teamName = $conn->real_escape_string($_POST['teamname']);
    $category = $conn->real_escape_string($_POST['category']);
    $contact = $conn->real_escape_string($_POST['contact']);
    
    // New address fields
    $houseNumber = $conn->real_escape_string($_POST['houseNumber']);
    $lane = $conn->real_escape_string($_POST['lane']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $pincode = $conn->real_escape_string($_POST['pincode']);
    
    $teamSize = isset($_POST['teamsize']) ? (int)$_POST['teamsize'] : 1;

    // Insert form data into the `performances` table
    $sql = "INSERT INTO performances (team_name, category, contact, house_number, lane, city, state, pincode, team_size) 
            VALUES ('$teamName', '$category', '$contact', '$houseNumber', '$lane', '$city', '$state', '$pincode', '$teamSize')";

    if ($conn->query($sql) === TRUE) {
        echo "Thank you, $teamName! Your stage performance entry has been successfully submitted.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
?>
