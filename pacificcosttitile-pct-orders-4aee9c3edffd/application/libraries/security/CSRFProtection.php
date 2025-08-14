<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CSRF Protection Library
 * 
 * Provides Cross-Site Request Forgery protection for all forms and AJAX requests.
 * Implements token-based validation with configurable token lifetimes and storage methods.
 * 
 * @package    Transaction Desk
 * @subpackage Security
 * @category   Libraries
 * @author     Security Team
 * @version    1.0.0
 */
class CSRFProtection
{
    private $CI;
    private $tokenName = 'csrf_token';
    private $tokenLength = 32;
    private $tokenLifetime = 3600; // 1 hour
    private $regenerateOnUse = true;
    private $storageMethod = 'session'; // session, database, or file
    
    public function __construct($config = [])
    {
        $this->CI =& get_instance();
        
        // Override default settings with config
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Initialize token storage
        $this->initializeStorage();
    }
    
    /**
     * Generate a new CSRF token
     */
    public function generateToken()
    {
        $token = bin2hex(random_bytes($this->tokenLength));
        $tokenData = [
            'token' => $token,
            'created_at' => time(),
            'expires_at' => time() + $this->tokenLifetime,
            'used' => false
        ];
        
        $this->storeToken($tokenData);
        
        return $token;
    }
    
    /**
     * Get current CSRF token (generate if doesn't exist)
     */
    public function getToken()
    {
        $tokenData = $this->getStoredToken();
        
        if (!$tokenData || $this->isTokenExpired($tokenData)) {
            return $this->generateToken();
        }
        
        return $tokenData['token'];
    }
    
    /**
     * Validate CSRF token
     */
    public function validateToken($providedToken)
    {
        if (empty($providedToken)) {
            $this->logCSRFEvent('TOKEN_MISSING', 'No CSRF token provided');
            return false;
        }
        
        $storedTokenData = $this->getStoredToken();
        
        if (!$storedTokenData) {
            $this->logCSRFEvent('TOKEN_NOT_FOUND', 'No stored token found');
            return false;
        }
        
        if ($this->isTokenExpired($storedTokenData)) {
            $this->logCSRFEvent('TOKEN_EXPIRED', 'Token has expired');
            $this->clearToken();
            return false;
        }
        
        if ($storedTokenData['used'] && $this->regenerateOnUse) {
            $this->logCSRFEvent('TOKEN_REUSE', 'Token already used');
            return false;
        }
        
        if (!hash_equals($storedTokenData['token'], $providedToken)) {
            $this->logCSRFEvent('TOKEN_MISMATCH', 'Token does not match');
            return false;
        }
        
        // Mark token as used
        if ($this->regenerateOnUse) {
            $storedTokenData['used'] = true;
            $this->storeToken($storedTokenData);
        }
        
        $this->logCSRFEvent('TOKEN_VALID', 'Token validated successfully');
        return true;
    }
    
    /**
     * Validate token from request (POST, GET, or headers)
     */
    public function validateRequest()
    {
        $token = $this->getTokenFromRequest();
        return $this->validateToken($token);
    }
    
    /**
     * Get token from various request sources
     */
    private function getTokenFromRequest()
    {
        // Check POST data
        if (isset($_POST[$this->tokenName])) {
            return $_POST[$this->tokenName];
        }
        
        // Check GET data (for AJAX requests)
        if (isset($_GET[$this->tokenName])) {
            return $_GET[$this->tokenName];
        }
        
        // Check HTTP headers
        $headerName = 'HTTP_X_' . strtoupper(str_replace('-', '_', $this->tokenName));
        if (isset($_SERVER[$headerName])) {
            return $_SERVER[$headerName];
        }
        
        // Check Authorization header
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth = $_SERVER['HTTP_AUTHORIZATION'];
            if (preg_match('/Bearer\s+(.+)/', $auth, $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }
    
    /**
     * Generate HTML hidden input for forms
     */
    public function getHiddenInput()
    {
        $token = $this->getToken();
        return '<input type="hidden" name="' . htmlspecialchars($this->tokenName) . '" value="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * Generate meta tag for AJAX requests
     */
    public function getMetaTag()
    {
        $token = $this->getToken();
        return '<meta name="' . htmlspecialchars($this->tokenName) . '" content="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * Generate JavaScript object with token
     */
    public function getJavaScriptObject($varName = 'csrfToken')
    {
        $token = $this->getToken();
        return '<script>var ' . $varName . ' = "' . addslashes($token) . '";</script>';
    }
    
    /**
     * Middleware for automatic CSRF protection
     */
    public function protectRequest()
    {
        // Skip CSRF for GET, HEAD, OPTIONS requests
        if (in_array($_SERVER['REQUEST_METHOD'], ['GET', 'HEAD', 'OPTIONS'])) {
            return true;
        }
        
        // Skip CSRF for API endpoints with proper authentication
        if ($this->isAPIRequest() && $this->hasValidAPIAuth()) {
            return true;
        }
        
        if (!$this->validateRequest()) {
            $this->handleCSRFFailure();
            return false;
        }
        
        return true;
    }
    
    /**
     * Check if current request is an API request
     */
    private function isAPIRequest()
    {
        // Check for API path
        $requestUri = $_SERVER['REQUEST_URI'] ?? '';
        if (strpos($requestUri, '/api/') !== false) {
            return true;
        }
        
        // Check for API content type
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (strpos($contentType, 'application/json') !== false) {
            return true;
        }
        
        // Check for API headers
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return true;
        }
        
        return false;
    }
    
    /**
     * Check for valid API authentication
     */
    private function hasValidAPIAuth()
    {
        // Check for Bearer token
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $auth = $_SERVER['HTTP_AUTHORIZATION'];
            if (strpos($auth, 'Bearer ') === 0) {
                return true; // Assume valid for now, should verify token
            }
        }
        
        // Check for API key
        if (isset($_SERVER['HTTP_X_API_KEY'])) {
            return true; // Assume valid for now, should verify key
        }
        
        return false;
    }
    
    /**
     * Handle CSRF validation failure
     */
    private function handleCSRFFailure()
    {
        http_response_code(403);
        
        if ($this->isAPIRequest()) {
            header('Content-Type: application/json');
            echo json_encode([
                'error' => 'CSRF token validation failed',
                'code' => 'CSRF_TOKEN_INVALID'
            ]);
        } else {
            echo '<html><body><h1>403 Forbidden</h1><p>CSRF token validation failed. Please refresh the page and try again.</p></body></html>';
        }
        
        exit;
    }
    
    /**
     * Check if token is expired
     */
    private function isTokenExpired($tokenData)
    {
        return isset($tokenData['expires_at']) && time() > $tokenData['expires_at'];
    }
    
    /**
     * Initialize token storage based on method
     */
    private function initializeStorage()
    {
        switch ($this->storageMethod) {
            case 'database':
                $this->initializeDatabaseStorage();
                break;
            case 'file':
                $this->initializeFileStorage();
                break;
            case 'session':
            default:
                // Session storage requires no initialization
                break;
        }
    }
    
    /**
     * Store token based on storage method
     */
    private function storeToken($tokenData)
    {
        switch ($this->storageMethod) {
            case 'database':
                $this->storeTokenInDatabase($tokenData);
                break;
            case 'file':
                $this->storeTokenInFile($tokenData);
                break;
            case 'session':
            default:
                $_SESSION[$this->tokenName] = $tokenData;
                break;
        }
    }
    
    /**
     * Get stored token based on storage method
     */
    private function getStoredToken()
    {
        switch ($this->storageMethod) {
            case 'database':
                return $this->getTokenFromDatabase();
            case 'file':
                return $this->getTokenFromFile();
            case 'session':
            default:
                return $_SESSION[$this->tokenName] ?? null;
        }
    }
    
    /**
     * Clear token from storage
     */
    public function clearToken()
    {
        switch ($this->storageMethod) {
            case 'database':
                $this->clearTokenFromDatabase();
                break;
            case 'file':
                $this->clearTokenFromFile();
                break;
            case 'session':
            default:
                unset($_SESSION[$this->tokenName]);
                break;
        }
    }
    
    /**
     * Initialize database storage
     */
    private function initializeDatabaseStorage()
    {
        $this->CI->db->query("
            CREATE TABLE IF NOT EXISTS `csrf_tokens` (
                `id` varchar(128) NOT NULL,
                `token_data` text NOT NULL,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `expires_at` timestamp NOT NULL,
                PRIMARY KEY (`id`),
                KEY `expires_at` (`expires_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }
    
    /**
     * Store token in database
     */
    private function storeTokenInDatabase($tokenData)
    {
        $sessionId = session_id();
        $data = [
            'id' => $sessionId,
            'token_data' => json_encode($tokenData),
            'expires_at' => date('Y-m-d H:i:s', $tokenData['expires_at'])
        ];
        
        $this->CI->db->replace('csrf_tokens', $data);
    }
    
    /**
     * Get token from database
     */
    private function getTokenFromDatabase()
    {
        $sessionId = session_id();
        $this->CI->db->select('token_data');
        $this->CI->db->from('csrf_tokens');
        $this->CI->db->where('id', $sessionId);
        $this->CI->db->where('expires_at >', date('Y-m-d H:i:s'));
        $query = $this->CI->db->get();
        
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return json_decode($row->token_data, true);
        }
        
        return null;
    }
    
    /**
     * Clear token from database
     */
    private function clearTokenFromDatabase()
    {
        $sessionId = session_id();
        $this->CI->db->where('id', $sessionId);
        $this->CI->db->delete('csrf_tokens');
    }
    
    /**
     * Initialize file storage
     */
    private function initializeFileStorage()
    {
        $tokenDir = APPPATH . '../tmp/csrf_tokens';
        if (!is_dir($tokenDir)) {
            mkdir($tokenDir, 0755, true);
        }
    }
    
    /**
     * Store token in file
     */
    private function storeTokenInFile($tokenData)
    {
        $sessionId = session_id();
        $tokenFile = APPPATH . '../tmp/csrf_tokens/' . hash('sha256', $sessionId) . '.json';
        
        $fileData = [
            'token_data' => $tokenData,
            'session_id' => $sessionId
        ];
        
        file_put_contents($tokenFile, json_encode($fileData), LOCK_EX);
    }
    
    /**
     * Get token from file
     */
    private function getTokenFromFile()
    {
        $sessionId = session_id();
        $tokenFile = APPPATH . '../tmp/csrf_tokens/' . hash('sha256', $sessionId) . '.json';
        
        if (file_exists($tokenFile)) {
            $fileData = json_decode(file_get_contents($tokenFile), true);
            
            if ($fileData && isset($fileData['token_data'])) {
                $tokenData = $fileData['token_data'];
                
                // Check if token is expired
                if (!$this->isTokenExpired($tokenData)) {
                    return $tokenData;
                } else {
                    unlink($tokenFile); // Remove expired token
                }
            }
        }
        
        return null;
    }
    
    /**
     * Clear token from file
     */
    private function clearTokenFromFile()
    {
        $sessionId = session_id();
        $tokenFile = APPPATH . '../tmp/csrf_tokens/' . hash('sha256', $sessionId) . '.json';
        
        if (file_exists($tokenFile)) {
            unlink($tokenFile);
        }
    }
    
    /**
     * Clean up expired tokens (should be called periodically)
     */
    public function cleanupExpiredTokens()
    {
        switch ($this->storageMethod) {
            case 'database':
                $this->CI->db->where('expires_at <', date('Y-m-d H:i:s'));
                $this->CI->db->delete('csrf_tokens');
                break;
                
            case 'file':
                $tokenDir = APPPATH . '../tmp/csrf_tokens';
                if (is_dir($tokenDir)) {
                    $files = glob($tokenDir . '/*.json');
                    foreach ($files as $file) {
                        $fileData = json_decode(file_get_contents($file), true);
                        if ($fileData && isset($fileData['token_data'])) {
                            if ($this->isTokenExpired($fileData['token_data'])) {
                                unlink($file);
                            }
                        }
                    }
                }
                break;
        }
    }
    
    /**
     * Log CSRF events
     */
    private function logCSRFEvent($event, $message)
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'event' => $event,
            'message' => $message,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
            'session_id' => session_id()
        ];
        
        $logFile = APPPATH . '../logs/csrf_protection.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Get CSRF protection statistics
     */
    public function getStats()
    {
        return [
            'token_name' => $this->tokenName,
            'token_lifetime' => $this->tokenLifetime,
            'storage_method' => $this->storageMethod,
            'regenerate_on_use' => $this->regenerateOnUse,
            'current_token_exists' => !empty($this->getStoredToken())
        ];
    }
}
?>
