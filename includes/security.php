<?php
/**
 * Security utility functions for the website
 * This file contains functions for CSRF protection, security headers, and other security features
 */

/**
 * Set security headers to protect against common web vulnerabilities
 */
function setSecurityHeaders(): void {
    // Prevent XSS attacks by controlling which resources can be loaded
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdnjs.cloudflare.com https://kit.fontawesome.com https://www.google.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; img-src 'self' data: https://github.com https://raw.githubusercontent.com; font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; connect-src 'self';");
    
    // Prevent clickjacking attacks
    header("X-Frame-Options: SAMEORIGIN");
    
    // Prevent MIME type sniffing
    header("X-Content-Type-Options: nosniff");
    
    // Enable browser XSS protection
    header("X-XSS-Protection: 1; mode=block");
    
    // Control referrer information
    header("Referrer-Policy: strict-origin-when-cross-origin");
    
    // Restrict browser features
    header("Permissions-Policy: geolocation=(), microphone=(), camera=()");
}

/**
 * Generate a CSRF token and store it in the session
 * 
 * @return string The generated CSRF token
 */
function generateCsrfToken(): string {
    if (session_status() === PHP_SESSION_NONE) {
        // Configure secure session parameters
        ini_set('session.use_only_cookies', 1);
        ini_set('session.use_strict_mode', 1);
        
        session_start([
            'cookie_httponly' => true,     // Not accessible via JavaScript
            'cookie_samesite' => 'Lax'     // Restrict cross-site requests
        ]);
    }
    
    // Generate a new token if one doesn't exist or is expired
    if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time']) || 
        $_SESSION['csrf_token_time'] < (time() - 3600)) {
        
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Validate a CSRF token against the one stored in the session
 * 
 * @param string $token The token to validate
 * @return bool True if the token is valid, false otherwise
 */
function validateCsrfToken(?string $token): bool {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['csrf_token']) || !isset($token)) {
        return false;
    }
    
    // Check if token matches and is not expired
    if ($_SESSION['csrf_token'] === $token && 
        isset($_SESSION['csrf_token_time']) && 
        $_SESSION['csrf_token_time'] > (time() - 3600)) {
        return true;
    }
    
    return false;
}

/**
 * Verify CSRF token and exit with error if invalid
 * 
 * @param string $token The token to verify
 */
function verifyCsrfToken(?string $token): void {
    if (!validateCsrfToken($token)) {
        header('Content-Type: application/json');
        http_response_code(403);
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid security token. Please refresh the page and try again.'
        ]);
        exit;
    }
}

/**
 * Secure file upload handling
 * 
 * @param array $file The uploaded file ($_FILES['field'])
 * @param array $allowedTypes Array of allowed MIME types
 * @param array $allowedExtensions Array of allowed file extensions
 * @param int $maxSize Maximum file size in bytes
 * @param string $uploadDir Directory to store the file
 * @return string|null The new filename if successful, null if no file was uploaded
 * @throws RuntimeException If the file upload fails validation
 */
function secureFileUpload(array $file, array $allowedTypes, array $allowedExtensions, int $maxSize, string $uploadDir): ?string {
    // Check if a file was uploaded
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    
    // Check for upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
        ];
        
        $errorMessage = $errorMessages[$file['error']] ?? 'Unknown upload error';
        throw new RuntimeException($errorMessage);
    }
    
    // Check file size
    if ($file['size'] > $maxSize) {
        throw new RuntimeException('File size exceeds the limit (' . ($maxSize / 1048576) . 'MB)');
    }
    
    // Verify MIME type
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);
    
    if (!in_array($mimeType, $allowedTypes)) {
        throw new RuntimeException('Invalid file type. Allowed types: ' . implode(', ', $allowedExtensions));
    }
    
    // Verify file extension
    $originalName = basename($file['name']);
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    
    if (!in_array($extension, $allowedExtensions)) {
        throw new RuntimeException('Invalid file extension. Allowed extensions: ' . implode(', ', $allowedExtensions));
    }
    
    // Create upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            throw new RuntimeException('Failed to create upload directory');
        }
    }
    
    // Generate a secure filename
    $newFilename = bin2hex(random_bytes(16)) . '_' . time() . '.' . $extension;
    $filePath = $uploadDir . $newFilename;
    
    // Move the file
    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
        throw new RuntimeException('Failed to move uploaded file');
    }
    
    // Set proper file permissions
    chmod($filePath, 0644);
    
    return $newFilename;
}

/**
 * Sanitize input data
 * 
 * @param string $data The data to sanitize
 * @return string The sanitized data
 */
function sanitizeInput(string $data): string {
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

/**
 * Implement basic rate limiting
 * 
 * @param string $formName Identifier for the form being protected
 * @param int $maxAttempts Maximum number of attempts allowed in the time window
 * @param int $timeWindow Time window in seconds
 */
function checkRateLimit(string $formName, int $maxAttempts = 5, int $timeWindow = 3600): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    $now = time();
    $ip = $_SERVER['REMOTE_ADDR'];
    $key = "rate_limit_{$formName}_{$ip}";
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [
            'attempts' => 0,
            'reset_time' => $now + $timeWindow
        ];
    }
    
    // Reset if time window has passed
    if ($now > $_SESSION[$key]['reset_time']) {
        $_SESSION[$key] = [
            'attempts' => 0,
            'reset_time' => $now + $timeWindow
        ];
    }
    
    // Check if max attempts reached
    if ($_SESSION[$key]['attempts'] >= $maxAttempts) {
        header('Content-Type: application/json');
        http_response_code(429);
        echo json_encode([
            'status' => 'error',
            'message' => 'Too many attempts. Please try again later.'
        ]);
        exit;
    }
    
    // Increment attempt counter
    $_SESSION[$key]['attempts']++;
}