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

$stmt = $conn->prepare("INSERT INTO free_pass (name, contact, address) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $contact, $address);

$name = $_POST['name'];
$contact = $_POST['contact'];
$address = $_POST['address'];

if ($stmt->execute()) {
    echo "Free pass request submitted!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>