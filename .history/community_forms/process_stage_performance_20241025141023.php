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

$stmt = $conn->prepare("INSERT INTO stage_performance (team_name, category, contact, address, team_size) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $team_name, $category, $contact, $address, $team_size);

$team_name = $_POST['teamName'];
$category = $_POST['category'];
$contact = $_POST['contact'];
$address = $_POST['address'];
$team_size = $_POST['teamSize'] ?? 0;

if ($stmt->execute()) {
    echo "Stage performance registration successful!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
