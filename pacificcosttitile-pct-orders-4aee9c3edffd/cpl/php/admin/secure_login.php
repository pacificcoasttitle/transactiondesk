<?php
/**
 * Secure CPL Admin Login
 * 
 * SECURITY IMPROVEMENTS:
 * - Replaced MD5 with bcrypt password hashing
 * - Added CSRF protection
 * - Implemented secure session handling
 * - Added input validation and sanitization
 * - Implemented rate limiting
 * - Added security logging
 */

session_start();

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Rate limiting check
require_once('security/rate_limiter.php');
$rateLimiter = new RateLimiter();
$clientIP = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

if (!$rateLimiter->checkLimit($clientIP, 'login', 5, 900)) { // 5 attempts per 15 minutes
    http_response_code(429);
    exit('<div class="flo-notification alert-error">Too many login attempts. Please try again in 15 minutes.</div>');
}

// CSRF Protection
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // CSRF token validation
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        logSecurityEvent('CSRF_VIOLATION', 'high', 'Invalid CSRF token in login attempt', $clientIP);
        exit('<div class="flo-notification alert-error">Security token mismatch. Please refresh and try again.</div>');
    }
    
    // Input validation and sanitization
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        logSecurityEvent('LOGIN_MISSING_FIELDS', 'medium', 'Missing username or password', $clientIP);
        exit('<div class="flo-notification alert-error">Username and password are required.</div>');
    }
    
    $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING);
    $password = $_POST['password']; // Don't sanitize passwords
    
    // Enhanced username validation
    if (!preg_match("/^[A-Za-z0-9_]{3,20}$/", $username)) {
        logSecurityEvent('LOGIN_INVALID_USERNAME', 'medium', "Invalid username format: {$username}", $clientIP);
        exit('<div class="flo-notification alert-error">Invalid username format. Only letters, numbers, and underscores allowed (3-20 characters).</div>');
    }
    
    // Password length check
    if (strlen($password) < 8 || strlen($password) > 128) {
        logSecurityEvent('LOGIN_INVALID_PASSWORD_LENGTH', 'medium', "Password length violation for user: {$username}", $clientIP);
        exit('<div class="flo-notification alert-error">Password must be between 8 and 128 characters.</div>');
    }
    
    require('users_secure.php');
    
    if (array_key_exists($username, $users)) {
        $userData = $users[$username];
        
        // Check if account is locked
        if (isset($userData['locked_until']) && time() < $userData['locked_until']) {
            $lockRemaining = ceil(($userData['locked_until'] - time()) / 60);
            logSecurityEvent('LOGIN_ACCOUNT_LOCKED', 'high', "Login attempt on locked account: {$username}", $clientIP);
            exit('<div class="flo-notification alert-error">Account locked. Try again in ' . $lockRemaining . ' minutes.</div>');
        }
        
        // Verify password using secure bcrypt
        if (password_verify($password, $userData['password_hash'])) {
            // Reset failed attempts on successful login
            $users[$username]['failed_attempts'] = 0;
            $users[$username]['last_attempt'] = time();
            saveUsers($users);
            
            // Generate secure session
            session_regenerate_id(true);
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['login_time'] = time();
            $_SESSION['session_token'] = bin2hex(random_bytes(32));
            $_SESSION['last_activity'] = time();
            
            // Set secure session cookie parameters
            $sessionParams = session_get_cookie_params();
            setcookie(session_name(), session_id(), [
                'expires' => time() + 3600, // 1 hour
                'path' => $sessionParams['path'],
                'domain' => $sessionParams['domain'],
                'secure' => isset($_SERVER['HTTPS']), // Only over HTTPS
                'httponly' => true, // Prevent XSS
                'samesite' => 'Strict' // CSRF protection
            ]);
            
            logSecurityEvent('LOGIN_SUCCESS', 'info', "Successful login for user: {$username}", $clientIP);
            echo('<div class="flo-notification alert-success">Login Successful. Redirecting...</div>');
            echo('<script>setTimeout(function(){ window.location.href = "dashboard.php"; }, 1000);</script>');
            exit;
        } else {
            // Track failed login attempts
            $users[$username]['failed_attempts'] = ($users[$username]['failed_attempts'] ?? 0) + 1;
            $users[$username]['last_attempt'] = time();
            
            // Lock account after 5 failed attempts
            if ($users[$username]['failed_attempts'] >= 5) {
                $users[$username]['locked_until'] = time() + 900; // 15 minutes
                logSecurityEvent('ACCOUNT_LOCKED', 'high', "Account locked due to failed attempts: {$username}", $clientIP);
                saveUsers($users);
                exit('<div class="flo-notification alert-error">Account locked due to multiple failed attempts. Try again in 15 minutes.</div>');
            }
            
            saveUsers($users);
            logSecurityEvent('LOGIN_FAILED', 'medium', "Failed login attempt for user: {$username}", $clientIP);
            exit('<div class="flo-notification alert-error">Invalid username or password. Attempts remaining: ' . (5 - $users[$username]['failed_attempts']) . '</div>');
        }
    } else {
        logSecurityEvent('LOGIN_USER_NOT_FOUND', 'medium', "Login attempt for non-existent user: {$username}", $clientIP);
        exit('<div class="flo-notification alert-error">Invalid username or password.</div>');
    }
}

/**
 * Security logging function
 */
function logSecurityEvent($event, $severity, $message, $ip) {
    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'event' => $event,
        'severity' => $severity,
        'message' => $message,
        'ip_address' => $ip,
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ];
    
    $logFile = __DIR__ . '/../../logs/security.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
}
?>
<!DOCTYPE html>
<html lang="en"> 
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-Content-Type-Options" content="nosniff">
        <meta http-equiv="X-Frame-Options" content="DENY">
        <meta http-equiv="X-XSS-Protection" content="1; mode=block">
        <title>Secure Admin Login</title>
        <link rel="stylesheet" href="../css/login.css">
        <link rel="stylesheet" href="../css/font-awesome.min.css">
        <script src="../js/jquery-1.9.1.min.js"></script>
        <script src="../js/plugins.js"></script>
        <script src="../js/secure_login.js"></script>        
    </head>
    <body class="smartbg">
        <form method="post" action="secure_login.php" class="smartLogin" id="loginfm">
        <div class="smart-container">
            <div class="frm-header">
            	<h4><i class="fa fa-lock"></i> Secure Admin Login </h4>
            </div>
            <div class="frm-body">
                <!-- CSRF Token -->
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                
                <div class="elem-group colm colm6">
                    <label class="field">
                        <input type="text" 
                               name="username" 
                               id="username" 
                               class="flo-input" 
                               placeholder="Username"
                               pattern="[A-Za-z0-9_]{3,20}"
                               title="Username must be 3-20 characters, letters, numbers, and underscores only"
                               required
                               autocomplete="username">
                    </label>                            
                </div>
                <div class="elem-group colm colm6">
                    <label class="field">
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="flo-input" 
                               placeholder="Password"
                               minlength="8"
                               maxlength="128"
                               required
                               autocomplete="current-password">
                    </label>                            
                </div>
                <div class="response"></div>
            </div>
            <div class="frm-footer">
                <button type="submit" class="flo-button">Secure Login</button>
                <small class="security-notice">
                    🔒 This is a secure login. All activities are logged for security purposes.
                </small>
            </div>
        </div>                  
        </form>
        
        <script>
        // Client-side security enhancements
        $(document).ready(function() {
            // Prevent form submission on Enter in username field to avoid timing attacks
            $('#username').keypress(function(e) {
                if (e.which === 13) {
                    $('#password').focus();
                    return false;
                }
            });
            
            // Clear form data on page unload for security
            $(window).on('beforeunload', function() {
                $('#username, #password').val('');
            });
            
            // Add visual feedback for password strength
            $('#password').on('input', function() {
                var password = $(this).val();
                var strength = calculatePasswordStrength(password);
                updatePasswordStrengthIndicator(strength);
            });
        });
        
        function calculatePasswordStrength(password) {
            var score = 0;
            if (password.length >= 8) score++;
            if (password.length >= 12) score++;
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;
            return score;
        }
        
        function updatePasswordStrengthIndicator(strength) {
            var indicator = $('.password-strength');
            if (indicator.length === 0) {
                $('#password').after('<div class="password-strength"></div>');
                indicator = $('.password-strength');
            }
            
            var strengthText = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
            var strengthClass = ['very-weak', 'weak', 'fair', 'good', 'strong', 'very-strong'];
            
            indicator.removeClass().addClass('password-strength ' + strengthClass[strength])
                     .text(strengthText[strength]);
        }
        </script>
        
        <style>
        .password-strength {
            margin-top: 5px;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 12px;
            text-align: center;
        }
        .very-weak { background-color: #ff4444; color: white; }
        .weak { background-color: #ff8800; color: white; }
        .fair { background-color: #ffbb33; color: black; }
        .good { background-color: #00C851; color: white; }
        .strong { background-color: #007E33; color: white; }
        .very-strong { background-color: #0d47a1; color: white; }
        .security-notice { 
            display: block; 
            margin-top: 10px; 
            color: #666; 
            font-size: 11px; 
            text-align: center; 
        }
        </style>
    </body>
</html>
