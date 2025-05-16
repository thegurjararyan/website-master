<?php
declare(strict_types=1);

// Include security utilities
require_once '../includes/security.php';

// Set security headers
setSecurityHeaders();

class Database {
    private readonly PDO $pdo;

    public function __construct() {
        // Load database configuration from external file
        $config = require_once '../includes/config.php';
        $dbConfig = $config['database'];

        try {
            $this->pdo = new PDO(
                "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}",
                $dbConfig['username'],
                $dbConfig['password'],
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
    private const ALLOWED_EXTENSIONS = ['pdf', 'doc', 'docx'];

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
        try {
            // Use the secure file upload function from security.php
            return secureFileUpload(
                $_FILES['document'] ?? [],
                self::ALLOWED_TYPES,
                self::ALLOWED_EXTENSIONS,
                self::MAX_FILE_SIZE,
                $this->uploadDir
            );
        } catch (RuntimeException $e) {
            throw new RuntimeException($e->getMessage());
        }
    }

    private function sanitizeInput(string $data): string {
        // Use the sanitization function from security.php
        return sanitizeInput($data);
    }

    public function processSubmission(array $postData): void {
        try {
            // Apply rate limiting
            checkRateLimit('business_form', 10, 3600); // 10 submissions per hour
            
            // Verify CSRF token
            if (!isset($postData['csrf_token'])) {
                throw new InvalidArgumentException('Security token missing');
            }
            
            verifyCsrfToken($postData['csrf_token']);
            
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