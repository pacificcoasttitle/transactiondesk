# CRM Code Templates

## 📝 Ready-to-Use Code Components

This document contains complete, production-ready code templates for implementing the CRM system. Each template follows the existing Transaction Desk patterns and conventions.

## 🗄 Database Migrations

### **Migration Files**

#### **Migration 1: CRM Client Notes Table**
```php
// File: db/migrations/20241201000001_create_crm_client_notes_table.php
<?php
use Phinx\Migration\AbstractMigration;

class CreateCrmClientNotesTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('pct_crm_client_notes');
        $table->addColumn('sales_rep_id', 'integer', ['null' => false, 'comment' => 'FK to customer_basic_details.id where is_sales_rep=1'])
              ->addColumn('client_id', 'integer', ['null' => false, 'comment' => 'FK to customer_basic_details.id (client)'])
              ->addColumn('note_type', 'enum', [
                  'values' => ['general','follow_up','meeting','call','email','task'],
                  'default' => 'general'
              ])
              ->addColumn('subject', 'string', ['limit' => 255, 'null' => false])
              ->addColumn('content', 'text', ['null' => false])
              ->addColumn('priority', 'enum', [
                  'values' => ['low','normal','high','urgent'],
                  'default' => 'normal'
              ])
              ->addColumn('follow_up_date', 'date', ['null' => true, 'comment' => 'Date for follow-up reminder'])
              ->addColumn('is_completed', 'boolean', ['default' => 0, 'comment' => 'For follow-up tasks'])
              ->addColumn('is_private', 'boolean', ['default' => 0, 'comment' => 'Hide from managers if 1'])
              ->addColumn('order_id', 'integer', ['null' => true, 'comment' => 'Optional FK to order_details.id'])
              ->addColumn('created_by', 'integer', ['null' => false, 'comment' => 'User who created the note'])
              ->addColumn('updated_by', 'integer', ['null' => true, 'comment' => 'User who last updated the note'])
              ->addTimestamps()
              ->addIndex(['sales_rep_id', 'client_id'], ['name' => 'idx_sales_rep_client'])
              ->addIndex(['follow_up_date', 'is_completed'], ['name' => 'idx_follow_up_date'])
              ->addIndex(['client_id', 'created_at'], ['name' => 'idx_client_created'])
              ->addIndex(['note_type'], ['name' => 'idx_note_type'])
              ->addIndex(['priority'], ['name' => 'idx_priority'])
              ->create();
    }
}
```

#### **Migration 2: CRM Activities Table**
```php
// File: db/migrations/20241201000002_create_crm_activities_table.php
<?php
use Phinx\Migration\AbstractMigration;

class CreateCrmActivitiesTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('pct_crm_activities');
        $table->addColumn('sales_rep_id', 'integer', ['null' => false])
              ->addColumn('client_id', 'integer', ['null' => false])
              ->addColumn('activity_type', 'enum', [
                  'values' => ['email','call','meeting','note','task','order','document'],
                  'null' => false
              ])
              ->addColumn('subject', 'string', ['limit' => 255, 'null' => false])
              ->addColumn('description', 'text', ['null' => true])
              ->addColumn('activity_date', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('duration_minutes', 'integer', ['null' => true, 'comment' => 'Duration for calls/meetings'])
              ->addColumn('outcome', 'text', ['null' => true, 'comment' => 'Result or outcome of activity'])
              ->addColumn('next_action', 'text', ['null' => true, 'comment' => 'Recommended next steps'])
              ->addColumn('contact_method', 'enum', [
                  'values' => ['phone','email','in_person','video','text'],
                  'null' => true
              ])
              ->addColumn('participants', 'text', ['null' => true, 'comment' => 'JSON array of other participants'])
              ->addColumn('order_id', 'integer', ['null' => true, 'comment' => 'Related order if applicable'])
              ->addColumn('note_id', 'integer', ['null' => true, 'comment' => 'Related note if applicable'])
              ->addColumn('created_by', 'integer', ['null' => false])
              ->addTimestamp('created_at', ['default' => 'CURRENT_TIMESTAMP'])
              ->addIndex(['sales_rep_id', 'activity_date'], ['name' => 'idx_sales_rep_date'])
              ->addIndex(['client_id', 'activity_date'], ['name' => 'idx_client_date'])
              ->addIndex(['activity_type'], ['name' => 'idx_activity_type'])
              ->addIndex(['activity_date'], ['name' => 'idx_activity_date'])
              ->create();
    }
}
```

#### **Migration 3: CRM Follow-up Queue Table**
```php
// File: db/migrations/20241201000003_create_crm_follow_up_queue_table.php
<?php
use Phinx\Migration\AbstractMigration;

class CreateCrmFollowUpQueueTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('pct_crm_follow_up_queue');
        $table->addColumn('sales_rep_id', 'integer', ['null' => false])
              ->addColumn('client_id', 'integer', ['null' => false])
              ->addColumn('note_id', 'integer', ['null' => false, 'comment' => 'Related note with follow_up_date'])
              ->addColumn('due_date', 'date', ['null' => false])
              ->addColumn('priority', 'enum', [
                  'values' => ['low','normal','high','urgent'],
                  'default' => 'normal'
              ])
              ->addColumn('status', 'enum', [
                  'values' => ['pending','completed','snoozed','cancelled'],
                  'default' => 'pending'
              ])
              ->addColumn('completed_at', 'datetime', ['null' => true])
              ->addColumn('snoozed_until', 'date', ['null' => true])
              ->addColumn('reminder_sent', 'boolean', ['default' => 0])
              ->addTimestamps()
              ->addIndex(['sales_rep_id', 'due_date', 'status'], ['name' => 'idx_sales_rep_due'])
              ->addIndex(['due_date', 'status'], ['name' => 'idx_due_date_status'])
              ->addIndex(['reminder_sent', 'due_date'], ['name' => 'idx_reminder_sent'])
              ->create();
    }
}
```

#### **Migration 4: CRM Settings Table**
```php
// File: db/migrations/20241201000004_create_crm_settings_table.php
<?php
use Phinx\Migration\AbstractMigration;

class CreateCrmSettingsTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('pct_crm_settings');
        $table->addColumn('sales_rep_id', 'integer', ['null' => false])
              ->addColumn('setting_name', 'string', ['limit' => 100, 'null' => false])
              ->addColumn('setting_value', 'text', ['null' => false])
              ->addColumn('setting_type', 'enum', [
                  'values' => ['string','integer','boolean','json'],
                  'default' => 'string'
              ])
              ->addColumn('updated_by', 'integer', ['null' => false])
              ->addTimestamp('updated_at', [
                  'default' => 'CURRENT_TIMESTAMP',
                  'update' => 'CURRENT_TIMESTAMP'
              ])
              ->addIndex(['sales_rep_id', 'setting_name'], ['unique' => true, 'name' => 'unique_setting_per_user'])
              ->create();
    }
}
```

#### **Migration 5: Initialize Default Settings**
```php
// File: db/migrations/20241201000005_initialize_crm_default_settings.php
<?php
use Phinx\Migration\AbstractMigration;

class InitializeCrmDefaultSettings extends AbstractMigration
{
    public function up()
    {
        // Insert default CRM settings for all existing sales reps
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

        $this->execute("
            INSERT INTO pct_crm_settings (sales_rep_id, setting_name, setting_value, setting_type, updated_by, updated_at)
            SELECT 
                id as sales_rep_id,
                'dashboard_widgets' as setting_name,
                '{\"client_stats\":true,\"recent_notes\":true,\"pending_followups\":true,\"activity_summary\":true}' as setting_value,
                'json' as setting_type,
                id as updated_by,
                NOW() as updated_at
            FROM customer_basic_details 
            WHERE is_sales_rep = 1 AND status = 1
        ");

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
    }

    public function down()
    {
        $this->execute("DELETE FROM pct_crm_settings WHERE setting_name IN ('default_note_type', 'dashboard_widgets', 'followup_reminder_days')");
    }
}
```

## 🎮 Controllers

### **Main CRM Controller**
```php
// File: application/modules/frontend/controllers/order/Crm.php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crm extends MX_Controller 
{
    private $version = '01';
    
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('file', 'url', 'form'));
        $this->load->library('order/salesDashboardTemplate');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('order/crm_model');
        $this->load->model('order/crmNotes_model');
        $this->load->model('order/crmActivities_model');
        $this->load->library('order/common');
        
        $this->version = strtotime(date('Y-m-d'));
        
        // Ensure user is authenticated as sales rep
        $this->common->is_sales_user();
    }

    /**
     * Main CRM Dashboard
     */
    public function index()
    {
        $userdata = $this->session->userdata('user');
        $sales_rep_id = $userdata['id'];
        
        $data['title'] = 'CRM Dashboard | Pacific Coast Title Company';
        $data['stats'] = $this->crm_model->getDashboardStats($sales_rep_id);
        $data['recent_clients'] = $this->crm_model->getMyClients($sales_rep_id, '', 10);
        $data['pending_followups'] = $this->crmNotes_model->getPendingFollowUps($sales_rep_id, 5);
        $data['recent_activities'] = $this->crmActivities_model->getRecentActivities($sales_rep_id, 10);
        
        // Add CRM-specific JavaScript and CSS
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/crm.js?v=' . $this->version));
        $this->salesdashboardtemplate->addCSS(base_url('assets/frontend/css/crm.css?v=' . $this->version));
        
        $this->salesdashboardtemplate->show("crm/dashboard", "dashboard", $data);
    }

    /**
     * Client List Management
     */
    public function clients()
    {
        $userdata = $this->session->userdata('user');
        $sales_rep_id = $userdata['id'];
        
        // Handle AJAX requests for client search and pagination
        if ($this->input->is_ajax_request()) {
            $search = $this->input->post('search', true);
            $page = (int)$this->input->post('page', true) ?: 0;
            $limit = (int)$this->input->post('limit', true) ?: 25;
            $offset = $page * $limit;
            
            $clients = $this->crm_model->getMyClients($sales_rep_id, $search, $limit, $offset);
            
            echo json_encode([
                'success' => true,
                'data' => $clients,
                'page' => $page,
                'has_more' => count($clients) == $limit,
                'total_displayed' => count($clients)
            ]);
            return;
        }
        
        // Regular page load
        $data['title'] = 'My Clients | CRM';
        $data['clients'] = $this->crm_model->getMyClients($sales_rep_id, '', 25);
        $data['client_count'] = $this->crm_model->getClientCount($sales_rep_id);
        
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/crm-clients.js?v=' . $this->version));
        $this->salesdashboardtemplate->addCSS(base_url('assets/frontend/css/crm.css?v=' . $this->version));
        
        $this->salesdashboardtemplate->show("crm/clients", "clients", $data);
    }

    /**
     * Individual Client Profile
     */
    public function client($client_id)
    {
        $userdata = $this->session->userdata('user');
        $sales_rep_id = $userdata['id'];
        
        // Verify this client belongs to this sales rep
        if (!$this->crm_model->isMyClient($sales_rep_id, $client_id)) {
            show_404();
        }
        
        $data['title'] = 'Client Profile | CRM';
        $data['client'] = $this->crm_model->getClientDetails($client_id, $sales_rep_id);
        $data['notes'] = $this->crmNotes_model->getClientNotes($client_id, $sales_rep_id);
        $data['activities'] = $this->crmActivities_model->getClientActivities($client_id, $sales_rep_id);
        
        // Get recent orders for this client
        $data['orders'] = $this->crm_model->getClientOrders($client_id, $sales_rep_id);
        
        if (!$data['client']) {
            show_404();
        }
        
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/crm-client.js?v=' . $this->version));
        $this->salesdashboardtemplate->addCSS(base_url('assets/frontend/css/crm.css?v=' . $this->version));
        
        $this->salesdashboardtemplate->show("crm/client_profile", "client_profile", $data);
    }

    /**
     * Save Note (AJAX)
     */
    public function save_note()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $userdata = $this->session->userdata('user');
        $sales_rep_id = $userdata['id'];
        
        // Form validation
        $this->form_validation->set_rules('client_id', 'Client', 'required|integer');
        $this->form_validation->set_rules('subject', 'Subject', 'required|max_length[255]');
        $this->form_validation->set_rules('content', 'Content', 'required');
        $this->form_validation->set_rules('note_type', 'Note Type', 'required|in_list[general,follow_up,meeting,call,email,task]');
        $this->form_validation->set_rules('priority', 'Priority', 'required|in_list[low,normal,high,urgent]');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'errors' => validation_errors()
            ]);
            return;
        }
        
        $client_id = $this->input->post('client_id');
        
        // Verify client belongs to this sales rep
        if (!$this->crm_model->isMyClient($sales_rep_id, $client_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'Access denied to this client'
            ]);
            return;
        }
        
        $note_data = [
            'sales_rep_id' => $sales_rep_id,
            'client_id' => $client_id,
            'note_type' => $this->input->post('note_type'),
            'subject' => $this->input->post('subject'),
            'content' => $this->input->post('content'),
            'priority' => $this->input->post('priority'),
            'follow_up_date' => $this->input->post('follow_up_date') ?: null,
            'order_id' => $this->input->post('order_id') ?: null,
            'is_private' => $this->input->post('is_private') ? 1 : 0,
            'created_by' => $sales_rep_id
        ];
        
        $note_id = $this->crmNotes_model->saveNote($note_data);
        
        if ($note_id) {
            // Log this as an activity
            $activity_data = [
                'sales_rep_id' => $sales_rep_id,
                'client_id' => $client_id,
                'activity_type' => 'note',
                'subject' => $note_data['subject'],
                'description' => substr($note_data['content'], 0, 255),
                'note_id' => $note_id,
                'created_by' => $sales_rep_id
            ];
            $this->crmActivities_model->logActivity($activity_data);
            
            echo json_encode([
                'success' => true,
                'message' => 'Note saved successfully',
                'note_id' => $note_id
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to save note'
            ]);
        }
    }

    /**
     * Delete Note (AJAX)
     */
    public function delete_note()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $userdata = $this->session->userdata('user');
        $sales_rep_id = $userdata['id'];
        $note_id = $this->input->post('note_id');
        
        if (!$note_id) {
            echo json_encode([
                'success' => false,
                'message' => 'Note ID required'
            ]);
            return;
        }
        
        $result = $this->crmNotes_model->deleteNote($note_id, $sales_rep_id);
        
        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Note deleted successfully' : 'Failed to delete note'
        ]);
    }

    /**
     * Complete Follow-up (AJAX)
     */
    public function complete_followup()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $userdata = $this->session->userdata('user');
        $sales_rep_id = $userdata['id'];
        $followup_id = $this->input->post('followup_id');
        
        if (!$followup_id) {
            echo json_encode([
                'success' => false,
                'message' => 'Follow-up ID required'
            ]);
            return;
        }
        
        $result = $this->crmNotes_model->completeFollowUp($followup_id, $sales_rep_id);
        
        if ($result) {
            // Log completion as an activity
            $followup = $this->crmNotes_model->getFollowUpDetails($followup_id);
            if ($followup) {
                $activity_data = [
                    'sales_rep_id' => $sales_rep_id,
                    'client_id' => $followup['client_id'],
                    'activity_type' => 'task',
                    'subject' => 'Completed follow-up: ' . $followup['subject'],
                    'description' => 'Follow-up task marked as completed',
                    'note_id' => $followup['note_id'],
                    'created_by' => $sales_rep_id
                ];
                $this->crmActivities_model->logActivity($activity_data);
            }
        }
        
        echo json_encode([
            'success' => $result,
            'message' => $result ? 'Follow-up marked as completed' : 'Failed to update follow-up'
        ]);
    }

    /**
     * Quick Note Creation (AJAX)
     */
    public function quick_note()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $userdata = $this->session->userdata('user');
        $sales_rep_id = $userdata['id'];
        
        $client_id = $this->input->post('client_id');
        $note_content = $this->input->post('note');
        
        if (!$client_id || !$note_content) {
            echo json_encode([
                'success' => false,
                'message' => 'Client and note content required'
            ]);
            return;
        }
        
        // Verify client belongs to this sales rep
        if (!$this->crm_model->isMyClient($sales_rep_id, $client_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'Access denied to this client'
            ]);
            return;
        }
        
        $note_data = [
            'sales_rep_id' => $sales_rep_id,
            'client_id' => $client_id,
            'note_type' => 'general',
            'subject' => 'Quick Note - ' . date('M j, Y g:i A'),
            'content' => $note_content,
            'priority' => 'normal',
            'created_by' => $sales_rep_id
        ];
        
        $note_id = $this->crmNotes_model->saveNote($note_data);
        
        echo json_encode([
            'success' => (bool)$note_id,
            'message' => $note_id ? 'Quick note added successfully' : 'Failed to add note',
            'note_id' => $note_id
        ]);
    }

    /**
     * Log Activity (AJAX)
     */
    public function log_activity()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $userdata = $this->session->userdata('user');
        $sales_rep_id = $userdata['id'];
        
        // Form validation
        $this->form_validation->set_rules('client_id', 'Client', 'required|integer');
        $this->form_validation->set_rules('activity_type', 'Activity Type', 'required|in_list[email,call,meeting,note,task,order,document]');
        $this->form_validation->set_rules('subject', 'Subject', 'required|max_length[255]');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'success' => false,
                'errors' => validation_errors()
            ]);
            return;
        }
        
        $client_id = $this->input->post('client_id');
        
        // Verify client belongs to this sales rep
        if (!$this->crm_model->isMyClient($sales_rep_id, $client_id)) {
            echo json_encode([
                'success' => false,
                'message' => 'Access denied to this client'
            ]);
            return;
        }
        
        $activity_data = [
            'sales_rep_id' => $sales_rep_id,
            'client_id' => $client_id,
            'activity_type' => $this->input->post('activity_type'),
            'subject' => $this->input->post('subject'),
            'description' => $this->input->post('description'),
            'duration_minutes' => $this->input->post('duration_minutes') ?: null,
            'outcome' => $this->input->post('outcome'),
            'next_action' => $this->input->post('next_action'),
            'contact_method' => $this->input->post('contact_method'),
            'order_id' => $this->input->post('order_id') ?: null,
            'created_by' => $sales_rep_id
        ];
        
        $activity_id = $this->crmActivities_model->logActivity($activity_data);
        
        echo json_encode([
            'success' => (bool)$activity_id,
            'message' => $activity_id ? 'Activity logged successfully' : 'Failed to log activity',
            'activity_id' => $activity_id
        ]);
    }

    /**
     * Search Clients (AJAX)
     */
    public function search_clients()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $userdata = $this->session->userdata('user');
        $sales_rep_id = $userdata['id'];
        $search_term = $this->input->post('search');
        
        if (strlen($search_term) < 2) {
            echo json_encode([
                'success' => false,
                'message' => 'Search term must be at least 2 characters'
            ]);
            return;
        }
        
        $clients = $this->crm_model->searchClients($sales_rep_id, $search_term, 10);
        
        echo json_encode([
            'success' => true,
            'data' => $clients
        ]);
    }

    /**
     * Dashboard Statistics (AJAX)
     */
    public function dashboard_stats()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $userdata = $this->session->userdata('user');
        $sales_rep_id = $userdata['id'];
        
        $stats = $this->crm_model->getDashboardStats($sales_rep_id);
        
        echo json_encode([
            'success' => true,
            'data' => $stats
        ]);
    }
}
```

## 📊 Models

### **Main CRM Model**
```php
// File: application/modules/frontend/models/order/Crm_model.php
<?php
class Crm_model extends CI_Model
{
    protected $clients_table = 'customer_basic_details';
    protected $transactions_table = 'transaction_details';
    protected $orders_table = 'order_details';
    protected $notes_table = 'pct_crm_client_notes';
    protected $activities_table = 'pct_crm_activities';
    protected $settings_table = 'pct_crm_settings';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all clients for a specific sales rep with CRM summary data
     */
    public function getMyClients($sales_rep_id, $search = '', $limit = 50, $offset = 0)
    {
        $this->db->select('
            c.id,
            c.first_name,
            c.last_name, 
            c.company_name,
            c.email_address,
            c.telephone_no,
            c.street_address,
            c.city,
            c.state,
            c.zip_code,
            c.created_at as client_since,
            COUNT(DISTINCT o.id) as total_orders,
            COALESCE(SUM(CASE WHEN t.sales_amount IS NOT NULL THEN t.sales_amount ELSE 0 END), 0) as total_sales,
            MAX(o.created_at) as last_order_date,
            (SELECT COUNT(*) FROM ' . $this->notes_table . ' WHERE client_id = c.id AND sales_rep_id = ' . (int)$sales_rep_id . ') as note_count,
            (SELECT MAX(activity_date) FROM ' . $this->activities_table . ' WHERE client_id = c.id AND sales_rep_id = ' . (int)$sales_rep_id . ') as last_activity,
            (SELECT COUNT(*) FROM pct_crm_follow_up_queue WHERE client_id = c.id AND sales_rep_id = ' . (int)$sales_rep_id . ' AND status = "pending") as pending_followups
        ');
        
        $this->db->from($this->clients_table . ' c');
        $this->db->join($this->transactions_table . ' t', 'c.id = t.customer_id', 'inner');
        $this->db->join($this->orders_table . ' o', 't.id = o.transaction_id', 'left');
        $this->db->where('t.sales_representative', $sales_rep_id);
        $this->db->where('c.status', 1);
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('c.first_name', $search);
            $this->db->or_like('c.last_name', $search);
            $this->db->or_like('c.company_name', $search);
            $this->db->or_like('c.email_address', $search);
            $this->db->or_like('c.telephone_no', $search);
            $this->db->group_end();
        }
        
        $this->db->group_by('c.id');
        $this->db->order_by('c.company_name', 'ASC');
        
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result_array();
    }

    /**
     * Get client count for a sales rep
     */
    public function getClientCount($sales_rep_id)
    {
        $this->db->select('COUNT(DISTINCT c.id) as total');
        $this->db->from($this->clients_table . ' c');
        $this->db->join($this->transactions_table . ' t', 'c.id = t.customer_id', 'inner');
        $this->db->where('t.sales_representative', $sales_rep_id);
        $this->db->where('c.status', 1);
        
        $result = $this->db->get()->row_array();
        return $result['total'];
    }

    /**
     * Get detailed client information including CRM data
     */
    public function getClientDetails($client_id, $sales_rep_id = null)
    {
        $this->db->select('
            c.*,
            COUNT(DISTINCT o.id) as total_orders,
            COALESCE(SUM(CASE WHEN t.sales_amount IS NOT NULL THEN t.sales_amount ELSE 0 END), 0) as total_sales,
            MAX(o.created_at) as last_order_date,
            MIN(o.created_at) as first_order_date,
            AVG(t.sales_amount) as avg_order_value
        ');
        
        $this->db->from($this->clients_table . ' c');
        $this->db->join($this->transactions_table . ' t', 'c.id = t.customer_id', 'left');
        $this->db->join($this->orders_table . ' o', 't.id = o.transaction_id', 'left');
        $this->db->where('c.id', $client_id);
        
        if ($sales_rep_id) {
            $this->db->where('t.sales_representative', $sales_rep_id);
        }
        
        $this->db->group_by('c.id');
        
        return $this->db->get()->row_array();
    }

    /**
     * Verify that a client belongs to a specific sales rep
     */
    public function isMyClient($sales_rep_id, $client_id)
    {
        $this->db->select('COUNT(*) as count');
        $this->db->from($this->transactions_table);
        $this->db->where('sales_representative', $sales_rep_id);
        $this->db->where('customer_id', $client_id);
        
        $result = $this->db->get()->row_array();
        return $result['count'] > 0;
    }

    /**
     * Get client orders for a specific sales rep
     */
    public function getClientOrders($client_id, $sales_rep_id)
    {
        $this->db->select('
            o.id,
            o.file_number,
            o.created_at,
            o.status,
            t.sales_amount,
            p.address as property_address,
            p.city as property_city,
            p.state as property_state
        ');
        $this->db->from($this->orders_table . ' o');
        $this->db->join($this->transactions_table . ' t', 'o.transaction_id = t.id', 'inner');
        $this->db->join('property_details p', 'o.property_id = p.id', 'left');
        $this->db->where('t.customer_id', $client_id);
        $this->db->where('t.sales_representative', $sales_rep_id);
        $this->db->order_by('o.created_at', 'DESC');
        
        return $this->db->get()->result_array();
    }

    /**
     * Search clients by multiple criteria
     */
    public function searchClients($sales_rep_id, $search_term, $limit = 10)
    {
        $this->db->select('
            c.id,
            c.first_name,
            c.last_name,
            c.company_name,
            c.email_address,
            c.telephone_no
        ');
        $this->db->from($this->clients_table . ' c');
        $this->db->join($this->transactions_table . ' t', 'c.id = t.customer_id', 'inner');
        $this->db->where('t.sales_representative', $sales_rep_id);
        $this->db->where('c.status', 1);
        
        $this->db->group_start();
        $this->db->like('c.first_name', $search_term);
        $this->db->or_like('c.last_name', $search_term);
        $this->db->or_like('c.company_name', $search_term);
        $this->db->or_like('c.email_address', $search_term);
        $this->db->or_like('c.telephone_no', $search_term);
        $this->db->group_end();
        
        $this->db->group_by('c.id');
        $this->db->limit($limit);
        
        return $this->db->get()->result_array();
    }

    /**
     * Get dashboard statistics for CRM overview
     */
    public function getDashboardStats($sales_rep_id)
    {
        $stats = [];
        
        // Total clients
        $stats['total_clients'] = $this->getClientCount($sales_rep_id);
        
        // Notes this month
        $this->db->select('COUNT(*) as notes_this_month');
        $this->db->from($this->notes_table);
        $this->db->where('sales_rep_id', $sales_rep_id);
        $this->db->where('MONTH(created_at)', date('m'));
        $this->db->where('YEAR(created_at)', date('Y'));
        $result = $this->db->get()->row_array();
        $stats['notes_this_month'] = $result['notes_this_month'];
        
        // Pending follow-ups
        $this->db->select('COUNT(*) as pending_followups');
        $this->db->from('pct_crm_follow_up_queue');
        $this->db->where('sales_rep_id', $sales_rep_id);
        $this->db->where('status', 'pending');
        $this->db->where('due_date <=', date('Y-m-d'));
        $result = $this->db->get()->row_array();
        $stats['pending_followups'] = $result['pending_followups'];
        
        // Activities this week
        $this->db->select('COUNT(*) as activities_this_week');
        $this->db->from($this->activities_table);
        $this->db->where('sales_rep_id', $sales_rep_id);
        $this->db->where('activity_date >=', date('Y-m-d', strtotime('-7 days')));
        $result = $this->db->get()->row_array();
        $stats['activities_this_week'] = $result['activities_this_week'];
        
        // New clients this month
        $this->db->select('COUNT(DISTINCT c.id) as new_clients_this_month');
        $this->db->from($this->clients_table . ' c');
        $this->db->join($this->transactions_table . ' t', 'c.id = t.customer_id', 'inner');
        $this->db->where('t.sales_representative', $sales_rep_id);
        $this->db->where('MONTH(c.created_at)', date('m'));
        $this->db->where('YEAR(c.created_at)', date('Y'));
        $result = $this->db->get()->row_array();
        $stats['new_clients_this_month'] = $result['new_clients_this_month'];
        
        // Overdue follow-ups
        $this->db->select('COUNT(*) as overdue_followups');
        $this->db->from('pct_crm_follow_up_queue');
        $this->db->where('sales_rep_id', $sales_rep_id);
        $this->db->where('status', 'pending');
        $this->db->where('due_date <', date('Y-m-d'));
        $result = $this->db->get()->row_array();
        $stats['overdue_followups'] = $result['overdue_followups'];
        
        return $stats;
    }

    /**
     * Get CRM setting for a user
     */
    public function getSetting($sales_rep_id, $setting_name, $default_value = null)
    {
        $this->db->select('setting_value, setting_type');
        $this->db->from($this->settings_table);
        $this->db->where('sales_rep_id', $sales_rep_id);
        $this->db->where('setting_name', $setting_name);
        
        $result = $this->db->get()->row_array();
        
        if (!$result) {
            return $default_value;
        }
        
        $value = $result['setting_value'];
        
        // Convert based on type
        switch ($result['setting_type']) {
            case 'integer':
                return (int)$value;
            case 'boolean':
                return (bool)$value;
            case 'json':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    /**
     * Save CRM setting for a user
     */
    public function saveSetting($sales_rep_id, $setting_name, $setting_value, $setting_type = 'string')
    {
        // Convert value based on type
        switch ($setting_type) {
            case 'json':
                $value = json_encode($setting_value);
                break;
            case 'boolean':
                $value = $setting_value ? '1' : '0';
                break;
            default:
                $value = (string)$setting_value;
        }
        
        $data = [
            'sales_rep_id' => $sales_rep_id,
            'setting_name' => $setting_name,
            'setting_value' => $value,
            'setting_type' => $setting_type,
            'updated_by' => $sales_rep_id,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        // Check if setting exists
        $this->db->where('sales_rep_id', $sales_rep_id);
        $this->db->where('setting_name', $setting_name);
        $existing = $this->db->get($this->settings_table)->row_array();
        
        if ($existing) {
            // Update existing setting
            $this->db->where('sales_rep_id', $sales_rep_id);
            $this->db->where('setting_name', $setting_name);
            return $this->db->update($this->settings_table, $data);
        } else {
            // Insert new setting
            return $this->db->insert($this->settings_table, $data);
        }
    }
}
```

### **CRM Notes Model**
```php
// File: application/modules/frontend/models/order/CrmNotes_model.php
<?php
class CrmNotes_model extends CI_Model
{
    protected $table = 'pct_crm_client_notes';
    protected $followup_table = 'pct_crm_follow_up_queue';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all notes for a specific client
     */
    public function getClientNotes($client_id, $sales_rep_id = null, $limit = 50, $offset = 0)
    {
        $this->db->select('
            n.*,
            CONCAT(u.first_name, " ", u.last_name) as created_by_name,
            CONCAT(u2.first_name, " ", u2.last_name) as updated_by_name,
            o.file_number,
            f.id as followup_id,
            f.status as followup_status,
            f.due_date as followup_due_date
        ');
        $this->db->from($this->table . ' n');
        $this->db->join('customer_basic_details u', 'n.created_by = u.id', 'left');
        $this->db->join('customer_basic_details u2', 'n.updated_by = u2.id', 'left');
        $this->db->join('order_details o', 'n.order_id = o.id', 'left');
        $this->db->join($this->followup_table . ' f', 'n.id = f.note_id AND f.status = "pending"', 'left');
        $this->db->where('n.client_id', $client_id);
        
        if ($sales_rep_id) {
            $this->db->where('n.sales_rep_id', $sales_rep_id);
        }
        
        $this->db->order_by('n.created_at', 'DESC');
        
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result_array();
    }

    /**
     * Get a specific note by ID
     */
    public function getNote($note_id, $sales_rep_id = null)
    {
        $this->db->select('
            n.*,
            CONCAT(u.first_name, " ", u.last_name) as created_by_name,
            c.first_name as client_first_name,
            c.last_name as client_last_name,
            c.company_name as client_company
        ');
        $this->db->from($this->table . ' n');
        $this->db->join('customer_basic_details u', 'n.created_by = u.id', 'left');
        $this->db->join('customer_basic_details c', 'n.client_id = c.id', 'left');
        $this->db->where('n.id', $note_id);
        
        if ($sales_rep_id) {
            $this->db->where('n.sales_rep_id', $sales_rep_id);
        }
        
        return $this->db->get()->row_array();
    }

    /**
     * Save a new note or update existing one
     */
    public function saveNote($data)
    {
        if (isset($data['id']) && !empty($data['id'])) {
            // Update existing note
            $note_id = $data['id'];
            unset($data['id']);
            $data['updated_at'] = date('Y-m-d H:i:s');
            
            $this->db->where('id', $note_id);
            $result = $this->db->update($this->table, $data);
            
            return $result ? $note_id : false;
        } else {
            // Insert new note
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['updated_at'] = date('Y-m-d H:i:s');
            
            $result = $this->db->insert($this->table, $data);
            $note_id = $this->db->insert_id();
            
            // Create follow-up queue entry if follow_up_date is set
            if ($result && !empty($data['follow_up_date'])) {
                $this->createFollowUpEntry($note_id, $data);
            }
            
            return $result ? $note_id : false;
        }
    }

    /**
     * Delete a note
     */
    public function deleteNote($note_id, $sales_rep_id)
    {
        // Verify ownership before deletion
        $this->db->where('id', $note_id);
        $this->db->where('sales_rep_id', $sales_rep_id);
        
        $note = $this->db->get($this->table)->row_array();
        
        if (!$note) {
            return false;
        }
        
        // Delete associated follow-ups first
        $this->db->where('note_id', $note_id);
        $this->db->delete($this->followup_table);
        
        // Delete the note
        $this->db->where('id', $note_id);
        $this->db->where('sales_rep_id', $sales_rep_id);
        
        return $this->db->delete($this->table);
    }

    /**
     * Get pending follow-ups for a sales rep
     */
    public function getPendingFollowUps($sales_rep_id, $limit = 10)
    {
        $this->db->select('
            f.*,
            n.subject,
            n.note_type,
            n.priority,
            n.content,
            c.first_name,
            c.last_name,
            c.company_name,
            c.email_address,
            c.telephone_no
        ');
        $this->db->from($this->followup_table . ' f');
        $this->db->join($this->table . ' n', 'f.note_id = n.id');
        $this->db->join('customer_basic_details c', 'f.client_id = c.id');
        $this->db->where('f.sales_rep_id', $sales_rep_id);
        $this->db->where('f.status', 'pending');
        $this->db->where('f.due_date <=', date('Y-m-d'));
        $this->db->order_by('f.priority DESC, f.due_date ASC');
        
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result_array();
    }

    /**
     * Get follow-up details
     */
    public function getFollowUpDetails($followup_id)
    {
        $this->db->select('f.*, n.subject, n.client_id');
        $this->db->from($this->followup_table . ' f');
        $this->db->join($this->table . ' n', 'f.note_id = n.id');
        $this->db->where('f.id', $followup_id);
        
        return $this->db->get()->row_array();
    }

    /**
     * Create follow-up queue entry
     */
    private function createFollowUpEntry($note_id, $note_data)
    {
        $follow_up_data = [
            'sales_rep_id' => $note_data['sales_rep_id'],
            'client_id' => $note_data['client_id'],
            'note_id' => $note_id,
            'due_date' => $note_data['follow_up_date'],
            'priority' => $note_data['priority'],
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        return $this->db->insert($this->followup_table, $follow_up_data);
    }

    /**
     * Mark follow-up as completed
     */
    public function completeFollowUp($followup_id, $sales_rep_id)
    {
        $data = [
            'status' => 'completed',
            'completed_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $followup_id);
        $this->db->where('sales_rep_id', $sales_rep_id);
        
        return $this->db->update($this->followup_table, $data);
    }

    /**
     * Snooze follow-up to a new date
     */
    public function snoozeFollowUp($followup_id, $sales_rep_id, $new_date)
    {
        $data = [
            'status' => 'snoozed',
            'snoozed_until' => $new_date,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->db->where('id', $followup_id);
        $this->db->where('sales_rep_id', $sales_rep_id);
        
        return $this->db->update($this->followup_table, $data);
    }

    /**
     * Get note statistics for a sales rep
     */
    public function getNoteStats($sales_rep_id, $days = 30)
    {
        $stats = [];
        
        // Total notes
        $this->db->select('COUNT(*) as total');
        $this->db->from($this->table);
        $this->db->where('sales_rep_id', $sales_rep_id);
        $result = $this->db->get()->row_array();
        $stats['total_notes'] = $result['total'];
        
        // Notes in last X days
        $this->db->select('COUNT(*) as recent');
        $this->db->from($this->table);
        $this->db->where('sales_rep_id', $sales_rep_id);
        $this->db->where('created_at >=', date('Y-m-d H:i:s', strtotime("-{$days} days")));
        $result = $this->db->get()->row_array();
        $stats['recent_notes'] = $result['recent'];
        
        // Notes by type
        $this->db->select('note_type, COUNT(*) as count');
        $this->db->from($this->table);
        $this->db->where('sales_rep_id', $sales_rep_id);
        $this->db->group_by('note_type');
        $result = $this->db->get()->result_array();
        $stats['notes_by_type'] = $result;
        
        // Follow-up completion rate
        $this->db->select('
            COUNT(*) as total_followups,
            SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_followups
        ');
        $this->db->from($this->followup_table);
        $this->db->where('sales_rep_id', $sales_rep_id);
        $this->db->where('created_at >=', date('Y-m-d H:i:s', strtotime("-{$days} days")));
        $result = $this->db->get()->row_array();
        $stats['followup_completion_rate'] = $result['total_followups'] > 0 
            ? round(($result['completed_followups'] / $result['total_followups']) * 100, 1)
            : 0;
        
        return $stats;
    }
}
```

### **CRM Activities Model**
```php
// File: application/modules/frontend/models/order/CrmActivities_model.php
<?php
class CrmActivities_model extends CI_Model
{
    protected $table = 'pct_crm_activities';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Log a new activity
     */
    public function logActivity($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        
        if (empty($data['activity_date'])) {
            $data['activity_date'] = date('Y-m-d H:i:s');
        }
        
        $result = $this->db->insert($this->table, $data);
        
        return $result ? $this->db->insert_id() : false;
    }

    /**
     * Get activities for a specific client
     */
    public function getClientActivities($client_id, $sales_rep_id = null, $limit = 50, $offset = 0)
    {
        $this->db->select('
            a.*,
            CONCAT(u.first_name, " ", u.last_name) as created_by_name,
            o.file_number,
            n.subject as note_subject
        ');
        $this->db->from($this->table . ' a');
        $this->db->join('customer_basic_details u', 'a.created_by = u.id', 'left');
        $this->db->join('order_details o', 'a.order_id = o.id', 'left');
        $this->db->join('pct_crm_client_notes n', 'a.note_id = n.id', 'left');
        $this->db->where('a.client_id', $client_id);
        
        if ($sales_rep_id) {
            $this->db->where('a.sales_rep_id', $sales_rep_id);
        }
        
        $this->db->order_by('a.activity_date', 'DESC');
        
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }
        
        return $this->db->get()->result_array();
    }

    /**
     * Get recent activities for a sales rep
     */
    public function getRecentActivities($sales_rep_id, $limit = 10)
    {
        $this->db->select('
            a.*,
            c.first_name as client_first_name,
            c.last_name as client_last_name,
            c.company_name as client_company,
            o.file_number
        ');
        $this->db->from($this->table . ' a');
        $this->db->join('customer_basic_details c', 'a.client_id = c.id');
        $this->db->join('order_details o', 'a.order_id = o.id', 'left');
        $this->db->where('a.sales_rep_id', $sales_rep_id);
        $this->db->order_by('a.activity_date', 'DESC');
        
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        
        return $this->db->get()->result_array();
    }

    /**
     * Get activity statistics for a sales rep
     */
    public function getActivityStats($sales_rep_id, $days = 30)
    {
        $stats = [];
        
        // Total activities
        $this->db->select('COUNT(*) as total');
        $this->db->from($this->table);
        $this->db->where('sales_rep_id', $sales_rep_id);
        $result = $this->db->get()->row_array();
        $stats['total_activities'] = $result['total'];
        
        // Activities by type in last X days
        $this->db->select('activity_type, COUNT(*) as count');
        $this->db->from($this->table);
        $this->db->where('sales_rep_id', $sales_rep_id);
        $this->db->where('activity_date >=', date('Y-m-d H:i:s', strtotime("-{$days} days")));
        $this->db->group_by('activity_type');
        $result = $this->db->get()->result_array();
        $stats['activities_by_type'] = $result;
        
        // Average activities per day (last 30 days)
        $this->db->select('COUNT(*) as count');
        $this->db->from($this->table);
        $this->db->where('sales_rep_id', $sales_rep_id);
        $this->db->where('activity_date >=', date('Y-m-d H:i:s', strtotime('-30 days')));
        $result = $this->db->get()->row_array();
        $stats['avg_activities_per_day'] = round($result['count'] / 30, 1);
        
        // Most active clients (last 30 days)
        $this->db->select('
            c.id,
            c.first_name,
            c.last_name,
            c.company_name,
            COUNT(*) as activity_count
        ');
        $this->db->from($this->table . ' a');
        $this->db->join('customer_basic_details c', 'a.client_id = c.id');
        $this->db->where('a.sales_rep_id', $sales_rep_id);
        $this->db->where('a.activity_date >=', date('Y-m-d H:i:s', strtotime('-30 days')));
        $this->db->group_by('a.client_id');
        $this->db->order_by('activity_count', 'DESC');
        $this->db->limit(5);
        $result = $this->db->get()->result_array();
        $stats['most_active_clients'] = $result;
        
        return $stats;
    }

    /**
     * Update an activity
     */
    public function updateActivity($activity_id, $data, $sales_rep_id)
    {
        $this->db->where('id', $activity_id);
        $this->db->where('sales_rep_id', $sales_rep_id);
        
        return $this->db->update($this->table, $data);
    }

    /**
     * Delete an activity
     */
    public function deleteActivity($activity_id, $sales_rep_id)
    {
        $this->db->where('id', $activity_id);
        $this->db->where('sales_rep_id', $sales_rep_id);
        
        return $this->db->delete($this->table);
    }

    /**
     * Get activity timeline for dashboard
     */
    public function getActivityTimeline($sales_rep_id, $days = 7)
    {
        $this->db->select('
            DATE(activity_date) as date,
            activity_type,
            COUNT(*) as count
        ');
        $this->db->from($this->table);
        $this->db->where('sales_rep_id', $sales_rep_id);
        $this->db->where('activity_date >=', date('Y-m-d', strtotime("-{$days} days")));
        $this->db->group_by('DATE(activity_date), activity_type');
        $this->db->order_by('date ASC');
        
        return $this->db->get()->result_array();
    }
}
```

This comprehensive code template provides all the necessary components to implement a fully functional CRM system that integrates seamlessly with the existing Transaction Desk infrastructure.
