<?php
// process_join_form.php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['formType'] === 'joinForm') {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $interest = $_POST['interest'];
    $otherInterest = $_POST['otherInterest'] ?? null;

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'join_community_db');

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
