<?php
// process_stall_form.php


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['formType'] === 'stallForm') {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $category = $_POST['category'];
    $uniqueness = $_POST['uniqueness'];
    $shops = $_POST['shops'];
    $requirements = $_POST['requirements'];

    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'stall_book_db');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO stall_book (name, contact, address, category, uniqueness, shops, requirements) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssis", $name, $contact, $address, $category, $uniqueness, $shops, $requirements);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    echo "Stall form submitted successfully!";
}
?>