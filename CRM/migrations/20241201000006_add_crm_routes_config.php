<?php
use Phinx\Migration\AbstractMigration;

class AddCrmRoutesConfig extends AbstractMigration
{
    /**
     * Add CRM Routes Configuration
     * 
     * This migration creates a configuration record that contains
     * all the routes that need to be added to the routes.php file.
     * 
     * Note: This is informational only - routes must be manually added
     * to application/config/routes.php
     */
    public function up()
    {
        // Create configuration table if it doesn't exist
        if (!$this->hasTable('pct_system_config')) {
            $table = $this->table('pct_system_config');
            $table->addColumn('config_key', 'string', ['limit' => 100, 'null' => false])
                  ->addColumn('config_value', 'text', ['null' => false])
                  ->addColumn('config_type', 'string', ['limit' => 50, 'default' => 'string'])
                  ->addColumn('description', 'text', ['null' => true])
                  ->addTimestamps()
                  ->addIndex(['config_key'], ['unique' => true])
                  ->create();
        }

        // Insert CRM routes configuration
        $routes_config = [
            '// CRM Main Routes',
            '$route[\'crm\'] = \'frontend/order/crm/index\';',
            '$route[\'crm/dashboard\'] = \'frontend/order/crm/index\';',
            '$route[\'crm/clients\'] = \'frontend/order/crm/clients\';',
            '$route[\'crm/client/(:num)\'] = \'frontend/order/crm/client/$1\';',
            '$route[\'crm/follow-ups\'] = \'frontend/order/crm/follow_ups\';',
            '$route[\'crm/activities\'] = \'frontend/order/crm/activities\';',
            '',
            '// CRM AJAX API Routes',
            '$route[\'crm/save-note\'] = \'frontend/order/crm/save_note\';',
            '$route[\'crm/delete-note\'] = \'frontend/order/crm/delete_note\';',
            '$route[\'crm/quick-note\'] = \'frontend/order/crm/quick_note\';',
            '$route[\'crm/log-activity\'] = \'frontend/order/crm/log_activity\';',
            '$route[\'crm/complete-followup\'] = \'frontend/order/crm/complete_followup\';',
            '$route[\'crm/snooze-followup\'] = \'frontend/order/crm/snooze_followup\';',
            '$route[\'crm/search-clients\'] = \'frontend/order/crm/search_clients\';',
            '$route[\'crm/dashboard-stats\'] = \'frontend/order/crm/dashboard_stats\';',
            '',
            '// CRM Settings Routes',
            '$route[\'crm/settings\'] = \'frontend/order/crm/settings\';',
            '$route[\'crm/save-settings\'] = \'frontend/order/crm/save_settings\';',
            '',
            '// CRM Export Routes',
            '$route[\'crm/export/clients\'] = \'frontend/order/crm/export_clients\';',
            '$route[\'crm/export/notes/(:num)\'] = \'frontend/order/crm/export_notes/$1\';',
            '$route[\'crm/export/activities/(:num)\'] = \'frontend/order/crm/export_activities/$1\';'
        ];

        $this->execute("
            INSERT INTO pct_system_config (config_key, config_value, config_type, description, created_at, updated_at)
            VALUES (
                'crm_routes',
                '" . implode("\\n", $routes_config) . "',
                'routes',
                'CRM module routes that need to be added to application/config/routes.php',
                NOW(),
                NOW()
            )
            ON DUPLICATE KEY UPDATE
                config_value = VALUES(config_value),
                updated_at = NOW()
        ");

        // Insert navigation menu configuration
        $navigation_config = [
            'Sales Dashboard → CRM Section:',
            '',
            '<div class="crm-navigation">',
            '    <h6 class="sidebar-heading">Client Relationship Management</h6>',
            '    <ul class="nav flex-column">',
            '        <li class="nav-item">',
            '            <a class="nav-link" href="<?= base_url(\'crm/dashboard\') ?>">',
            '                <i class="fas fa-tachometer-alt"></i> CRM Dashboard',
            '            </a>',
            '        </li>',
            '        <li class="nav-item">',
            '            <a class="nav-link" href="<?= base_url(\'crm/clients\') ?>">',
            '                <i class="fas fa-users"></i> My Clients',
            '            </a>',
            '        </li>',
            '        <li class="nav-item">',
            '            <a class="nav-link" href="<?= base_url(\'crm/follow-ups\') ?>">',
            '                <i class="fas fa-bell"></i> Follow-ups',
            '                <?php if ($pending_followups > 0): ?>',
            '                <span class="badge badge-warning"><?= $pending_followups ?></span>',
            '                <?php endif; ?>',
            '            </a>',
            '        </li>',
            '        <li class="nav-item">',
            '            <a class="nav-link" href="<?= base_url(\'crm/activities\') ?>">',
            '                <i class="fas fa-chart-line"></i> Activities',
            '            </a>',
            '        </li>',
            '    </ul>',
            '</div>'
        ];

        $this->execute("
            INSERT INTO pct_system_config (config_key, config_value, config_type, description, created_at, updated_at)
            VALUES (
                'crm_navigation',
                '" . implode("\\n", $navigation_config) . "',
                'template',
                'CRM navigation menu HTML for sales dashboard sidebar',
                NOW(),
                NOW()
            )
            ON DUPLICATE KEY UPDATE
                config_value = VALUES(config_value),
                updated_at = NOW()
        ");

        // Output instructions for manual configuration
        echo "\n";
        echo "========================================\n";
        echo "CRM INSTALLATION INSTRUCTIONS\n";
        echo "========================================\n";
        echo "\n";
        echo "1. ADD ROUTES TO CONFIG FILE:\n";
        echo "   File: application/config/routes.php\n";
        echo "   Add the following routes:\n\n";
        
        foreach ($routes_config as $route) {
            if (!empty(trim($route))) {
                echo "   " . $route . "\n";
            }
        }
        
        echo "\n";
        echo "2. UPDATE SALES DASHBOARD NAVIGATION:\n";
        echo "   File: application/modules/frontend/views/order/salesRep/sidebar.php\n";
        echo "   Add CRM navigation section to sidebar\n";
        echo "\n";
        echo "3. VERIFY DATABASE TABLES:\n";
        echo "   - pct_crm_client_notes\n";
        echo "   - pct_crm_activities\n";
        echo "   - pct_crm_follow_up_queue\n";
        echo "   - pct_crm_settings\n";
        echo "\n";
        echo "4. CREATE CONTROLLER FILES:\n";
        echo "   - application/modules/frontend/controllers/order/Crm.php\n";
        echo "   - application/modules/frontend/models/order/Crm_model.php\n";
        echo "   - application/modules/frontend/models/order/CrmNotes_model.php\n";
        echo "   - application/modules/frontend/models/order/CrmActivities_model.php\n";
        echo "\n";
        echo "5. CREATE VIEW FILES:\n";
        echo "   - application/modules/frontend/views/order/crm/dashboard.php\n";
        echo "   - application/modules/frontend/views/order/crm/clients.php\n";
        echo "   - application/modules/frontend/views/order/crm/client_profile.php\n";
        echo "\n";
        echo "========================================\n";
    }

    /**
     * Rollback changes
     */
    public function down()
    {
        $this->execute("DELETE FROM pct_system_config WHERE config_key IN ('crm_routes', 'crm_navigation')");
    }
}
