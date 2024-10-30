<?php
// process_join_form.php


// Database connection parameters
$servername = "localhost";   // Usually 'localhost'
$username = "root";          // MySQL username, adjust if necessary
$password = "";              // MySQL password, adjust if necessary
$dbname = "freepass";         // Database name (test_db)

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['formType'] === 'joinForm') {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $interest = $_POST['interest'];
    $otherInterest = $_POST['otherInterest'] ?? null;

    // Database connection
    $conn = new mysqli('localhost', 'username', 'password', 'database');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO join_community (name, contact, email, address, interest, other_interest) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $contact, $email, $address, $interest, $otherInterest);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "Join form submitted successfully!";
}
?>