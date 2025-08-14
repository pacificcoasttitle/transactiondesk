<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Role-Based Access Control (RBAC) Library
 * 
 * Implements comprehensive role and permission management system
 * with granular access controls for the Transaction Desk platform.
 * 
 * @package    Transaction Desk
 * @subpackage Security
 * @category   Libraries
 * @author     Security Team
 * @version    1.0.0
 */
class RoleBasedAuth
{
    private $CI;
    private $userRoles = [];
    private $permissions = [];
    private $roleHierarchy = [];
    
    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->initializeDefaultRoles();
        $this->loadUserRoles();
    }
    
    /**
     * Initialize default roles and permissions
     */
    private function initializeDefaultRoles()
    {
        $this->permissions = [
            // Order Management
            'order.view' => 'View orders',
            'order.create' => 'Create new orders',
            'order.edit' => 'Edit existing orders',
            'order.delete' => 'Delete orders',
            'order.approve' => 'Approve orders',
            'order.cancel' => 'Cancel orders',
            
            // Document Management
            'document.view' => 'View documents',
            'document.upload' => 'Upload documents',
            'document.download' => 'Download documents',
            'document.delete' => 'Delete documents',
            'document.generate' => 'Generate documents',
            
            // User Management
            'user.view' => 'View users',
            'user.create' => 'Create users',
            'user.edit' => 'Edit users',
            'user.delete' => 'Delete users',
            'user.roles' => 'Manage user roles',
            
            // Financial Operations
            'finance.view' => 'View financial data',
            'finance.reports' => 'Generate financial reports',
            'finance.dashboard' => 'Access CFO dashboard',
            'finance.revenue_view' => 'View revenue data',
            'finance.sales_view' => 'View sales data',
            
            // System Administration
            'admin.settings' => 'Modify system settings',
            'admin.logs' => 'View system logs',
            'admin.security' => 'Manage security settings',
            'admin.backup' => 'Perform system backups',
            'admin.maintenance' => 'System maintenance mode',
            
            // API Access
            'api.read' => 'Read API access',
            'api.write' => 'Write API access',
            'api.admin' => 'Administrative API access',
            
            // Reporting
            'report.view' => 'View reports',
            'report.generate' => 'Generate reports',
            'report.export' => 'Export reports',
            'report.schedule' => 'Schedule automated reports'
        ];
        
        $this->userRoles = [
            'super_admin' => [
                'name' => 'Super Administrator',
                'description' => 'Full system access',
                'permissions' => ['*'], // Wildcard for all permissions
                'level' => 100
            ],
            'admin' => [
                'name' => 'Administrator',
                'description' => 'Administrative access with most privileges',
                'permissions' => [
                    'order.*', 'document.*', 'user.*', 'finance.*',
                    'admin.settings', 'admin.logs', 'api.admin',
                    'report.*'
                ],
                'level' => 90
            ],
            'cfo' => [
                'name' => 'Chief Financial Officer',
                'description' => 'Financial oversight and reporting access',
                'permissions' => [
                    'order.view', 'document.view', 'user.view',
                    'finance.*', 'report.*', 'api.read'
                ],
                'level' => 85
            ],
            'finance_manager' => [
                'name' => 'Finance Manager',
                'description' => 'Financial data access and reporting',
                'permissions' => [
                    'order.view', 'document.view',
                    'finance.view', 'finance.reports', 'finance.dashboard',
                    'report.view', 'report.generate', 'report.export'
                ],
                'level' => 70
            ],
            'sales_manager' => [
                'name' => 'Sales Manager',
                'description' => 'Sales team oversight and reporting',
                'permissions' => [
                    'order.view', 'order.edit', 'order.approve',
                    'document.view', 'document.upload',
                    'finance.sales_view', 'user.view',
                    'report.view', 'report.generate'
                ],
                'level' => 65
            ],
            'escrow_officer' => [
                'name' => 'Escrow Officer',
                'description' => 'Order processing and document management',
                'permissions' => [
                    'order.view', 'order.create', 'order.edit',
                    'document.*', 'report.view'
                ],
                'level' => 60
            ],
            'title_officer' => [
                'name' => 'Title Officer',
                'description' => 'Title examination and approval',
                'permissions' => [
                    'order.view', 'order.edit', 'order.approve',
                    'document.view', 'document.generate',
                    'report.view'
                ],
                'level' => 55
            ],
            'sales_rep' => [
                'name' => 'Sales Representative',
                'description' => 'Order creation and client management',
                'permissions' => [
                    'order.view', 'order.create', 'order.edit',
                    'document.view', 'document.upload',
                    'finance.sales_view', 'report.view'
                ],
                'level' => 50
            ],
            'processor' => [
                'name' => 'Order Processor',
                'description' => 'Basic order processing',
                'permissions' => [
                    'order.view', 'order.edit',
                    'document.view', 'document.upload'
                ],
                'level' => 40
            ],
            'viewer' => [
                'name' => 'Viewer',
                'description' => 'Read-only access',
                'permissions' => [
                    'order.view', 'document.view', 'report.view'
                ],
                'level' => 10
            ]
        ];
        
        // Define role hierarchy (higher level roles inherit from lower level ones)
        $this->roleHierarchy = [
            'super_admin' => ['admin', 'cfo', 'finance_manager', 'sales_manager', 'escrow_officer', 'title_officer', 'sales_rep', 'processor', 'viewer'],
            'admin' => ['finance_manager', 'sales_manager', 'escrow_officer', 'title_officer', 'sales_rep', 'processor', 'viewer'],
            'cfo' => ['finance_manager', 'viewer'],
            'finance_manager' => ['viewer'],
            'sales_manager' => ['sales_rep', 'viewer'],
            'escrow_officer' => ['processor', 'viewer'],
            'title_officer' => ['viewer'],
            'sales_rep' => ['viewer'],
            'processor' => ['viewer']
        ];
    }
    
    /**
     * Load user roles from database
     */
    private function loadUserRoles()
    {
        // Create user_roles table if it doesn't exist
        $this->createUserRolesTable();
        
        // Load custom roles from database
        $query = $this->CI->db->get('user_roles');
        foreach ($query->result_array() as $role) {
            $this->userRoles[$role['role_name']] = [
                'name' => $role['display_name'],
                'description' => $role['description'],
                'permissions' => json_decode($role['permissions'], true),
                'level' => $role['level'],
                'custom' => true
            ];
        }
    }
    
    /**
     * Check if user has specific permission
     */
    public function hasPermission($userId, $permission)
    {
        $userRoles = $this->getUserRoles($userId);
        
        foreach ($userRoles as $role) {
            if ($this->roleHasPermission($role, $permission)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Check if role has specific permission
     */
    public function roleHasPermission($roleName, $permission)
    {
        if (!isset($this->userRoles[$roleName])) {
            return false;
        }
        
        $rolePermissions = $this->userRoles[$roleName]['permissions'];
        
        // Check for wildcard permission
        if (in_array('*', $rolePermissions)) {
            return true;
        }
        
        // Check for exact permission match
        if (in_array($permission, $rolePermissions)) {
            return true;
        }
        
        // Check for wildcard category permissions (e.g., 'order.*')
        $permissionParts = explode('.', $permission);
        if (count($permissionParts) > 1) {
            $categoryWildcard = $permissionParts[0] . '.*';
            if (in_array($categoryWildcard, $rolePermissions)) {
                return true;
            }
        }
        
        // Check inherited permissions from role hierarchy
        if (isset($this->roleHierarchy[$roleName])) {
            foreach ($this->roleHierarchy[$roleName] as $inheritedRole) {
                if ($this->roleHasPermission($inheritedRole, $permission)) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Get user roles
     */
    public function getUserRoles($userId)
    {
        // Check session first
        $sessionRoles = $this->getSessionRoles();
        if (!empty($sessionRoles)) {
            return $sessionRoles;
        }
        
        // Fall back to database lookup
        $this->CI->db->select('role_name');
        $this->CI->db->from('user_role_assignments');
        $this->CI->db->where('user_id', $userId);
        $this->CI->db->where('is_active', 1);
        $query = $this->CI->db->get();
        
        $roles = [];
        foreach ($query->result_array() as $row) {
            $roles[] = $row['role_name'];
        }
        
        return $roles;
    }
    
    /**
     * Get roles from session
     */
    private function getSessionRoles()
    {
        $userdata = $this->CI->session->userdata('user') ?: $this->CI->session->userdata('admin');
        
        if (empty($userdata)) {
            return [];
        }
        
        $roles = [];
        
        // Map legacy session data to roles
        if (isset($userdata['is_admin']) && $userdata['is_admin'] == 1) {
            $roles[] = 'admin';
        }
        
        if (isset($userdata['is_master']) && $userdata['is_master'] == 1) {
            $roles[] = 'super_admin';
        }
        
        if (isset($userdata['is_sales_rep']) && $userdata['is_sales_rep'] == 1) {
            $roles[] = 'sales_rep';
        }
        
        if (isset($userdata['role_id'])) {
            $roleMapping = [
                1 => 'admin',
                2 => 'escrow_officer',
                3 => 'title_officer',
                4 => 'sales_rep',
                5 => 'processor'
            ];
            
            if (isset($roleMapping[$userdata['role_id']])) {
                $roles[] = $roleMapping[$userdata['role_id']];
            }
        }
        
        return array_unique($roles);
    }
    
    /**
     * Assign role to user
     */
    public function assignRole($userId, $roleName)
    {
        if (!isset($this->userRoles[$roleName])) {
            return false;
        }
        
        // Check if assignment already exists
        $this->CI->db->select('id');
        $this->CI->db->from('user_role_assignments');
        $this->CI->db->where('user_id', $userId);
        $this->CI->db->where('role_name', $roleName);
        $existing = $this->CI->db->get()->row();
        
        if ($existing) {
            // Update existing assignment
            $this->CI->db->where('id', $existing->id);
            $this->CI->db->update('user_role_assignments', [
                'is_active' => 1,
                'assigned_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Create new assignment
            $this->CI->db->insert('user_role_assignments', [
                'user_id' => $userId,
                'role_name' => $roleName,
                'is_active' => 1,
                'assigned_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        
        $this->logSecurityEvent('ROLE_ASSIGNED', [
            'user_id' => $userId,
            'role' => $roleName
        ]);
        
        return true;
    }
    
    /**
     * Remove role from user
     */
    public function removeRole($userId, $roleName)
    {
        $this->CI->db->where('user_id', $userId);
        $this->CI->db->where('role_name', $roleName);
        $this->CI->db->update('user_role_assignments', [
            'is_active' => 0,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        $this->logSecurityEvent('ROLE_REMOVED', [
            'user_id' => $userId,
            'role' => $roleName
        ]);
        
        return $this->CI->db->affected_rows() > 0;
    }
    
    /**
     * Create custom role
     */
    public function createRole($roleName, $displayName, $description, $permissions, $level = 50)
    {
        if (isset($this->userRoles[$roleName])) {
            return false; // Role already exists
        }
        
        $roleData = [
            'role_name' => $roleName,
            'display_name' => $displayName,
            'description' => $description,
            'permissions' => json_encode($permissions),
            'level' => $level,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->CI->db->insert('user_roles', $roleData);
        
        if ($this->CI->db->affected_rows() > 0) {
            $this->userRoles[$roleName] = [
                'name' => $displayName,
                'description' => $description,
                'permissions' => $permissions,
                'level' => $level,
                'custom' => true
            ];
            
            $this->logSecurityEvent('ROLE_CREATED', [
                'role' => $roleName,
                'permissions' => $permissions
            ]);
            
            return true;
        }
        
        return false;
    }
    
    /**
     * Get all available roles
     */
    public function getAllRoles()
    {
        return $this->userRoles;
    }
    
    /**
     * Get all available permissions
     */
    public function getAllPermissions()
    {
        return $this->permissions;
    }
    
    /**
     * Middleware to check permissions
     */
    public function requirePermission($permission, $redirectUrl = null)
    {
        $userdata = $this->CI->session->userdata('user') ?: $this->CI->session->userdata('admin');
        
        if (empty($userdata)) {
            $this->handleUnauthorized($redirectUrl, 'No user session');
            return false;
        }
        
        if (!$this->hasPermission($userdata['id'], $permission)) {
            $this->handleUnauthorized($redirectUrl, "Missing permission: {$permission}");
            return false;
        }
        
        return true;
    }
    
    /**
     * Handle unauthorized access
     */
    private function handleUnauthorized($redirectUrl, $reason)
    {
        $this->logSecurityEvent('UNAUTHORIZED_ACCESS', [
            'reason' => $reason,
            'requested_url' => $_SERVER['REQUEST_URI'] ?? 'unknown'
        ]);
        
        if ($redirectUrl) {
            redirect($redirectUrl);
        } else {
            show_error('Access Denied: You do not have permission to access this resource.', 403);
        }
    }
    
    /**
     * Create necessary database tables
     */
    private function createUserRolesTable()
    {
        // Create user_roles table
        $this->CI->db->query("
            CREATE TABLE IF NOT EXISTS `user_roles` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `role_name` varchar(50) NOT NULL,
                `display_name` varchar(100) NOT NULL,
                `description` text DEFAULT NULL,
                `permissions` json NOT NULL,
                `level` int(11) DEFAULT 50,
                `is_active` tinyint(1) DEFAULT 1,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `role_name` (`role_name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
        
        // Create user_role_assignments table
        $this->CI->db->query("
            CREATE TABLE IF NOT EXISTS `user_role_assignments` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_id` int(11) NOT NULL,
                `role_name` varchar(50) NOT NULL,
                `is_active` tinyint(1) DEFAULT 1,
                `assigned_by` int(11) DEFAULT NULL,
                `assigned_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `user_id` (`user_id`),
                KEY `role_name` (`role_name`),
                KEY `user_role_active` (`user_id`, `role_name`, `is_active`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }
    
    /**
     * Log security events
     */
    private function logSecurityEvent($event, $details = [])
    {
        $logEntry = [
            'timestamp' => date('Y-m-d H:i:s'),
            'event' => $event,
            'details' => $details,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ];
        
        $logFile = APPPATH . '../logs/rbac_security.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($logFile, json_encode($logEntry) . "\n", FILE_APPEND | LOCK_EX);
    }
}
?>
