<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CFO Dashboard Controller
 * 
 * Main controller for CFO revenue dashboard functionality.
 * Handles dashboard display, data aggregation, and analytics.
 * 
 * @package    CFO Dashboard
 * @subpackage Controllers
 * @category   Finance
 * @author     Transaction Desk Development Team
 * @version    1.0.0
 */
class CfoDashboard extends MX_Controller
{
    private $version;
    
    public function __construct()
    {
        parent::__construct();
        
        // Load required libraries and helpers
        $this->load->helper(['url', 'form', 'date', 'number']);
        $this->load->library(['session', 'form_validation', 'cache']);
        $this->load->library('order/adminTemplate');
        $this->load->library('order/common');
        $this->load->library('finance/revenueAnalytics');
        $this->load->library('finance/predictiveAnalytics');
        
        // Load models
        $this->load->model('order/home_model');
        $this->load->model('order/order_model');
        
        // Set version for cache busting
        $this->version = strtotime(date('Y-m-d'));
        
        // Check admin access
        $this->common->is_admin();
        
        // Check CFO dashboard permissions
        $this->checkCfoDashboardAccess();
    }
    
    /**
     * Main CFO Dashboard Index
     * 
     * Displays the main CFO dashboard with KPIs, charts, and analytics
     */
    public function index()
    {
        $this->common->checkRoleAccess();
        
        // Get dashboard data
        $data = $this->getDashboardData();
        
        // Set page metadata
        $data['title'] = 'CFO Revenue Dashboard | Pacific Coast Title Company';
        $data['page_title'] = 'CFO Revenue Dashboard';
        $data['breadcrumbs'] = [
            ['title' => 'Admin', 'url' => '/admin'],
            ['title' => 'Finance', 'url' => '/admin/finance'],
            ['title' => 'CFO Dashboard', 'url' => '', 'active' => true]
        ];
        
        // Load CSS and JavaScript assets
        $this->loadDashboardAssets();
        
        // Display dashboard
        $this->admintemplate->show('finance', 'cfo_dashboard', $data);
    }
    
    /**
     * Revenue Analytics Page
     * 
     * Detailed revenue analytics with advanced charts and trends
     */
    public function revenue_analytics()
    {
        $this->common->checkRoleAccess();
        
        // Get analytics data
        $data = $this->getRevenueAnalyticsData();
        
        // Set page metadata
        $data['title'] = 'Revenue Analytics | CFO Dashboard';
        $data['page_title'] = 'Revenue Analytics';
        $data['breadcrumbs'] = [
            ['title' => 'Admin', 'url' => '/admin'],
            ['title' => 'Finance', 'url' => '/admin/finance'],
            ['title' => 'CFO Dashboard', 'url' => '/admin/finance/cfo-dashboard'],
            ['title' => 'Revenue Analytics', 'url' => '', 'active' => true]
        ];
        
        // Load analytics-specific assets
        $this->loadAnalyticsAssets();
        
        // Display analytics page
        $this->admintemplate->show('finance', 'revenue_analytics', $data);
    }
    
    /**
     * Sales Performance Page
     * 
     * Detailed sales rep performance analysis
     */
    public function sales_performance()
    {
        $this->common->checkRoleAccess();
        
        // Get sales performance data
        $data = $this->getSalesPerformanceData();
        
        // Set page metadata
        $data['title'] = 'Sales Performance | CFO Dashboard';
        $data['page_title'] = 'Sales Performance Analysis';
        $data['breadcrumbs'] = [
            ['title' => 'Admin', 'url' => '/admin'],
            ['title' => 'Finance', 'url' => '/admin/finance'],
            ['title' => 'CFO Dashboard', 'url' => '/admin/finance/cfo-dashboard'],
            ['title' => 'Sales Performance', 'url' => '', 'active' => true]
        ];
        
        // Load sales performance assets
        $this->loadSalesPerformanceAssets();
        
        // Display sales performance page
        $this->admintemplate->show('finance', 'sales_performance', $data);
    }
    
    /**
     * Forecasting & Predictions Page
     * 
     * Revenue forecasting and predictive analytics
     */
    public function forecasting()
    {
        $this->common->checkRoleAccess();
        
        // Get forecasting data
        $data = $this->getForecastingData();
        
        // Set page metadata
        $data['title'] = 'Revenue Forecasting | CFO Dashboard';
        $data['page_title'] = 'Revenue Forecasting & Predictions';
        $data['breadcrumbs'] = [
            ['title' => 'Admin', 'url' => '/admin'],
            ['title' => 'Finance', 'url' => '/admin/finance'],
            ['title' => 'CFO Dashboard', 'url' => '/admin/finance/cfo-dashboard'],
            ['title' => 'Forecasting', 'url' => '', 'active' => true]
        ];
        
        // Load forecasting assets
        $this->loadForecastingAssets();
        
        // Display forecasting page
        $this->admintemplate->show('finance', 'forecasting', $data);
    }
    
    /**
     * Settings Page
     * 
     * Dashboard configuration and alert settings
     */
    public function settings()
    {
        $this->common->checkRoleAccess();
        
        // Handle form submission
        if ($this->input->post()) {
            $this->updateDashboardSettings();
        }
        
        // Get settings data
        $data = $this->getSettingsData();
        
        // Set page metadata
        $data['title'] = 'Dashboard Settings | CFO Dashboard';
        $data['page_title'] = 'Dashboard Settings';
        $data['breadcrumbs'] = [
            ['title' => 'Admin', 'url' => '/admin'],
            ['title' => 'Finance', 'url' => '/admin/finance'],
            ['title' => 'CFO Dashboard', 'url' => '/admin/finance/cfo-dashboard'],
            ['title' => 'Settings', 'url' => '', 'active' => true]
        ];
        
        // Load settings assets
        $this->loadSettingsAssets();
        
        // Display settings page
        $this->admintemplate->show('finance', 'dashboard_settings', $data);
    }
    
    /**
     * Get Real-time Dashboard Data (AJAX Endpoint)
     * 
     * Returns JSON data for real-time dashboard updates
     */
    public function get_realtime_data()
    {
        // Check if request is AJAX
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $data = [
            'revenue_summary' => $this->getRevenueSummary(),
            'sales_performance' => $this->getSalesPerformanceSummary(),
            'revenue_trends' => $this->getRevenueTrends(),
            'alerts' => $this->getActiveAlerts(),
            'last_updated' => date('Y-m-d H:i:s')
        ];
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
    
    /**
     * Get Revenue Trends Data (AJAX Endpoint)
     * 
     * @param string $period Period type (daily, weekly, monthly)
     * @param int $days Number of days to retrieve
     */
    public function get_revenue_trends($period = 'daily', $days = 30)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $trends = $this->revenueanalytics->getRevenueTrends($days, $period);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($trends));
    }
    
    /**
     * Get Sales Rep Performance Data (AJAX Endpoint)
     * 
     * @param string $period Period type (monthly, quarterly, yearly)
     */
    public function get_sales_rep_performance($period = 'monthly')
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $performance = $this->revenueanalytics->getSalesRepPerformance($period);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($performance));
    }
    
    /**
     * Generate Revenue Forecast (AJAX Endpoint)
     * 
     * @param string $period Period type
     * @param int $periods_ahead Number of periods to forecast
     * @param string $method Forecasting method
     */
    public function generate_forecast($period = 'monthly', $periods_ahead = 3, $method = 'linear')
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        
        $forecasts = $this->predictiveanalytics->forecastRevenue($period, $periods_ahead, $method);
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($forecasts));
    }
    
    /**
     * Export Dashboard Data
     * 
     * @param string $format Export format (pdf, excel, csv)
     * @param string $type Data type (summary, detailed, forecast)
     */
    public function export($format = 'pdf', $type = 'summary')
    {
        $this->common->checkRoleAccess();
        
        switch ($format) {
            case 'excel':
                $this->exportToExcel($type);
                break;
            case 'csv':
                $this->exportToCsv($type);
                break;
            case 'pdf':
            default:
                $this->exportToPdf($type);
                break;
        }
    }
    
    /**
     * Update Revenue Summaries (Cron Job Endpoint)
     * 
     * Updates daily revenue summaries for dashboard
     */
    public function update_revenue_summaries()
    {
        // Verify this is being called from cron or admin
        if (!$this->isCronOrAdmin()) {
            show_404();
        }
        
        $dates = $this->getDateRangeForUpdate();
        $updated = 0;
        
        foreach ($dates as $date) {
            if ($this->revenueanalytics->generateDailySummary($date)) {
                $updated++;
            }
        }
        
        log_message('info', "Updated {$updated} revenue summaries");
        
        if ($this->input->is_cli_request()) {
            echo "Updated {$updated} revenue summaries\n";
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['updated' => $updated, 'status' => 'success']));
        }
    }
    
    /**
     * Update Sales Rep Performance (Cron Job Endpoint)
     * 
     * Updates sales rep performance metrics
     */
    public function update_sales_performance()
    {
        if (!$this->isCronOrAdmin()) {
            show_404();
        }
        
        $success = $this->revenueanalytics->updateSalesRepPerformance();
        
        log_message('info', 'Sales rep performance updated: ' . ($success ? 'success' : 'failed'));
        
        if ($this->input->is_cli_request()) {
            echo "Sales rep performance " . ($success ? 'updated successfully' : 'update failed') . "\n";
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => $success ? 'success' : 'error']));
        }
    }
    
    /**
     * Generate Daily Forecasts (Cron Job Endpoint)
     * 
     * Generates revenue forecasts for upcoming periods
     */
    public function generate_daily_forecasts()
    {
        if (!$this->isCronOrAdmin()) {
            show_404();
        }
        
        $forecasts = [
            'monthly' => $this->predictiveanalytics->forecastRevenue('monthly', 3),
            'quarterly' => $this->predictiveanalytics->forecastRevenue('quarterly', 2),
            'yearly' => $this->predictiveanalytics->forecastRevenue('yearly', 1)
        ];
        
        $totalForecasts = array_sum(array_map('count', $forecasts));
        
        log_message('info', "Generated {$totalForecasts} revenue forecasts");
        
        if ($this->input->is_cli_request()) {
            echo "Generated {$totalForecasts} revenue forecasts\n";
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['forecasts_generated' => $totalForecasts, 'status' => 'success']));
        }
    }
    
    /**
     * Check Revenue Alerts (Cron Job Endpoint)
     * 
     * Checks for revenue alerts and sends notifications
     */
    public function check_revenue_alerts()
    {
        if (!$this->isCronOrAdmin()) {
            show_404();
        }
        
        $alerts = $this->checkForAlerts();
        $alertsSent = 0;
        
        foreach ($alerts as $alert) {
            if ($this->sendAlert($alert)) {
                $alertsSent++;
            }
        }
        
        log_message('info', "Checked alerts, sent {$alertsSent} notifications");
        
        if ($this->input->is_cli_request()) {
            echo "Sent {$alertsSent} alert notifications\n";
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['alerts_sent' => $alertsSent, 'status' => 'success']));
        }
    }
    
    /**
     * Cleanup Cache (Cron Job Endpoint)
     * 
     * Cleans up old cached data
     */
    public function cleanup_cache()
    {
        if (!$this->isCronOrAdmin()) {
            show_404();
        }
        
        $this->revenueanalytics->clearAllCache();
        
        log_message('info', 'CFO Dashboard cache cleaned up');
        
        if ($this->input->is_cli_request()) {
            echo "Cache cleaned up successfully\n";
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['status' => 'success', 'message' => 'Cache cleaned up']));
        }
    }
    
    /**
     * Sync Historical Data (One-time setup)
     * 
     * Imports historical data for dashboard initialization
     */
    public function sync_historical_data()
    {
        if (!$this->isCronOrAdmin()) {
            show_404();
        }
        
        $startDate = date('Y-m-d', strtotime('-12 months'));
        $endDate = date('Y-m-d');
        
        $dates = [];
        $currentDate = $startDate;
        
        while ($currentDate <= $endDate) {
            $dates[] = $currentDate;
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }
        
        $synced = 0;
        foreach ($dates as $date) {
            if ($this->revenueanalytics->generateDailySummary($date)) {
                $synced++;
            }
        }
        
        log_message('info', "Synced {$synced} days of historical data");
        
        if ($this->input->is_cli_request()) {
            echo "Synced {$synced} days of historical data\n";
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['synced_days' => $synced, 'status' => 'success']));
        }
    }
    
    /**
     * Test Database Connection
     * 
     * Tests database connectivity for CFO dashboard tables
     */
    public function test_database_connection()
    {
        if (!$this->isCronOrAdmin()) {
            show_404();
        }
        
        $tables = [
            'cfo_revenue_daily_summary',
            'cfo_sales_rep_performance',
            'cfo_revenue_forecasts',
            'cfo_dashboard_settings'
        ];
        
        $results = [];
        
        foreach ($tables as $table) {
            try {
                $query = $this->db->query("SELECT COUNT(*) as count FROM {$table}");
                $result = $query->row_array();
                $results[$table] = [
                    'status' => 'success',
                    'record_count' => $result['count']
                ];
            } catch (Exception $e) {
                $results[$table] = [
                    'status' => 'error',
                    'error' => $e->getMessage()
                ];
            }
        }
        
        if ($this->input->is_cli_request()) {
            foreach ($results as $table => $result) {
                echo "{$table}: " . $result['status'];
                if ($result['status'] === 'success') {
                    echo " ({$result['record_count']} records)";
                } else {
                    echo " - Error: {$result['error']}";
                }
                echo "\n";
            }
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($results));
        }
    }
    
    /**
     * Performance Test
     * 
     * Tests dashboard performance metrics
     */
    public function performance_test()
    {
        if (!$this->isCronOrAdmin()) {
            show_404();
        }
        
        $startTime = microtime(true);
        
        // Test dashboard data loading
        $dashboardStart = microtime(true);
        $dashboardData = $this->getDashboardData();
        $dashboardTime = microtime(true) - $dashboardStart;
        
        // Test revenue trends
        $trendsStart = microtime(true);
        $trends = $this->revenueanalytics->getRevenueTrends(30);
        $trendsTime = microtime(true) - $trendsStart;
        
        // Test sales performance
        $salesStart = microtime(true);
        $salesPerformance = $this->revenueanalytics->getSalesRepPerformance();
        $salesTime = microtime(true) - $salesStart;
        
        $totalTime = microtime(true) - $startTime;
        
        $results = [
            'total_time' => round($totalTime, 3),
            'dashboard_load_time' => round($dashboardTime, 3),
            'trends_load_time' => round($trendsTime, 3),
            'sales_performance_time' => round($salesTime, 3),
            'memory_usage' => round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB',
            'status' => $totalTime < 3 ? 'good' : ($totalTime < 5 ? 'acceptable' : 'slow')
        ];
        
        if ($this->input->is_cli_request()) {
            echo "Performance Test Results:\n";
            echo "Total Time: {$results['total_time']}s\n";
            echo "Dashboard Load: {$results['dashboard_load_time']}s\n";
            echo "Trends Load: {$results['trends_load_time']}s\n";
            echo "Sales Performance: {$results['sales_performance_time']}s\n";
            echo "Memory Usage: {$results['memory_usage']}\n";
            echo "Status: {$results['status']}\n";
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($results));
        }
    }
    
    /**
     * Verify Data Accuracy
     * 
     * Compares dashboard data with source data for accuracy
     */
    public function verify_data_accuracy()
    {
        if (!$this->isCronOrAdmin()) {
            show_404();
        }
        
        $today = date('Y-m-d');
        
        // Get dashboard revenue for today
        $dashboardRevenue = $this->revenueanalytics->getDailyRevenue($today);
        
        // Get direct revenue from source tables
        $sourceRevenue = $this->getSourceRevenue($today);
        
        // Calculate accuracy
        $accuracy = 100;
        if ($sourceRevenue > 0) {
            $accuracy = 100 - (abs($dashboardRevenue - $sourceRevenue) / $sourceRevenue * 100);
        }
        
        $results = [
            'dashboard_revenue' => $dashboardRevenue,
            'source_revenue' => $sourceRevenue,
            'variance' => $dashboardRevenue - $sourceRevenue,
            'accuracy_percent' => round($accuracy, 2),
            'status' => $accuracy >= 99.9 ? 'excellent' : ($accuracy >= 95 ? 'good' : 'needs_attention')
        ];
        
        if ($this->input->is_cli_request()) {
            echo "Data Accuracy Verification:\n";
            echo "Dashboard Revenue: $" . number_format($results['dashboard_revenue'], 2) . "\n";
            echo "Source Revenue: $" . number_format($results['source_revenue'], 2) . "\n";
            echo "Variance: $" . number_format($results['variance'], 2) . "\n";
            echo "Accuracy: {$results['accuracy_percent']}%\n";
            echo "Status: {$results['status']}\n";
        } else {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($results));
        }
    }
    
    // Private helper methods
    
    /**
     * Check CFO Dashboard Access Permissions
     */
    private function checkCfoDashboardAccess()
    {
        $userdata = $this->session->userdata('user');
        
        // Check if user has CFO dashboard access
        $allowedRoles = ['cfo', 'finance_manager', 'admin'];
        $userRole = $userdata['role'] ?? 'user';
        
        if (!in_array($userRole, $allowedRoles) && !$userdata['is_master']) {
            show_error('Access Denied: You do not have permission to access the CFO Dashboard.', 403);
        }
    }
    
    /**
     * Get main dashboard data
     */
    private function getDashboardData()
    {
        return [
            'revenue_summary' => $this->getRevenueSummary(),
            'sales_performance' => $this->getSalesPerformanceSummary(),
            'revenue_trends' => $this->getRevenueTrends(),
            'top_performers' => $this->getTopPerformers(),
            'revenue_by_product' => $this->getRevenueByProduct(),
            'revenue_by_underwriter' => $this->getRevenueByUnderwriter(),
            'alerts' => $this->getActiveAlerts(),
            'forecast_summary' => $this->getForecastSummary()
        ];
    }
    
    /**
     * Get revenue summary data
     */
    private function getRevenueSummary()
    {
        return [
            'mtd_revenue' => $this->revenueanalytics->getMonthToDateRevenue(),
            'ytd_revenue' => $this->revenueanalytics->getYearToDateRevenue(),
            'mtd_vs_previous' => $this->revenueanalytics->getMonthOverMonthGrowth(),
            'projected_monthly' => $this->revenueanalytics->getProjectedMonthlyRevenue(),
            'budget_variance' => $this->revenueanalytics->getBudgetVariance(),
            'daily_revenue' => $this->revenueanalytics->getDailyRevenue()
        ];
    }
    
    /**
     * Get sales performance summary
     */
    private function getSalesPerformanceSummary()
    {
        $performance = $this->revenueanalytics->getSalesRepPerformance('monthly');
        
        return [
            'total_reps' => count($performance),
            'top_performer' => !empty($performance) ? $performance[0] : null,
            'avg_performance' => $this->calculateAveragePerformance($performance),
            'goals_met_count' => count(array_filter($performance, function($rep) {
                return $rep['goals_met'];
            }))
        ];
    }
    
    /**
     * Get revenue trends
     */
    private function getRevenueTrends()
    {
        return $this->revenueanalytics->getRevenueTrends(30, 'daily');
    }
    
    /**
     * Get top performers
     */
    private function getTopPerformers()
    {
        return $this->revenueanalytics->getTopPerformers(5);
    }
    
    /**
     * Get revenue by product type
     */
    private function getRevenueByProduct()
    {
        return $this->revenueanalytics->getRevenueByProductType();
    }
    
    /**
     * Get revenue by underwriter
     */
    private function getRevenueByUnderwriter()
    {
        return $this->revenueanalytics->getRevenueByUnderwriter();
    }
    
    /**
     * Get active alerts
     */
    private function getActiveAlerts()
    {
        // This would implement alert checking logic
        // For now, return empty array
        return [];
    }
    
    /**
     * Get forecast summary
     */
    private function getForecastSummary()
    {
        $forecasts = $this->predictiveanalytics->forecastRevenue('monthly', 1);
        return !empty($forecasts) ? $forecasts[0] : null;
    }
    
    /**
     * Load dashboard assets
     */
    private function loadDashboardAssets()
    {
        // CSS files
        $this->admintemplate->addCSS(base_url('assets/backend/css/cfo-dashboard.css?v=' . $this->version));
        $this->admintemplate->addCSS(base_url('assets/backend/vendor/chart.js/Chart.min.css'));
        
        // JavaScript files
        $this->admintemplate->addJS(base_url('assets/backend/vendor/chart.js/Chart.min.js'));
        $this->admintemplate->addJS(base_url('assets/backend/js/cfo-dashboard.js?v=' . $this->version));
        $this->admintemplate->addJS(base_url('assets/backend/js/cfo-charts.js?v=' . $this->version));
    }
    
    /**
     * Load analytics assets
     */
    private function loadAnalyticsAssets()
    {
        $this->loadDashboardAssets();
        $this->admintemplate->addCSS(base_url('assets/backend/css/revenue-analytics.css?v=' . $this->version));
        $this->admintemplate->addJS(base_url('assets/backend/js/revenue-analytics.js?v=' . $this->version));
    }
    
    /**
     * Load sales performance assets
     */
    private function loadSalesPerformanceAssets()
    {
        $this->loadDashboardAssets();
        $this->admintemplate->addCSS(base_url('assets/backend/css/sales-performance.css?v=' . $this->version));
        $this->admintemplate->addJS(base_url('assets/backend/js/sales-performance.js?v=' . $this->version));
    }
    
    /**
     * Load forecasting assets
     */
    private function loadForecastingAssets()
    {
        $this->loadDashboardAssets();
        $this->admintemplate->addCSS(base_url('assets/backend/css/forecasting.css?v=' . $this->version));
        $this->admintemplate->addJS(base_url('assets/backend/js/forecasting.js?v=' . $this->version));
    }
    
    /**
     * Load settings assets
     */
    private function loadSettingsAssets()
    {
        $this->admintemplate->addCSS(base_url('assets/backend/css/dashboard-settings.css?v=' . $this->version));
        $this->admintemplate->addJS(base_url('assets/backend/js/dashboard-settings.js?v=' . $this->version));
    }
    
    /**
     * Check if request is from cron or admin
     */
    private function isCronOrAdmin()
    {
        // Allow CLI requests (cron jobs)
        if ($this->input->is_cli_request()) {
            return true;
        }
        
        // Allow admin users
        $userdata = $this->session->userdata('user');
        if ($userdata && ($userdata['is_master'] || in_array($userdata['role'], ['admin', 'cfo']))) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Get date range for revenue summary updates
     */
    private function getDateRangeForUpdate()
    {
        // Update last 7 days by default
        $dates = [];
        for ($i = 6; $i >= 0; $i--) {
            $dates[] = date('Y-m-d', strtotime("-{$i} days"));
        }
        return $dates;
    }
    
    /**
     * Calculate average performance
     */
    private function calculateAveragePerformance($performance)
    {
        if (empty($performance)) {
            return 0;
        }
        
        $totalAchievement = array_sum(array_column($performance, 'goal_achievement_percent'));
        return $totalAchievement / count($performance);
    }
    
    /**
     * Get source revenue for accuracy verification
     */
    private function getSourceRevenue($date)
    {
        $sql = "
            SELECT SUM(premium) as total_revenue
            FROM order_details od
            WHERE DATE(sent_to_accounting_date) = ?
            AND status = 'closed'
        ";
        
        $query = $this->db->query($sql, [$date]);
        $result = $query->row_array();
        
        return $result['total_revenue'] ?: 0;
    }
    
    /**
     * Placeholder methods for additional functionality
     */
    
    private function getRevenueAnalyticsData()
    {
        return array_merge($this->getDashboardData(), [
            'detailed_trends' => $this->revenueanalytics->getRevenueTrends(90, 'daily'),
            'monthly_trends' => $this->revenueanalytics->getRevenueTrends(365, 'monthly'),
            'underwriter_analysis' => $this->getDetailedUnderwriterAnalysis(),
            'product_analysis' => $this->getDetailedProductAnalysis()
        ]);
    }
    
    private function getSalesPerformanceData()
    {
        return [
            'monthly_performance' => $this->revenueanalytics->getSalesRepPerformance('monthly'),
            'quarterly_performance' => $this->revenueanalytics->getSalesRepPerformance('quarterly'),
            'roi_analysis' => $this->predictiveanalytics->calculateSalesRepROI(),
            'performance_trends' => $this->getSalesRepTrends()
        ];
    }
    
    private function getForecastingData()
    {
        return [
            'monthly_forecasts' => $this->predictiveanalytics->forecastRevenue('monthly', 6),
            'quarterly_forecasts' => $this->predictiveanalytics->forecastRevenue('quarterly', 4),
            'opportunities' => $this->predictiveanalytics->identifyRevenueOpportunities(),
            'accuracy_history' => $this->getForecastAccuracyHistory()
        ];
    }
    
    private function getSettingsData()
    {
        return [
            'dashboard_settings' => $this->getDashboardSettings(),
            'alert_configurations' => $this->getAlertConfigurations(),
            'user_preferences' => $this->getUserPreferences()
        ];
    }
    
    // Placeholder methods to be implemented
    private function getDetailedUnderwriterAnalysis() { return []; }
    private function getDetailedProductAnalysis() { return []; }
    private function getSalesRepTrends() { return []; }
    private function getForecastAccuracyHistory() { return []; }
    private function getDashboardSettings() { return []; }
    private function getAlertConfigurations() { return []; }
    private function getUserPreferences() { return []; }
    private function updateDashboardSettings() { return true; }
    private function checkForAlerts() { return []; }
    private function sendAlert($alert) { return true; }
    private function exportToPdf($type) { return true; }
    private function exportToExcel($type) { return true; }
    private function exportToCsv($type) { return true; }
}
