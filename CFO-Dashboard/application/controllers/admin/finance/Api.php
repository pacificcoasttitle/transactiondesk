<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CFO Dashboard API Controller
 * 
 * RESTful API endpoints for CFO Dashboard data retrieval and management.
 * Provides JSON responses for AJAX calls and external integrations.
 * 
 * @package    CFO Dashboard
 * @subpackage API Controllers
 * @category   Finance
 * @author     Transaction Desk Development Team
 * @version    1.0.0
 */
class Api extends MX_Controller
{
    private $version;
    
    public function __construct()
    {
        parent::__construct();
        
        // Load required libraries
        $this->load->helper(['url', 'date']);
        $this->load->library(['session', 'form_validation']);
        $this->load->library('order/common');
        $this->load->library('finance/revenueAnalytics');
        $this->load->library('finance/predictiveAnalytics');
        
        // Set version for cache control
        $this->version = strtotime(date('Y-m-d'));
        
        // Check admin access
        $this->common->is_admin();
        
        // Check API access permissions
        $this->checkApiAccess();
        
        // Set JSON response headers
        $this->output
            ->set_content_type('application/json')
            ->set_header('Access-Control-Allow-Origin: *')
            ->set_header('Access-Control-Allow-Methods: GET, POST, OPTIONS')
            ->set_header('Access-Control-Allow-Headers: Content-Type, Authorization');
    }
    
    /**
     * Revenue Summary API Endpoint
     * 
     * GET /api/finance/revenue-summary
     * 
     * Returns comprehensive revenue summary data
     */
    public function revenue_summary()
    {
        if (!$this->input->is_ajax_request() && !$this->isApiCall()) {
            $this->returnError('Invalid request method', 405);
        }
        
        try {
            $date = $this->input->get('date') ?: date('Y-m-d');
            
            $summary = [
                'mtd_revenue' => $this->revenueanalytics->getMonthToDateRevenue($date),
                'ytd_revenue' => $this->revenueanalytics->getYearToDateRevenue($date),
                'daily_revenue' => $this->revenueanalytics->getDailyRevenue($date),
                'mtd_vs_previous' => $this->revenueanalytics->getMonthOverMonthGrowth($date),
                'projected_monthly' => $this->revenueanalytics->getProjectedMonthlyRevenue($date),
                'budget_variance' => $this->revenueanalytics->getBudgetVariance($date),
                'currency' => 'USD',
                'last_updated' => date('Y-m-d H:i:s'),
                'data_date' => $date
            ];
            
            $this->returnSuccess($summary);
            
        } catch (Exception $e) {
            $this->returnError('Error retrieving revenue summary: ' . $e->getMessage());
        }
    }
    
    /**
     * Revenue Trends API Endpoint
     * 
     * GET /api/finance/revenue-trends/{period}/{days}
     * 
     * Returns revenue trend data for charts
     * 
     * @param string $period Granularity (daily, weekly, monthly)
     * @param int $days Number of days to look back
     */
    public function revenue_trends($period = 'daily', $days = 30)
    {
        if (!$this->input->is_ajax_request() && !$this->isApiCall()) {
            $this->returnError('Invalid request method', 405);
        }
        
        try {
            // Validate parameters
            $validPeriods = ['daily', 'weekly', 'monthly'];
            if (!in_array($period, $validPeriods)) {
                $this->returnError('Invalid period parameter', 400);
            }
            
            $days = (int) $days;
            if ($days < 1 || $days > 365) {
                $this->returnError('Days parameter must be between 1 and 365', 400);
            }
            
            $trends = $this->revenueanalytics->getRevenueTrends($days, $period);
            
            // Add metadata
            $response = [
                'trends' => $trends,
                'period' => $period,
                'days' => $days,
                'total_points' => count($trends),
                'last_updated' => date('Y-m-d H:i:s')
            ];
            
            $this->returnSuccess($response);
            
        } catch (Exception $e) {
            $this->returnError('Error retrieving revenue trends: ' . $e->getMessage());
        }
    }
    
    /**
     * Sales Performance API Endpoint
     * 
     * GET /api/finance/sales-performance
     * 
     * Returns sales rep performance data
     */
    public function sales_performance()
    {
        if (!$this->input->is_ajax_request() && !$this->isApiCall()) {
            $this->returnError('Invalid request method', 405);
        }
        
        try {
            $period = $this->input->get('period') ?: 'monthly';
            $date = $this->input->get('date') ?: null;
            $limit = (int) ($this->input->get('limit') ?: 0);
            
            $performance = $this->revenueanalytics->getSalesRepPerformance($period, $date);
            
            // Apply limit if specified
            if ($limit > 0) {
                $performance = array_slice($performance, 0, $limit);
            }
            
            // Calculate summary statistics
            $summary = $this->calculatePerformanceSummary($performance);
            
            $response = [
                'performance' => $performance,
                'summary' => $summary,
                'period' => $period,
                'total_reps' => count($performance),
                'last_updated' => date('Y-m-d H:i:s')
            ];
            
            $this->returnSuccess($response);
            
        } catch (Exception $e) {
            $this->returnError('Error retrieving sales performance: ' . $e->getMessage());
        }
    }
    
    /**
     * Sales Rep ROI API Endpoint
     * 
     * GET /api/finance/sales-rep-roi
     * 
     * Returns ROI analysis for sales representatives
     */
    public function sales_rep_roi()
    {
        if (!$this->input->is_ajax_request() && !$this->isApiCall()) {
            $this->returnError('Invalid request method', 405);
        }
        
        try {
            $roiData = $this->predictiveanalytics->calculateSalesRepROI();
            
            $response = [
                'roi_analysis' => $roiData,
                'total_reps' => count($roiData),
                'avg_roi' => $this->calculateAverageROI($roiData),
                'last_updated' => date('Y-m-d H:i:s')
            ];
            
            $this->returnSuccess($response);
            
        } catch (Exception $e) {
            $this->returnError('Error retrieving ROI analysis: ' . $e->getMessage());
        }
    }
    
    /**
     * Revenue Forecast API Endpoint
     * 
     * GET/POST /api/finance/revenue-forecast
     * 
     * Generates or retrieves revenue forecasts
     */
    public function revenue_forecast()
    {
        if (!$this->input->is_ajax_request() && !$this->isApiCall()) {
            $this->returnError('Invalid request method', 405);
        }
        
        try {
            if ($this->input->method() === 'post') {
                // Generate new forecast
                $period = $this->input->post('period') ?: 'monthly';
                $periodsAhead = (int) ($this->input->post('periods_ahead') ?: 3);
                $method = $this->input->post('method') ?: 'linear';
                
                $forecasts = $this->predictiveanalytics->forecastRevenue($period, $periodsAhead, $method);
                
                $response = [
                    'forecasts' => $forecasts,
                    'period' => $period,
                    'periods_ahead' => $periodsAhead,
                    'method' => $method,
                    'generated_at' => date('Y-m-d H:i:s')
                ];
                
            } else {
                // Retrieve existing forecasts
                $period = $this->input->get('period') ?: 'monthly';
                $forecasts = $this->getExistingForecasts($period);
                
                $response = [
                    'forecasts' => $forecasts,
                    'period' => $period,
                    'last_updated' => date('Y-m-d H:i:s')
                ];
            }
            
            $this->returnSuccess($response);
            
        } catch (Exception $e) {
            $this->returnError('Error processing forecast request: ' . $e->getMessage());
        }
    }
    
    /**
     * Revenue Opportunities API Endpoint
     * 
     * GET /api/finance/revenue-opportunities
     * 
     * Returns revenue optimization opportunities
     */
    public function revenue_opportunities()
    {
        if (!$this->input->is_ajax_request() && !$this->isApiCall()) {
            $this->returnError('Invalid request method', 405);
        }
        
        try {
            $opportunities = $this->predictiveanalytics->identifyRevenueOpportunities();
            
            $response = [
                'opportunities' => $opportunities,
                'total_opportunities' => $this->countOpportunities($opportunities),
                'priority_summary' => $this->prioritizeOpportunities($opportunities),
                'last_updated' => date('Y-m-d H:i:s')
            ];
            
            $this->returnSuccess($response);
            
        } catch (Exception $e) {
            $this->returnError('Error retrieving opportunities: ' . $e->getMessage());
        }
    }
    
    /**
     * Product Mix Analysis API Endpoint
     * 
     * GET /api/finance/product-mix
     * 
     * Returns product mix analysis and recommendations
     */
    public function product_mix()
    {
        if (!$this->input->is_ajax_request() && !$this->isApiCall()) {
            $this->returnError('Invalid request method', 405);
        }
        
        try {
            $date = $this->input->get('date') ?: null;
            
            $currentMix = $this->revenueanalytics->getRevenueByProductType($date);
            $recommendations = $this->predictiveanalytics->getProductMixRecommendations();
            
            $response = [
                'current_mix' => $currentMix,
                'recommendations' => $recommendations,
                'analysis_date' => $date ?: date('Y-m-d'),
                'last_updated' => date('Y-m-d H:i:s')
            ];
            
            $this->returnSuccess($response);
            
        } catch (Exception $e) {
            $this->returnError('Error retrieving product mix: ' . $e->getMessage());
        }
    }
    
    /**
     * Underwriter Analysis API Endpoint
     * 
     * GET /api/finance/underwriter-analysis
     * 
     * Returns revenue breakdown by underwriter
     */
    public function underwriter_analysis()
    {
        if (!$this->input->is_ajax_request() && !$this->isApiCall()) {
            $this->returnError('Invalid request method', 405);
        }
        
        try {
            $date = $this->input->get('date') ?: null;
            
            $underwriterData = $this->revenueanalytics->getRevenueByUnderwriter($date);
            $analysis = $this->analyzeUnderwriterPerformance($underwriterData);
            
            $response = [
                'underwriter_data' => $underwriterData,
                'analysis' => $analysis,
                'analysis_date' => $date ?: date('Y-m-d'),
                'last_updated' => date('Y-m-d H:i:s')
            ];
            
            $this->returnSuccess($response);
            
        } catch (Exception $e) {
            $this->returnError('Error retrieving underwriter analysis: ' . $e->getMessage());
        }
    }
    
    /**
     * Dashboard Status API Endpoint
     * 
     * GET /api/finance/dashboard-status
     * 
     * Returns system status and health metrics
     */
    public function dashboard_status()
    {
        if (!$this->input->is_ajax_request() && !$this->isApiCall()) {
            $this->returnError('Invalid request method', 405);
        }
        
        try {
            $status = [
                'system_status' => 'operational',
                'database_status' => $this->checkDatabaseStatus(),
                'cache_status' => $this->checkCacheStatus(),
                'api_status' => 'available',
                'last_data_update' => $this->getLastDataUpdate(),
                'active_users' => $this->getActiveUsers(),
                'server_time' => date('Y-m-d H:i:s'),
                'version' => '1.0.0'
            ];
            
            $this->returnSuccess($status);
            
        } catch (Exception $e) {
            $this->returnError('Error retrieving dashboard status: ' . $e->getMessage());
        }
    }
    
    /**
     * Alerts API Endpoint
     * 
     * GET /api/finance/alerts
     * 
     * Returns active alerts and notifications
     */
    public function alerts()
    {
        if (!$this->input->is_ajax_request() && !$this->isApiCall()) {
            $this->returnError('Invalid request method', 405);
        }
        
        try {
            $alerts = $this->getActiveAlerts();
            
            $response = [
                'alerts' => $alerts,
                'total_alerts' => count($alerts),
                'critical_count' => $this->countAlertsByPriority($alerts, 'critical'),
                'high_count' => $this->countAlertsByPriority($alerts, 'high'),
                'last_updated' => date('Y-m-d H:i:s')
            ];
            
            $this->returnSuccess($response);
            
        } catch (Exception $e) {
            $this->returnError('Error retrieving alerts: ' . $e->getMessage());
        }
    }
    
    /**
     * Export Data API Endpoint
     * 
     * POST /api/finance/export
     * 
     * Triggers data export and returns download URL
     */
    public function export()
    {
        if ($this->input->method() !== 'post') {
            $this->returnError('Invalid request method', 405);
        }
        
        try {
            $format = $this->input->post('format') ?: 'pdf';
            $type = $this->input->post('type') ?: 'summary';
            $dateRange = $this->input->post('date_range') ?: null;
            
            // Validate format
            $validFormats = ['pdf', 'excel', 'csv'];
            if (!in_array($format, $validFormats)) {
                $this->returnError('Invalid export format', 400);
            }
            
            // Generate export file
            $filename = $this->generateExportFile($format, $type, $dateRange);
            
            $response = [
                'export_id' => uniqid('export_'),
                'filename' => $filename,
                'download_url' => base_url('admin/finance/cfo-dashboard/download/' . $filename),
                'format' => $format,
                'type' => $type,
                'generated_at' => date('Y-m-d H:i:s'),
                'expires_at' => date('Y-m-d H:i:s', strtotime('+24 hours'))
            ];
            
            $this->returnSuccess($response);
            
        } catch (Exception $e) {
            $this->returnError('Error generating export: ' . $e->getMessage());
        }
    }
    
    /**
     * Update Settings API Endpoint
     * 
     * POST /api/finance/settings
     * 
     * Updates dashboard settings
     */
    public function settings()
    {
        if ($this->input->method() !== 'post') {
            $this->returnError('Invalid request method', 405);
        }
        
        try {
            $settings = $this->input->post();
            
            // Validate and update settings
            $updated = $this->updateDashboardSettings($settings);
            
            $response = [
                'updated_settings' => $updated,
                'success' => true,
                'message' => 'Settings updated successfully',
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            $this->returnSuccess($response);
            
        } catch (Exception $e) {
            $this->returnError('Error updating settings: ' . $e->getMessage());
        }
    }
    
    // Private helper methods
    
    /**
     * Check API access permissions
     */
    private function checkApiAccess()
    {
        $userdata = $this->session->userdata('user');
        
        // Check if user has API access
        $allowedRoles = ['cfo', 'finance_manager', 'admin'];
        $userRole = $userdata['role'] ?? 'user';
        
        if (!in_array($userRole, $allowedRoles) && !$userdata['is_master']) {
            $this->returnError('Access Denied: Insufficient permissions for API access', 403);
        }
    }
    
    /**
     * Check if request is an API call
     */
    private function isApiCall()
    {
        $headers = $this->input->request_headers();
        return isset($headers['Accept']) && strpos($headers['Accept'], 'application/json') !== false;
    }
    
    /**
     * Return success response
     */
    private function returnSuccess($data)
    {
        $response = [
            'success' => true,
            'data' => $data,
            'timestamp' => date('c')
        ];
        
        $this->output->set_output(json_encode($response));
    }
    
    /**
     * Return error response
     */
    private function returnError($message, $code = 500)
    {
        $this->output->set_status_header($code);
        
        $response = [
            'success' => false,
            'error' => [
                'message' => $message,
                'code' => $code
            ],
            'timestamp' => date('c')
        ];
        
        $this->output->set_output(json_encode($response));
    }
    
    /**
     * Calculate performance summary statistics
     */
    private function calculatePerformanceSummary($performance)
    {
        if (empty($performance)) {
            return [
                'total_revenue' => 0,
                'avg_revenue' => 0,
                'top_performer_revenue' => 0,
                'goals_met_percentage' => 0
            ];
        }
        
        $totalRevenue = array_sum(array_column($performance, 'total_revenue'));
        $goalsMet = count(array_filter($performance, function($rep) {
            return $rep['goals_met'];
        }));
        
        return [
            'total_revenue' => $totalRevenue,
            'avg_revenue' => $totalRevenue / count($performance),
            'top_performer_revenue' => $performance[0]['total_revenue'] ?? 0,
            'goals_met_percentage' => ($goalsMet / count($performance)) * 100
        ];
    }
    
    /**
     * Calculate average ROI
     */
    private function calculateAverageROI($roiData)
    {
        if (empty($roiData)) {
            return 0;
        }
        
        $totalROI = array_sum(array_column($roiData, 'roi_percentage'));
        return $totalROI / count($roiData);
    }
    
    /**
     * Get existing forecasts from database
     */
    private function getExistingForecasts($period)
    {
        $this->db->where('forecast_type', $period);
        $this->db->where('is_active', 1);
        $this->db->where('forecast_period >=', date('Y-m-d'));
        $this->db->order_by('forecast_period', 'ASC');
        
        $query = $this->db->get('cfo_revenue_forecasts');
        return $query->result_array();
    }
    
    /**
     * Count opportunities by type
     */
    private function countOpportunities($opportunities)
    {
        $count = 0;
        foreach ($opportunities as $category) {
            if (is_array($category)) {
                $count += count($category);
            }
        }
        return $count;
    }
    
    /**
     * Prioritize opportunities
     */
    private function prioritizeOpportunities($opportunities)
    {
        $summary = [
            'high_priority' => 0,
            'medium_priority' => 0,
            'low_priority' => 0
        ];
        
        foreach ($opportunities as $category) {
            if (is_array($category)) {
                foreach ($category as $opportunity) {
                    if (isset($opportunity['priority'])) {
                        $summary[$opportunity['priority'] . '_priority']++;
                    }
                }
            }
        }
        
        return $summary;
    }
    
    /**
     * Analyze underwriter performance
     */
    private function analyzeUnderwriterPerformance($underwriterData)
    {
        $totalRevenue = array_sum(array_column($underwriterData, 'revenue'));
        
        $analysis = [];
        foreach ($underwriterData as $underwriter) {
            $percentage = $totalRevenue > 0 ? ($underwriter['revenue'] / $totalRevenue) * 100 : 0;
            
            $analysis[] = [
                'underwriter' => $underwriter['underwriter'],
                'revenue_percentage' => $percentage,
                'concentration_risk' => $percentage > 50 ? 'high' : ($percentage > 30 ? 'medium' : 'low'),
                'growth_trend' => 'stable' // This would be calculated from historical data
            ];
        }
        
        return $analysis;
    }
    
    /**
     * Check database status
     */
    private function checkDatabaseStatus()
    {
        try {
            $this->db->query('SELECT 1');
            return 'connected';
        } catch (Exception $e) {
            return 'error';
        }
    }
    
    /**
     * Check cache status
     */
    private function checkCacheStatus()
    {
        try {
            $this->cache->get('test_key');
            return 'available';
        } catch (Exception $e) {
            return 'error';
        }
    }
    
    /**
     * Get last data update timestamp
     */
    private function getLastDataUpdate()
    {
        $this->db->select('MAX(last_updated) as last_update');
        $this->db->from('cfo_revenue_daily_summary');
        $query = $this->db->get();
        $result = $query->row_array();
        
        return $result['last_update'] ?? null;
    }
    
    /**
     * Get active users count
     */
    private function getActiveUsers()
    {
        // This would count active sessions or logged-in users
        // Implementation depends on session management
        return 1; // Placeholder
    }
    
    /**
     * Get active alerts
     */
    private function getActiveAlerts()
    {
        // This would implement alert logic
        // For now, return empty array
        return [];
    }
    
    /**
     * Count alerts by priority
     */
    private function countAlertsByPriority($alerts, $priority)
    {
        return count(array_filter($alerts, function($alert) use ($priority) {
            return $alert['priority'] === $priority;
        }));
    }
    
    /**
     * Generate export file
     */
    private function generateExportFile($format, $type, $dateRange)
    {
        // This would implement export generation
        // For now, return placeholder filename
        return 'cfo_dashboard_export_' . date('YmdHis') . '.' . $format;
    }
    
    /**
     * Update dashboard settings
     */
    private function updateDashboardSettings($settings)
    {
        // This would implement settings update logic
        // For now, return the settings passed in
        return $settings;
    }
}
