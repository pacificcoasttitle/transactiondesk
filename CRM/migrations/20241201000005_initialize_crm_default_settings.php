<?php
use Phinx\Migration\AbstractMigration;

class InitializeCrmDefaultSettings extends AbstractMigration
{
    /**
     * Initialize Default CRM Settings
     * 
     * This migration populates default CRM settings for all existing
     * sales representatives in the system.
     */
    public function up()
    {
        // Insert default note type setting for all sales reps
        $this->execute("
            INSERT INTO pct_crm_settings (sales_rep_id, setting_name, setting_value, setting_type, updated_by, updated_at)
            SELECT 
                id as sales_rep_id,
                'default_note_type' as setting_name,
                'general' as setting_value,
                'string' as setting_type,
                id as updated_by,
                NOW() as updated_at
            FROM customer_basic_details 
            WHERE is_sales_rep = 1 AND status = 1
        ");

        // Insert dashboard widget configuration
        $this->execute("
            INSERT INTO pct_crm_settings (sales_rep_id, setting_name, setting_value, setting_type, updated_by, updated_at)
            SELECT 
                id as sales_rep_id,
                'dashboard_widgets' as setting_name,
                '{\"client_stats\":true,\"recent_notes\":true,\"pending_followups\":true,\"activity_summary\":true,\"quick_actions\":true}' as setting_value,
                'json' as setting_type,
                id as updated_by,
                NOW() as updated_at
            FROM customer_basic_details 
            WHERE is_sales_rep = 1 AND status = 1
        ");

        // Insert follow-up reminder preferences
        $this->execute("
            INSERT INTO pct_crm_settings (sales_rep_id, setting_name, setting_value, setting_type, updated_by, updated_at)
            SELECT 
                id as sales_rep_id,
                'followup_reminder_days' as setting_name,
                '1' as setting_value,
                'integer' as setting_type,
                id as updated_by,
                NOW() as updated_at
            FROM customer_basic_details 
            WHERE is_sales_rep = 1 AND status = 1
        ");

        // Insert email notification preferences
        $this->execute("
            INSERT INTO pct_crm_settings (sales_rep_id, setting_name, setting_value, setting_type, updated_by, updated_at)
            SELECT 
                id as sales_rep_id,
                'email_notifications' as setting_name,
                '{\"daily_digest\":true,\"overdue_followups\":true,\"new_notes\":false,\"client_activities\":false}' as setting_value,
                'json' as setting_type,
                id as updated_by,
                NOW() as updated_at
            FROM customer_basic_details 
            WHERE is_sales_rep = 1 AND status = 1
        ");

        // Insert display preferences
        $this->execute("
            INSERT INTO pct_crm_settings (sales_rep_id, setting_name, setting_value, setting_type, updated_by, updated_at)
            SELECT 
                id as sales_rep_id,
                'display_preferences' as setting_name,
                '{\"clients_per_page\":25,\"default_view\":\"table\",\"sort_by\":\"company\",\"sort_order\":\"asc\"}' as setting_value,
                'json' as setting_type,
                id as updated_by,
                NOW() as updated_at
            FROM customer_basic_details 
            WHERE is_sales_rep = 1 AND status = 1
        ");

        // Insert activity tracking preferences
        $this->execute("
            INSERT INTO pct_crm_settings (sales_rep_id, setting_name, setting_value, setting_type, updated_by, updated_at)
            SELECT 
                id as sales_rep_id,
                'activity_tracking' as setting_name,
                '{\"auto_log_emails\":false,\"auto_log_calls\":true,\"default_call_duration\":15,\"require_outcome\":false}' as setting_value,
                'json' as setting_type,
                id as updated_by,
                NOW() as updated_at
            FROM customer_basic_details 
            WHERE is_sales_rep = 1 AND status = 1
        ");
    }

    /**
     * Rollback changes
     */
    public function down()
    {
        $this->execute("
            DELETE FROM pct_crm_settings 
            WHERE setting_name IN (
                'default_note_type', 
                'dashboard_widgets', 
                'followup_reminder_days',
                'email_notifications',
                'display_preferences',
                'activity_tracking'
            )
        ");
    }
}
