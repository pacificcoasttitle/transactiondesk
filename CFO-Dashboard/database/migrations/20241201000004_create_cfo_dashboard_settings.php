<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCfoDashboardSettings extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Create the CFO dashboard settings table for configuration and alert thresholds.
     * This table stores customizable dashboard settings and alert configurations.
     */
    public function change(): void
    {
        $table = $this->table('cfo_dashboard_settings');
        
        $table->addColumn('setting_name', 'varchar', [
                'limit' => 100,
                'null' => false,
                'comment' => 'Name of the setting'
            ])
            ->addColumn('setting_value', 'text', [
                'null' => true,
                'comment' => 'Value of the setting (JSON for complex values)'
            ])
            ->addColumn('setting_type', 'enum', [
                'values' => ['string', 'integer', 'decimal', 'boolean', 'json', 'array'],
                'default' => 'string',
                'comment' => 'Data type of the setting'
            ])
            ->addColumn('category', 'varchar', [
                'limit' => 50,
                'default' => 'general',
                'comment' => 'Category of setting'
            ])
            ->addColumn('description', 'text', [
                'null' => true,
                'comment' => 'Description of what this setting does'
            ])
            ->addColumn('default_value', 'text', [
                'null' => true,
                'comment' => 'Default value for this setting'
            ])
            ->addColumn('validation_rules', 'text', [
                'null' => true,
                'comment' => 'Validation rules for this setting'
            ])
            ->addColumn('is_editable', 'boolean', [
                'default' => true,
                'comment' => 'Whether setting can be edited via UI'
            ])
            ->addColumn('requires_restart', 'boolean', [
                'default' => false,
                'comment' => 'Whether changing this setting requires restart'
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'comment' => 'User who created this setting'
            ])
            ->addColumn('updated_by', 'integer', [
                'null' => true,
                'comment' => 'User who last updated this setting'
            ])
            ->addTimestamps()
            
            // Indexes
            ->addIndex(['setting_name'], ['unique' => true, 'name' => 'idx_setting_name_unique'])
            ->addIndex(['category'], ['name' => 'idx_category'])
            ->addIndex(['is_editable'], ['name' => 'idx_is_editable'])
            ->addIndex(['created_by'], ['name' => 'idx_created_by'])
            ->addIndex(['updated_by'], ['name' => 'idx_updated_by'])
            
            ->create();
            
        // Create alert configurations table
        $alertTable = $this->table('cfo_alert_configurations');
        
        $alertTable->addColumn('alert_name', 'varchar', [
                'limit' => 100,
                'null' => false,
                'comment' => 'Name of the alert'
            ])
            ->addColumn('alert_type', 'enum', [
                'values' => ['revenue_threshold', 'performance_warning', 'budget_variance', 'system_error'],
                'null' => false,
                'comment' => 'Type of alert'
            ])
            ->addColumn('threshold_value', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'null' => true,
                'comment' => 'Threshold value that triggers alert'
            ])
            ->addColumn('threshold_operator', 'enum', [
                'values' => ['>', '<', '>=', '<=', '=', '!='],
                'default' => '<',
                'comment' => 'Operator for threshold comparison'
            ])
            ->addColumn('threshold_percentage', 'decimal', [
                'precision' => 5,
                'scale' => 2,
                'null' => true,
                'comment' => 'Percentage threshold (alternative to value)'
            ])
            ->addColumn('time_period', 'enum', [
                'values' => ['daily', 'weekly', 'monthly', 'quarterly', 'yearly'],
                'default' => 'daily',
                'comment' => 'Time period for alert evaluation'
            ])
            ->addColumn('priority', 'enum', [
                'values' => ['low', 'medium', 'high', 'critical'],
                'default' => 'medium',
                'comment' => 'Priority level of alert'
            ])
            ->addColumn('notification_methods', 'json', [
                'null' => true,
                'comment' => 'Methods to use for notifications (email, slack, sms)'
            ])
            ->addColumn('recipient_roles', 'json', [
                'null' => true,
                'comment' => 'User roles that should receive this alert'
            ])
            ->addColumn('recipient_emails', 'text', [
                'null' => true,
                'comment' => 'Specific email addresses for notifications'
            ])
            ->addColumn('message_template', 'text', [
                'null' => true,
                'comment' => 'Template for alert message'
            ])
            ->addColumn('is_active', 'boolean', [
                'default' => true,
                'comment' => 'Whether this alert is active'
            ])
            ->addColumn('check_frequency', 'integer', [
                'default' => 15,
                'comment' => 'How often to check in minutes'
            ])
            ->addColumn('last_triggered', 'timestamp', [
                'null' => true,
                'comment' => 'When this alert was last triggered'
            ])
            ->addColumn('trigger_count', 'integer', [
                'default' => 0,
                'comment' => 'Number of times this alert has been triggered'
            ])
            ->addColumn('snooze_until', 'timestamp', [
                'null' => true,
                'comment' => 'Snooze alert until this time'
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'comment' => 'User who created this alert'
            ])
            ->addTimestamps()
            
            // Indexes
            ->addIndex(['alert_name'], ['unique' => true, 'name' => 'idx_alert_name_unique'])
            ->addIndex(['alert_type'], ['name' => 'idx_alert_type'])
            ->addIndex(['priority'], ['name' => 'idx_priority'])
            ->addIndex(['is_active'], ['name' => 'idx_is_active'])
            ->addIndex(['last_triggered'], ['name' => 'idx_last_triggered'])
            ->addIndex(['snooze_until'], ['name' => 'idx_snooze_until'])
            ->addIndex(['created_by'], ['name' => 'idx_created_by'])
            
            ->create();
            
        // Add table comments
        $this->execute("ALTER TABLE cfo_dashboard_settings COMMENT = 'Configuration settings for CFO dashboard'");
        $this->execute("ALTER TABLE cfo_alert_configurations COMMENT = 'Alert configurations and thresholds for CFO dashboard'");
    }
    
    /**
     * Migrate Up.
     * Insert default settings and alert configurations.
     */
    public function up(): void
    {
        // Call the change method to create tables
        $this->change();
        
        // Insert default dashboard settings
        $this->insertDefaultSettings();
        
        // Insert default alert configurations
        $this->insertDefaultAlerts();
    }
    
    /**
     * Insert default dashboard settings
     */
    private function insertDefaultSettings(): void
    {
        $defaultSettings = [
            [
                'setting_name' => 'dashboard_refresh_interval',
                'setting_value' => '300',
                'setting_type' => 'integer',
                'category' => 'performance',
                'description' => 'Dashboard auto-refresh interval in seconds',
                'default_value' => '300',
                'validation_rules' => 'min:60,max:3600'
            ],
            [
                'setting_name' => 'revenue_currency',
                'setting_value' => 'USD',
                'setting_type' => 'string',
                'category' => 'display',
                'description' => 'Currency for revenue display',
                'default_value' => 'USD',
                'validation_rules' => 'in:USD,EUR,GBP'
            ],
            [
                'setting_name' => 'fiscal_year_start',
                'setting_value' => '01-01',
                'setting_type' => 'string',
                'category' => 'business',
                'description' => 'Fiscal year start date (MM-DD)',
                'default_value' => '01-01',
                'validation_rules' => 'date_format:m-d'
            ],
            [
                'setting_name' => 'monthly_revenue_budget',
                'setting_value' => '500000.00',
                'setting_type' => 'decimal',
                'category' => 'budget',
                'description' => 'Monthly revenue budget target',
                'default_value' => '500000.00',
                'validation_rules' => 'numeric,min:0'
            ],
            [
                'setting_name' => 'enable_predictive_analytics',
                'setting_value' => 'true',
                'setting_type' => 'boolean',
                'category' => 'features',
                'description' => 'Enable predictive analytics and forecasting',
                'default_value' => 'true',
                'validation_rules' => 'boolean'
            ],
            [
                'setting_name' => 'chart_animation_enabled',
                'setting_value' => 'true',
                'setting_type' => 'boolean',
                'category' => 'display',
                'description' => 'Enable chart animations',
                'default_value' => 'true',
                'validation_rules' => 'boolean'
            ],
            [
                'setting_name' => 'data_retention_days',
                'setting_value' => '1095',
                'setting_type' => 'integer',
                'category' => 'data',
                'description' => 'Number of days to retain detailed data',
                'default_value' => '1095',
                'validation_rules' => 'integer,min:365'
            ],
            [
                'setting_name' => 'timezone',
                'setting_value' => 'America/Los_Angeles',
                'setting_type' => 'string',
                'category' => 'display',
                'description' => 'Timezone for date/time display',
                'default_value' => 'America/Los_Angeles',
                'validation_rules' => 'timezone'
            ]
        ];
        
        $this->table('cfo_dashboard_settings')->insert($defaultSettings)->save();
    }
    
    /**
     * Insert default alert configurations
     */
    private function insertDefaultAlerts(): void
    {
        $defaultAlerts = [
            [
                'alert_name' => 'daily_revenue_low',
                'alert_type' => 'revenue_threshold',
                'threshold_percentage' => 20.00,
                'threshold_operator' => '<',
                'time_period' => 'daily',
                'priority' => 'high',
                'notification_methods' => '["email", "dashboard"]',
                'recipient_roles' => '["cfo", "finance_manager"]',
                'message_template' => 'Daily revenue is {{percentage}}% below target. Current: ${{current_value}}, Target: ${{target_value}}',
                'check_frequency' => 60
            ],
            [
                'alert_name' => 'monthly_budget_variance',
                'alert_type' => 'budget_variance',
                'threshold_percentage' => 5.00,
                'threshold_operator' => '>',
                'time_period' => 'monthly',
                'priority' => 'medium',
                'notification_methods' => '["email"]',
                'recipient_roles' => '["cfo"]',
                'message_template' => 'Monthly budget variance is {{percentage}}%. Projected: ${{projected}}, Budget: ${{budget}}',
                'check_frequency' => 1440
            ],
            [
                'alert_name' => 'sales_rep_underperformance',
                'alert_type' => 'performance_warning',
                'threshold_percentage' => 30.00,
                'threshold_operator' => '<',
                'time_period' => 'monthly',
                'priority' => 'medium',
                'notification_methods' => '["email", "dashboard"]',
                'recipient_roles' => '["cfo", "sales_manager"]',
                'message_template' => '{{count}} sales reps are performing below 30% of their targets',
                'check_frequency' => 1440
            ],
            [
                'alert_name' => 'system_data_sync_error',
                'alert_type' => 'system_error',
                'priority' => 'critical',
                'notification_methods' => '["email", "slack"]',
                'recipient_roles' => '["admin", "cfo"]',
                'message_template' => 'CFO Dashboard data sync error: {{error_message}}',
                'check_frequency' => 15
            ]
        ];
        
        $this->table('cfo_alert_configurations')->insert($defaultAlerts)->save();
    }
}
