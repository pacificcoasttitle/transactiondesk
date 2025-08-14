<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * API Rate Limiter Library
 * 
 * Advanced rate limiting system for API endpoints with multiple algorithms,
 * configurable windows, and comprehensive monitoring capabilities.
 * 
 * @package    Transaction Desk
 * @subpackage Security
 * @category   Libraries
 * @author     Security Team
 * @version    1.0.0
 */
class ApiRateLimiter
{
    private $CI;
    private $storageMethod = 'database'; // database, redis, file
    private $algorithm = 'sliding_window'; // fixed_window, sliding_window, token_bucket
    private $defaultLimits = [
        'requests_per_minute' => 60,
        'requests_per_hour' => 1000,
        'requests_per_day' => 10000
    ];
    private $burstAllowance = 10; // Additional requests allowed in burst
    private $logViolations = true;
    
    public function __construct($config = [])
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        
        // Override default settings
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        
        $this->initializeStorage();
    }
    
    /**
     * Check if request is within rate limits
     */
    public function checkLimit($identifier, $endpoint = null, $customLimits = [])
    {
        $limits = array_merge($this->defaultLimits, $customLimits);
        $checkResult = [
            'allowed' => true,
            'remaining' => [],
            'reset_times' => [],
            'retry_after' => 0
        ];
        
        foreach ($limits as $window => $maxRequests) {
            $windowSeconds = $this->getWindowSeconds($window);
            $usage = $this->getUsage($identifier, $endpoint, $windowSeconds);
            
            if ($usage >= $maxRequests) {
                $checkResult['allowed'] = false;
                $checkResult['retry_after'] = max($checkResult['retry_after'], $this->getRetryAfter($identifier, $endpoint, $windowSeconds));
                
                if ($this->logViolations) {
                    $this->logRateLimitViolation($identifier, $endpoint, $window, $usage, $maxRequests);
                }
            }
            
            $checkResult['remaining'][$window] = max(0, $maxRequests - $usage);
            $checkResult['reset_times'][$window] = time() + $windowSeconds;
        }
        
        if ($checkResult['allowed']) {
            $this->recordRequest($identifier, $endpoint);
        }
        
        return $checkResult;
    }
    
    /**
     * Advanced rate limiting with token bucket algorithm
     */
    public function checkTokenBucket($identifier, $endpoint = null, $capacity = 100, $refillRate = 10)
    {
        $bucketKey = $this->getBucketKey($identifier, $endpoint);
        $bucket = $this->getBucket($bucketKey);
        
        $now = microtime(true);
        $timePassed = $now - $bucket['last_refill'];
        
        // Calculate tokens to add
        $tokensToAdd = $timePassed * $refillRate;
        $bucket['tokens'] = min($capacity, $bucket['tokens'] + $tokensToAdd);
        $bucket['last_refill'] = $now;
        
        if ($bucket['tokens'] >= 1) {
            $bucket['tokens']--;
            $this->saveBucket($bucketKey, $bucket);
            
            return [
                'allowed' => true,
                'tokens_remaining' => floor($bucket['tokens']),
                'refill_rate' => $refillRate,
                'capacity' => $capacity
            ];
        } else {
            $retryAfter = (1 - $bucket['tokens']) / $refillRate;
            
            if ($this->logViolations) {
                $this->logRateLimitViolation($identifier, $endpoint, 'token_bucket', 0, 1);
            }
            
            return [
                'allowed' => false,
                'tokens_remaining' => 0,
                'retry_after' => $retryAfter,
                'refill_rate' => $refillRate,
                'capacity' => $capacity
            ];
        }
    }
    
    /**
     * Sliding window rate limiting
     */
    public function checkSlidingWindow($identifier, $endpoint = null, $windowSize = 3600, $maxRequests = 1000)
    {
        $requests = $this->getRequestHistory($identifier, $endpoint, $windowSize);
        $currentTime = time();
        
        // Filter requests within the sliding window
        $recentRequests = array_filter($requests, function($timestamp) use ($currentTime, $windowSize) {
            return ($currentTime - $timestamp) < $windowSize;
        });
        
        if (count($recentRequests) >= $maxRequests) {
            $oldestRequest = min($recentRequests);
            $retryAfter = $windowSize - ($currentTime - $oldestRequest);
            
            if ($this->logViolations) {
                $this->logRateLimitViolation($identifier, $endpoint, 'sliding_window', count($recentRequests), $maxRequests);
            }
            
            return [
                'allowed' => false,
                'current_usage' => count($recentRequests),
                'max_requests' => $maxRequests,
                'window_size' => $windowSize,
                'retry_after' => $retryAfter
            ];
        }
        
        // Record this request
        $this->recordRequestHistory($identifier, $endpoint, $currentTime);
        
        return [
            'allowed' => true,
            'current_usage' => count($recentRequests) + 1,
            'max_requests' => $maxRequests,
            'window_size' => $windowSize,
            'remaining' => $maxRequests - count($recentRequests) - 1
        ];
    }
    
    /**
     * Adaptive rate limiting based on user behavior
     */
    public function checkAdaptive($identifier, $endpoint = null)
    {
        $userProfile = $this->getUserProfile($identifier);
        $adaptiveLimits = $this->calculateAdaptiveLimits($userProfile);
        
        return $this->checkLimit($identifier, $endpoint, $adaptiveLimits);
    }
    
    /**
     * IP-based rate limiting with geographic considerations
     */
    public function checkIPRateLimit($ipAddress, $endpoint = null)
    {
        $ipInfo = $this->getIPInfo($ipAddress);
        $ipLimits = $this->getIPBasedLimits($ipInfo);
        
        // Check for suspicious IP patterns
        if ($this->isSuspiciousIP($ipAddress)) {
            $ipLimits = $this->applySuspiciousIPLimits($ipLimits);
        }
        
        return $this->checkLimit('ip:' . $ipAddress, $endpoint, $ipLimits);
    }
    
    /**
     * User-based rate limiting with role considerations
     */
    public function checkUserRateLimit($userId, $endpoint = null)
    {
        $userRole = $this->getUserRole($userId);
        $roleLimits = $this->getRoleBasedLimits($userRole);
        
        // Premium users get higher limits
        if ($this->isPremiumUser($userId)) {
            $roleLimits = $this->applyPremiumMultiplier($roleLimits);
        }
        
        return $this->checkLimit('user:' . $userId, $endpoint, $roleLimits);
    }
    
    /**
     * Endpoint-specific rate limiting
     */
    public function checkEndpointLimit($identifier, $endpoint)
    {
        $endpointLimits = $this->getEndpointLimits($endpoint);
        return $this->checkLimit($identifier, $endpoint, $endpointLimits);
    }
    
    /**
     * Whitelist management
     */
    public function isWhitelisted($identifier)
    {
        $this->CI->db->select('id');
        $this->CI->db->from('rate_limit_whitelist');
        $this->CI->db->where('identifier', $identifier);
        $this->CI->db->where('is_active', 1);
        $this->CI->db->where('expires_at >', date('Y-m-d H:i:s'));
        
        return $this->CI->db->get()->num_rows() > 0;
    }
    
    /**
     * Blacklist management
     */
    public function isBlacklisted($identifier)
    {
        $this->CI->db->select('id');
        $this->CI->db->from('rate_limit_blacklist');
        $this->CI->db->where('identifier', $identifier);
        $this->CI->db->where('is_active', 1);
        $this->CI->db->where('expires_at >', date('Y-m-d H:i:s'));
        
        return $this->CI->db->get()->num_rows() > 0;
    }
    
    /**
     * Add to whitelist
     */
    public function addToWhitelist($identifier, $reason, $expiresAt = null)
    {
        $data = [
            'identifier' => $identifier,
            'reason' => $reason,
            'expires_at' => $expiresAt ?: date('Y-m-d H:i:s', strtotime('+1 year')),
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->CI->db->insert('rate_limit_whitelist', $data);
    }
    
    /**
     * Add to blacklist
     */
    public function addToBlacklist($identifier, $reason, $expiresAt = null)
    {
        $data = [
            'identifier' => $identifier,
            'reason' => $reason,
            'expires_at' => $expiresAt ?: date('Y-m-d H:i:s', strtotime('+1 day')),
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->CI->db->insert('rate_limit_blacklist', $data);
    }
    
    /**
     * Get current usage for identifier
     */
    private function getUsage($identifier, $endpoint, $windowSeconds)
    {
        $key = $this->getCacheKey($identifier, $endpoint, $windowSeconds);
        
        switch ($this->storageMethod) {
            case 'redis':
                return $this->getUsageFromRedis($key);
            case 'database':
                return $this->getUsageFromDatabase($identifier, $endpoint, $windowSeconds);
            case 'file':
            default:
                return $this->getUsageFromFile($key);
        }
    }
    
    /**
     * Record a request
     */
    private function recordRequest($identifier, $endpoint)
    {
        $data = [
            'identifier' => $identifier,
            'endpoint' => $endpoint,
            'request_time' => date('Y-m-d H:i:s'),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        $this->CI->db->insert('rate_limit_requests', $data);
        
        // Also update cache for faster lookups
        $this->updateRequestCache($identifier, $endpoint);
    }
    
    /**
     * Get usage from database
     */
    private function getUsageFromDatabase($identifier, $endpoint, $windowSeconds)
    {
        $this->CI->db->select('COUNT(*) as count');
        $this->CI->db->from('rate_limit_requests');
        $this->CI->db->where('identifier', $identifier);
        
        if ($endpoint) {
            $this->CI->db->where('endpoint', $endpoint);
        }
        
        $this->CI->db->where('request_time >', date('Y-m-d H:i:s', time() - $windowSeconds));
        
        $result = $this->CI->db->get()->row();
        return $result ? (int)$result->count : 0;
    }
    
    /**
     * Calculate adaptive limits based on user behavior
     */
    private function calculateAdaptiveLimits($userProfile)
    {
        $baseLimits = $this->defaultLimits;
        
        // Adjust based on user reputation
        $reputationMultiplier = max(0.1, min(2.0, $userProfile['reputation'] / 50));
        
        // Adjust based on historical behavior
        if ($userProfile['violations'] > 5) {
            $reputationMultiplier *= 0.5; // Reduce limits for repeat offenders
        }
        
        if ($userProfile['days_active'] > 30) {
            $reputationMultiplier *= 1.2; // Increase limits for long-term users
        }
        
        $adaptiveLimits = [];
        foreach ($baseLimits as $window => $limit) {
            $adaptiveLimits[$window] = (int)($limit * $reputationMultiplier);
        }
        
        return $adaptiveLimits;
    }
    
    /**
     * Get IP-based limits
     */
    private function getIPBasedLimits($ipInfo)
    {
        $baseLimits = $this->defaultLimits;
        
        // Reduce limits for high-risk countries
        $highRiskCountries = ['CN', 'RU', 'KP', 'IR'];
        if (in_array($ipInfo['country_code'], $highRiskCountries)) {
            foreach ($baseLimits as $window => $limit) {
                $baseLimits[$window] = (int)($limit * 0.5);
            }
        }
        
        // Reduce limits for known VPN/Proxy IPs
        if ($ipInfo['is_proxy'] || $ipInfo['is_vpn']) {
            foreach ($baseLimits as $window => $limit) {
                $baseLimits[$window] = (int)($limit * 0.7);
            }
        }
        
        return $baseLimits;
    }
    
    /**
     * Get role-based limits
     */
    private function getRoleBasedLimits($role)
    {
        $roleLimits = [
            'admin' => [
                'requests_per_minute' => 300,
                'requests_per_hour' => 5000,
                'requests_per_day' => 50000
            ],
            'premium' => [
                'requests_per_minute' => 150,
                'requests_per_hour' => 2500,
                'requests_per_day' => 25000
            ],
            'basic' => $this->defaultLimits,
            'guest' => [
                'requests_per_minute' => 30,
                'requests_per_hour' => 500,
                'requests_per_day' => 2000
            ]
        ];
        
        return $roleLimits[$role] ?? $this->defaultLimits;
    }
    
    /**
     * Get endpoint-specific limits
     */
    private function getEndpointLimits($endpoint)
    {
        $endpointLimits = [
            '/api/auth/login' => [
                'requests_per_minute' => 5,
                'requests_per_hour' => 50,
                'requests_per_day' => 200
            ],
            '/api/search' => [
                'requests_per_minute' => 100,
                'requests_per_hour' => 1500,
                'requests_per_day' => 15000
            ],
            '/api/upload' => [
                'requests_per_minute' => 10,
                'requests_per_hour' => 100,
                'requests_per_day' => 500
            ]
        ];
        
        return $endpointLimits[$endpoint] ?? $this->defaultLimits;
    }
    
    /**
     * Initialize storage tables
     */
    private function initializeStorage()
    {
        // Create rate limit requests table
        $this->CI->db->query("
            CREATE TABLE IF NOT EXISTS `rate_limit_requests` (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `identifier` varchar(255) NOT NULL,
                `endpoint` varchar(255) DEFAULT NULL,
                `request_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `ip_address` varchar(45) DEFAULT NULL,
                `user_agent` text DEFAULT NULL,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_identifier_time` (`identifier`, `request_time`),
                KEY `idx_endpoint_time` (`endpoint`, `request_time`),
                KEY `idx_cleanup` (`request_time`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        
        // Create whitelist table
        $this->CI->db->query("
            CREATE TABLE IF NOT EXISTS `rate_limit_whitelist` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `identifier` varchar(255) NOT NULL,
                `reason` text DEFAULT NULL,
                `expires_at` timestamp NULL DEFAULT NULL,
                `is_active` tinyint(1) DEFAULT 1,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_identifier_active` (`identifier`, `is_active`, `expires_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        
        // Create blacklist table
        $this->CI->db->query("
            CREATE TABLE IF NOT EXISTS `rate_limit_blacklist` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `identifier` varchar(255) NOT NULL,
                `reason` text DEFAULT NULL,
                `expires_at` timestamp NULL DEFAULT NULL,
                `is_active` tinyint(1) DEFAULT 1,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_identifier_active` (`identifier`, `is_active`, `expires_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        
        // Create violations log table
        $this->CI->db->query("
            CREATE TABLE IF NOT EXISTS `rate_limit_violations` (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `identifier` varchar(255) NOT NULL,
                `endpoint` varchar(255) DEFAULT NULL,
                `window_type` varchar(50) NOT NULL,
                `current_usage` int(11) NOT NULL,
                `max_allowed` int(11) NOT NULL,
                `ip_address` varchar(45) DEFAULT NULL,
                `user_agent` text DEFAULT NULL,
                `violation_time` timestamp DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_identifier_time` (`identifier`, `violation_time`),
                KEY `idx_cleanup` (`violation_time`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }
    
    /**
     * Helper methods
     */
    private function getWindowSeconds($window)
    {
        $windows = [
            'requests_per_minute' => 60,
            'requests_per_hour' => 3600,
            'requests_per_day' => 86400
        ];
        
        return $windows[$window] ?? 3600;
    }
    
    private function getCacheKey($identifier, $endpoint, $windowSeconds)
    {
        return 'rate_limit:' . md5($identifier . ':' . $endpoint . ':' . $windowSeconds);
    }
    
    private function getBucketKey($identifier, $endpoint)
    {
        return 'token_bucket:' . md5($identifier . ':' . $endpoint);
    }
    
    private function getBucket($key)
    {
        // Default bucket state
        return [
            'tokens' => 100,
            'last_refill' => microtime(true)
        ];
    }
    
    private function saveBucket($key, $bucket)
    {
        // Save bucket state (implement based on storage method)
    }
    
    private function getRetryAfter($identifier, $endpoint, $windowSeconds)
    {
        // Calculate when the rate limit resets
        return $windowSeconds;
    }
    
    private function logRateLimitViolation($identifier, $endpoint, $window, $usage, $limit)
    {
        $data = [
            'identifier' => $identifier,
            'endpoint' => $endpoint,
            'window_type' => $window,
            'current_usage' => $usage,
            'max_allowed' => $limit,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'violation_time' => date('Y-m-d H:i:s')
        ];
        
        $this->CI->db->insert('rate_limit_violations', $data);
    }
    
    // Placeholder methods for advanced features
    private function getUserProfile($identifier) { return ['reputation' => 50, 'violations' => 0, 'days_active' => 1]; }
    private function getIPInfo($ip) { return ['country_code' => 'US', 'is_proxy' => false, 'is_vpn' => false]; }
    private function isSuspiciousIP($ip) { return false; }
    private function getUserRole($userId) { return 'basic'; }
    private function isPremiumUser($userId) { return false; }
    private function applySuspiciousIPLimits($limits) { return $limits; }
    private function applyPremiumMultiplier($limits) { return $limits; }
    private function getRequestHistory($identifier, $endpoint, $windowSize) { return []; }
    private function recordRequestHistory($identifier, $endpoint, $timestamp) { }
    private function updateRequestCache($identifier, $endpoint) { }
}
?>
