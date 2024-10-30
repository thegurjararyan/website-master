<?php
// process_stall_form.php

// Database connection parameters
$servername = "localhost";   // Usually 'localhost'
$username = "root";          // MySQL username, adjust if necessary
$password = "";              // MySQL password, adjust if necessary
$dbname = "stall_book";         // Database name (test_db)

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['formType'] === 'stallForm') {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $category = $_POST['category'];
    $uniqueness = $_POST['uniqueness'];
    $shops = $_POST['shops'];
    $requirements = $_POST['requirements'];

    // Database connection
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO stall_book (name, contact, address, category, uniqueness, shops, requirements) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $name, $contact, $address, $category, $uniqueness, $shops, $requirements);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "Stall form submitted successfully!";
}
?>