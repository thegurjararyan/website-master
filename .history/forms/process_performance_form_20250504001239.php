<?php
declare(strict_types=1);

// Include security utilities
require_once '../includes/security.php';

// Set security headers
setSecurityHeaders();

class DatabaseConnection {
    private mysqli $conn;

    public function __construct() {
        // Load database configuration from external file
        $config = require_once '../includes/config.php';
        $dbConfig = $config['database'];
        
        $this->connect($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['dbname']);
    }

    private function connect(string $host, string $username, string $password, string $database): void {
        try {
            $this->conn = new mysqli(
                $host,
                $username,
                $password,
                $database
            );

            if ($this->conn->connect_error) {
                throw new Exception("Connection failed: " . $this->conn->connect_error);
            }

            $this->conn->set_charset("utf8mb4");
        } catch (Exception $e) {
            $this->sendJsonResponse('error', $e->getMessage());
            exit;
        }
    }

    public function getConnection(): mysqli {
        return $this->conn;
    }

    public function closeConnection(): void {
        $this->conn->close();
    }
}

class PerformanceFormHandler {
    private mysqli $conn;

    public function __construct(DatabaseConnection $db) {
        $this->conn = $db->getConnection();
    }

    private function cleanInput(string $data): string {
        // Use the sanitization function from security.php
        return sanitizeInput($data);
    }

    private function validateInput(array $input): bool {
        // Add any specific validation rules here
        if (strlen($input['contact']) !== 10 || !ctype_digit($input['contact'])) {
            return false;
        }
        if (strlen($input['pincode']) !== 6 || !ctype_digit($input['pincode'])) {
            return false;
        }
        return true;
    }

    public function processForm(array $postData): void {
        try {
            // Apply rate limiting
            checkRateLimit('performance_form', 10, 3600); // 10 submissions per hour
            
            // Verify CSRF token
            if (!isset($postData['csrf_token'])) {
                throw new Exception('Security token missing');
            }
            
            verifyCsrfToken($postData['csrf_token']);
            
            if (!isset($postData['formtype']) || $postData['formtype'] !== 'performanceform') {
                throw new Exception('Invalid form submission');
            }

            // Collect and clean form data
            $formData = [
                'team_name' => $this->cleanInput($postData['teamname']),
                'category' => $this->cleanInput($postData['category']),
                'contact' => $this->cleanInput($postData['contact']),
                'house_number' => $this->cleanInput($postData['houseNumber']),
                'lane' => $this->cleanInput($postData['lane']),
                'city' => $this->cleanInput($postData['city']),
                'state' => $this->cleanInput($postData['state']),
                'pincode' => $this->cleanInput($postData['pincode']),
                'team_size' => !empty($postData['teamsize']) ? (int)$this->cleanInput($postData['teamsize']) : null
            ];

            // Validate input
            if (!$this->validateInput($formData)) {
                throw new Exception('Invalid input data');
            }

            // Prepare and execute SQL statement
            $stmt = $this->conn->prepare("
                INSERT INTO stage_performances 
                (team_name, category, contact, house_number, lane, city, state, pincode, team_size) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            if (!$stmt) {
                throw new Exception('Failed to prepare statement');
            }

            $stmt->bind_param(
                "ssssssssi",
                $formData['team_name'],
                $formData['category'],
                $formData['contact'],
                $formData['house_number'],
                $formData['lane'],
                $formData['city'],
                $formData['state'],
                $formData['pincode'],
                $formData['team_size']
            );

            if (!$stmt->execute()) {
                throw new Exception('Failed to execute statement');
            }

            $stmt->close();
            $this->sendJsonResponse('success', 'Performance registration submitted successfully!');

        } catch (Exception $e) {
            $this->sendJsonResponse('error', $e->getMessage());
        }
    }

    private function sendJsonResponse(string $status, string $message): void {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => $status,
            'message' => $message
        ]);
    }
}

// Handle the request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $db = new DatabaseConnection();
    $handler = new PerformanceFormHandler($db);
    $handler->processForm($_POST);
    $db->closeConnection();
}
?>