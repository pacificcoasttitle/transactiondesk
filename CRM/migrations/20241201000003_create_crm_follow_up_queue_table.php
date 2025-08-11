<?php
use Phinx\Migration\AbstractMigration;

class CreateCrmFollowUpQueueTable extends AbstractMigration
{
    /**
     * Create CRM Follow-up Queue Table
     * 
     * This table manages follow-up reminders and tasks,
     * automatically created from notes with follow_up_date.
     */
    public function change()
    {
        $table = $this->table('pct_crm_follow_up_queue');
        $table->addColumn('sales_rep_id', 'integer', [
                  'null' => false,
                  'comment' => 'FK to customer_basic_details.id (sales rep)'
              ])
              ->addColumn('client_id', 'integer', [
                  'null' => false,
                  'comment' => 'FK to customer_basic_details.id (client)'
              ])
              ->addColumn('note_id', 'integer', [
                  'null' => false, 
                  'comment' => 'FK to pct_crm_client_notes.id'
              ])
              ->addColumn('due_date', 'date', [
                  'null' => false,
                  'comment' => 'Date when follow-up is due'
              ])
              ->addColumn('priority', 'enum', [
                  'values' => ['low','normal','high','urgent'],
                  'default' => 'normal',
                  'comment' => 'Priority level for the follow-up'
              ])
              ->addColumn('status', 'enum', [
                  'values' => ['pending','completed','snoozed','cancelled'],
                  'default' => 'pending',
                  'comment' => 'Current status of the follow-up'
              ])
              ->addColumn('completed_at', 'datetime', [
                  'null' => true,
                  'comment' => 'Timestamp when follow-up was completed'
              ])
              ->addColumn('snoozed_until', 'date', [
                  'null' => true,
                  'comment' => 'New due date if follow-up was snoozed'
              ])
              ->addColumn('reminder_sent', 'boolean', [
                  'default' => 0,
                  'comment' => 'Flag to track if reminder notification was sent'
              ])
              ->addTimestamps()
              
              // Performance indexes for dashboard queries
              ->addIndex(['sales_rep_id', 'due_date', 'status'], [
                  'name' => 'idx_sales_rep_due'
              ])
              ->addIndex(['due_date', 'status'], [
                  'name' => 'idx_due_date_status'
              ])
              ->addIndex(['reminder_sent', 'due_date'], [
                  'name' => 'idx_reminder_sent'
              ])
              ->addIndex(['note_id'], [
                  'name' => 'idx_note_id'
              ])
              ->addIndex(['client_id'], [
                  'name' => 'idx_client_id'
              ])
              
              ->create();
    }
    
    /**
     * Rollback changes
     */
    public function down()
    {
        $this->table('pct_crm_follow_up_queue')->drop()->save();
    }
}
