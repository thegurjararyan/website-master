<?php
// process_performance_form.php

// Database connection parameters
$servername = "localhost";   // Usually 'localhost'
$username = "root";          // MySQL username, adjust if necessary
$password = "";              // MySQL password, adjust if necessary
$dbname = "performance_form";         // Database name (test_db)

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['formType'] === 'performanceForm') {
    $teamName = $_POST['teamName'];
    $category = $_POST['category'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $teamSize = $_POST['teamSize'];

    // Database connection
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO stage_performance (team_name, category, contact, address, team_size) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $teamName, $category, $contact, $address, $teamSize);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "Performance form submitted successfully!";
}
?>