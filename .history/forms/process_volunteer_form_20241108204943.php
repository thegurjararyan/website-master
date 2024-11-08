<?php
declare(strict_types=1);

// Enable error reporting for development (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

class VolunteerFormHandler {
    private PDO $pdo;
    
    public function __construct() {
        $this->initializeDatabase();
    }
    
    private function initializeDatabase(): void {
        $dbConfig = [
            'host' => 'localhost',
            'dbname' => 'forms_db',
            'username' => 'Arnish',
            'password' => 'ArnishGACT',
            'charset' => 'utf8mb4'
        ];
        
        $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
        
        try {
            $this->pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            $this->handleError('Database connection failed: ' . $e->getMessage());
        }
    }
    
    public function handleFormSubmission(): void {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Invalid request method');
            }
            
            if (!isset($_POST['formType']) || $_POST['formType'] !== 'volunteerForm') {
                throw new Exception('Invalid form type');
            }
            
            $this->validateFormData();
            $this->saveVolunteerData();
            
            // Send success response
            $this->sendJsonResponse([
                'status' => 'success',
                'message' => 'Volunteer registration successful!'
            ]);
            
        } catch (Exception $e) {
            $this->handleError($e->getMessage());
        }
    }
    
    private function validateFormData(): void {
        $requiredFields = [
            'name', 'contact', 'houseNumber', 'lane', 'city', 
            'state', 'pincode', 'gender', 'age', 'field', 'days'
        ];
        
        foreach ($requiredFields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception("$field is required");
            }
        }
        
        // Validate contact number (10 digits)
        if (!preg_match('/^[0-9]{10}$/', $_POST['contact'])) {
            throw new Exception('Invalid contact number');
        }
        
        // Validate pincode (6 digits)
        if (!preg_match('/^[0-9]{6}$/', $_POST['pincode'])) {
            throw new Exception('Invalid pincode');
        }
        
        // Validate age (1-100)
        $age = (int)$_POST['age'];
        if ($age < 1 || $age > 100) {
            throw new Exception('Invalid age');
        }
        
        // Validate days (1-3)
        $days = (int)$_POST['days'];
        if ($days < 1 || $days > 3) {
            throw new Exception('Invalid number of days');
        }
    }
    
    private function saveVolunteerData(): void {
        $sql = "INSERT INTO volunteers (
            name, contact, house_number, lane, city, state,
            pincode, gender, age, field, days, created_at
        ) VALUES (
            :name, :contact, :house_number, :lane, :city, :state,
            :pincode, :gender, :age, :field, :days, NOW()
        )";
        
        try {
            $stmt = $this->pdo->prepare($sql);
            
            $stmt->execute([
                'name' => $this->sanitize($_POST['name']),
                'contact' => $this->sanitize($_POST['contact']),
                'house_number' => $this->sanitize($_POST['houseNumber']),
                'lane' => $this->sanitize($_POST['lane']),
                'city' => $this->sanitize($_POST['city']),
                'state' => $this->sanitize($_POST['state']),
                'pincode' => $this->sanitize($_POST['pincode']),
                'gender' => $this->sanitize($_POST['gender']),
                'age' => (int)$_POST['age'],
                'field' => $this->sanitize($_POST['field']),
                'days' => (int)$_POST['days']
            ]);
            
        } catch (PDOException $e) {
            throw new Exception('Failed to save volunteer data');
        }
    }
    
    private function sanitize(string $data): string {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    private function handleError(string $message): void {
        $this->sendJsonResponse([
            'status' => 'error',
            'message' => $message
        ], 400);
    }
    
    private function sendJsonResponse(array $data, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

// Initialize and handle the form
$handler = new VolunteerFormHandler();
$handler->handleFormSubmission();