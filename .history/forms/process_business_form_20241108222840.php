<?php
declare(strict_types=1);

class Database {
    private readonly PDO $pdo;

    public function __construct() {
        $host = 'localhost';
        $dbname = 'forms_db';
        $username = 'Arnish';
        $password = 'ArnishGACT';

        try {
            $this->pdo = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            $this->sendResponse(false, 'Database connection failed');
            error_log($e->getMessage());
            exit;
        }
    }

    public function getPdo(): PDO {
        return $this->pdo;
    }
}

class BusinessIdeaHandler {
    private PDO $pdo;
    private string $uploadDir = '../uploads/business_docs/';
    private const MAX_FILE_SIZE = 5242880; // 5MB in bytes
    private const ALLOWED_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    public function __construct(Database $db) {
        $this->pdo = $db->getPdo();
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    private function validateInput(array $data): bool {
        if (!preg_match('/^[6-9]\d{9}$/', $data['contact'])) {
            throw new InvalidArgumentException('Invalid phone number format');
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }

        if (!preg_match('/^[1-9][0-9]{5}$/', $data['pincode'])) {
            throw new InvalidArgumentException('Invalid pincode format');
        }

        return true;
    }

    private function handleFileUpload(): ?string {
        if (!isset($_FILES['document']) || $_FILES['document']['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $file = $_FILES['document'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('File upload failed');
        }

        if ($file['size'] > self::MAX_FILE_SIZE) {
            throw new RuntimeException('File size exceeds limit (5MB)');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!in_array($mimeType, self::ALLOWED_TYPES)) {
            throw new RuntimeException('Invalid file type');
        }

        $fileName = uniqid('doc_', true) . '_' . basename($file['name']);
        $filePath = $this->uploadDir . $fileName;

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new RuntimeException('Failed to move uploaded file');
        }

        return $fileName;
    }

    private function sanitizeInput(string $data): string {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    public function processSubmission(array $postData): void {
        try {
            if (!isset($postData['formType']) || $postData['formType'] !== 'businessForm') {
                throw new InvalidArgumentException('Invalid form submission');
            }

            // Sanitize and collect form data
            $formData = [
                'name' => $this->sanitizeInput($postData['name']),
                'contact' => $this->sanitizeInput($postData['contact']),
                'email' => $this->sanitizeInput($postData['email']),
                'house_number' => $this->sanitizeInput($postData['houseNumber']),
                'lane' => $this->sanitizeInput($postData['lane']),
                'city' => $this->sanitizeInput($postData['city']),
                'state' => $this->sanitizeInput($postData['state']),
                'pincode' => $this->sanitizeInput($postData['pincode']),
                'business_idea' => $this->sanitizeInput($postData['businessIdea'])
            ];

            // Validate the input
            $this->validateInput($formData);

            // Handle file upload
            $documentPath = $this->handleFileUpload();

            // Prepare and execute the SQL query
            $sql = "INSERT INTO business_ideas (
                full_name, contact_number, email, house_number, lane,
                city, state, pincode, business_idea, document_path
            ) VALUES (
                :name, :contact, :email, :house_number, :lane,
                :city, :state, :pincode, :business_idea, :document_path
            )";

            $stmt = $this->pdo->prepare($sql);
            
            $success = $stmt->execute([
                ':name' => $formData['name'],
                ':contact' => $formData['contact'],
                ':email' => $formData['email'],
                ':house_number' => $formData['house_number'],
                ':lane' => $formData['lane'],
                ':city' => $formData['city'],
                ':state' => $formData['state'],
                ':pincode' => $formData['pincode'],
                ':business_idea' => $formData['business_idea'],
                ':document_path' => $documentPath
            ]);

            if ($success) {
                $this->sendResponse(true, 'Business idea submitted successfully!');
            } else {
                throw new Exception('Failed to save submission');
            }

        } catch (InvalidArgumentException $e) {
            $this->sendResponse(false, $e->getMessage());
        } catch (RuntimeException $e) {
            $this->sendResponse(false, $e->getMessage());
        } catch (Exception $e) {
            $this->sendResponse(false, 'An error occurred while processing your request');
            error_log($e->getMessage());
        }
    }

    private function sendResponse(bool $success, string $message): void {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => $success ? 'success' : 'error',
            'message' => $message
        ]);
        exit;
    }
}

// Process the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new Database();
    $handler = new BusinessIdeaHandler($db);
    $handler->processSubmission($_POST);
}
?>