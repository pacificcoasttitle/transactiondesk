/**
 * CFO Dashboard JavaScript
 * 
 * Interactive dashboard functionality with real-time updates,
 * chart management, and user interface controls.
 * 
 * @package    CFO Dashboard
 * @subpackage JavaScript
 * @version    1.0.0
 */

class CfoDashboard {
    constructor() {
        this.charts = {};
        this.config = window.cfoDashboardConfig || {};
        this.isUpdating = false;
        this.updateInterval = null;
        
        // Initialize dashboard when DOM is ready
        $(document).ready(() => {
            this.init();
        });
    }
    
    /**
     * Initialize the dashboard
     */
    init() {
        console.log('Initializing CFO Dashboard...');
        
        // Initialize charts
        this.initializeCharts();
        
        // Set up real-time updates
        this.setupRealtimeUpdates();
        
        // Set up event listeners
        this.setupEventListeners();
        
        // Initial data load
        this.updateDashboardData();
        
        console.log('CFO Dashboard initialized successfully');
    }
    
    /**
     * Initialize all dashboard charts
     */
    initializeCharts() {
        this.initializeRevenueTrendsChart();
        this.initializeProductMixChart();
        this.initializeSalesPerformanceChart();
    }
    
    /**
     * Initialize revenue trends line chart
     */
    initializeRevenueTrendsChart() {
        const ctx = document.getElementById('revenueTrendsChart');
        if (!ctx) return;
        
        const trendsData = this.config.revenueTrends || [];
        
        this.charts.revenueTrends = new Chart(ctx, {
            type: 'line',
            data: {
                labels: trendsData.map(item => this.formatDateLabel(item.period_label || item.summary_date)),
                datasets: [
                    {
                        label: 'Total Revenue',
                        data: trendsData.map(item => parseFloat(item.total_revenue || 0)),
                        borderColor: 'rgb(78, 115, 223)',
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Sales Revenue',
                        data: trendsData.map(item => parseFloat(item.sales_revenue || 0)),
                        borderColor: 'rgb(28, 200, 138)',
                        backgroundColor: 'rgba(28, 200, 138, 0.1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4
                    },
                    {
                        label: 'Refinance Revenue',
                        data: trendsData.map(item => parseFloat(item.refi_revenue || 0)),
                        borderColor: 'rgb(54, 185, 204)',
                        backgroundColor: 'rgba(54, 185, 204, 0.1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': $' + 
                                       context.parsed.y.toLocaleString('en-US', {
                                           minimumFractionDigits: 0,
                                           maximumFractionDigits: 0
                                       });
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Date'
                        },
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Revenue ($)'
                        },
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString('en-US', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                });
                            }
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }
    
    /**
     * Initialize product mix doughnut chart
     */
    initializeProductMixChart() {
        const ctx = document.getElementById('productMixChart');
        if (!ctx) return;
        
        const productData = this.config.productMix || [];
        
        this.charts.productMix = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: productData.map(item => this.formatProductLabel(item.prod_type)),
                datasets: [{
                    data: productData.map(item => parseFloat(item.revenue || 0)),
                    backgroundColor: [
                        'rgb(78, 115, 223)',
                        'rgb(28, 200, 138)',
                        'rgb(54, 185, 204)',
                        'rgb(246, 194, 62)'
                    ],
                    borderColor: [
                        'rgba(78, 115, 223, 1)',
                        'rgba(28, 200, 138, 1)',
                        'rgba(54, 185, 204, 1)',
                        'rgba(246, 194, 62, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': $' + 
                                       context.parsed.toLocaleString('en-US', {
                                           minimumFractionDigits: 0,
                                           maximumFractionDigits: 0
                                       }) + 
                                       ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    duration: 1000
                }
            }
        });
    }
    
    /**
     * Initialize sales performance bar chart (if container exists)
     */
    initializeSalesPerformanceChart() {
        const ctx = document.getElementById('salesPerformanceChart');
        if (!ctx) return;
        
        const salesData = this.config.salesPerformance || [];
        
        this.charts.salesPerformance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: salesData.map(item => this.formatSalesRepName(item.sales_rep_name)),
                datasets: [
                    {
                        label: 'Revenue',
                        data: salesData.map(item => parseFloat(item.total_revenue || 0)),
                        backgroundColor: 'rgba(78, 115, 223, 0.8)',
                        borderColor: 'rgb(78, 115, 223)',
                        borderWidth: 1
                    },
                    {
                        label: 'Commission',
                        data: salesData.map(item => parseFloat(item.commission_earned || 0)),
                        backgroundColor: 'rgba(28, 200, 138, 0.8)',
                        borderColor: 'rgb(28, 200, 138)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': $' + 
                                       context.parsed.y.toLocaleString('en-US', {
                                           minimumFractionDigits: 0,
                                           maximumFractionDigits: 0
                                       });
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        display: true,
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value.toLocaleString('en-US', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                });
                            }
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }
    
    /**
     * Set up real-time updates
     */
    setupRealtimeUpdates() {
        const interval = this.config.refreshInterval || 300000; // 5 minutes default
        
        this.updateInterval = setInterval(() => {
            if (!this.isUpdating) {
                this.updateDashboardData();
            }
        }, interval);
        
        console.log(`Real-time updates set to ${interval / 1000} seconds`);
    }
    
    /**
     * Set up event listeners
     */
    setupEventListeners() {
        // Page visibility change - pause updates when page is hidden
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                if (this.updateInterval) {
                    clearInterval(this.updateInterval);
                }
            } else {
                this.setupRealtimeUpdates();
                this.updateDashboardData();
            }
        });
        
        // Window focus events
        window.addEventListener('focus', () => {
            this.updateDashboardData();
        });
        
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (e.ctrlKey || e.metaKey) {
                switch (e.key) {
                    case 'r':
                        e.preventDefault();
                        this.refreshDashboard();
                        break;
                    case 'e':
                        e.preventDefault();
                        this.showExportModal();
                        break;
                }
            }
        });
    }
    
    /**
     * Update dashboard data from server
     */
    async updateDashboardData() {
        if (this.isUpdating) return;
        
        this.isUpdating = true;
        this.showLoadingIndicator();
        
        try {
            const response = await fetch(this.config.endpoints.realtimeData, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            // Update KPI cards
            this.updateKpiCards(data.revenue_summary);
            
            // Update charts
            this.updateCharts(data);
            
            // Update tables
            this.updateTables(data);
            
            // Update last update time
            this.updateLastUpdateTime(data.last_updated);
            
            // Handle alerts
            this.handleAlerts(data.alerts);
            
        } catch (error) {
            console.error('Error updating dashboard data:', error);
            this.showErrorMessage('Failed to update dashboard data. Please refresh the page.');
        } finally {
            this.isUpdating = false;
            this.hideLoadingIndicator();
        }
    }
    
    /**
     * Update KPI cards with new data
     */
    updateKpiCards(revenueSummary) {
        if (!revenueSummary) return;
        
        // Update MTD Revenue
        const mtdElement = document.getElementById('mtd-revenue');
        if (mtdElement) {
            mtdElement.textContent = '$' + this.formatNumber(revenueSummary.mtd_revenue);
        }
        
        // Update YTD Revenue
        const ytdElement = document.getElementById('ytd-revenue');
        if (ytdElement) {
            ytdElement.textContent = '$' + this.formatNumber(revenueSummary.ytd_revenue);
        }
        
        // Update Projected Monthly
        const projectedElement = document.getElementById('projected-monthly');
        if (projectedElement) {
            projectedElement.textContent = '$' + this.formatNumber(revenueSummary.projected_monthly);
        }
        
        // Update Budget Variance
        const varianceElement = document.getElementById('budget-variance');
        if (varianceElement) {
            const variance = parseFloat(revenueSummary.budget_variance || 0);
            varianceElement.textContent = (variance >= 0 ? '+' : '') + variance.toFixed(1) + '%';
            
            // Update color based on variance
            const card = varianceElement.closest('.card');
            if (card) {
                card.className = card.className.replace(/border-left-\w+/, 
                    variance >= 0 ? 'border-left-success' : 'border-left-danger');
            }
        }
    }
    
    /**
     * Update charts with new data
     */
    updateCharts(data) {
        // Update revenue trends chart
        if (this.charts.revenueTrends && data.revenue_trends) {
            const chart = this.charts.revenueTrends;
            chart.data.labels = data.revenue_trends.map(item => this.formatDateLabel(item.period_label || item.summary_date));
            chart.data.datasets[0].data = data.revenue_trends.map(item => parseFloat(item.total_revenue || 0));
            chart.data.datasets[1].data = data.revenue_trends.map(item => parseFloat(item.sales_revenue || 0));
            chart.data.datasets[2].data = data.revenue_trends.map(item => parseFloat(item.refi_revenue || 0));
            chart.update('none'); // Update without animation
        }
        
        // Update product mix chart
        if (this.charts.productMix && data.revenue_by_product) {
            const chart = this.charts.productMix;
            chart.data.labels = data.revenue_by_product.map(item => this.formatProductLabel(item.prod_type));
            chart.data.datasets[0].data = data.revenue_by_product.map(item => parseFloat(item.revenue || 0));
            chart.update('none');
        }
    }
    
    /**
     * Update data tables
     */
    updateTables(data) {
        // This would update the sales rep performance table
        // Implementation depends on specific table structure
        if (data.sales_performance) {
            // Update top performers table if it exists
            // Implementation would go here
        }
    }
    
    /**
     * Update revenue trends chart with different period
     */
    async updateRevenueChart(period, days) {
        this.showLoadingIndicator();
        
        try {
            const response = await fetch(`${this.config.endpoints.revenueTrends}/${period}/${days}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const trendsData = await response.json();
            
            if (this.charts.revenueTrends) {
                const chart = this.charts.revenueTrends;
                chart.data.labels = trendsData.map(item => this.formatDateLabel(item.period_label || item.summary_date));
                chart.data.datasets[0].data = trendsData.map(item => parseFloat(item.total_revenue || 0));
                chart.data.datasets[1].data = trendsData.map(item => parseFloat(item.sales_revenue || 0));
                chart.data.datasets[2].data = trendsData.map(item => parseFloat(item.refi_revenue || 0));
                chart.update();
            }
            
        } catch (error) {
            console.error('Error updating revenue chart:', error);
            this.showErrorMessage('Failed to update revenue chart data.');
        } finally {
            this.hideLoadingIndicator();
        }
    }
    
    /**
     * Refresh entire dashboard
     */
    refreshDashboard() {
        console.log('Refreshing dashboard...');
        this.updateDashboardData();
    }
    
    /**
     * Handle alerts and notifications
     */
    handleAlerts(alerts) {
        if (!alerts || alerts.length === 0) return;
        
        const alertBanner = document.getElementById('alert-banner');
        const alertMessage = document.getElementById('alert-message');
        
        if (alertBanner && alertMessage) {
            // Show the most critical alert
            const criticalAlert = alerts.find(alert => alert.priority === 'critical') || alerts[0];
            
            alertMessage.textContent = criticalAlert.message;
            alertBanner.className = `alert alert-${this.getAlertClass(criticalAlert.priority)} alert-dismissible fade show`;
        }
    }
    
    /**
     * Show loading indicator
     */
    showLoadingIndicator() {
        const loadingOverlay = document.getElementById('loading-overlay');
        if (loadingOverlay) {
            loadingOverlay.classList.remove('d-none');
        }
    }
    
    /**
     * Hide loading indicator
     */
    hideLoadingIndicator() {
        const loadingOverlay = document.getElementById('loading-overlay');
        if (loadingOverlay) {
            loadingOverlay.classList.add('d-none');
        }
    }
    
    /**
     * Show error message
     */
    showErrorMessage(message) {
        console.error(message);
        
        // Show toast notification if available
        if (typeof toastr !== 'undefined') {
            toastr.error(message);
        } else {
            alert(message);
        }
    }
    
    /**
     * Update last update time display
     */
    updateLastUpdateTime(timestamp) {
        window.lastUpdateTime = timestamp;
        
        // Update any "last updated" displays on the page
        const lastUpdateElements = document.querySelectorAll('.last-updated');
        lastUpdateElements.forEach(element => {
            element.textContent = 'Last updated: ' + this.formatDateTime(timestamp);
        });
    }
    
    /**
     * Utility functions
     */
    
    formatNumber(number) {
        return parseFloat(number || 0).toLocaleString('en-US', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        });
    }
    
    formatDateLabel(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric' 
        });
    }
    
    formatProductLabel(prodType) {
        return prodType === 'sale' ? 'Sales' : 'Refinance';
    }
    
    formatSalesRepName(fullName) {
        const parts = fullName.split(' ');
        if (parts.length >= 2) {
            return parts[0] + ' ' + parts[parts.length - 1].charAt(0) + '.';
        }
        return fullName;
    }
    
    formatDateTime(timestamp) {
        const date = new Date(timestamp);
        return date.toLocaleString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
    
    getAlertClass(priority) {
        const classes = {
            'critical': 'danger',
            'high': 'warning',
            'medium': 'info',
            'low': 'secondary'
        };
        return classes[priority] || 'info';
    }
    
    /**
     * Export functionality
     */
    showExportModal() {
        // Implementation for export modal
        console.log('Export modal would be shown here');
    }
    
    /**
     * Cleanup when page is unloaded
     */
    cleanup() {
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
        }
        
        // Cleanup charts
        Object.values(this.charts).forEach(chart => {
            if (chart && typeof chart.destroy === 'function') {
                chart.destroy();
            }
        });
    }
}

// Global functions for template usage
window.refreshDashboard = function() {
    if (window.cfoDashboardInstance) {
        window.cfoDashboardInstance.refreshDashboard();
    }
};

window.updateRevenueChart = function(period, days) {
    if (window.cfoDashboardInstance) {
        window.cfoDashboardInstance.updateRevenueChart(period, days);
    }
};

// Initialize dashboard when script loads
window.cfoDashboardInstance = new CfoDashboard();

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (window.cfoDashboardInstance) {
        window.cfoDashboardInstance.cleanup();
    }
});
