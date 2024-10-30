<?php
// Database connection parameters
$servername = "localhost";  // Usually 'localhost'
$username = "root";         // MySQL username, adjust if necessary
$password = "";             // MySQL password, adjust if necessary
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
    $name = $conn->real_escape_string($_POST['name']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $address = $conn->real_escape_string($_POST['address']);

    // Insert form data into the `free_passes` table
    $sql = "INSERT INTO free_passes (name, contact, address) 
            VALUES ('$name', '$contact', '$address')";

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
