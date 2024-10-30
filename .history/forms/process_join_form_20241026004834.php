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
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);
    $interest = $conn->real_escape_string($_POST['interest']);
    $otherInterest = isset($_POST['otherInterest']) ? $conn->real_escape_string($_POST['otherInterest']) : NULL;

    // Insert form data into the `members` table
    $sql = "INSERT INTO members (name, contact, email, address, interest, other_interest) 
            VALUES ('$name', '$contact', '$email', '$address', '$interest', '$otherInterest')";

    if ($conn->query($sql) === TRUE) {
        echo "Thank you for joining our community, $name! Your membership has been successfully registered.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
?>
