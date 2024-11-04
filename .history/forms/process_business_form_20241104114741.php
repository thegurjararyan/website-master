<?php
// Database configuration
$host = 'localhost';
$dbname = 'forms_db';
$username = 'Arnish';
$password = 'ArnishGACT';

// File upload configuration
$uploadDir = '../uploads/business_docs/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['formType'] == 'businessForm') {
        $name = $_POST['name'];
        $contact = $_POST['contact'];
        $email = $_POST['email'];
        $address = $_POST['address'];
        $businessIdea = $_POST['businessIdea'];
        $documentPath = null;

        // Handle file upload
        if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
            $file = $_FILES['document'];
            $fileName = time() . '_' . basename($file['name']);
            $targetPath = $uploadDir . $fileName;

            // Validate file type
            $allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
            if (in_array($file['type'], $allowedTypes) && $file['size'] <= 5 * 1024 * 1024) {
                if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                    $documentPath = 'uploads/business_docs/' . $fileName;
                }
            }
        }

        // Insert data into database
        $stmt = $pdo->prepare("INSERT INTO business_ideas (name, contact, email, address, business_idea, document_path) 
                              VALUES (?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([$name, $contact, $email, $address, $businessIdea, $documentPath]);

        // Return JSON response
        if ($success) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        echo json_encode(['success' => false]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?> 