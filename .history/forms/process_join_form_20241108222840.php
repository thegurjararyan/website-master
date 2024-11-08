<?php
declare(strict_types=1);

header('Content-Type: application/json');

class MembershipHandler {
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
        }
    }
    
    private function generateMemberId(): string {
        $prefix = 'GACT';
        $year = date('Y');
        $random = str_pad((string)mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        return $prefix . $year . $random;
    }
    
    private function validateInput(): array {
        $required = [
            'name', 'contact', 'email', 'bloodGroup', 'profession',
            'houseNumber', 'lane', 'city', 'state', 'pincode', 'interest'
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
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
    
    public function processMembership(): void {
        try {
            $data = $this->validateInput();
            $memberId = $this->generateMemberId();
            
            $sql = "INSERT INTO members (
                name, contact, email, blood_group, profession,
                house_number, lane, city, state, pincode,
                interest, other_interest, member_id
            ) VALUES (
                :name, :contact, :email, :blood_group, :profession,
                :house_number, :lane, :city, :state, :pincode,
                :interest, :other_interest, :member_id
            )";
            
            $stmt = $this->pdo->prepare($sql);
            
            $stmt->execute([
                'name' => $data['name'],
                'contact' => $data['contact'],
                'email' => $data['email'],
                'blood_group' => $data['bloodGroup'],
                'profession' => $data['profession'],
                'house_number' => $data['houseNumber'],
                'lane' => $data['lane'],
                'city' => $data['city'],
                'state' => $data['state'],
                'pincode' => $data['pincode'],
                'interest' => $data['interest'],
                'other_interest' => $_POST['otherInterest'] ?? null,
                'member_id' => $memberId
            ]);
            
            $memberData = [
                'memberId' => $memberId,
                'name' => $data['name'],
                'contact' => $data['contact'],
                'email' => $data['email'],
                'bloodGroup' => $data['bloodGroup'],
                'dateJoined' => date('Y-m-d')
            ];
            
            $this->sendResponse(true, 'Membership registration successful!', $memberData);
            
        } catch (PDOException $e) {
            $this->sendResponse(false, 'Failed to save membership data', null);
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
$handler = new MembershipHandler();
$handler->processMembership();