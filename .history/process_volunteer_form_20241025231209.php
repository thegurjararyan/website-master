<?php
// process_volunteer_form.php

// Database connection parameters
$servername = "localhost";   // Usually 'localhost'
$username = "root";          // MySQL username, adjust if necessary
$password = "";              // MySQL password, adjust if necessary
$dbname = "volunteer_form";         // Database name (test_db)

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['formType'] === 'volunteerForm') {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $field = $_POST['field'];

    // Database connection
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO volunteer (name, contact, address, gender, age, field) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssis", $name, $contact, $address, $gender, $age, $field);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "Volunteer form submitted successfully!";
}
?>