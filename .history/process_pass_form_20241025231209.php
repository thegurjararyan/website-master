<?php
// process_pass_form.php

// Database connection parameters
$servername = "localhost";   // Usually 'localhost'
$username = "root";          // MySQL username, adjust if necessary
$password = "";              // MySQL password, adjust if necessary
$dbname = "pass_form";         // Database name (test_db)

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['formType'] === 'passForm') {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    // Database connection
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO free_pass (name, contact, address) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $contact, $address);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "Pass form submitted successfully!";
}
?>