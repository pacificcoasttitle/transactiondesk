<?php
/**
 * Rate Limiter Class
 * 
 * Implements rate limiting to prevent brute force attacks and API abuse.
 * Uses file-based storage for simplicity in environments without Redis.
 */

class RateLimiter {
    private $storageFile;
    private $cleanupInterval = 3600; // Clean old entries every hour
    
    public function __construct($storageFile = null) {
        $this->storageFile = $storageFile ?: __DIR__ . '/../../logs/rate_limits.json';
        $this->ensureStorageDirectory();
    }
    
    /**
     * Check if request is within rate limit
     * 
     * @param string $identifier Unique identifier (IP, user ID, etc.)
     * @param string $action Action being rate limited
     * @param int $maxRequests Maximum requests allowed
     * @param int $timeWindow Time window in seconds
     * @return bool True if within limit, false if exceeded
     */
    public function checkLimit($identifier, $action, $maxRequests = 100, $timeWindow = 3600) {
        $key = $this->generateKey($identifier, $action);
        $data = $this->loadData();
        $now = time();
        
        // Clean old entries periodically
        if (rand(1, 100) === 1) { // 1% chance to trigger cleanup
            $this->cleanup($data, $now);
        }
        
        // Initialize or get existing entry
        if (!isset($data[$key])) {
            $data[$key] = [
                'requests' => [],
                'first_request' => $now,
                'last_cleanup' => $now
            ];
        }
        
        $entry = &$data[$key];
        
        // Remove requests outside the time window
        $entry['requests'] = array_filter($entry['requests'], function($timestamp) use ($now, $timeWindow) {
            return ($now - $timestamp) < $timeWindow;
        });
        
        // Check if limit exceeded
        if (count($entry['requests']) >= $maxRequests) {
            $this->saveData($data);
            $this->logRateLimitViolation($identifier, $action, $maxRequests, $timeWindow);
            return false;
        }
        
        // Add current request
        $entry['requests'][] = $now;
        $this->saveData($data);
        
        return true;
    }
    
    /**
     * Get current usage for an identifier/action combination
     */
    public function getCurrentUsage($identifier, $action, $timeWindow = 3600) {
        $key = $this->generateKey($identifier, $action);
        $data = $this->loadData();
        $now = time();
        
        if (!isset($data[$key])) {
            return 0;
        }
        
        $entry = $data[$key];
        $recentRequests = array_filter($entry['requests'], function($timestamp) use ($now, $timeWindow) {
            return ($now - $timestamp) < $timeWindow;
        });
        
        return count($recentRequests);
    }
    
    /**
     * Reset rate limit for specific identifier/action
     */
    public function resetLimit($identifier, $action) {
        $key = $this->generateKey($identifier, $action);
        $data = $this->loadData();
        
        if (isset($data[$key])) {
            unset($data[$key]);
            $this->saveData($data);
        }
    }
    
    /**
     * Get time until rate limit resets
     */
    public function getTimeToReset($identifier, $action, $timeWindow = 3600) {
        $key = $this->generateKey($identifier, $action);
        $data = $this->loadData();
        $now = time();
        
        if (!isset($data[$key]) || empty($data[$key]['requests'])) {
            return 0;
        }
        
        $oldestRequest = min($data[$key]['requests']);
        $resetTime = $oldestRequest + $timeWindow;
        
        return max(0, $resetTime - $now);
    }
    
    /**
     * Generate unique key for identifier/action combination
     */
    private function generateKey($identifier, $action) {
        return hash('sha256', $identifier . ':' . $action);
    }
    
    /**
     * Load rate limit data from storage
     */
    private function loadData() {
        if (!file_exists($this->storageFile)) {
            return [];
        }
        
        $content = file_get_contents($this->storageFile);
        $data = json_decode($content, true);
        
        return is_array($data) ? $data : [];
    }
    
    /**
     * Save rate limit data to storage
     */
    private function saveData($data) {
        $json = json_encode($data, JSON_PRETTY_PRINT);
        
        // Use file locking to prevent race conditions
        $fp = fopen($this->storageFile, 'w');
        if ($fp && flock($fp, LOCK_EX)) {
            fwrite($fp, $json);
            fflush($fp);
            flock($fp, LOCK_UN);
            fclose($fp);
        } else {
            error_log("Failed to acquire lock for rate limiter storage file");
        }
    }
    
    /**
     * Clean up old entries
     */
    private function cleanup($data, $now) {
        $maxAge = 86400; // Remove entries older than 24 hours
        
        foreach ($data as $key => $entry) {
            if (isset($entry['last_cleanup']) && ($now - $entry['last_cleanup']) > $maxAge) {
                unset($data[$key]);
            }
        }
        
        $this->saveData($data);
    }
    
    /**
     * Ensure storage directory exists
     */
    private function ensureStorageDirectory() {
        $dir = dirname($this->storageFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }
    
    /**
     * Log rate limit violations
     */
    private function logRateLimitViolation($identifier, $action, $maxRequests, $timeWindow) {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'event' => 'RATE_LIMIT_EXCEEDED',
            'severity' => 'medium',
            'identifier' => $identifier,
            'action' => $action,
            'limit' => $maxRequests,
            'window' => $timeWindow,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];
        
        $logFile = dirname($this->storageFile) . '/security.log';
        file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Get rate limit statistics
     */
    public function getStats() {
        $data = $this->loadData();
        $now = time();
        $stats = [
            'total_keys' => count($data),
            'active_limits' => 0,
            'top_violators' => []
        ];
        
        foreach ($data as $key => $entry) {
            if (!empty($entry['requests'])) {
                $recentRequests = array_filter($entry['requests'], function($timestamp) use ($now) {
                    return ($now - $timestamp) < 3600; // Last hour
                });
                
                if (!empty($recentRequests)) {
                    $stats['active_limits']++;
                }
            }
        }
        
        return $stats;
    }
}
?>
