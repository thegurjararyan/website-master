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

$stmt = $conn->prepare("INSERT INTO stall_book (name, contact, address, category, uniqueness, shops, requirements) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssis", $name, $contact, $address, $category, $uniqueness, $shops, $requirements);

$name = $_POST['name'];
$contact = $_POST['contact'];
$address = $_POST['address'];
$category = $_POST['category'];
$uniqueness = $_POST['uniqueness'];
$shops = $_POST['shops'];
$requirements = $_POST['requirements'] ?? '';

if ($stmt->execute()) {
    echo "Stall booking successful!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>