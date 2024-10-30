<?php
// process_pass_form.php


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['formType'] === 'passForm') {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'free_pass_db');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO free_pass (name, contact, address) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $contact, $address);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "Pass form submitted successfully!";
}
?>