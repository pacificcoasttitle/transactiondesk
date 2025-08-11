<?php
use Phinx\Migration\AbstractMigration;

class CreateCrmActivitiesTable extends AbstractMigration
{
    /**
     * Create CRM Activities Table
     * 
     * This table tracks all client interactions and activities
     * for comprehensive relationship management.
     */
    public function change()
    {
        $table = $this->table('pct_crm_activities');
        $table->addColumn('sales_rep_id', 'integer', [
                  'null' => false,
                  'comment' => 'FK to customer_basic_details.id (sales rep)'
              ])
              ->addColumn('client_id', 'integer', [
                  'null' => false,
                  'comment' => 'FK to customer_basic_details.id (client)'
              ])
              ->addColumn('activity_type', 'enum', [
                  'values' => ['email','call','meeting','note','task','order','document'],
                  'null' => false,
                  'comment' => 'Type of activity performed'
              ])
              ->addColumn('subject', 'string', [
                  'limit' => 255, 
                  'null' => false,
                  'comment' => 'Brief description of the activity'
              ])
              ->addColumn('description', 'text', [
                  'null' => true,
                  'comment' => 'Detailed description of the activity'
              ])
              ->addColumn('activity_date', 'datetime', [
                  'default' => 'CURRENT_TIMESTAMP',
                  'comment' => 'When the activity occurred'
              ])
              ->addColumn('duration_minutes', 'integer', [
                  'null' => true, 
                  'comment' => 'Duration for calls/meetings in minutes'
              ])
              ->addColumn('outcome', 'text', [
                  'null' => true, 
                  'comment' => 'Result or outcome of the activity'
              ])
              ->addColumn('next_action', 'text', [
                  'null' => true, 
                  'comment' => 'Recommended next steps or follow-up actions'
              ])
              ->addColumn('contact_method', 'enum', [
                  'values' => ['phone','email','in_person','video','text'],
                  'null' => true,
                  'comment' => 'Method of contact used'
              ])
              ->addColumn('participants', 'text', [
                  'null' => true, 
                  'comment' => 'JSON array of other participants in the activity'
              ])
              ->addColumn('order_id', 'integer', [
                  'null' => true, 
                  'comment' => 'Related order ID if applicable'
              ])
              ->addColumn('note_id', 'integer', [
                  'null' => true, 
                  'comment' => 'Related note ID if applicable'
              ])
              ->addColumn('created_by', 'integer', [
                  'null' => false,
                  'comment' => 'User ID who logged this activity'
              ])
              ->addTimestamp('created_at', [
                  'default' => 'CURRENT_TIMESTAMP'
              ])
              
              // Performance indexes
              ->addIndex(['sales_rep_id', 'activity_date'], [
                  'name' => 'idx_sales_rep_date'
              ])
              ->addIndex(['client_id', 'activity_date'], [
                  'name' => 'idx_client_date'
              ])
              ->addIndex(['activity_type'], [
                  'name' => 'idx_activity_type'
              ])
              ->addIndex(['activity_date'], [
                  'name' => 'idx_activity_date'
              ])
              ->addIndex(['created_by'], [
                  'name' => 'idx_created_by'
              ])
              
              ->create();
    }
    
    /**
     * Rollback changes
     */
    public function down()
    {
        $this->table('pct_crm_activities')->drop()->save();
    }
}
