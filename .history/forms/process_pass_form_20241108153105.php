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
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form data
    $name = $conn->real_escape_string($_POST['name']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $houseNumber = $conn->real_escape_string($_POST['houseNumber']);
    $lane = $conn->real_escape_string($_post['lane']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $pincode = $conn->real_escape_string($_POST['pincode']);

    // Insert form data into the `free_passes` table
    $sql = "INSERT INTO free_passes (name, contact, house_number, lane, city, state, pincode) 
            VALUES ('$name', '$contact', '$houseNumber', '$lane', '$city', '$state', '$pincode')";

    if ($conn->query($sql) === TRUE) {
        echo "Thank you, $name! Your free pass request has been successfully submitted.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
?>
