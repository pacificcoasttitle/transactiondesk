<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Secure File Upload Library
 * 
 * Comprehensive file upload security with virus scanning, MIME validation,
 * malicious content detection, and secure storage management.
 * 
 * @package    Transaction Desk
 * @subpackage Security
 * @category   Libraries
 * @author     Security Team
 * @version    1.0.0
 */
class SecureFileUpload
{
    private $CI;
    private $uploadPath = './uploads/secure/';
    private $allowedTypes = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'tif', 'tiff', 'xls', 'xlsx'];
    private $allowedMimeTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'image/jpeg',
        'image/png',
        'image/tiff',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];
    private $maxFileSize = 10485760; // 10MB
    private $encryptFiles = true;
    private $scanForViruses = true;
    private $quarantinePath = './uploads/quarantine/';
    private $logPath = './logs/file_uploads.log';
    
    public function __construct($config = [])
    {
        $this->CI =& get_instance();
        $this->CI->load->helper(['file', 'security', 'string']);
        
        // Override default settings
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        
        $this->initializeDirectories();
    }
    
    /**
     * Process file upload with comprehensive security checks
     */
    public function processUpload($fileField, $options = [])
    {
        $uploadResult = [
            'success' => false,
            'errors' => [],
            'file_info' => null,
            'security_checks' => []
        ];
        
        try {
            // Check if file was uploaded
            if (!isset($_FILES[$fileField]) || $_FILES[$fileField]['error'] === UPLOAD_ERR_NO_FILE) {
                $uploadResult['errors'][] = 'No file uploaded';
                return $uploadResult;
            }
            
            $file = $_FILES[$fileField];
            
            // Basic upload error check
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $uploadResult['errors'][] = $this->getUploadErrorMessage($file['error']);
                return $uploadResult;
            }
            
            // Security validation pipeline
            $securityChecks = [
                'file_size' => $this->validateFileSize($file),
                'file_extension' => $this->validateFileExtension($file),
                'mime_type' => $this->validateMimeType($file),
                'malicious_content' => $this->scanForMaliciousContent($file),
                'virus_scan' => $this->scanForViruses($file),
                'image_validation' => $this->validateImageFile($file)
            ];
            
            $uploadResult['security_checks'] = $securityChecks;
            
            // Check if any security validation failed
            foreach ($securityChecks as $check => $result) {
                if (!$result['passed']) {
                    $uploadResult['errors'][] = $result['message'];
                }
            }
            
            if (!empty($uploadResult['errors'])) {
                $this->quarantineFile($file, $uploadResult['errors']);
                return $uploadResult;
            }
            
            // Generate secure filename
            $secureFilename = $this->generateSecureFilename($file);
            $targetPath = $this->uploadPath . $secureFilename;
            
            // Move and process file
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                // Encrypt file if enabled
                if ($this->encryptFiles) {
                    $this->encryptFile($targetPath);
                }
                
                // Set secure permissions
                chmod($targetPath, 0644);
                
                $uploadResult['success'] = true;
                $uploadResult['file_info'] = [
                    'original_name' => $file['name'],
                    'secure_filename' => $secureFilename,
                    'file_path' => $targetPath,
                    'file_size' => $file['size'],
                    'mime_type' => $securityChecks['mime_type']['detected_type'],
                    'upload_date' => date('Y-m-d H:i:s'),
                    'encrypted' => $this->encryptFiles
                ];
                
                $this->logUpload($uploadResult['file_info'], 'SUCCESS');
            } else {
                $uploadResult['errors'][] = 'Failed to move uploaded file to secure location';
                $this->logUpload(['original_name' => $file['name']], 'MOVE_FAILED');
            }
            
        } catch (Exception $e) {
            $uploadResult['errors'][] = 'Upload processing error: ' . $e->getMessage();
            $this->logUpload(['original_name' => $file['name'] ?? 'unknown'], 'EXCEPTION', $e->getMessage());
        }
        
        return $uploadResult;
    }
    
    /**
     * Validate file size
     */
    private function validateFileSize($file)
    {
        if ($file['size'] > $this->maxFileSize) {
            return [
                'passed' => false,
                'message' => 'File size exceeds maximum allowed size (' . ($this->maxFileSize / 1024 / 1024) . 'MB)'
            ];
        }
        
        if ($file['size'] === 0) {
            return [
                'passed' => false,
                'message' => 'File is empty'
            ];
        }
        
        return ['passed' => true, 'message' => 'File size valid'];
    }
    
    /**
     * Validate file extension
     */
    private function validateFileExtension($file)
    {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (empty($extension)) {
            return [
                'passed' => false,
                'message' => 'File has no extension'
            ];
        }
        
        if (!in_array($extension, $this->allowedTypes)) {
            return [
                'passed' => false,
                'message' => 'File type not allowed. Allowed types: ' . implode(', ', $this->allowedTypes)
            ];
        }
        
        return [
            'passed' => true,
            'message' => 'File extension valid',
            'extension' => $extension
        ];
    }
    
    /**
     * Validate MIME type
     */
    private function validateMimeType($file)
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedMime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!$detectedMime) {
            return [
                'passed' => false,
                'message' => 'Could not detect file MIME type'
            ];
        }
        
        if (!in_array($detectedMime, $this->allowedMimeTypes)) {
            return [
                'passed' => false,
                'message' => 'MIME type not allowed: ' . $detectedMime
            ];
        }
        
        // Check for MIME/extension mismatch
        $expectedMimes = $this->getExpectedMimeTypes($file['name']);
        if (!empty($expectedMimes) && !in_array($detectedMime, $expectedMimes)) {
            return [
                'passed' => false,
                'message' => 'MIME type does not match file extension'
            ];
        }
        
        return [
            'passed' => true,
            'message' => 'MIME type valid',
            'detected_type' => $detectedMime
        ];
    }
    
    /**
     * Get expected MIME types for file extension
     */
    private function getExpectedMimeTypes($filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        $mimeMap = [
            'pdf' => ['application/pdf'],
            'doc' => ['application/msword'],
            'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            'jpg' => ['image/jpeg'],
            'jpeg' => ['image/jpeg'],
            'png' => ['image/png'],
            'tif' => ['image/tiff'],
            'tiff' => ['image/tiff'],
            'xls' => ['application/vnd.ms-excel'],
            'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        ];
        
        return $mimeMap[$extension] ?? [];
    }
    
    /**
     * Scan for malicious content
     */
    private function scanForMaliciousContent($file)
    {
        $content = file_get_contents($file['tmp_name'], false, null, 0, 8192); // Read first 8KB
        
        // Patterns to detect potentially malicious content
        $maliciousPatterns = [
            // PHP tags
            '/<\?php/i',
            '/<\?=/i',
            '/<script.*php/i',
            
            // JavaScript
            '/<script[^>]*>/i',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload\s*=/i',
            '/onerror\s*=/i',
            '/onclick\s*=/i',
            
            // Server-side includes
            '/<%/i',
            '/<\?/i',
            
            // Dangerous functions
            '/eval\s*\(/i',
            '/exec\s*\(/i',
            '/system\s*\(/i',
            '/shell_exec\s*\(/i',
            '/passthru\s*\(/i',
            '/file_get_contents\s*\(/i',
            '/file_put_contents\s*\(/i',
            
            // SQL injection patterns
            '/union\s+select/i',
            '/drop\s+table/i',
            '/insert\s+into/i',
            '/update\s+set/i',
            
            // Command injection
            '/\|\s*nc\s/i',
            '/\|\s*netcat/i',
            '/\|\s*telnet/i',
            '/\|\s*bash/i',
            '/\|\s*sh\s/i',
            
            // Embedded executables
            '/\x4d\x5a/', // PE header
            '/\x7f\x45\x4c\x46/', // ELF header
            '/\xca\xfe\xba\xbe/', // Mach-O header
        ];
        
        foreach ($maliciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return [
                    'passed' => false,
                    'message' => 'File contains potentially malicious content'
                ];
            }
        }
        
        // Check for suspicious file headers
        $suspiciousHeaders = [
            'data:text/html',
            'data:application/javascript',
            'data:text/javascript'
        ];
        
        foreach ($suspiciousHeaders as $header) {
            if (strpos($content, $header) === 0) {
                return [
                    'passed' => false,
                    'message' => 'File contains suspicious data URI'
                ];
            }
        }
        
        return ['passed' => true, 'message' => 'No malicious content detected'];
    }
    
    /**
     * Validate image files
     */
    private function validateImageFile($file)
    {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'tif', 'tiff'])) {
            return ['passed' => true, 'message' => 'Not an image file'];
        }
        
        // Validate image using GD library
        $imageInfo = @getimagesize($file['tmp_name']);
        
        if ($imageInfo === false) {
            return [
                'passed' => false,
                'message' => 'Invalid image file'
            ];
        }
        
        // Check image dimensions
        if ($imageInfo[0] > 10000 || $imageInfo[1] > 10000) {
            return [
                'passed' => false,
                'message' => 'Image dimensions too large (max 10000x10000)'
            ];
        }
        
        // Validate MIME type matches extension
        $validMimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'tif' => 'image/tiff',
            'tiff' => 'image/tiff'
        ];
        
        if (isset($validMimeTypes[$extension]) && $imageInfo['mime'] !== $validMimeTypes[$extension]) {
            return [
                'passed' => false,
                'message' => 'Image MIME type does not match extension'
            ];
        }
        
        return [
            'passed' => true,
            'message' => 'Valid image file',
            'dimensions' => $imageInfo[0] . 'x' . $imageInfo[1]
        ];
    }
    
    /**
     * Scan for viruses (placeholder for actual antivirus integration)
     */
    private function scanForViruses($file)
    {
        if (!$this->scanForViruses) {
            return ['passed' => true, 'message' => 'Virus scanning disabled'];
        }
        
        // Placeholder for ClamAV or other antivirus integration
        // In production, integrate with:
        // - ClamAV via command line: clamscan
        // - VirusTotal API
        // - Windows Defender via PowerShell
        // - Third-party antivirus APIs
        
        // Basic file signature check for known malicious patterns
        $content = file_get_contents($file['tmp_name'], false, null, 0, 1024);
        
        // Known malicious signatures (simplified)
        $virusSignatures = [
            'EICAR-STANDARD-ANTIVIRUS-TEST-FILE', // EICAR test file
            'X5O!P%@AP[4\PZX54(P^)7CC)7}$EICAR', // EICAR variant
        ];
        
        foreach ($virusSignatures as $signature) {
            if (strpos($content, $signature) !== false) {
                return [
                    'passed' => false,
                    'message' => 'Virus detected in file'
                ];
            }
        }
        
        return ['passed' => true, 'message' => 'No viruses detected'];
    }
    
    /**
     * Generate secure filename
     */
    private function generateSecureFilename($file)
    {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $timestamp = date('Y-m-d_H-i-s');
        $randomString = bin2hex(random_bytes(8));
        
        return $timestamp . '_' . $randomString . '.' . $extension;
    }
    
    /**
     * Encrypt file content
     */
    private function encryptFile($filePath)
    {
        $content = file_get_contents($filePath);
        $key = $this->getEncryptionKey();
        $iv = openssl_random_pseudo_bytes(16);
        
        $encrypted = openssl_encrypt($content, 'AES-256-CBC', $key, 0, $iv);
        $encryptedContent = base64_encode($iv . $encrypted);
        
        file_put_contents($filePath . '.enc', $encryptedContent);
        unlink($filePath); // Remove original
        
        return $filePath . '.enc';
    }
    
    /**
     * Decrypt file content
     */
    public function decryptFile($encryptedFilePath)
    {
        $encryptedContent = file_get_contents($encryptedFilePath);
        $data = base64_decode($encryptedContent);
        
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        $key = $this->getEncryptionKey();
        
        return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
    }
    
    /**
     * Get encryption key
     */
    private function getEncryptionKey()
    {
        // Use environment variable or generate from application key
        $appKey = getenv('ENCRYPTION_KEY') ?: 'default_file_encryption_key_change_in_production';
        return hash('sha256', $appKey, true);
    }
    
    /**
     * Quarantine suspicious file
     */
    private function quarantineFile($file, $reasons)
    {
        if (!is_dir($this->quarantinePath)) {
            mkdir($this->quarantinePath, 0755, true);
        }
        
        $quarantineFilename = date('Y-m-d_H-i-s') . '_' . bin2hex(random_bytes(4)) . '_quarantine';
        $quarantinePath = $this->quarantinePath . $quarantineFilename;
        
        if (move_uploaded_file($file['tmp_name'], $quarantinePath)) {
            // Create metadata file
            $metadata = [
                'original_name' => $file['name'],
                'quarantine_date' => date('Y-m-d H:i:s'),
                'reasons' => $reasons,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
            ];
            
            file_put_contents($quarantinePath . '.meta', json_encode($metadata, JSON_PRETTY_PRINT));
            
            $this->logUpload([
                'original_name' => $file['name'],
                'quarantine_path' => $quarantinePath,
                'reasons' => $reasons
            ], 'QUARANTINED');
        }
    }
    
    /**
     * Initialize required directories
     */
    private function initializeDirectories()
    {
        $directories = [
            $this->uploadPath,
            $this->quarantinePath,
            dirname($this->logPath)
        ];
        
        foreach ($directories as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
        }
        
        // Create .htaccess for upload directories
        $htaccessContent = "Order Deny,Allow\nDeny from all\n";
        file_put_contents($this->uploadPath . '.htaccess', $htaccessContent);
        file_put_contents($this->quarantinePath . '.htaccess', $htaccessContent);
    }
    
    /**
     * Get upload error message
     */
    private function getUploadErrorMessage($errorCode)
    {
        $messages = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize directive',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE directive',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
        ];
        
        return $messages[$errorCode] ?? 'Unknown upload error';
    }
    
    /**
     * Log upload events
     */
    private function logUpload($fileInfo, $status, $additionalInfo = '')
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'status' => $status,
            'file_info' => $fileInfo,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'additional_info' => $additionalInfo
        ];
        
        file_put_contents($this->logPath, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Get upload statistics
     */
    public function getUploadStats()
    {
        $stats = [
            'total_uploads' => 0,
            'successful_uploads' => 0,
            'quarantined_files' => 0,
            'total_size' => 0
        ];
        
        if (file_exists($this->logPath)) {
            $logs = file($this->logPath, FILE_IGNORE_NEW_LINES);
            foreach ($logs as $log) {
                $entry = json_decode($log, true);
                if ($entry) {
                    $stats['total_uploads']++;
                    
                    if ($entry['status'] === 'SUCCESS') {
                        $stats['successful_uploads']++;
                        if (isset($entry['file_info']['file_size'])) {
                            $stats['total_size'] += $entry['file_info']['file_size'];
                        }
                    } elseif ($entry['status'] === 'QUARANTINED') {
                        $stats['quarantined_files']++;
                    }
                }
            }
        }
        
        return $stats;
    }
}
?>
