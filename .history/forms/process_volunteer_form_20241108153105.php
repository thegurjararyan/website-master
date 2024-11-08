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
    $houseNumber = $conn->real_escape_string($_POST['houseNumber']);
    $lane = $conn->real_escape_string($_POST['lane']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $pincode = $conn->real_escape_string($_POST['pincode']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $age = (int)$_POST['age'];
    $field = $conn->real_escape_string($_POST['field']);
    $days = (int)$_POST['days'];

    // Debugging: Output the values being inserted
    echo "Debug: Name: $name, Contact: $contact, House Number: $houseNumber, Lane: $lane, City: $city, State: $state, Pincode: $pincode, Gender: $gender, Age: $age, Field: $field, Days: $days<br>";

    // Insert form data into the `volunteers` table
    $sql = "INSERT INTO volunteers (name, contact, house_number, lane, city, state, pincode, gender, age, field, days) 
            VALUES ('$name', '$contact', '$houseNumber', '$lane', '$city', '$state', '$pincode', '$gender', $age, '$field', $days)";

    if ($conn->query($sql) === TRUE) {
        echo "Thank you, $name! Your volunteer information has been successfully submitted.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
?>
