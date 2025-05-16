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

class StallBookingHandler {
    private PDO $pdo;

    public function __construct(Database $db) {
        $this->pdo = $db->getPdo();
    }

    private function validateInput(array $data): bool {
        // Validate phone number (10 digits to match database schema)
        if (!preg_match('/^[0-9]{10}$/', $data['contact'])) {
            throw new InvalidArgumentException('Invalid phone number format. Must be 10 digits.');
        }

        // Validate email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }

        // Validate pincode
        if (!preg_match('/^[1-9][0-9]{5}$/', $data['pincode'])) {
            throw new InvalidArgumentException('Invalid pincode format');
        }

        // Validate shops required
        if ($data['shops'] < 1 || $data['shops'] > 5) {
            throw new InvalidArgumentException('Number of shops must be between 1 and 5');
        }

        return true;
    }

    private function sanitizeInput(string $data): string {
        // Use the sanitization function from security.php
        return sanitizeInput($data);
    }

    public function processBooking(array $postData): void {
        try {
            // Apply rate limiting
            checkRateLimit('stall_form', 5, 3600); // 5 submissions per hour
            
            // Verify CSRF token
            if (!isset($postData['csrf_token'])) {
                throw new InvalidArgumentException('Security token missing');
            }
            
            verifyCsrfToken($postData['csrf_token']);
            
            if (!isset($postData['formType']) || $postData['formType'] !== 'stallForm') {
                throw new InvalidArgumentException('Invalid form submission');
            }

            // Sanitize and collect form data
            $formData = [
                'name' => $this->sanitizeInput($postData['name']),
                'contact' => $this->sanitizeInput($postData['contactNumber']),
                'email' => $this->sanitizeInput($postData['email']),
                'house_number' => $this->sanitizeInput($postData['houseNumber']),
                'lane' => $this->sanitizeInput($postData['lane']),
                'city' => $this->sanitizeInput($postData['city']),
                'state' => $this->sanitizeInput($postData['state']),
                'pincode' => $this->sanitizeInput($postData['pincode']),
                'category' => $this->sanitizeInput($postData['category']),
                'uniqueness' => $this->sanitizeInput($postData['uniqueness']),
                'shops' => (int)$this->sanitizeInput($postData['shops']),
                'requirements' => isset($postData['requirements']) ? 
                    $this->sanitizeInput($postData['requirements']) : null
            ];

            // Validate the input
            $this->validateInput($formData);

            // Prepare and execute the SQL query
            $sql = "INSERT INTO stall_bookings (
                full_name, contact_number, email, house_number, lane, 
                city, state, pincode, stall_category, uniqueness, 
                shops_required, other_requirements
            ) VALUES (
                :name, :contact, :email, :house_number, :lane,
                :city, :state, :pincode, :category, :uniqueness,
                :shops, :requirements
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
                ':category' => $formData['category'],
                ':uniqueness' => $formData['uniqueness'],
                ':shops' => $formData['shops'],
                ':requirements' => $formData['requirements']
            ]);

            if ($success) {
                $this->sendResponse(true, 'Stall booking submitted successfully!');
            } else {
                throw new Exception('Failed to save booking');
            }

        } catch (InvalidArgumentException $e) {
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
    $handler = new StallBookingHandler($db);
    $handler->processBooking($_POST);
}
?>