<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Security Monitor Library
 * 
 * Comprehensive security monitoring, alerting, and incident response system.
 * Tracks security events, detects anomalies, and provides real-time threat detection.
 * 
 * @package    Transaction Desk
 * @subpackage Security
 * @category   Libraries
 * @author     Security Team
 * @version    1.0.0
 */
class SecurityMonitor
{
    private $CI;
    private $alertThresholds = [
        'failed_logins' => 5,
        'rate_limit_violations' => 10,
        'suspicious_activities' => 3,
        'file_upload_rejections' => 5
    ];
    private $alertChannels = ['email', 'log', 'database'];
    private $realTimeMonitoring = true;
    private $logLevel = 'info'; // debug, info, warning, error, critical
    
    public function __construct($config = [])
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->library('email');
        
        // Override default settings
        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        
        $this->initializeDatabase();
        
        if ($this->realTimeMonitoring) {
            $this->startRealTimeMonitoring();
        }
    }
    
    /**
     * Log security event
     */
    public function logEvent($eventType, $severity, $details = [], $userId = null)
    {
        $event = [
            'event_type' => $eventType,
            'severity' => $severity,
            'details' => is_array($details) ? json_encode($details) : $details,
            'user_id' => $userId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'request_uri' => $_SERVER['REQUEST_URI'] ?? 'unknown',
            'session_id' => session_id(),
            'timestamp' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Store in database
        $this->CI->db->insert('security_events', $event);
        $eventId = $this->CI->db->insert_id();
        
        // Write to log file
        $this->writeToLogFile($event);
        
        // Check for alert conditions
        $this->checkAlertConditions($eventType, $severity, $event);
        
        // Real-time threat detection
        $this->analyzeEvent($event);
        
        return $eventId;
    }
    
    /**
     * Detect authentication anomalies
     */
    public function detectAuthAnomalies($userId, $authEvent)
    {
        $anomalies = [];
        
        // Check for unusual login times
        $unusualTime = $this->detectUnusualLoginTime($userId);
        if ($unusualTime) {
            $anomalies[] = 'unusual_login_time';
        }
        
        // Check for geolocation anomalies
        $geoAnomaly = $this->detectGeolocationAnomaly($userId);
        if ($geoAnomaly) {
            $anomalies[] = 'geolocation_anomaly';
        }
        
        // Check for device fingerprint changes
        $deviceAnomaly = $this->detectDeviceAnomaly($userId);
        if ($deviceAnomaly) {
            $anomalies[] = 'device_anomaly';
        }
        
        // Check for rapid successive attempts
        $rapidAttempts = $this->detectRapidLoginAttempts($userId);
        if ($rapidAttempts) {
            $anomalies[] = 'rapid_attempts';
        }
        
        if (!empty($anomalies)) {
            $this->logEvent('AUTH_ANOMALY_DETECTED', 'warning', [
                'user_id' => $userId,
                'anomalies' => $anomalies,
                'auth_event' => $authEvent
            ], $userId);
        }
        
        return $anomalies;
    }
    
    /**
     * Monitor file upload activities
     */
    public function monitorFileUpload($uploadResult, $userId = null)
    {
        if (!$uploadResult['success']) {
            $this->logEvent('FILE_UPLOAD_REJECTED', 'warning', [
                'errors' => $uploadResult['errors'],
                'security_checks' => $uploadResult['security_checks']
            ], $userId);
            
            // Check for repeated upload failures
            $this->checkRepeatedUploadFailures($userId);
        } else {
            $this->logEvent('FILE_UPLOAD_SUCCESS', 'info', [
                'file_info' => $uploadResult['file_info']
            ], $userId);
        }
    }
    
    /**
     * Monitor API usage patterns
     */
    public function monitorAPIUsage($endpoint, $userId = null, $responseTime = null, $statusCode = null)
    {
        $apiEvent = [
            'endpoint' => $endpoint,
            'user_id' => $userId,
            'response_time' => $responseTime,
            'status_code' => $statusCode,
            'timestamp' => time()
        ];
        
        // Detect API abuse patterns
        $abusePatterns = $this->detectAPIAbuse($endpoint, $userId);
        
        if (!empty($abusePatterns)) {
            $this->logEvent('API_ABUSE_DETECTED', 'warning', [
                'patterns' => $abusePatterns,
                'api_event' => $apiEvent
            ], $userId);
        }
        
        // Store API usage data
        $this->storeAPIUsage($apiEvent);
    }
    
    /**
     * Detect SQL injection attempts
     */
    public function detectSQLInjection($input, $context = 'unknown')
    {
        $sqlPatterns = [
            '/\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|EXECUTE)\b/i',
            '/\b(UNION|OR|AND)\s+\d+\s*=\s*\d+/i',
            '/\'\s*(OR|AND)\s*\'\w*\'\s*=\s*\'\w*\'/i',
            '/--[\s\S]*?$/m',
            '/\/\*[\s\S]*?\*\//',
            '/\b(BENCHMARK|SLEEP|WAITFOR)\s*\(/i',
            '/\b(LOAD_FILE|INTO\s+OUTFILE|INTO\s+DUMPFILE)\b/i'
        ];
        
        foreach ($sqlPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                $this->logEvent('SQL_INJECTION_ATTEMPT', 'high', [
                    'input' => substr($input, 0, 500), // Truncate for logging
                    'context' => $context,
                    'pattern_matched' => $pattern
                ]);
                
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Detect XSS attempts
     */
    public function detectXSS($input, $context = 'unknown')
    {
        $xssPatterns = [
            '/<script[^>]*>.*?<\/script>/is',
            '/javascript:/i',
            '/vbscript:/i',
            '/on\w+\s*=/i',
            '/<iframe[^>]*>.*?<\/iframe>/is',
            '/<object[^>]*>.*?<\/object>/is',
            '/<embed[^>]*>/i',
            '/expression\s*\(/i'
        ];
        
        foreach ($xssPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                $this->logEvent('XSS_ATTEMPT', 'high', [
                    'input' => substr($input, 0, 500),
                    'context' => $context,
                    'pattern_matched' => $pattern
                ]);
                
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Monitor session hijacking attempts
     */
    public function monitorSession($sessionId, $userId = null)
    {
        $sessionData = [
            'session_id' => $sessionId,
            'user_id' => $userId,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'timestamp' => time()
        ];
        
        // Check for session anomalies
        $anomalies = $this->detectSessionAnomalies($sessionId, $userId);
        
        if (!empty($anomalies)) {
            $this->logEvent('SESSION_ANOMALY', 'warning', [
                'anomalies' => $anomalies,
                'session_data' => $sessionData
            ], $userId);
        }
        
        // Store session activity
        $this->storeSessionActivity($sessionData);
    }
    
    /**
     * Generate security dashboard data
     */
    public function getDashboardData($timeRange = '24h')
    {
        $dashboard = [
            'summary' => $this->getSecuritySummary($timeRange),
            'events_by_type' => $this->getEventsByType($timeRange),
            'events_by_severity' => $this->getEventsBySeverity($timeRange),
            'top_attackers' => $this->getTopAttackers($timeRange),
            'attack_timeline' => $this->getAttackTimeline($timeRange),
            'threat_intelligence' => $this->getThreatIntelligence($timeRange)
        ];
        
        return $dashboard;
    }
    
    /**
     * Get security summary
     */
    private function getSecuritySummary($timeRange)
    {
        $since = $this->getTimeRangeStart($timeRange);
        
        $this->CI->db->select('
            COUNT(*) as total_events,
            SUM(CASE WHEN severity = "critical" THEN 1 ELSE 0 END) as critical_events,
            SUM(CASE WHEN severity = "high" THEN 1 ELSE 0 END) as high_events,
            SUM(CASE WHEN severity = "warning" THEN 1 ELSE 0 END) as warning_events,
            COUNT(DISTINCT ip_address) as unique_ips,
            COUNT(DISTINCT user_id) as affected_users
        ');
        $this->CI->db->from('security_events');
        $this->CI->db->where('timestamp >=', $since);
        
        return $this->CI->db->get()->row_array();
    }
    
    /**
     * Get events by type
     */
    private function getEventsByType($timeRange)
    {
        $since = $this->getTimeRangeStart($timeRange);
        
        $this->CI->db->select('event_type, COUNT(*) as count');
        $this->CI->db->from('security_events');
        $this->CI->db->where('timestamp >=', $since);
        $this->CI->db->group_by('event_type');
        $this->CI->db->order_by('count', 'DESC');
        $this->CI->db->limit(10);
        
        return $this->CI->db->get()->result_array();
    }
    
    /**
     * Get top attackers
     */
    private function getTopAttackers($timeRange)
    {
        $since = $this->getTimeRangeStart($timeRange);
        
        $this->CI->db->select('
            ip_address,
            COUNT(*) as attack_count,
            COUNT(DISTINCT event_type) as attack_types,
            MAX(timestamp) as last_attack
        ');
        $this->CI->db->from('security_events');
        $this->CI->db->where('timestamp >=', $since);
        $this->CI->db->where_in('severity', ['high', 'critical']);
        $this->CI->db->group_by('ip_address');
        $this->CI->db->order_by('attack_count', 'DESC');
        $this->CI->db->limit(10);
        
        return $this->CI->db->get()->result_array();
    }
    
    /**
     * Real-time threat analysis
     */
    private function analyzeEvent($event)
    {
        $threatScore = $this->calculateThreatScore($event);
        
        if ($threatScore >= 80) {
            $this->triggerHighThreatAlert($event, $threatScore);
        } elseif ($threatScore >= 60) {
            $this->triggerMediumThreatAlert($event, $threatScore);
        }
        
        // Update threat intelligence
        $this->updateThreatIntelligence($event, $threatScore);
    }
    
    /**
     * Calculate threat score for an event
     */
    private function calculateThreatScore($event)
    {
        $score = 0;
        
        // Base score by severity
        $severityScores = [
            'info' => 10,
            'warning' => 30,
            'high' => 60,
            'critical' => 90
        ];
        $score += $severityScores[$event['severity']] ?? 0;
        
        // Increase score for repeat offenders
        $repeatOffenses = $this->getRecentEventCount($event['ip_address'], '1h');
        $score += min(30, $repeatOffenses * 5);
        
        // Increase score for known malicious IPs
        if ($this->isKnownMaliciousIP($event['ip_address'])) {
            $score += 40;
        }
        
        // Increase score for automated attacks
        if ($this->detectAutomatedAttack($event)) {
            $score += 25;
        }
        
        return min(100, $score);
    }
    
    /**
     * Check alert conditions
     */
    private function checkAlertConditions($eventType, $severity, $event)
    {
        $shouldAlert = false;
        $alertReason = '';
        
        // Check threshold-based alerts
        foreach ($this->alertThresholds as $type => $threshold) {
            $count = $this->getRecentEventCount($event['ip_address'], '1h', $type);
            if ($count >= $threshold) {
                $shouldAlert = true;
                $alertReason = "Threshold exceeded for {$type}: {$count} events";
                break;
            }
        }
        
        // Check severity-based alerts
        if (in_array($severity, ['high', 'critical'])) {
            $shouldAlert = true;
            $alertReason = "High severity event: {$eventType}";
        }
        
        if ($shouldAlert) {
            $this->sendAlert($event, $alertReason);
        }
    }
    
    /**
     * Send security alert
     */
    private function sendAlert($event, $reason)
    {
        $alertData = [
            'event' => $event,
            'reason' => $reason,
            'timestamp' => date('Y-m-d H:i:s'),
            'alert_id' => uniqid('alert_')
        ];
        
        foreach ($this->alertChannels as $channel) {
            switch ($channel) {
                case 'email':
                    $this->sendEmailAlert($alertData);
                    break;
                case 'log':
                    $this->writeAlertToLog($alertData);
                    break;
                case 'database':
                    $this->storeAlert($alertData);
                    break;
            }
        }
    }
    
    /**
     * Send email alert
     */
    private function sendEmailAlert($alertData)
    {
        $subject = 'Security Alert: ' . $alertData['event']['event_type'];
        $message = "Security Alert Triggered\n\n";
        $message .= "Event Type: " . $alertData['event']['event_type'] . "\n";
        $message .= "Severity: " . $alertData['event']['severity'] . "\n";
        $message .= "Reason: " . $alertData['reason'] . "\n";
        $message .= "IP Address: " . $alertData['event']['ip_address'] . "\n";
        $message .= "Timestamp: " . $alertData['timestamp'] . "\n";
        $message .= "Details: " . $alertData['event']['details'] . "\n";
        
        $adminEmail = getenv('SECURITY_ALERT_EMAIL') ?: 'admin@pacificcoasttitle.com';
        
        $this->CI->email->from('security@pacificcoasttitle.com', 'Security Monitor');
        $this->CI->email->to($adminEmail);
        $this->CI->email->subject($subject);
        $this->CI->email->message($message);
        $this->CI->email->send();
    }
    
    /**
     * Initialize database tables
     */
    private function initializeDatabase()
    {
        // Security events table
        $this->CI->db->query("
            CREATE TABLE IF NOT EXISTS `security_events` (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `event_type` varchar(100) NOT NULL,
                `severity` enum('info','warning','high','critical') NOT NULL,
                `details` text DEFAULT NULL,
                `user_id` int(11) DEFAULT NULL,
                `ip_address` varchar(45) DEFAULT NULL,
                `user_agent` text DEFAULT NULL,
                `request_uri` varchar(500) DEFAULT NULL,
                `session_id` varchar(128) DEFAULT NULL,
                `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_event_type_time` (`event_type`, `timestamp`),
                KEY `idx_severity_time` (`severity`, `timestamp`),
                KEY `idx_ip_time` (`ip_address`, `timestamp`),
                KEY `idx_user_time` (`user_id`, `timestamp`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        
        // Security alerts table
        $this->CI->db->query("
            CREATE TABLE IF NOT EXISTS `security_alerts` (
                `id` bigint(20) NOT NULL AUTO_INCREMENT,
                `alert_id` varchar(50) NOT NULL,
                `event_id` bigint(20) DEFAULT NULL,
                `alert_type` varchar(100) NOT NULL,
                `severity` enum('low','medium','high','critical') NOT NULL,
                `title` varchar(255) NOT NULL,
                `description` text DEFAULT NULL,
                `status` enum('open','investigating','resolved','false_positive') DEFAULT 'open',
                `assigned_to` int(11) DEFAULT NULL,
                `resolution_notes` text DEFAULT NULL,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `resolved_at` timestamp NULL DEFAULT NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `alert_id` (`alert_id`),
                KEY `idx_status_severity` (`status`, `severity`),
                KEY `idx_created_at` (`created_at`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        
        // Threat intelligence table
        $this->CI->db->query("
            CREATE TABLE IF NOT EXISTS `threat_intelligence` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `ip_address` varchar(45) NOT NULL,
                `threat_score` int(11) DEFAULT 0,
                `country_code` varchar(2) DEFAULT NULL,
                `is_malicious` tinyint(1) DEFAULT 0,
                `is_bot` tinyint(1) DEFAULT 0,
                `is_proxy` tinyint(1) DEFAULT 0,
                `attack_types` text DEFAULT NULL,
                `first_seen` timestamp DEFAULT CURRENT_TIMESTAMP,
                `last_seen` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `event_count` int(11) DEFAULT 0,
                PRIMARY KEY (`id`),
                UNIQUE KEY `ip_address` (`ip_address`),
                KEY `idx_threat_score` (`threat_score`),
                KEY `idx_last_seen` (`last_seen`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }
    
    /**
     * Helper methods
     */
    private function writeToLogFile($event)
    {
        $logFile = APPPATH . '../logs/security_monitor.log';
        $logEntry = date('Y-m-d H:i:s') . ' [' . strtoupper($event['severity']) . '] ' . 
                   $event['event_type'] . ' - ' . $event['details'] . "\n";
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    private function getTimeRangeStart($timeRange)
    {
        $intervals = [
            '1h' => '-1 hour',
            '24h' => '-24 hours',
            '7d' => '-7 days',
            '30d' => '-30 days'
        ];
        
        $interval = $intervals[$timeRange] ?? '-24 hours';
        return date('Y-m-d H:i:s', strtotime($interval));
    }
    
    private function getRecentEventCount($ipAddress, $timeRange, $eventType = null)
    {
        $since = $this->getTimeRangeStart($timeRange);
        
        $this->CI->db->select('COUNT(*) as count');
        $this->CI->db->from('security_events');
        $this->CI->db->where('ip_address', $ipAddress);
        $this->CI->db->where('timestamp >=', $since);
        
        if ($eventType) {
            $this->CI->db->where('event_type', $eventType);
        }
        
        $result = $this->CI->db->get()->row();
        return $result ? (int)$result->count : 0;
    }
    
    // Placeholder methods for advanced features
    private function startRealTimeMonitoring() { }
    private function detectUnusualLoginTime($userId) { return false; }
    private function detectGeolocationAnomaly($userId) { return false; }
    private function detectDeviceAnomaly($userId) { return false; }
    private function detectRapidLoginAttempts($userId) { return false; }
    private function checkRepeatedUploadFailures($userId) { }
    private function detectAPIAbuse($endpoint, $userId) { return []; }
    private function storeAPIUsage($apiEvent) { }
    private function detectSessionAnomalies($sessionId, $userId) { return []; }
    private function storeSessionActivity($sessionData) { }
    private function getAttackTimeline($timeRange) { return []; }
    private function getThreatIntelligence($timeRange) { return []; }
    private function triggerHighThreatAlert($event, $score) { }
    private function triggerMediumThreatAlert($event, $score) { }
    private function updateThreatIntelligence($event, $score) { }
    private function isKnownMaliciousIP($ip) { return false; }
    private function detectAutomatedAttack($event) { return false; }
    private function writeAlertToLog($alertData) { }
    private function storeAlert($alertData) { }
}
?>
