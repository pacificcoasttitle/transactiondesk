<?php
use Phinx\Migration\AbstractMigration;

class CreateCrmClientNotesTable extends AbstractMigration
{
    /**
     * Create CRM Client Notes Table
     * 
     * This table stores all client-related notes, follow-ups, and tasks
     * for sales representatives in the Transaction Desk CRM system.
     */
    public function change()
    {
        $table = $this->table('pct_crm_client_notes');
        $table->addColumn('sales_rep_id', 'integer', [
                  'null' => false, 
                  'comment' => 'FK to customer_basic_details.id where is_sales_rep=1'
              ])
              ->addColumn('client_id', 'integer', [
                  'null' => false, 
                  'comment' => 'FK to customer_basic_details.id (client)'
              ])
              ->addColumn('note_type', 'enum', [
                  'values' => ['general','follow_up','meeting','call','email','task'],
                  'default' => 'general',
                  'comment' => 'Type of note for categorization'
              ])
              ->addColumn('subject', 'string', [
                  'limit' => 255, 
                  'null' => false,
                  'comment' => 'Brief description or title of the note'
              ])
              ->addColumn('content', 'text', [
                  'null' => false,
                  'comment' => 'Full content of the note'
              ])
              ->addColumn('priority', 'enum', [
                  'values' => ['low','normal','high','urgent'],
                  'default' => 'normal',
                  'comment' => 'Priority level for follow-up and task management'
              ])
              ->addColumn('follow_up_date', 'date', [
                  'null' => true, 
                  'comment' => 'Date for follow-up reminder (optional)'
              ])
              ->addColumn('is_completed', 'boolean', [
                  'default' => 0, 
                  'comment' => 'For follow-up tasks - marks completion status'
              ])
              ->addColumn('is_private', 'boolean', [
                  'default' => 0, 
                  'comment' => 'Hide from managers if set to 1'
              ])
              ->addColumn('order_id', 'integer', [
                  'null' => true, 
                  'comment' => 'Optional FK to order_details.id for order-specific notes'
              ])
              ->addColumn('created_by', 'integer', [
                  'null' => false, 
                  'comment' => 'User ID who created the note'
              ])
              ->addColumn('updated_by', 'integer', [
                  'null' => true, 
                  'comment' => 'User ID who last updated the note'
              ])
              ->addTimestamps()
              
              // Indexes for performance optimization
              ->addIndex(['sales_rep_id', 'client_id'], [
                  'name' => 'idx_sales_rep_client'
              ])
              ->addIndex(['follow_up_date', 'is_completed'], [
                  'name' => 'idx_follow_up_date'
              ])
              ->addIndex(['client_id', 'created_at'], [
                  'name' => 'idx_client_created'
              ])
              ->addIndex(['note_type'], [
                  'name' => 'idx_note_type'
              ])
              ->addIndex(['priority'], [
                  'name' => 'idx_priority'
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
        $this->table('pct_crm_client_notes')->drop()->save();
    }
}
