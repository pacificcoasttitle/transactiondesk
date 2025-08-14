<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Security Validator Library
 * 
 * Centralized security validation and sanitization for all user inputs.
 * Provides comprehensive protection against common web vulnerabilities.
 * 
 * @package    Transaction Desk
 * @subpackage Security
 * @category   Libraries
 * @author     Security Team
 * @version    1.0.0
 */
class SecurityValidator
{
    private $CI;
    private $errors = [];
    private $sanitizedData = [];
    
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('form_validation');
        $this->CI->load->helper('security');
    }
    
    /**
     * Validate and sanitize order input data
     */
    public function validateOrderInput($data)
    {
        $this->errors = [];
        $this->sanitizedData = [];
        
        // Basic sanitization
        foreach ($data as $key => $value) {
            $this->sanitizedData[$key] = $this->sanitizeInput($value);
        }
        
        // Specific validations
        $this->validateName($this->sanitizedData['OpenName'] ?? '', 'First Name');
        $this->validateName($this->sanitizedData['OpenLastName'] ?? '', 'Last Name');
        $this->validateEmail($this->sanitizedData['OpenEmail'] ?? '');
        $this->validatePhone($this->sanitizedData['Opentelephone'] ?? '');
        $this->validateAddress($this->sanitizedData['Property'] ?? '');
        $this->validateAPN($this->sanitizedData['apn'] ?? '');
        
        return [
            'valid' => empty($this->errors),
            'errors' => $this->errors,
            'data' => $this->sanitizedData
        ];
    }
    
    /**
     * Validate login credentials
     */
    public function validateLoginInput($username, $password)
    {
        $this->errors = [];
        
        // Sanitize username
        $username = $this->sanitizeInput($username);
        
        // Username validation
        if (empty($username)) {
            $this->errors[] = 'Username is required';
        } elseif (!preg_match('/^[A-Za-z0-9_]{3,20}$/', $username)) {
            $this->errors[] = 'Username must be 3-20 characters, letters, numbers, and underscores only';
        }
        
        // Password validation
        if (empty($password)) {
            $this->errors[] = 'Password is required';
        } elseif (strlen($password) < 8) {
            $this->errors[] = 'Password must be at least 8 characters';
        } elseif (strlen($password) > 128) {
            $this->errors[] = 'Password is too long (maximum 128 characters)';
        }
        
        return [
            'valid' => empty($this->errors),
            'errors' => $this->errors,
            'username' => $username,
            'password' => $password // Don't sanitize passwords
        ];
    }
    
    /**
     * Validate API input data
     */
    public function validateApiInput($data, $rules = [])
    {
        $this->errors = [];
        $this->sanitizedData = [];
        
        foreach ($data as $key => $value) {
            $sanitized = $this->sanitizeInput($value);
            $this->sanitizedData[$key] = $sanitized;
            
            // Apply specific rules if provided
            if (isset($rules[$key])) {
                $this->applyValidationRule($key, $sanitized, $rules[$key]);
            }
        }
        
        return [
            'valid' => empty($this->errors),
            'errors' => $this->errors,
            'data' => $this->sanitizedData
        ];
    }
    
    /**
     * Sanitize general input
     */
    public function sanitizeInput($input)
    {
        if (is_array($input)) {
            return array_map([$this, 'sanitizeInput'], $input);
        }
        
        // Remove null bytes
        $input = str_replace(chr(0), '', $input);
        
        // Trim whitespace
        $input = trim($input);
        
        // Remove potential XSS
        $input = $this->CI->security->xss_clean($input);
        
        // HTML entity encode
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        
        return $input;
    }
    
    /**
     * Validate name fields
     */
    private function validateName($name, $fieldName)
    {
        if (empty($name)) {
            $this->errors[] = "{$fieldName} is required";
        } elseif (strlen($name) < 2) {
            $this->errors[] = "{$fieldName} must be at least 2 characters";
        } elseif (strlen($name) > 50) {
            $this->errors[] = "{$fieldName} must be less than 50 characters";
        } elseif (!preg_match('/^[A-Za-z\s\-\'\.]+$/', $name)) {
            $this->errors[] = "{$fieldName} contains invalid characters";
        }
    }
    
    /**
     * Validate email address
     */
    private function validateEmail($email)
    {
        if (empty($email)) {
            $this->errors[] = 'Email address is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Invalid email address format';
        } elseif (strlen($email) > 100) {
            $this->errors[] = 'Email address is too long';
        }
    }
    
    /**
     * Validate phone number
     */
    private function validatePhone($phone)
    {
        if (empty($phone)) {
            $this->errors[] = 'Phone number is required';
        } else {
            // Remove all non-numeric characters for validation
            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
            
            if (strlen($cleanPhone) < 10) {
                $this->errors[] = 'Phone number must be at least 10 digits';
            } elseif (strlen($cleanPhone) > 15) {
                $this->errors[] = 'Phone number is too long';
            }
        }
    }
    
    /**
     * Validate property address
     */
    private function validateAddress($address)
    {
        if (empty($address)) {
            $this->errors[] = 'Property address is required';
        } elseif (strlen($address) < 10) {
            $this->errors[] = 'Property address is too short';
        } elseif (strlen($address) > 200) {
            $this->errors[] = 'Property address is too long';
        } elseif (!preg_match('/^[A-Za-z0-9\s\-\.,#]+$/', $address)) {
            $this->errors[] = 'Property address contains invalid characters';
        }
    }
    
    /**
     * Validate APN (Assessor's Parcel Number)
     */
    private function validateAPN($apn)
    {
        if (!empty($apn)) {
            // Remove common separators for validation
            $cleanAPN = preg_replace('/[-\s]/', '', $apn);
            
            if (!preg_match('/^[A-Za-z0-9]{6,20}$/', $cleanAPN)) {
                $this->errors[] = 'Invalid APN format';
            }
        }
    }
    
    /**
     * Apply specific validation rule
     */
    private function applyValidationRule($field, $value, $rule)
    {
        switch ($rule) {
            case 'numeric':
                if (!is_numeric($value)) {
                    $this->errors[] = "{$field} must be numeric";
                }
                break;
                
            case 'integer':
                if (!filter_var($value, FILTER_VALIDATE_INT)) {
                    $this->errors[] = "{$field} must be an integer";
                }
                break;
                
            case 'url':
                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->errors[] = "{$field} must be a valid URL";
                }
                break;
                
            case 'date':
                if (!$this->validateDate($value)) {
                    $this->errors[] = "{$field} must be a valid date";
                }
                break;
                
            case 'alpha':
                if (!preg_match('/^[A-Za-z]+$/', $value)) {
                    $this->errors[] = "{$field} must contain only letters";
                }
                break;
                
            case 'alphanumeric':
                if (!preg_match('/^[A-Za-z0-9]+$/', $value)) {
                    $this->errors[] = "{$field} must contain only letters and numbers";
                }
                break;
        }
    }
    
    /**
     * Validate date format
     */
    private function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
    
    /**
     * Validate file upload
     */
    public function validateFileUpload($file, $options = [])
    {
        $this->errors = [];
        
        $maxSize = $options['max_size'] ?? 5242880; // 5MB default
        $allowedTypes = $options['allowed_types'] ?? ['pdf', 'doc', 'docx', 'jpg', 'png'];
        $allowedMimes = $options['allowed_mimes'] ?? [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'image/jpeg',
            'image/png'
        ];
        
        // Check if file was uploaded
        if (!isset($file['tmp_name']) || empty($file['tmp_name'])) {
            $this->errors[] = 'No file uploaded';
            return ['valid' => false, 'errors' => $this->errors];
        }
        
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->getUploadErrorMessage($file['error']);
            return ['valid' => false, 'errors' => $this->errors];
        }
        
        // Check file size
        if ($file['size'] > $maxSize) {
            $this->errors[] = 'File size exceeds maximum allowed size (' . ($maxSize / 1024 / 1024) . 'MB)';
        }
        
        // Check file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $allowedTypes)) {
            $this->errors[] = 'File type not allowed. Allowed types: ' . implode(', ', $allowedTypes);
        }
        
        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedMimes)) {
            $this->errors[] = 'Invalid file type detected';
        }
        
        // Check for malicious content
        if ($this->containsMaliciousContent($file['tmp_name'])) {
            $this->errors[] = 'File contains potentially malicious content';
        }
        
        return [
            'valid' => empty($this->errors),
            'errors' => $this->errors,
            'mime_type' => $mimeType,
            'extension' => $extension
        ];
    }
    
    /**
     * Get upload error message
     */
    private function getUploadErrorMessage($errorCode)
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return 'File is too large';
            case UPLOAD_ERR_PARTIAL:
                return 'File upload was interrupted';
            case UPLOAD_ERR_NO_FILE:
                return 'No file was uploaded';
            case UPLOAD_ERR_NO_TMP_DIR:
                return 'Server configuration error: no temporary directory';
            case UPLOAD_ERR_CANT_WRITE:
                return 'Server configuration error: cannot write file';
            case UPLOAD_ERR_EXTENSION:
                return 'File upload blocked by server extension';
            default:
                return 'Unknown upload error';
        }
    }
    
    /**
     * Check for malicious content in uploaded files
     */
    private function containsMaliciousContent($filePath)
    {
        $content = file_get_contents($filePath, false, null, 0, 1024); // Read first 1KB
        
        // Check for common malicious patterns
        $maliciousPatterns = [
            '/<\?php/i',
            '/<%/i',
            '/<script/i',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload=/i',
            '/onerror=/i',
            '/eval\s*\(/i',
            '/exec\s*\(/i',
            '/system\s*\(/i'
        ];
        
        foreach ($maliciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Generate CSRF token
     */
    public function generateCSRFToken()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        
        return $token;
    }
    
    /**
     * Validate CSRF token
     */
    public function validateCSRFToken($token)
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if (!isset($_SESSION['csrf_token'])) {
            return false;
        }
        
        return hash_equals($_SESSION['csrf_token'], $token);
    }
    
    /**
     * Validate SQL input to prevent injection
     */
    public function validateSQLInput($input)
    {
        // List of dangerous SQL keywords
        $dangerousPatterns = [
            '/\b(DROP|DELETE|INSERT|UPDATE|REPLACE|CREATE|ALTER|EXEC|EXECUTE|UNION|SELECT)\b/i',
            '/--/',
            '/\/\*.*\*\//',
            '/;/',
            '/\|/',
            '/&&/',
            '/\|\|/',
            '/@/',
            '/char\(/i',
            '/concat\(/i',
            '/load_file\(/i',
            '/into\s+outfile/i'
        ];
        
        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Log security validation events
     */
    public function logSecurityEvent($event, $severity, $details = [])
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'event' => $event,
            'severity' => $severity,
            'details' => $details,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown'
        ];
        
        $logFile = APPPATH . '../logs/security_validation.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
    }
}
?>
