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

$stmt = $conn->prepare("INSERT INTO volunteering (name, contact, address, gender, age, field) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssis", $name, $contact, $address, $gender, $age, $field);

$name = $_POST['name'];
$contact = $_POST['contact'];
$address = $_POST['address'];
$gender = $_POST['gender'];
$age = $_POST['age'];
$field = $_POST['field'];

if ($stmt->execute()) {
    echo "Volunteering registration successful!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>