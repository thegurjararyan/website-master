<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "community_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO join_community (name, contact, email, address, interest, other_interest) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $name, $contact, $email, $address, $interest, $other_interest);

$name = $_POST['name'];
$contact = $_POST['contact'];
$email = $_POST['email'];
$address = $_POST['address'];
$interest = $_POST['interest'];
$other_interest = $_POST['otherInterest'] ?? '';

if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>