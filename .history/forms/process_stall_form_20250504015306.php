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

            // Handle business type (if "other" is selected)
            $businessType = $this->sanitizeInput($postData['businessType']);
            if ($businessType === 'Other' && isset($postData['otherBusinessType'])) {
                $businessType = $this->sanitizeInput($postData['otherBusinessType']);
            }

            // Sanitize and collect form data
            $formData = [
                'organization_name' => $this->sanitizeInput($postData['organizationName']),
                'contact_person' => $this->sanitizeInput($postData['contactPerson']),
                'contact' => $this->sanitizeInput($postData['contactNumber']),
                'email' => $this->sanitizeInput($postData['email']),
                'house_number' => $this->sanitizeInput($postData['houseNumber']),
                'lane' => $this->sanitizeInput($postData['lane']),
                'city' => $this->sanitizeInput($postData['city']),
                'state' => $this->sanitizeInput($postData['state']),
                'pincode' => $this->sanitizeInput($postData['pincode']),
                'business_type' => $businessType,
                'stall_size' => $this->sanitizeInput($postData['stallSize']),
                'uniqueness' => $this->sanitizeInput($postData['uniqueness']),
                'special_requirements' => isset($postData['specialRequirements']) ? 
                    $this->sanitizeInput($postData['specialRequirements']) : null
            ];

            // Validate the input
            $this->validateInput($formData);

            // Prepare and execute the SQL query
            $sql = "INSERT INTO stall_bookings (
                organization_name, contact_person, contact_number, email, house_number, lane, 
                city, state, pincode, business_type, stall_size, uniqueness, 
                special_requirements
            ) VALUES (
                :organization_name, :contact_person, :contact_number, :email, :house_number, :lane,
                :city, :state, :pincode, :business_type, :stall_size, :uniqueness,
                :special_requirements
            )";

            $stmt = $this->pdo->prepare($sql);
            
            $success = $stmt->execute([
                ':organization_name' => $formData['organization_name'],
                ':contact_person' => $formData['contact_person'],
                ':contact_number' => $formData['contact'],
                ':email' => $formData['email'],
                ':house_number' => $formData['house_number'],
                ':lane' => $formData['lane'],
                ':city' => $formData['city'],
                ':state' => $formData['state'],
                ':pincode' => $formData['pincode'],
                ':business_type' => $formData['business_type'],
                ':stall_size' => $formData['stall_size'],
                ':uniqueness' => $formData['uniqueness'],
                ':special_requirements' => $formData['special_requirements']
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