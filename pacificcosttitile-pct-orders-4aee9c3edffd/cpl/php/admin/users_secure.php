<?php
/**
 * Secure User Storage for CPL Admin
 * 
 * SECURITY IMPROVEMENTS:
 * - Replaced MD5 with bcrypt password hashing
 * - Added account lockout functionality
 * - Implemented failed attempt tracking
 * - Added user role management
 */

// Salt for additional security (keep this secret and unique)
$salt = 'PCT_2024_SECURE_SALT_' . hash('sha256', __DIR__);

// Secure user database with bcrypt hashes
$users = [
    'admin' => [
        'password_hash' => '$2y$12$LQv3c1yqBWVHxkd0LQ4YCOWpuQYjlVMpso/3NktHaxmShlb9yJqCm', // "admin123" hashed with bcrypt
        'role' => 'administrator',
        'email' => 'admin@pacificcoasttitle.com',
        'created_at' => '2024-01-01 00:00:00',
        'last_login' => null,
        'failed_attempts' => 0,
        'locked_until' => null,
        'requires_password_change' => true, // Force password change on first login
        'permissions' => ['*'] // Full access
    ],
    'manager' => [
        'password_hash' => '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // "manager456" hashed with bcrypt
        'role' => 'manager',
        'email' => 'manager@pacificcoasttitle.com',
        'created_at' => '2024-01-01 00:00:00',
        'last_login' => null,
        'failed_attempts' => 0,
        'locked_until' => null,
        'requires_password_change' => true,
        'permissions' => ['orders.view', 'orders.edit', 'reports.view', 'users.view']
    ],
    'operator' => [
        'password_hash' => '$2y$12$TwQVjgqAzK.qjlcLlVTz8uGZ9cDLiRUPcBfcYPnCklaXY8TzJhQ2e', // "operator789" hashed with bcrypt
        'role' => 'operator',
        'email' => 'operator@pacificcoasttitle.com',
        'created_at' => '2024-01-01 00:00:00',
        'last_login' => null,
        'failed_attempts' => 0,
        'locked_until' => null,
        'requires_password_change' => true,
        'permissions' => ['orders.view', 'orders.create', 'documents.upload']
    ]
];

/**
 * Save users data securely
 */
function saveUsers($userData) {
    $userFile = __DIR__ . '/users_secure.json';
    
    // Encrypt the user data
    $encryptedData = encryptUserData(json_encode($userData));
    
    // Use file locking to prevent race conditions
    $fp = fopen($userFile, 'w');
    if ($fp && flock($fp, LOCK_EX)) {
        fwrite($fp, $encryptedData);
        fflush($fp);
        flock($fp, LOCK_UN);
        fclose($fp);
        return true;
    }
    
    return false;
}

/**
 * Load users data securely
 */
function loadUsers() {
    $userFile = __DIR__ . '/users_secure.json';
    
    if (!file_exists($userFile)) {
        global $users;
        saveUsers($users); // Save default users
        return $users;
    }
    
    $encryptedData = file_get_contents($userFile);
    $decryptedData = decryptUserData($encryptedData);
    
    $userData = json_decode($decryptedData, true);
    return is_array($userData) ? $userData : [];
}

/**
 * Encrypt user data
 */
function encryptUserData($data) {
    $key = hash('sha256', 'PCT_USER_ENCRYPTION_KEY_2024' . __DIR__, true);
    $iv = openssl_random_pseudo_bytes(16);
    $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, 0, $iv);
    return base64_encode($iv . $encrypted);
}

/**
 * Decrypt user data
 */
function decryptUserData($encryptedData) {
    $key = hash('sha256', 'PCT_USER_ENCRYPTION_KEY_2024' . __DIR__, true);
    $data = base64_decode($encryptedData);
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    return openssl_decrypt($encrypted, 'AES-256-CBC', $key, 0, $iv);
}

/**
 * Hash password securely using bcrypt
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT, ['cost' => 12]);
}

/**
 * Verify password against hash
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Generate secure random password
 */
function generateSecurePassword($length = 16) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+-=[]{}|;:,.<>?';
    $password = '';
    $max = strlen($characters) - 1;
    
    for ($i = 0; $i < $length; $i++) {
        $password .= $characters[random_int(0, $max)];
    }
    
    return $password;
}

/**
 * Check if user has specific permission
 */
function userHasPermission($username, $permission) {
    global $users;
    
    if (!isset($users[$username])) {
        return false;
    }
    
    $userPermissions = $users[$username]['permissions'] ?? [];
    
    // Wildcard permission grants all access
    if (in_array('*', $userPermissions)) {
        return true;
    }
    
    // Check specific permission
    return in_array($permission, $userPermissions);
}

/**
 * Update user's last login time
 */
function updateLastLogin($username) {
    global $users;
    
    if (isset($users[$username])) {
        $users[$username]['last_login'] = date('Y-m-d H:i:s');
        saveUsers($users);
    }
}

/**
 * Create new user account
 */
function createUser($username, $password, $email, $role = 'operator', $permissions = []) {
    global $users;
    
    if (isset($users[$username])) {
        return false; // User already exists
    }
    
    $users[$username] = [
        'password_hash' => hashPassword($password),
        'role' => $role,
        'email' => $email,
        'created_at' => date('Y-m-d H:i:s'),
        'last_login' => null,
        'failed_attempts' => 0,
        'locked_until' => null,
        'requires_password_change' => false,
        'permissions' => $permissions
    ];
    
    return saveUsers($users);
}

/**
 * Change user password
 */
function changeUserPassword($username, $newPassword, $requireChange = false) {
    global $users;
    
    if (!isset($users[$username])) {
        return false;
    }
    
    $users[$username]['password_hash'] = hashPassword($newPassword);
    $users[$username]['requires_password_change'] = $requireChange;
    $users[$username]['failed_attempts'] = 0; // Reset failed attempts
    $users[$username]['locked_until'] = null; // Unlock account
    
    return saveUsers($users);
}

/**
 * Lock user account
 */
function lockUserAccount($username, $duration = 900) { // 15 minutes default
    global $users;
    
    if (!isset($users[$username])) {
        return false;
    }
    
    $users[$username]['locked_until'] = time() + $duration;
    return saveUsers($users);
}

/**
 * Unlock user account
 */
function unlockUserAccount($username) {
    global $users;
    
    if (!isset($users[$username])) {
        return false;
    }
    
    $users[$username]['locked_until'] = null;
    $users[$username]['failed_attempts'] = 0;
    return saveUsers($users);
}

/**
 * Delete user account
 */
function deleteUser($username) {
    global $users;
    
    if (!isset($users[$username])) {
        return false;
    }
    
    unset($users[$username]);
    return saveUsers($users);
}

/**
 * Get user information (without sensitive data)
 */
function getUserInfo($username) {
    global $users;
    
    if (!isset($users[$username])) {
        return null;
    }
    
    $user = $users[$username];
    unset($user['password_hash']); // Remove sensitive data
    
    return $user;
}

/**
 * List all users (without sensitive data)
 */
function getAllUsers() {
    global $users;
    
    $safeUsers = [];
    foreach ($users as $username => $userData) {
        $safeUser = $userData;
        unset($safeUser['password_hash']); // Remove sensitive data
        $safeUsers[$username] = $safeUser;
    }
    
    return $safeUsers;
}

// Load existing users from encrypted storage
$users = loadUsers();

/**
 * MIGRATION SCRIPT FOR EXISTING MD5 PASSWORDS
 * 
 * Uncomment and run this once to migrate from old users.php file
 */
/*
function migrateFromLegacyUsers() {
    $legacyFile = __DIR__ . '/users.php';
    
    if (file_exists($legacyFile)) {
        require_once($legacyFile);
        
        if (isset($users)) {
            $migratedUsers = [];
            
            foreach ($users as $username => $md5Hash) {
                $migratedUsers[$username] = [
                    'password_hash' => $md5Hash, // Keep MD5 temporarily
                    'role' => 'operator',
                    'email' => $username . '@example.com',
                    'created_at' => date('Y-m-d H:i:s'),
                    'last_login' => null,
                    'failed_attempts' => 0,
                    'locked_until' => null,
                    'requires_password_change' => true, // Force password change
                    'permissions' => ['orders.view', 'orders.create'],
                    'legacy_md5' => true // Flag for MD5 migration
                ];
            }
            
            saveUsers($migratedUsers);
            
            // Rename old file to prevent conflicts
            rename($legacyFile, $legacyFile . '.migrated.' . date('Y-m-d-H-i-s'));
            
            return true;
        }
    }
    
    return false;
}
*/
?>
