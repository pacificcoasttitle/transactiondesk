/**
 * CRM Frontend JavaScript
 * 
 * Handles all client-side interactions for the CRM system
 * including AJAX calls, form handling, and UI updates.
 */

$(document).ready(function() {
    // Initialize CRM functionality
    initializeCRM();
});

/**
 * Initialize CRM Components
 */
function initializeCRM() {
    // Initialize search functionality
    initializeSearch();
    
    // Initialize quick actions
    initializeQuickActions();
    
    // Initialize follow-up management
    initializeFollowUps();
    
    // Initialize activity logging
    initializeActivityLogging();
    
    // Initialize modals
    initializeModals();
    
    // Initialize auto-save for forms
    initializeAutoSave();
    
    // Initialize keyboard shortcuts
    initializeKeyboardShortcuts();
}

/**
 * Client Search Functionality
 */
function initializeSearch() {
    let searchTimeout;
    
    $('#client-search-input, #client-search').on('input', function() {
        const searchTerm = $(this).val();
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(function() {
            if (searchTerm.length >= 2) {
                searchClients(searchTerm);
            } else if (searchTerm.length === 0) {
                resetClientList();
            }
        }, 300);
    });
    
    // Advanced search form
    $('#advanced-search-form').on('submit', function(e) {
        e.preventDefault();
        performAdvancedSearch();
    });
}

/**
 * Search clients via AJAX
 */
function searchClients(searchTerm) {
    $('#clients-loading').show();
    
    $.ajax({
        url: '/crm/search-clients',
        method: 'POST',
        data: {
            search: searchTerm,
            [csrfToken]: csrfHash
        },
        dataType: 'json'
    })
    .done(function(response) {
        if (response.success) {
            displaySearchResults(response.data);
        } else {
            showNotification('Search failed: ' + response.message, 'error');
        }
    })
    .fail(function() {
        showNotification('Search request failed', 'error');
    })
    .always(function() {
        $('#clients-loading').hide();
    });
}

/**
 * Display search results in the UI
 */
function displaySearchResults(clients) {
    const tableBody = $('#clients-table-body');
    const cardsContainer = $('#clients-cards-container');
    
    if (clients.length === 0) {
        const emptyMessage = `
            <tr>
                <td colspan="9" class="text-center py-4">
                    <i class="fas fa-search fa-2x text-muted mb-3"></i>
                    <p>No clients found matching your search.</p>
                </td>
            </tr>
        `;
        tableBody.html(emptyMessage);
        cardsContainer.html('<div class="col-12 text-center py-4"><p>No clients found.</p></div>');
        return;
    }
    
    // Update table view
    let tableHtml = '';
    clients.forEach(function(client) {
        tableHtml += buildClientTableRow(client);
    });
    tableBody.html(tableHtml);
    
    // Update cards view
    let cardsHtml = '';
    clients.forEach(function(client) {
        cardsHtml += buildClientCard(client);
    });
    cardsContainer.html(cardsHtml);
}

/**
 * Quick Actions Initialization
 */
function initializeQuickActions() {
    // Quick note form
    $('#quick-note-form').on('submit', function(e) {
        e.preventDefault();
        submitQuickNote();
    });
    
    // Quick activity buttons
    $(document).on('click', '.quick-activity-btn', function() {
        const clientId = $(this).data('client-id');
        const activityType = $(this).data('activity-type');
        showLogActivityModal(clientId, activityType);
    });
    
    // Quick follow-up buttons
    $(document).on('click', '.quick-followup-btn', function() {
        const clientId = $(this).data('client-id');
        showScheduleFollowUpModal(clientId);
    });
}

/**
 * Submit quick note via AJAX
 */
function submitQuickNote() {
    const form = $('#quick-note-form');
    const submitBtn = form.find('button[type="submit"]');
    const originalText = submitBtn.html();
    
    // Show loading state
    submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Adding...').prop('disabled', true);
    
    $.ajax({
        url: '/crm/quick-note',
        method: 'POST',
        data: form.serialize(),
        dataType: 'json'
    })
    .done(function(response) {
        if (response.success) {
            showNotification('Quick note added successfully!', 'success');
            form[0].reset();
            
            // Update note count in UI if on client list
            const clientId = form.find('select[name="client_id"]').val();
            updateClientNoteCount(clientId);
        } else {
            showNotification('Failed to add note: ' + response.message, 'error');
        }
    })
    .fail(function() {
        showNotification('Request failed. Please try again.', 'error');
    })
    .always(function() {
        submitBtn.html(originalText).prop('disabled', false);
    });
}

/**
 * Follow-up Management
 */
function initializeFollowUps() {
    // Complete follow-up buttons
    $(document).on('click', '.complete-followup-btn', function() {
        const followupId = $(this).data('followup-id');
        completeFollowUp(followupId);
    });
    
    // Snooze follow-up buttons
    $(document).on('click', '.snooze-followup-btn', function() {
        const followupId = $(this).data('followup-id');
        showSnoozeModal(followupId);
    });
    
    // Follow-up date picker
    $('.followup-date-picker').datepicker({
        minDate: 0, // Today or later
        dateFormat: 'yy-mm-dd'
    });
}

/**
 * Complete a follow-up
 */
function completeFollowUp(followupId) {
    if (!confirm('Mark this follow-up as completed?')) {
        return;
    }
    
    $.ajax({
        url: '/crm/complete-followup',
        method: 'POST',
        data: {
            followup_id: followupId,
            [csrfToken]: csrfHash
        },
        dataType: 'json'
    })
    .done(function(response) {
        if (response.success) {
            showNotification('Follow-up marked as completed!', 'success');
            
            // Remove from UI or update status
            $('[data-followup-id="' + followupId + '"]').fadeOut(function() {
                $(this).remove();
                updateFollowUpCounts();
            });
        } else {
            showNotification('Failed to update follow-up: ' + response.message, 'error');
        }
    })
    .fail(function() {
        showNotification('Request failed. Please try again.', 'error');
    });
}

/**
 * Activity Logging
 */
function initializeActivityLogging() {
    // Activity form submission
    $('#log-activity-form').on('submit', function(e) {
        e.preventDefault();
        submitActivityLog();
    });
    
    // Activity type change handler
    $('#activity-type').on('change', function() {
        const activityType = $(this).val();
        toggleActivityFields(activityType);
    });
}

/**
 * Submit activity log
 */
function submitActivityLog() {
    const form = $('#log-activity-form');
    const submitBtn = form.find('button[type="submit"]');
    const originalText = submitBtn.html();
    
    // Validate required fields
    if (!validateActivityForm()) {
        return;
    }
    
    submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Logging...').prop('disabled', true);
    
    $.ajax({
        url: '/crm/log-activity',
        method: 'POST',
        data: form.serialize(),
        dataType: 'json'
    })
    .done(function(response) {
        if (response.success) {
            showNotification('Activity logged successfully!', 'success');
            $('#log-activity-modal').modal('hide');
            form[0].reset();
            
            // Refresh activity timeline if on client profile
            if (typeof refreshActivityTimeline === 'function') {
                refreshActivityTimeline();
            }
        } else {
            showNotification('Failed to log activity: ' + response.message, 'error');
        }
    })
    .fail(function() {
        showNotification('Request failed. Please try again.', 'error');
    })
    .always(function() {
        submitBtn.html(originalText).prop('disabled', false);
    });
}

/**
 * Modal Management
 */
function initializeModals() {
    // Note modal
    $('#add-note-modal').on('shown.bs.modal', function() {
        $('#note-subject').focus();
    });
    
    // Activity modal
    $('#log-activity-modal').on('shown.bs.modal', function() {
        $('#activity-subject').focus();
    });
    
    // Clear forms when modals are hidden
    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0]?.reset();
        $(this).find('.is-invalid').removeClass('is-invalid');
        $(this).find('.invalid-feedback').hide();
    });
}

/**
 * Show notification to user
 */
function showNotification(message, type = 'info') {
    const alertClass = {
        'success': 'alert-success',
        'error': 'alert-danger',
        'warning': 'alert-warning',
        'info': 'alert-info'
    };
    
    const notification = `
        <div class="alert ${alertClass[type]} alert-dismissible fade show crm-notification" role="alert">
            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'}-circle"></i>
            ${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `;
    
    // Remove existing notifications
    $('.crm-notification').remove();
    
    // Add new notification
    $('body').prepend(notification);
    
    // Auto-hide after 5 seconds
    setTimeout(function() {
        $('.crm-notification').fadeOut();
    }, 5000);
}

/**
 * Auto-save functionality for forms
 */
function initializeAutoSave() {
    let autoSaveTimeout;
    
    $('.auto-save-form input, .auto-save-form textarea, .auto-save-form select').on('input change', function() {
        const form = $(this).closest('form');
        
        clearTimeout(autoSaveTimeout);
        autoSaveTimeout = setTimeout(function() {
            autoSaveForm(form);
        }, 2000); // Auto-save after 2 seconds of inactivity
    });
}

/**
 * Auto-save form data
 */
function autoSaveForm(form) {
    const formData = form.serialize();
    const autoSaveUrl = form.data('autosave-url');
    
    if (!autoSaveUrl) return;
    
    $.ajax({
        url: autoSaveUrl,
        method: 'POST',
        data: formData,
        dataType: 'json'
    })
    .done(function(response) {
        if (response.success) {
            // Show subtle auto-save indicator
            showAutoSaveIndicator(form);
        }
    });
}

/**
 * Show auto-save indicator
 */
function showAutoSaveIndicator(form) {
    const indicator = form.find('.auto-save-indicator');
    indicator.html('<i class="fas fa-check text-success"></i> Auto-saved').show();
    
    setTimeout(function() {
        indicator.fadeOut();
    }, 2000);
}

/**
 * Keyboard shortcuts
 */
function initializeKeyboardShortcuts() {
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + / for search
        if ((e.ctrlKey || e.metaKey) && e.key === '/') {
            e.preventDefault();
            $('#client-search-input').focus().select();
        }
        
        // Ctrl/Cmd + N for new note
        if ((e.ctrlKey || e.metaKey) && e.key === 'n') {
            e.preventDefault();
            showAddNoteModal();
        }
        
        // Ctrl/Cmd + A for new activity
        if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
            e.preventDefault();
            showLogActivityModal();
        }
        
        // Escape to close modals
        if (e.key === 'Escape') {
            $('.modal').modal('hide');
        }
    });
}

/**
 * Utility Functions
 */

/**
 * Build client table row HTML
 */
function buildClientTableRow(client) {
    return `
        <tr class="crm-client-row" data-client-id="${client.id}">
            <td>
                <div class="crm-client-info">
                    <div class="crm-client-avatar">
                        ${client.first_name.charAt(0) + client.last_name.charAt(0)}
                    </div>
                    <div class="crm-client-details">
                        <strong>${client.first_name} ${client.last_name}</strong>
                        <br><small class="text-muted">${client.email_address}</small>
                    </div>
                </div>
            </td>
            <td>
                <strong>${client.company_name || ''}</strong>
                <br><small class="text-muted">${client.city}, ${client.state}</small>
            </td>
            <td>
                <i class="fas fa-phone text-primary"></i> ${client.telephone_no}
            </td>
            <td>
                <span class="badge badge-info">${client.total_orders || 0}</span>
            </td>
            <td>
                <strong>$${formatNumber(client.total_sales || 0)}</strong>
            </td>
            <td>
                ${client.last_activity ? timeAgo(client.last_activity) : '<span class="text-muted">No activity</span>'}
            </td>
            <td>
                <span class="badge badge-secondary">${client.note_count || 0}</span>
            </td>
            <td>
                ${client.pending_followups > 0 ? 
                    `<span class="badge badge-warning">${client.pending_followups}</span>` :
                    '<span class="badge badge-success">0</span>'
                }
            </td>
            <td>
                <div class="btn-group">
                    <a href="/crm/client/${client.id}" class="btn btn-primary btn-sm" title="View Profile">
                        <i class="fas fa-eye"></i>
                    </a>
                    <button onclick="quickNote(${client.id})" class="btn btn-success btn-sm" title="Add Note">
                        <i class="fas fa-sticky-note"></i>
                    </button>
                    <button onclick="logActivity(${client.id})" class="btn btn-info btn-sm" title="Log Activity">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </td>
        </tr>
    `;
}

/**
 * Build client card HTML
 */
function buildClientCard(client) {
    return `
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card crm-client-card" data-client-id="${client.id}">
                <div class="card-body">
                    <div class="crm-client-header">
                        <div class="crm-client-avatar-large">
                            ${client.first_name.charAt(0) + client.last_name.charAt(0)}
                        </div>
                        <div class="crm-client-title">
                            <h6 class="mb-1">${client.first_name} ${client.last_name}</h6>
                            <p class="text-muted mb-0">${client.company_name || ''}</p>
                        </div>
                    </div>
                    
                    <div class="crm-client-stats mt-3">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="crm-stat-item">
                                    <h6 class="text-primary">${client.total_orders || 0}</h6>
                                    <small class="text-muted">Orders</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="crm-stat-item">
                                    <h6 class="text-success">$${formatNumber((client.total_sales || 0) / 1000)}K</h6>
                                    <small class="text-muted">Value</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="crm-stat-item">
                                    <h6 class="text-info">${client.note_count || 0}</h6>
                                    <small class="text-muted">Notes</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    ${client.pending_followups > 0 ? `
                    <div class="alert alert-warning py-2 mt-3">
                        <small><i class="fas fa-bell"></i> ${client.pending_followups} pending follow-up(s)</small>
                    </div>
                    ` : ''}
                </div>
                <div class="card-footer">
                    <div class="btn-group btn-group-sm w-100">
                        <a href="/crm/client/${client.id}" class="btn btn-primary">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <button onclick="quickNote(${client.id})" class="btn btn-success">
                            <i class="fas fa-sticky-note"></i> Note
                        </button>
                        <button onclick="logActivity(${client.id})" class="btn btn-info">
                            <i class="fas fa-plus"></i> Activity
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Format number for display
 */
function formatNumber(num) {
    return parseFloat(num).toLocaleString();
}

/**
 * Time ago helper function
 */
function timeAgo(date) {
    const now = new Date();
    const past = new Date(date);
    const diffInMs = now - past;
    const diffInDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));
    
    if (diffInDays === 0) return 'Today';
    if (diffInDays === 1) return 'Yesterday';
    if (diffInDays < 7) return `${diffInDays} days ago`;
    if (diffInDays < 30) return `${Math.floor(diffInDays / 7)} weeks ago`;
    if (diffInDays < 365) return `${Math.floor(diffInDays / 30)} months ago`;
    return `${Math.floor(diffInDays / 365)} years ago`;
}

/**
 * Modal Helper Functions
 */
function showAddNoteModal(clientId = null) {
    const modal = $('#add-note-modal');
    if (clientId) {
        modal.find('#note-client-id').val(clientId);
    }
    modal.modal('show');
}

function showLogActivityModal(clientId = null, activityType = null) {
    const modal = $('#log-activity-modal');
    if (clientId) {
        modal.find('#activity-client-id').val(clientId);
    }
    if (activityType) {
        modal.find('#activity-type').val(activityType);
        toggleActivityFields(activityType);
    }
    modal.modal('show');
}

function showScheduleFollowUpModal(clientId = null) {
    const modal = $('#schedule-followup-modal');
    if (clientId) {
        modal.find('#followup-client-id').val(clientId);
    }
    modal.modal('show');
}

/**
 * Quick action functions called from HTML
 */
function quickNote(clientId) {
    showAddNoteModal(clientId);
}

function logActivity(clientId, activityType = null) {
    showLogActivityModal(clientId, activityType);
}

/**
 * View mode toggle
 */
function setViewMode(mode) {
    $('.crm-view-mode').hide();
    $('#clients-' + mode + '-view').show();
    
    $('.crm-view-toggle .btn').removeClass('active');
    $('.crm-view-toggle .btn').filter(function() {
        return $(this).text().toLowerCase().includes(mode);
    }).addClass('active');
    
    // Save preference
    localStorage.setItem('crm-view-mode', mode);
}

// Load saved view mode
$(document).ready(function() {
    const savedViewMode = localStorage.getItem('crm-view-mode');
    if (savedViewMode) {
        setViewMode(savedViewMode);
    }
});

/**
 * Export functions for global access
 */
window.CRM = {
    searchClients: searchClients,
    completeFollowUp: completeFollowUp,
    showAddNoteModal: showAddNoteModal,
    showLogActivityModal: showLogActivityModal,
    showScheduleFollowUpModal: showScheduleFollowUpModal,
    quickNote: quickNote,
    logActivity: logActivity,
    setViewMode: setViewMode,
    showNotification: showNotification
};
