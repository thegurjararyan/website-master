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
    $name = $conn->real_escape_string($_POST['name']);
    $contact = $conn->real_escape_string($_POST['contact']);
    $address = $conn->real_escape_string($_POST['address']);
    $category = $conn->real_escape_string($_POST['category']);
    $uniqueness = $conn->real_escape_string($_POST['uniqueness']);
    $shops = (int)$_POST['shops'];
    $requirements = isset($_POST['requirements']) ? $conn->real_escape_string($_POST['requirements']) : NULL;

    // Insert form data into the `stalls` table
    $sql = "INSERT INTO stalls (name, contact, address, category, uniqueness, shops, requirements) 
            VALUES ('$name', '$contact', '$address', '$category', '$uniqueness', '$shops', '$requirements')";

    if ($conn->query($sql) === TRUE) {
        echo "Thank you for booking a stall, $name! Your application has been successfully submitted.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
?>
