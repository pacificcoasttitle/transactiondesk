<?php
use Phinx\Migration\AbstractMigration;

class CreateCrmSettingsTable extends AbstractMigration
{
    /**
     * Create CRM Settings Table
     * 
     * This table stores user preferences and CRM configuration
     * settings for individual sales representatives.
     */
    public function change()
    {
        $table = $this->table('pct_crm_settings');
        $table->addColumn('sales_rep_id', 'integer', [
                  'null' => false,
                  'comment' => 'FK to customer_basic_details.id (sales rep)'
              ])
              ->addColumn('setting_name', 'string', [
                  'limit' => 100, 
                  'null' => false,
                  'comment' => 'Name/key of the setting'
              ])
              ->addColumn('setting_value', 'text', [
                  'null' => false,
                  'comment' => 'Value of the setting (can be JSON for complex data)'
              ])
              ->addColumn('setting_type', 'enum', [
                  'values' => ['string','integer','boolean','json'],
                  'default' => 'string',
                  'comment' => 'Data type of the setting value'
              ])
              ->addColumn('updated_by', 'integer', [
                  'null' => false,
                  'comment' => 'User ID who last updated this setting'
              ])
              ->addTimestamp('updated_at', [
                  'default' => 'CURRENT_TIMESTAMP',
                  'update' => 'CURRENT_TIMESTAMP'
              ])
              
              // Unique constraint to prevent duplicate settings per user
              ->addIndex(['sales_rep_id', 'setting_name'], [
                  'unique' => true, 
                  'name' => 'unique_setting_per_user'
              ])
              
              ->create();
    }
    
    /**
     * Rollback changes
     */
    public function down()
    {
        $this->table('pct_crm_settings')->drop()->save();
    }
}
