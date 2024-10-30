<?php
// process_performance_form.php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['formType'] === 'performanceForm') {
    $teamName = $_POST['teamName'];
    $category = $_POST['category'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $teamSize = $_POST['teamSize'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'stage_performance_db');

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