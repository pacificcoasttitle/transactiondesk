# CRM User Interface Design

## 🎨 Design Philosophy

### **Design Principles**
- **Consistency**: Follow existing Transaction Desk UI patterns
- **Simplicity**: Clean, intuitive interfaces that require minimal training
- **Efficiency**: Quick access to common tasks and information
- **Responsiveness**: Mobile-first design for field work
- **Integration**: Seamless integration with existing sales dashboard

### **Visual Guidelines**
- **Color Scheme**: Use existing PCT brand colors
- **Typography**: Consistent with current system fonts
- **Icons**: FontAwesome icons matching existing interface
- **Layout**: Bootstrap 4 grid system and components
- **Interactions**: jQuery-based with smooth animations

## 📱 User Interface Components

### **1. Enhanced Sales Dashboard**

#### **CRM Dashboard Widgets**
```html
<!-- File: application/modules/frontend/views/order/crm/dashboard_widgets.php -->
<div class="row crm-dashboard-widgets">
    <!-- Client Stats Widget -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card crm-stat-card">
            <div class="card-body text-center">
                <div class="crm-stat-icon">
                    <i class="fas fa-users text-primary"></i>
                </div>
                <h3 class="crm-stat-number"><?= $stats['total_clients'] ?></h3>
                <p class="crm-stat-label">Total Clients</p>
                <div class="crm-stat-change">
                    <i class="fas fa-arrow-up text-success"></i>
                    <span class="text-success">+<?= $stats['new_clients_this_month'] ?> this month</span>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?= base_url('crm/clients') ?>" class="btn btn-primary btn-sm btn-block">
                    View All Clients
                </a>
            </div>
        </div>
    </div>

    <!-- Notes Widget -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card crm-stat-card">
            <div class="card-body text-center">
                <div class="crm-stat-icon">
                    <i class="fas fa-sticky-note text-warning"></i>
                </div>
                <h3 class="crm-stat-number"><?= $stats['notes_this_month'] ?></h3>
                <p class="crm-stat-label">Notes This Month</p>
                <div class="crm-stat-change">
                    <span class="text-muted">Keep tracking!</span>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-warning btn-sm btn-block" onclick="showQuickNoteModal()">
                    Add Quick Note
                </button>
            </div>
        </div>
    </div>

    <!-- Follow-ups Widget -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card crm-stat-card">
            <div class="card-body text-center">
                <div class="crm-stat-icon">
                    <i class="fas fa-bell text-danger"></i>
                </div>
                <h3 class="crm-stat-number"><?= $stats['pending_followups'] ?></h3>
                <p class="crm-stat-label">Pending Follow-ups</p>
                <div class="crm-stat-change">
                    <?php if ($stats['overdue_followups'] > 0): ?>
                    <i class="fas fa-exclamation-triangle text-danger"></i>
                    <span class="text-danger"><?= $stats['overdue_followups'] ?> overdue</span>
                    <?php else: ?>
                    <span class="text-success">All up to date!</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?= base_url('crm/follow-ups') ?>" class="btn btn-danger btn-sm btn-block">
                    Review Follow-ups
                </a>
            </div>
        </div>
    </div>

    <!-- Activities Widget -->
    <div class="col-md-3 col-sm-6 mb-4">
        <div class="card crm-stat-card">
            <div class="card-body text-center">
                <div class="crm-stat-icon">
                    <i class="fas fa-chart-line text-success"></i>
                </div>
                <h3 class="crm-stat-number"><?= $stats['activities_this_week'] ?></h3>
                <p class="crm-stat-label">Activities This Week</p>
                <div class="crm-stat-change">
                    <span class="text-muted">Stay active!</span>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-success btn-sm btn-block" onclick="showLogActivityModal()">
                    Log Activity
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions Row -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt text-warning"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Quick Note</h6>
                        <form id="quick-note-form" class="crm-quick-form">
                            <div class="input-group">
                                <select name="client_id" class="form-control" required>
                                    <option value="">Select Client...</option>
                                    <?php foreach($recent_clients as $client): ?>
                                    <option value="<?= $client['id'] ?>">
                                        <?= $client['company_name'] ?: $client['first_name'].' '.$client['last_name'] ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="text" name="note" class="form-control" placeholder="Quick note..." required>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6">
                        <h6>Client Search</h6>
                        <div class="input-group">
                            <input type="text" id="client-search" class="form-control" placeholder="Search clients...">
                            <div class="input-group-append">
                                <button class="btn btn-primary" onclick="searchClients()">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </div>
                        </div>
                        <div id="search-results" class="crm-search-results mt-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Feed -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-clock text-info"></i>
                    Recent Activities
                </h5>
            </div>
            <div class="card-body">
                <div class="crm-activity-feed">
                    <?php foreach($recent_activities as $activity): ?>
                    <div class="crm-activity-item">
                        <div class="crm-activity-icon">
                            <?php
                            $icon_map = [
                                'call' => 'fa-phone text-primary',
                                'email' => 'fa-envelope text-info',
                                'meeting' => 'fa-calendar text-success',
                                'note' => 'fa-sticky-note text-warning',
                                'task' => 'fa-tasks text-danger'
                            ];
                            $icon = $icon_map[$activity['activity_type']] ?? 'fa-circle text-secondary';
                            ?>
                            <i class="fas <?= $icon ?>"></i>
                        </div>
                        <div class="crm-activity-content">
                            <div class="crm-activity-title">
                                <?= htmlspecialchars($activity['subject']) ?>
                            </div>
                            <div class="crm-activity-meta">
                                <span class="crm-activity-client">
                                    <?= $activity['client_company'] ?: $activity['client_first_name'].' '.$activity['client_last_name'] ?>
                                </span>
                                <span class="crm-activity-time">
                                    <?= time_ago($activity['activity_date']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mt-3">
                    <a href="<?= base_url('crm/activities') ?>" class="btn btn-outline-primary btn-sm">
                        View All Activities
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-circle text-warning"></i>
                    Pending Follow-ups
                </h5>
            </div>
            <div class="card-body">
                <div class="crm-followup-list">
                    <?php if (empty($pending_followups)): ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <p>All caught up! No pending follow-ups.</p>
                    </div>
                    <?php else: ?>
                    <?php foreach($pending_followups as $followup): ?>
                    <div class="crm-followup-item" data-followup-id="<?= $followup['id'] ?>">
                        <div class="crm-followup-priority">
                            <?php
                            $priority_colors = [
                                'urgent' => 'badge-danger',
                                'high' => 'badge-warning',
                                'normal' => 'badge-info',
                                'low' => 'badge-secondary'
                            ];
                            $priority_color = $priority_colors[$followup['priority']] ?? 'badge-secondary';
                            ?>
                            <span class="badge <?= $priority_color ?>"><?= ucfirst($followup['priority']) ?></span>
                        </div>
                        <div class="crm-followup-content">
                            <div class="crm-followup-subject">
                                <?= htmlspecialchars($followup['subject']) ?>
                            </div>
                            <div class="crm-followup-client">
                                <?= $followup['company_name'] ?: $followup['first_name'].' '.$followup['last_name'] ?>
                            </div>
                            <div class="crm-followup-date">
                                Due: <?= date('M j, Y', strtotime($followup['due_date'])) ?>
                                <?php if ($followup['due_date'] < date('Y-m-d')): ?>
                                <span class="text-danger">(Overdue)</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="crm-followup-actions">
                            <button class="btn btn-success btn-sm" onclick="completeFollowUp(<?= $followup['id'] ?>)">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-warning btn-sm" onclick="snoozeFollowUp(<?= $followup['id'] ?>)">
                                <i class="fas fa-snooze"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="text-center mt-3">
                    <a href="<?= base_url('crm/follow-ups') ?>" class="btn btn-outline-warning btn-sm">
                        Manage All Follow-ups
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
```

### **2. Client List Interface**

#### **Client List Page**
```html
<!-- File: application/modules/frontend/views/order/crm/clients.php -->
<div class="container-fluid crm-clients">
    <!-- Page Header -->
    <div class="row">
        <div class="col-md-12">
            <div class="crm-page-header">
                <h2>
                    <i class="fas fa-users text-primary"></i>
                    My Clients
                    <span class="badge badge-secondary"><?= $client_count ?></span>
                </h2>
                <p class="text-muted">Manage your client relationships and interactions</p>
            </div>
        </div>
    </div>

    <!-- Search and Filter Controls -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="client-search-input">Search Clients</label>
                                <div class="input-group">
                                    <input type="text" id="client-search-input" class="form-control" 
                                           placeholder="Search by name, company, email, or phone...">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" onclick="searchClients()">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="filter-activity">Activity Level</label>
                                <select id="filter-activity" class="form-control" onchange="filterClients()">
                                    <option value="">All Clients</option>
                                    <option value="high">High Activity</option>
                                    <option value="medium">Medium Activity</option>
                                    <option value="low">Low Activity</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="filter-orders">Order Volume</label>
                                <select id="filter-orders" class="form-control" onchange="filterClients()">
                                    <option value="">All Orders</option>
                                    <option value="many">10+ Orders</option>
                                    <option value="some">5-9 Orders</option>
                                    <option value="few">1-4 Orders</option>
                                    <option value="none">No Orders</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="sort-by">Sort By</label>
                                <select id="sort-by" class="form-control" onchange="sortClients()">
                                    <option value="company">Company Name</option>
                                    <option value="name">Contact Name</option>
                                    <option value="activity">Last Activity</option>
                                    <option value="orders">Order Count</option>
                                    <option value="value">Total Value</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div>
                                    <button class="btn btn-success btn-block" onclick="showAddClientModal()">
                                        <i class="fas fa-plus"></i> Add Client
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Client List -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Client List</h5>
                    <div class="crm-view-toggle">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-outline-secondary active" onclick="setViewMode('table')">
                                <i class="fas fa-table"></i> Table
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="setViewMode('cards')">
                                <i class="fas fa-th-large"></i> Cards
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Loading Indicator -->
                    <div id="clients-loading" class="text-center py-4" style="display: none;">
                        <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                        <p class="mt-2">Loading clients...</p>
                    </div>

                    <!-- Table View -->
                    <div id="clients-table-view" class="crm-view-mode">
                        <div class="table-responsive">
                            <table class="table table-hover crm-clients-table">
                                <thead>
                                    <tr>
                                        <th>Client</th>
                                        <th>Company</th>
                                        <th>Contact</th>
                                        <th>Orders</th>
                                        <th>Total Value</th>
                                        <th>Last Activity</th>
                                        <th>Notes</th>
                                        <th>Follow-ups</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="clients-table-body">
                                    <?php foreach($clients as $client): ?>
                                    <tr class="crm-client-row" data-client-id="<?= $client['id'] ?>">
                                        <td>
                                            <div class="crm-client-info">
                                                <div class="crm-client-avatar">
                                                    <?= strtoupper(substr($client['first_name'], 0, 1) . substr($client['last_name'], 0, 1)) ?>
                                                </div>
                                                <div class="crm-client-details">
                                                    <strong><?= htmlspecialchars($client['first_name'].' '.$client['last_name']) ?></strong>
                                                    <br><small class="text-muted"><?= htmlspecialchars($client['email_address']) ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong><?= htmlspecialchars($client['company_name']) ?></strong>
                                            <br><small class="text-muted"><?= htmlspecialchars($client['city'].', '.$client['state']) ?></small>
                                        </td>
                                        <td>
                                            <i class="fas fa-phone text-primary"></i> <?= htmlspecialchars($client['telephone_no']) ?>
                                            <br><small class="text-muted">Client since <?= date('M Y', strtotime($client['client_since'])) ?></small>
                                        </td>
                                        <td>
                                            <span class="badge badge-info"><?= $client['total_orders'] ?></span>
                                            <?php if ($client['last_order_date']): ?>
                                            <br><small class="text-muted">Last: <?= date('M j, Y', strtotime($client['last_order_date'])) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong>$<?= number_format($client['total_sales'], 0) ?></strong>
                                            <?php if ($client['total_orders'] > 0): ?>
                                            <br><small class="text-muted">Avg: $<?= number_format($client['total_sales'] / $client['total_orders'], 0) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($client['last_activity']): ?>
                                            <?= time_ago($client['last_activity']) ?>
                                            <?php else: ?>
                                            <span class="text-muted">No activity</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge badge-secondary"><?= $client['note_count'] ?></span>
                                        </td>
                                        <td>
                                            <?php if ($client['pending_followups'] > 0): ?>
                                            <span class="badge badge-warning"><?= $client['pending_followups'] ?></span>
                                            <?php else: ?>
                                            <span class="badge badge-success">0</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?= base_url('crm/client/'.$client['id']) ?>" 
                                                   class="btn btn-primary btn-sm" title="View Profile">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <button onclick="quickNote(<?= $client['id'] ?>)" 
                                                        class="btn btn-success btn-sm" title="Add Note">
                                                    <i class="fas fa-sticky-note"></i>
                                                </button>
                                                <button onclick="logActivity(<?= $client['id'] ?>)" 
                                                        class="btn btn-info btn-sm" title="Log Activity">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="crm-pagination">
                            <nav aria-label="Client pagination">
                                <ul class="pagination justify-content-center" id="clients-pagination">
                                    <!-- Pagination will be populated by JavaScript -->
                                </ul>
                            </nav>
                        </div>
                    </div>

                    <!-- Cards View -->
                    <div id="clients-cards-view" class="crm-view-mode" style="display: none;">
                        <div class="row" id="clients-cards-container">
                            <?php foreach($clients as $client): ?>
                            <div class="col-md-4 col-lg-3 mb-4">
                                <div class="card crm-client-card" data-client-id="<?= $client['id'] ?>">
                                    <div class="card-body">
                                        <div class="crm-client-header">
                                            <div class="crm-client-avatar-large">
                                                <?= strtoupper(substr($client['first_name'], 0, 1) . substr($client['last_name'], 0, 1)) ?>
                                            </div>
                                            <div class="crm-client-title">
                                                <h6 class="mb-1"><?= htmlspecialchars($client['first_name'].' '.$client['last_name']) ?></h6>
                                                <p class="text-muted mb-0"><?= htmlspecialchars($client['company_name']) ?></p>
                                            </div>
                                        </div>
                                        
                                        <div class="crm-client-stats mt-3">
                                            <div class="row text-center">
                                                <div class="col-4">
                                                    <div class="crm-stat-item">
                                                        <h6 class="text-primary"><?= $client['total_orders'] ?></h6>
                                                        <small class="text-muted">Orders</small>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crm-stat-item">
                                                        <h6 class="text-success">$<?= number_format($client['total_sales']/1000, 0) ?>K</h6>
                                                        <small class="text-muted">Value</small>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="crm-stat-item">
                                                        <h6 class="text-info"><?= $client['note_count'] ?></h6>
                                                        <small class="text-muted">Notes</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="crm-client-contact mt-3">
                                            <small class="text-muted">
                                                <i class="fas fa-envelope"></i> <?= htmlspecialchars($client['email_address']) ?><br>
                                                <i class="fas fa-phone"></i> <?= htmlspecialchars($client['telephone_no']) ?><br>
                                                <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($client['city'].', '.$client['state']) ?>
                                            </small>
                                        </div>

                                        <?php if ($client['pending_followups'] > 0): ?>
                                        <div class="alert alert-warning py-2 mt-3">
                                            <small><i class="fas fa-bell"></i> <?= $client['pending_followups'] ?> pending follow-up(s)</small>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-footer">
                                        <div class="btn-group btn-group-sm w-100">
                                            <a href="<?= base_url('crm/client/'.$client['id']) ?>" class="btn btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <button onclick="quickNote(<?= $client['id'] ?>)" class="btn btn-success">
                                                <i class="fas fa-sticky-note"></i> Note
                                            </button>
                                            <button onclick="logActivity(<?= $client['id'] ?>)" class="btn btn-info">
                                                <i class="fas fa-plus"></i> Activity
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

### **3. Individual Client Profile**

#### **Client Profile Page**
```html
<!-- File: application/modules/frontend/views/order/crm/client_profile.php -->
<div class="container-fluid crm-client-profile">
    <!-- Client Header -->
    <div class="row">
        <div class="col-md-12">
            <div class="card crm-client-header-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="crm-client-profile-header">
                                <div class="crm-client-avatar-xl">
                                    <?= strtoupper(substr($client['first_name'], 0, 1) . substr($client['last_name'], 0, 1)) ?>
                                </div>
                                <div class="crm-client-info">
                                    <h2><?= htmlspecialchars($client['company_name'] ?: $client['first_name'].' '.$client['last_name']) ?></h2>
                                    <?php if ($client['company_name']): ?>
                                    <p class="text-muted mb-1"><?= htmlspecialchars($client['first_name'].' '.$client['last_name']) ?></p>
                                    <?php endif; ?>
                                    <div class="crm-client-contact-quick">
                                        <span class="crm-contact-item">
                                            <i class="fas fa-envelope text-primary"></i>
                                            <a href="mailto:<?= $client['email_address'] ?>"><?= htmlspecialchars($client['email_address']) ?></a>
                                        </span>
                                        <span class="crm-contact-item">
                                            <i class="fas fa-phone text-success"></i>
                                            <a href="tel:<?= $client['telephone_no'] ?>"><?= htmlspecialchars($client['telephone_no']) ?></a>
                                        </span>
                                        <span class="crm-contact-item">
                                            <i class="fas fa-map-marker-alt text-info"></i>
                                            <?= htmlspecialchars($client['city'].', '.$client['state']) ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="crm-client-stats">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="crm-stat-box">
                                            <h4 class="text-primary"><?= $client['total_orders'] ?></h4>
                                            <small class="text-muted">Total Orders</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="crm-stat-box">
                                            <h4 class="text-success">$<?= number_format($client['total_sales'], 0) ?></h4>
                                            <small class="text-muted">Total Value</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-6">
                                        <div class="crm-stat-box">
                                            <h4 class="text-info"><?= count($notes) ?></h4>
                                            <small class="text-muted">Notes</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="crm-stat-box">
                                            <h4 class="text-warning"><?= count(array_filter($notes, function($n) { return !empty($n['followup_due_date']) && $n['followup_status'] == 'pending'; })) ?></h4>
                                            <small class="text-muted">Follow-ups</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="crm-client-actions">
                                <button class="btn btn-primary" onclick="showAddNoteModal()">
                                    <i class="fas fa-sticky-note"></i> Add Note
                                </button>
                                <button class="btn btn-success" onclick="showLogActivityModal()">
                                    <i class="fas fa-plus"></i> Log Activity
                                </button>
                                <button class="btn btn-warning" onclick="showScheduleFollowUpModal()">
                                    <i class="fas fa-bell"></i> Schedule Follow-up
                                </button>
                                <button class="btn btn-info" onclick="showSendEmailModal()">
                                    <i class="fas fa-envelope"></i> Send Email
                                </button>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown">
                                        <i class="fas fa-ellipsis-h"></i> More
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" onclick="exportClientData()">
                                            <i class="fas fa-download"></i> Export Data
                                        </a>
                                        <a class="dropdown-item" href="#" onclick="printClientProfile()">
                                            <i class="fas fa-print"></i> Print Profile
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#" onclick="editClientInfo()">
                                            <i class="fas fa-edit"></i> Edit Info
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Tabs -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#notes-tab" role="tab">
                                <i class="fas fa-sticky-note"></i> Notes (<?= count($notes) ?>)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#activities-tab" role="tab">
                                <i class="fas fa-chart-line"></i> Activities (<?= count($activities) ?>)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#orders-tab" role="tab">
                                <i class="fas fa-file-alt"></i> Orders (<?= count($orders) ?>)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#details-tab" role="tab">
                                <i class="fas fa-info-circle"></i> Details
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <!-- Notes Tab -->
                        <div class="tab-pane fade show active" id="notes-tab" role="tabpanel">
                            <div class="crm-notes-section">
                                <?php if (empty($notes)): ?>
                                <div class="crm-empty-state">
                                    <i class="fas fa-sticky-note fa-3x text-muted"></i>
                                    <h5 class="mt-3">No notes yet</h5>
                                    <p class="text-muted">Start building a relationship history by adding your first note.</p>
                                    <button class="btn btn-primary" onclick="showAddNoteModal()">
                                        <i class="fas fa-plus"></i> Add First Note
                                    </button>
                                </div>
                                <?php else: ?>
                                <div class="crm-notes-list">
                                    <?php foreach($notes as $note): ?>
                                    <div class="crm-note-item" data-note-id="<?= $note['id'] ?>">
                                        <div class="crm-note-header">
                                            <div class="crm-note-meta">
                                                <span class="crm-note-type">
                                                    <?php
                                                    $type_icons = [
                                                        'general' => 'fa-sticky-note text-info',
                                                        'follow_up' => 'fa-bell text-warning',
                                                        'meeting' => 'fa-calendar text-success',
                                                        'call' => 'fa-phone text-primary',
                                                        'email' => 'fa-envelope text-info',
                                                        'task' => 'fa-tasks text-danger'
                                                    ];
                                                    $icon = $type_icons[$note['note_type']] ?? 'fa-sticky-note text-info';
                                                    ?>
                                                    <i class="fas <?= $icon ?>"></i>
                                                    <?= ucfirst(str_replace('_', ' ', $note['note_type'])) ?>
                                                </span>
                                                <span class="crm-note-priority">
                                                    <?php if ($note['priority'] != 'normal'): ?>
                                                    <span class="badge badge-<?= $note['priority'] == 'high' ? 'warning' : ($note['priority'] == 'urgent' ? 'danger' : 'secondary') ?>">
                                                        <?= ucfirst($note['priority']) ?>
                                                    </span>
                                                    <?php endif; ?>
                                                </span>
                                                <span class="crm-note-date">
                                                    <?= date('M j, Y g:i A', strtotime($note['created_at'])) ?>
                                                </span>
                                                <span class="crm-note-author">
                                                    by <?= $note['created_by_name'] ?>
                                                </span>
                                            </div>
                                            <div class="crm-note-actions">
                                                <button class="btn btn-sm btn-outline-secondary" onclick="editNote(<?= $note['id'] ?>)">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteNote(<?= $note['id'] ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="crm-note-content">
                                            <h6 class="crm-note-subject"><?= htmlspecialchars($note['subject']) ?></h6>
                                            <div class="crm-note-body">
                                                <?= nl2br(htmlspecialchars($note['content'])) ?>
                                            </div>
                                            <?php if ($note['follow_up_date']): ?>
                                            <div class="crm-note-followup">
                                                <i class="fas fa-bell text-warning"></i>
                                                Follow-up scheduled for <?= date('M j, Y', strtotime($note['follow_up_date'])) ?>
                                                <?php if ($note['followup_status'] == 'pending'): ?>
                                                <button class="btn btn-sm btn-success ml-2" onclick="completeFollowUp(<?= $note['followup_id'] ?>)">
                                                    Mark Complete
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                            <?php endif; ?>
                                            <?php if ($note['file_number']): ?>
                                            <div class="crm-note-order">
                                                <i class="fas fa-file-alt text-info"></i>
                                                Related to Order: <strong><?= $note['file_number'] ?></strong>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Activities Tab -->
                        <div class="tab-pane fade" id="activities-tab" role="tabpanel">
                            <div class="crm-activities-section">
                                <?php if (empty($activities)): ?>
                                <div class="crm-empty-state">
                                    <i class="fas fa-chart-line fa-3x text-muted"></i>
                                    <h5 class="mt-3">No activities yet</h5>
                                    <p class="text-muted">Start tracking your interactions with this client.</p>
                                    <button class="btn btn-success" onclick="showLogActivityModal()">
                                        <i class="fas fa-plus"></i> Log First Activity
                                    </button>
                                </div>
                                <?php else: ?>
                                <div class="crm-activity-timeline">
                                    <?php 
                                    $current_date = '';
                                    foreach($activities as $activity): 
                                    $activity_date = date('Y-m-d', strtotime($activity['activity_date']));
                                    if ($activity_date !== $current_date):
                                        $current_date = $activity_date;
                                    ?>
                                    <div class="crm-timeline-date">
                                        <h6><?= date('F j, Y', strtotime($activity['activity_date'])) ?></h6>
                                    </div>
                                    <?php endif; ?>
                                    <div class="crm-activity-timeline-item">
                                        <div class="crm-activity-timeline-marker">
                                            <?php
                                            $activity_icons = [
                                                'call' => 'fa-phone text-primary',
                                                'email' => 'fa-envelope text-info',
                                                'meeting' => 'fa-calendar text-success',
                                                'note' => 'fa-sticky-note text-warning',
                                                'task' => 'fa-tasks text-danger',
                                                'order' => 'fa-file-alt text-purple',
                                                'document' => 'fa-file text-secondary'
                                            ];
                                            $icon = $activity_icons[$activity['activity_type']] ?? 'fa-circle text-secondary';
                                            ?>
                                            <i class="fas <?= $icon ?>"></i>
                                        </div>
                                        <div class="crm-activity-timeline-content">
                                            <div class="crm-activity-header">
                                                <h6 class="crm-activity-subject"><?= htmlspecialchars($activity['subject']) ?></h6>
                                                <span class="crm-activity-time"><?= date('g:i A', strtotime($activity['activity_date'])) ?></span>
                                            </div>
                                            <?php if ($activity['description']): ?>
                                            <p class="crm-activity-description"><?= nl2br(htmlspecialchars($activity['description'])) ?></p>
                                            <?php endif; ?>
                                            <div class="crm-activity-details">
                                                <?php if ($activity['duration_minutes']): ?>
                                                <span class="crm-activity-detail">
                                                    <i class="fas fa-clock"></i> <?= $activity['duration_minutes'] ?> minutes
                                                </span>
                                                <?php endif; ?>
                                                <?php if ($activity['contact_method']): ?>
                                                <span class="crm-activity-detail">
                                                    <i class="fas fa-phone"></i> <?= ucfirst($activity['contact_method']) ?>
                                                </span>
                                                <?php endif; ?>
                                                <?php if ($activity['file_number']): ?>
                                                <span class="crm-activity-detail">
                                                    <i class="fas fa-file-alt"></i> Order <?= $activity['file_number'] ?>
                                                </span>
                                                <?php endif; ?>
                                                <span class="crm-activity-detail">
                                                    <i class="fas fa-user"></i> <?= $activity['created_by_name'] ?>
                                                </span>
                                            </div>
                                            <?php if ($activity['outcome'] || $activity['next_action']): ?>
                                            <div class="crm-activity-outcome mt-2">
                                                <?php if ($activity['outcome']): ?>
                                                <div class="crm-outcome">
                                                    <strong>Outcome:</strong> <?= nl2br(htmlspecialchars($activity['outcome'])) ?>
                                                </div>
                                                <?php endif; ?>
                                                <?php if ($activity['next_action']): ?>
                                                <div class="crm-next-action">
                                                    <strong>Next Action:</strong> <?= nl2br(htmlspecialchars($activity['next_action'])) ?>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Orders Tab -->
                        <div class="tab-pane fade" id="orders-tab" role="tabpanel">
                            <div class="crm-orders-section">
                                <?php if (empty($orders)): ?>
                                <div class="crm-empty-state">
                                    <i class="fas fa-file-alt fa-3x text-muted"></i>
                                    <h5 class="mt-3">No orders yet</h5>
                                    <p class="text-muted">When this client places orders, they will appear here.</p>
                                </div>
                                <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>File Number</th>
                                                <th>Property</th>
                                                <th>Order Date</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($orders as $order): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= $order['file_number'] ?></strong>
                                                </td>
                                                <td>
                                                    <?= htmlspecialchars($order['property_address']) ?><br>
                                                    <small class="text-muted"><?= htmlspecialchars($order['property_city'].', '.$order['property_state']) ?></small>
                                                </td>
                                                <td>
                                                    <?= date('M j, Y', strtotime($order['created_at'])) ?>
                                                </td>
                                                <td>
                                                    <?php if ($order['sales_amount']): ?>
                                                    <strong>$<?= number_format($order['sales_amount'], 2) ?></strong>
                                                    <?php else: ?>
                                                    <span class="text-muted">TBD</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info"><?= ucfirst($order['status']) ?></span>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url('order/view/'.$order['id']) ?>" class="btn btn-sm btn-primary">
                                                        View Order
                                                    </a>
                                                    <button class="btn btn-sm btn-success" onclick="addNoteForOrder(<?= $order['id'] ?>)">
                                                        Add Note
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Details Tab -->
                        <div class="tab-pane fade" id="details-tab" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Contact Information</h6>
                                        </div>
                                        <div class="card-body">
                                            <dl class="row">
                                                <dt class="col-sm-4">Full Name:</dt>
                                                <dd class="col-sm-8"><?= htmlspecialchars($client['first_name'].' '.$client['last_name']) ?></dd>
                                                
                                                <dt class="col-sm-4">Company:</dt>
                                                <dd class="col-sm-8"><?= htmlspecialchars($client['company_name']) ?></dd>
                                                
                                                <dt class="col-sm-4">Title:</dt>
                                                <dd class="col-sm-8"><?= htmlspecialchars($client['title']) ?></dd>
                                                
                                                <dt class="col-sm-4">Email:</dt>
                                                <dd class="col-sm-8"><a href="mailto:<?= $client['email_address'] ?>"><?= htmlspecialchars($client['email_address']) ?></a></dd>
                                                
                                                <dt class="col-sm-4">Phone:</dt>
                                                <dd class="col-sm-8"><a href="tel:<?= $client['telephone_no'] ?>"><?= htmlspecialchars($client['telephone_no']) ?></a></dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="mb-0">Address Information</h6>
                                        </div>
                                        <div class="card-body">
                                            <dl class="row">
                                                <dt class="col-sm-4">Address:</dt>
                                                <dd class="col-sm-8">
                                                    <?= htmlspecialchars($client['street_address']) ?><br>
                                                    <?php if ($client['street_address_2']): ?>
                                                    <?= htmlspecialchars($client['street_address_2']) ?><br>
                                                    <?php endif; ?>
                                                    <?= htmlspecialchars($client['city'].', '.$client['state'].' '.$client['zip_code']) ?>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>

                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h6 class="mb-0">Relationship Summary</h6>
                                        </div>
                                        <div class="card-body">
                                            <dl class="row">
                                                <dt class="col-sm-6">Client Since:</dt>
                                                <dd class="col-sm-6"><?= date('M j, Y', strtotime($client['created_at'])) ?></dd>
                                                
                                                <dt class="col-sm-6">Total Orders:</dt>
                                                <dd class="col-sm-6"><?= $client['total_orders'] ?></dd>
                                                
                                                <dt class="col-sm-6">Total Value:</dt>
                                                <dd class="col-sm-6">$<?= number_format($client['total_sales'], 2) ?></dd>
                                                
                                                <dt class="col-sm-6">Average Order:</dt>
                                                <dd class="col-sm-6">
                                                    <?php if ($client['total_orders'] > 0): ?>
                                                    $<?= number_format($client['total_sales'] / $client['total_orders'], 2) ?>
                                                    <?php else: ?>
                                                    N/A
                                                    <?php endif; ?>
                                                </dd>
                                                
                                                <dt class="col-sm-6">First Order:</dt>
                                                <dd class="col-sm-6">
                                                    <?= $client['first_order_date'] ? date('M j, Y', strtotime($client['first_order_date'])) : 'None' ?>
                                                </dd>
                                                
                                                <dt class="col-sm-6">Last Order:</dt>
                                                <dd class="col-sm-6">
                                                    <?= $client['last_order_date'] ? date('M j, Y', strtotime($client['last_order_date'])) : 'None' ?>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

## 📱 Responsive Design Considerations

### **Mobile Optimization**
```css
/* File: assets/frontend/css/crm.css */

/* Mobile-first responsive design */
@media (max-width: 768px) {
    .crm-client-header-card .row {
        flex-direction: column;
    }
    
    .crm-client-stats {
        margin-top: 1rem;
        text-align: center;
    }
    
    .crm-client-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
    }
    
    .crm-clients-table {
        font-size: 0.875rem;
    }
    
    .crm-clients-table td {
        padding: 0.5rem 0.25rem;
    }
    
    .crm-view-toggle {
        margin-bottom: 1rem;
    }
    
    .crm-note-item {
        margin-bottom: 1rem;
        padding: 1rem;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
    }
    
    .crm-activity-timeline-item {
        margin-left: 0;
        padding-left: 2rem;
    }
}

/* Tablet optimization */
@media (min-width: 768px) and (max-width: 1024px) {
    .crm-client-card {
        margin-bottom: 1rem;
    }
    
    .crm-dashboard-widgets .col-md-3 {
        flex: 0 0 50%;
        max-width: 50%;
    }
}
```

### **Touch-Friendly Interface**
```css
/* Touch-friendly button sizing */
.crm-touch-friendly {
    min-height: 44px;
    min-width: 44px;
}

.crm-mobile-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: white;
    border-top: 1px solid #dee2e6;
    padding: 0.5rem;
    z-index: 1000;
}

.crm-mobile-nav .nav-link {
    text-align: center;
    padding: 0.5rem;
}

/* Swipe gestures for mobile */
.crm-swipeable {
    touch-action: pan-y;
}
```

This UI design provides a comprehensive, modern, and responsive interface that integrates seamlessly with the existing Transaction Desk system while providing powerful CRM functionality optimized for both desktop and mobile use.
