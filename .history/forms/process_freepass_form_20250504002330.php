<?php
declare(strict_types=1);

// Include security utilities
require_once '../includes/security.php';

// Set security headers
setSecurityHeaders();

header('Content-Type: application/json');

class FreePassHandler {
    private PDO $pdo;
    
    public function __construct() {
        $this->initializeDatabase();
    }
    
    private function initializeDatabase(): void {
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
            $this->sendResponse(false, 'Database connection failed', null);
            error_log($e->getMessage());
        }
    }
    
    private function generatePassId(): string {
        $prefix = 'PASS';
        $year = date('Y');
        $random = str_pad((string)mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        return $prefix . $year . $random;
    }
    
    private function validateInput(): array {
        // Verify CSRF token
        if (!isset($_POST['csrf_token'])) {
            $this->sendResponse(false, 'Security token missing', null);
        }
        
        verifyCsrfToken($_POST['csrf_token']);
        
        // Apply rate limiting
        checkRateLimit('freepass_form', 5, 3600); // 5 submissions per hour
        
        $required = [
            'name', 'contact', 'email', 'ageGroup',
            'houseNumber', 'lane', 'city', 'state', 'pincode', 'source'
        ];
        
        $data = [];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $this->sendResponse(false, "Missing required field: $field", null);
            }
            $data[$field] = $this->sanitize($_POST[$field]);
        }
        
        // Validate email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->sendResponse(false, 'Invalid email address', null);
        }
        
        // Validate contact (10 digits)
        if (!preg_match('/^[0-9]{10}$/', $data['contact'])) {
            $this->sendResponse(false, 'Invalid contact number', null);
        }
        
        // Validate pincode (6 digits)
        if (!preg_match('/^[0-9]{6}$/', $data['pincode'])) {
            $this->sendResponse(false, 'Invalid pincode', null);
        }
        
        return $data;
    }
    
    private function sanitize(string $data): string {
        // Use the sanitization function from security.php
        return sanitizeInput($data);
    }
    
    public function processPass(): void {
        try {
            $data = $this->validateInput();
            $passId = $this->generatePassId();
            
            $sql = "INSERT INTO free_passes (
                name, contact, email, age_group,
                house_number, lane, city, state, pincode,
                source, pass_id
            ) VALUES (
                :name, :contact, :email, :age_group,
                :house_number, :lane, :city, :state, :pincode,
                :source, :pass_id
            )";
            
            $stmt = $this->pdo->prepare($sql);
            
            $stmt->execute([
                'name' => $data['name'],
                'contact' => $data['contact'],
                'email' => $data['email'],
                'age_group' => $data['ageGroup'],
                'house_number' => $data['houseNumber'],
                'lane' => $data['lane'],
                'city' => $data['city'],
                'state' => $data['state'],
                'pincode' => $data['pincode'],
                'source' => $data['source'],
                'pass_id' => $passId
            ]);
            
            $passData = [
                'passId' => $passId,
                'name' => $data['name'],
                'contact' => $data['contact'],
                'email' => $data['email'],
                'date' => date('Y-m-d')
            ];
            
            $this->sendResponse(true, 'Free pass generated successfully!', $passData);
            
        } catch (PDOException $e) {
            $this->sendResponse(false, 'Failed to save pass data', null);
            error_log($e->getMessage());
        } catch (Exception $e) {
            $this->sendResponse(false, $e->getMessage(), null);
            error_log($e->getMessage());
        }
    }
    
    private function sendResponse(bool $success, string $message, ?array $data): void {
        echo json_encode([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }
}

// Process the form
$handler = new FreePassHandler();
$handler->processPass();