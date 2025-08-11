# CRM Database Design

## 📊 Database Schema Overview

### **Design Principles**
- **Non-destructive**: Only add new tables, never modify existing ones
- **Relational Integrity**: Proper foreign key relationships with existing data
- **Performance Optimized**: Strategic indexing for fast queries
- **Scalable**: Design supports growth in users and data volume
- **Audit Trail**: Complete tracking of all CRM activities

## 🗃 New Tables Required

### **1. Client Notes Table**

```sql
CREATE TABLE `pct_crm_client_notes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_rep_id` int(11) NOT NULL COMMENT 'FK to customer_basic_details.id where is_sales_rep=1',
  `client_id` int(11) NOT NULL COMMENT 'FK to customer_basic_details.id (client)',
  `note_type` enum('general','follow_up','meeting','call','email','task') DEFAULT 'general',
  `subject` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `priority` enum('low','normal','high','urgent') DEFAULT 'normal',
  `follow_up_date` date NULL COMMENT 'Date for follow-up reminder',
  `is_completed` tinyint(1) DEFAULT 0 COMMENT 'For follow-up tasks',
  `is_private` tinyint(1) DEFAULT 0 COMMENT 'Hide from managers if 1',
  `order_id` int(11) NULL COMMENT 'Optional FK to order_details.id',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL COMMENT 'User who created the note',
  `updated_by` int(11) NULL COMMENT 'User who last updated the note',
  
  PRIMARY KEY (`id`),
  KEY `idx_sales_rep_client` (`sales_rep_id`, `client_id`),
  KEY `idx_follow_up_date` (`follow_up_date`, `is_completed`),
  KEY `idx_client_created` (`client_id`, `created_at`),
  KEY `idx_note_type` (`note_type`),
  KEY `idx_priority` (`priority`),
  
  CONSTRAINT `fk_crm_notes_sales_rep` 
    FOREIGN KEY (`sales_rep_id`) 
    REFERENCES `customer_basic_details` (`id`) 
    ON DELETE CASCADE,
  CONSTRAINT `fk_crm_notes_client` 
    FOREIGN KEY (`client_id`) 
    REFERENCES `customer_basic_details` (`id`) 
    ON DELETE CASCADE,
  CONSTRAINT `fk_crm_notes_order` 
    FOREIGN KEY (`order_id`) 
    REFERENCES `order_details` (`id`) 
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Purpose**: Store all client-related notes, tasks, and follow-up items

**Key Features**:
- Links sales reps to their client notes
- Supports different note types and priorities
- Follow-up date tracking with completion status
- Privacy controls for sensitive notes
- Optional linkage to specific orders
- Full audit trail with created/updated tracking

### **2. Activity Tracking Table**

```sql
CREATE TABLE `pct_crm_activities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_rep_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `activity_type` enum('email','call','meeting','note','task','order','document') NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NULL,
  `activity_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `duration_minutes` int(11) NULL COMMENT 'Duration for calls/meetings',
  `outcome` text NULL COMMENT 'Result or outcome of activity',
  `next_action` text NULL COMMENT 'Recommended next steps',
  `contact_method` enum('phone','email','in_person','video','text') NULL,
  `participants` text NULL COMMENT 'JSON array of other participants',
  `order_id` int(11) NULL COMMENT 'Related order if applicable',
  `note_id` int(11) NULL COMMENT 'Related note if applicable',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  
  PRIMARY KEY (`id`),
  KEY `idx_sales_rep_date` (`sales_rep_id`, `activity_date`),
  KEY `idx_client_date` (`client_id`, `activity_date`),
  KEY `idx_activity_type` (`activity_type`),
  KEY `idx_activity_date` (`activity_date`),
  
  CONSTRAINT `fk_crm_activities_sales_rep` 
    FOREIGN KEY (`sales_rep_id`) 
    REFERENCES `customer_basic_details` (`id`) 
    ON DELETE CASCADE,
  CONSTRAINT `fk_crm_activities_client` 
    FOREIGN KEY (`client_id`) 
    REFERENCES `customer_basic_details` (`id`) 
    ON DELETE CASCADE,
  CONSTRAINT `fk_crm_activities_order` 
    FOREIGN KEY (`order_id`) 
    REFERENCES `order_details` (`id`) 
    ON DELETE SET NULL,
  CONSTRAINT `fk_crm_activities_note` 
    FOREIGN KEY (`note_id`) 
    REFERENCES `pct_crm_client_notes` (`id`) 
    ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Purpose**: Comprehensive tracking of all client interactions and activities

**Key Features**:
- Records all types of client interactions
- Links to orders and notes when relevant
- Tracks duration and outcomes
- Supports multiple contact methods
- Maintains complete activity timeline

### **3. Client Tags Table**

```sql
CREATE TABLE `pct_crm_client_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_rep_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `tag_name` varchar(50) NOT NULL,
  `tag_color` varchar(7) DEFAULT '#007bff' COMMENT 'Hex color code',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tag_per_client` (`sales_rep_id`, `client_id`, `tag_name`),
  KEY `idx_sales_rep_tag` (`sales_rep_id`, `tag_name`),
  KEY `idx_client_tags` (`client_id`),
  
  CONSTRAINT `fk_crm_tags_sales_rep` 
    FOREIGN KEY (`sales_rep_id`) 
    REFERENCES `customer_basic_details` (`id`) 
    ON DELETE CASCADE,
  CONSTRAINT `fk_crm_tags_client` 
    FOREIGN KEY (`client_id`) 
    REFERENCES `customer_basic_details` (`id`) 
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Purpose**: Flexible tagging system for client categorization

**Key Features**:
- Custom tags per sales rep
- Color coding for visual organization
- Prevents duplicate tags per client
- Fast filtering by tag names

### **4. CRM Settings Table**

```sql
CREATE TABLE `pct_crm_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_rep_id` int(11) NOT NULL,
  `setting_name` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  `setting_type` enum('string','integer','boolean','json') DEFAULT 'string',
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL,
  
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_setting_per_user` (`sales_rep_id`, `setting_name`),
  
  CONSTRAINT `fk_crm_settings_sales_rep` 
    FOREIGN KEY (`sales_rep_id`) 
    REFERENCES `customer_basic_details` (`id`) 
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Purpose**: Store user preferences and CRM configuration

**Key Features**:
- Per-user customization settings
- Flexible value types (string, integer, boolean, JSON)
- Settings like: default_note_type, follow_up_reminder_days, dashboard_layout

### **5. Follow-up Queue Table**

```sql
CREATE TABLE `pct_crm_follow_up_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_rep_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `note_id` int(11) NOT NULL COMMENT 'Related note with follow_up_date',
  `due_date` date NOT NULL,
  `priority` enum('low','normal','high','urgent') DEFAULT 'normal',
  `status` enum('pending','completed','snoozed','cancelled') DEFAULT 'pending',
  `completed_at` datetime NULL,
  `snoozed_until` date NULL,
  `reminder_sent` tinyint(1) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  PRIMARY KEY (`id`),
  KEY `idx_sales_rep_due` (`sales_rep_id`, `due_date`, `status`),
  KEY `idx_due_date_status` (`due_date`, `status`),
  KEY `idx_reminder_sent` (`reminder_sent`, `due_date`),
  
  CONSTRAINT `fk_crm_followup_sales_rep` 
    FOREIGN KEY (`sales_rep_id`) 
    REFERENCES `customer_basic_details` (`id`) 
    ON DELETE CASCADE,
  CONSTRAINT `fk_crm_followup_client` 
    FOREIGN KEY (`client_id`) 
    REFERENCES `customer_basic_details` (`id`) 
    ON DELETE CASCADE,
  CONSTRAINT `fk_crm_followup_note` 
    FOREIGN KEY (`note_id`) 
    REFERENCES `pct_crm_client_notes` (`id`) 
    ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Purpose**: Manage follow-up reminders and task queue

**Key Features**:
- Automated follow-up tracking
- Support for snoozing and rescheduling
- Email reminder tracking
- Priority-based organization

## 🔗 Relationship Mapping

### **Integration with Existing Tables**

```sql
-- Sales Rep to Client Relationship (EXISTING)
customer_basic_details (sales_rep: is_sales_rep=1)
    ↓
transaction_details.sales_representative
    ↓  
customer_basic_details (client: is_sales_rep=0)

-- Enhanced with CRM Tables (NEW)
customer_basic_details (sales_rep)
    ↓
pct_crm_client_notes.sales_rep_id → pct_crm_client_notes.client_id
    ↓
customer_basic_details (client)

-- Activity Tracking
pct_crm_activities → links to notes, orders, clients, sales reps

-- Follow-up Management  
pct_crm_follow_up_queue → automatically created from notes with follow_up_date
```

### **Data Flow Examples**

```sql
-- Get all clients for a sales rep with CRM summary
SELECT 
    c.id,
    c.first_name,
    c.last_name, 
    c.company_name,
    c.email_address,
    COUNT(DISTINCT o.id) as total_orders,
    COUNT(DISTINCT n.id) as note_count,
    COUNT(DISTINCT a.id) as activity_count,
    MAX(a.activity_date) as last_activity
FROM customer_basic_details c
INNER JOIN transaction_details t ON c.id = t.customer_id  
LEFT JOIN order_details o ON t.id = o.transaction_id
LEFT JOIN pct_crm_client_notes n ON c.id = n.client_id
LEFT JOIN pct_crm_activities a ON c.id = a.client_id
WHERE t.sales_representative = ? 
GROUP BY c.id;

-- Get pending follow-ups for dashboard
SELECT 
    f.id,
    f.due_date,
    f.priority,
    c.first_name,
    c.last_name,
    c.company_name,
    n.subject,
    n.note_type
FROM pct_crm_follow_up_queue f
INNER JOIN customer_basic_details c ON f.client_id = c.id
INNER JOIN pct_crm_client_notes n ON f.note_id = n.id  
WHERE f.sales_rep_id = ?
  AND f.status = 'pending'
  AND f.due_date <= CURDATE()
ORDER BY f.priority DESC, f.due_date ASC;
```

## 📁 Migration Files

### **Migration 1: Create CRM Client Notes Table**

```php
// File: db/migrations/20241201000001_create_crm_client_notes_table.php
<?php
use Phinx\Migration\AbstractMigration;

class CreateCrmClientNotesTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('pct_crm_client_notes');
        $table->addColumn('sales_rep_id', 'integer', ['null' => false])
              ->addColumn('client_id', 'integer', ['null' => false])
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
              ->addColumn('follow_up_date', 'date', ['null' => true])
              ->addColumn('is_completed', 'boolean', ['default' => 0])
              ->addColumn('is_private', 'boolean', ['default' => 0])
              ->addColumn('order_id', 'integer', ['null' => true])
              ->addColumn('created_by', 'integer', ['null' => false])
              ->addColumn('updated_by', 'integer', ['null' => true])
              ->addTimestamps()
              ->addIndex(['sales_rep_id', 'client_id'])
              ->addIndex(['follow_up_date', 'is_completed'])
              ->addIndex(['client_id', 'created_at'])
              ->addIndex(['note_type'])
              ->addIndex(['priority'])
              ->create();
    }
}
```

### **Migration 2: Create CRM Activities Table**

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
              ->addColumn('duration_minutes', 'integer', ['null' => true])
              ->addColumn('outcome', 'text', ['null' => true])
              ->addColumn('next_action', 'text', ['null' => true])
              ->addColumn('contact_method', 'enum', [
                  'values' => ['phone','email','in_person','video','text'],
                  'null' => true
              ])
              ->addColumn('participants', 'text', ['null' => true])
              ->addColumn('order_id', 'integer', ['null' => true])
              ->addColumn('note_id', 'integer', ['null' => true])
              ->addColumn('created_by', 'integer', ['null' => false])
              ->addTimestamp('created_at', ['default' => 'CURRENT_TIMESTAMP'])
              ->addIndex(['sales_rep_id', 'activity_date'])
              ->addIndex(['client_id', 'activity_date'])
              ->addIndex(['activity_type'])
              ->addIndex(['activity_date'])
              ->create();
    }
}
```

### **Migration 3: Create CRM Client Tags Table**

```php
// File: db/migrations/20241201000003_create_crm_client_tags_table.php
<?php
use Phinx\Migration\AbstractMigration;

class CreateCrmClientTagsTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('pct_crm_client_tags');
        $table->addColumn('sales_rep_id', 'integer', ['null' => false])
              ->addColumn('client_id', 'integer', ['null' => false])
              ->addColumn('tag_name', 'string', ['limit' => 50, 'null' => false])
              ->addColumn('tag_color', 'string', ['limit' => 7, 'default' => '#007bff'])
              ->addColumn('created_by', 'integer', ['null' => false])
              ->addTimestamp('created_at', ['default' => 'CURRENT_TIMESTAMP'])
              ->addIndex(['sales_rep_id', 'client_id', 'tag_name'], ['unique' => true])
              ->addIndex(['sales_rep_id', 'tag_name'])
              ->addIndex(['client_id'])
              ->create();
    }
}
```

### **Migration 4: Create CRM Settings Table**

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
              ->addIndex(['sales_rep_id', 'setting_name'], ['unique' => true])
              ->create();
    }
}
```

### **Migration 5: Create Follow-up Queue Table**

```php
// File: db/migrations/20241201000005_create_crm_follow_up_queue_table.php
<?php
use Phinx\Migration\AbstractMigration;

class CreateCrmFollowUpQueueTable extends AbstractMigration
{
    public function change()
    {
        $table = $this->table('pct_crm_follow_up_queue');
        $table->addColumn('sales_rep_id', 'integer', ['null' => false])
              ->addColumn('client_id', 'integer', ['null' => false])
              ->addColumn('note_id', 'integer', ['null' => false])
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
              ->addIndex(['sales_rep_id', 'due_date', 'status'])
              ->addIndex(['due_date', 'status'])
              ->addIndex(['reminder_sent', 'due_date'])
              ->create();
    }
}
```

## 🚀 Database Performance Optimization

### **Indexing Strategy**

```sql
-- Primary performance indexes (already included in migrations above)
-- Additional composite indexes for common queries:

-- Dashboard queries
CREATE INDEX idx_crm_dashboard_stats ON pct_crm_client_notes 
(sales_rep_id, created_at, note_type);

-- Client search queries  
CREATE INDEX idx_client_activity_summary ON pct_crm_activities 
(client_id, activity_date, activity_type);

-- Follow-up reminder queries
CREATE INDEX idx_followup_reminders ON pct_crm_follow_up_queue 
(due_date, reminder_sent, status, sales_rep_id);
```

### **Query Optimization Examples**

```sql
-- Optimized query for client list with CRM data
SELECT SQL_CALC_FOUND_ROWS
    c.id,
    c.first_name,
    c.last_name,
    c.company_name,
    c.email_address,
    c.telephone_no,
    (SELECT COUNT(*) FROM pct_crm_client_notes WHERE client_id = c.id) as note_count,
    (SELECT MAX(activity_date) FROM pct_crm_activities WHERE client_id = c.id) as last_activity,
    (SELECT COUNT(*) FROM pct_crm_follow_up_queue WHERE client_id = c.id AND status = 'pending') as pending_followups
FROM customer_basic_details c
INNER JOIN transaction_details t ON c.id = t.customer_id
WHERE t.sales_representative = ?
  AND c.status = 1
ORDER BY c.company_name ASC
LIMIT 0, 50;
```

## 📋 Data Migration Considerations

### **Initial Data Population**

```sql
-- Create default CRM settings for existing sales reps
INSERT INTO pct_crm_settings (sales_rep_id, setting_name, setting_value, setting_type, updated_by)
SELECT 
    id,
    'default_note_type',
    'general',
    'string',
    id
FROM customer_basic_details 
WHERE is_sales_rep = 1 AND status = 1;

-- Create welcome notes for existing client relationships
INSERT INTO pct_crm_client_notes (sales_rep_id, client_id, note_type, subject, content, created_by)
SELECT DISTINCT
    t.sales_representative,
    t.customer_id,
    'general',
    'Initial CRM Setup',
    CONCAT('Client relationship established. Total orders: ', COUNT(o.id)),
    t.sales_representative
FROM transaction_details t
LEFT JOIN order_details o ON t.id = o.transaction_id
WHERE t.sales_representative IS NOT NULL
GROUP BY t.sales_representative, t.customer_id;
```

### **Data Integrity Checks**

```sql
-- Verify all CRM records have valid sales reps
SELECT 'Invalid sales_rep_id in notes' as issue, COUNT(*) as count
FROM pct_crm_client_notes n
LEFT JOIN customer_basic_details s ON n.sales_rep_id = s.id
WHERE s.id IS NULL OR s.is_sales_rep != 1;

-- Verify all CRM records have valid clients  
SELECT 'Invalid client_id in notes' as issue, COUNT(*) as count
FROM pct_crm_client_notes n
LEFT JOIN customer_basic_details c ON n.client_id = c.id
WHERE c.id IS NULL;
```

This database design provides a solid foundation for the CRM system while maintaining compatibility with the existing Transaction Desk infrastructure.
