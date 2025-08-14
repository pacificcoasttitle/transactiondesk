<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Revenue Analytics Library
 * 
 * Comprehensive revenue analytics and calculation engine for CFO Dashboard.
 * Handles revenue aggregation, performance metrics, and trend analysis.
 * 
 * @package    CFO Dashboard
 * @subpackage Libraries
 * @category   Finance
 * @author     Transaction Desk Development Team
 * @version    1.0.0
 */
class RevenueAnalytics
{
    private $CI;
    private $cache_enabled = true;
    private $cache_ttl = 3600; // 1 hour cache
    
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->database();
        $this->CI->load->helper('date');
        $this->CI->load->library('cache');
        
        // Load cache settings from configuration
        $this->cache_enabled = env('CFO_DASHBOARD_ENABLE_CACHING', true);
        $this->cache_ttl = env('CFO_DASHBOARD_CACHE_TTL', 3600);
    }
    
    /**
     * Generate daily revenue summary for a specific date
     * 
     * @param string $date Date in Y-m-d format (default: today)
     * @return bool Success status
     */
    public function generateDailySummary($date = null)
    {
        $date = $date ?: date('Y-m-d');
        
        try {
            // Get revenue data for the date
            $revenueData = $this->calculateDailyRevenue($date);
            
            if (empty($revenueData)) {
                log_message('info', "No revenue data found for date: {$date}");
                return false;
            }
            
            // Calculate additional metrics
            $additionalMetrics = $this->calculateAdditionalMetrics($date, $revenueData);
            
            // Merge all data
            $summaryData = array_merge($revenueData, $additionalMetrics);
            $summaryData['summary_date'] = $date;
            $summaryData['last_updated'] = date('Y-m-d H:i:s');
            
            // Insert or update summary
            $this->CI->db->replace('cfo_revenue_daily_summary', $summaryData);
            
            // Clear cache for this date
            $this->clearCache("daily_summary_{$date}");
            
            log_message('info', "Daily revenue summary generated for {$date}");
            return true;
            
        } catch (Exception $e) {
            log_message('error', "Error generating daily summary for {$date}: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Calculate daily revenue breakdown
     * 
     * @param string $date Date to calculate
     * @return array Revenue data
     */
    private function calculateDailyRevenue($date)
    {
        $sql = "
            SELECT 
                SUM(od.premium) as total_revenue,
                SUM(CASE WHEN od.prod_type = 'sale' THEN od.premium ELSE 0 END) as sales_revenue,
                SUM(CASE WHEN od.prod_type = 'loan' THEN od.premium ELSE 0 END) as refi_revenue,
                COUNT(*) as total_orders,
                SUM(CASE WHEN od.prod_type = 'sale' THEN 1 ELSE 0 END) as sales_orders,
                SUM(CASE WHEN od.prod_type = 'loan' THEN 1 ELSE 0 END) as refi_orders,
                AVG(od.premium) as avg_order_value,
                SUM(CASE WHEN od.underwriter = 'westcor' THEN od.premium ELSE 0 END) as westcor_revenue,
                SUM(CASE WHEN od.underwriter = 'fnf' THEN od.premium ELSE 0 END) as fnf_revenue,
                SUM(CASE WHEN od.underwriter = 'natic' OR od.underwriter = 'north_american' THEN od.premium ELSE 0 END) as natic_revenue
            FROM order_details od
            JOIN transaction_details td ON od.transaction_id = td.id
            WHERE DATE(od.sent_to_accounting_date) = ?
            AND od.status = 'closed'
        ";
        
        $query = $this->CI->db->query($sql, [$date]);
        $result = $query->row_array();
        
        // Convert null values to 0
        foreach ($result as $key => $value) {
            $result[$key] = $value ?: 0;
        }
        
        return $result;
    }
    
    /**
     * Calculate additional metrics for daily summary
     * 
     * @param string $date Current date
     * @param array $revenueData Basic revenue data
     * @return array Additional metrics
     */
    private function calculateAdditionalMetrics($date, $revenueData)
    {
        $metrics = [];
        
        // Calculate commission paid
        $metrics['commission_paid'] = $this->calculateDailyCommission($date);
        
        // Calculate profit margin
        if ($revenueData['total_revenue'] > 0) {
            $metrics['profit_margin'] = (($revenueData['total_revenue'] - $metrics['commission_paid']) / $revenueData['total_revenue']) * 100;
        } else {
            $metrics['profit_margin'] = 0;
        }
        
        // Get previous day revenue for comparison
        $previousDate = date('Y-m-d', strtotime($date . ' -1 day'));
        $metrics['previous_day_revenue'] = $this->getDailyRevenue($previousDate);
        
        // Calculate MTD and YTD revenue
        $metrics['mtd_revenue'] = $this->getMonthToDateRevenue($date);
        $metrics['ytd_revenue'] = $this->getYearToDateRevenue($date);
        
        // Calculate budget variance
        $monthlyBudget = $this->getMonthlyBudget(date('Y-m', strtotime($date)));
        $daysInMonth = date('t', strtotime($date));
        $dayOfMonth = date('j', strtotime($date));
        $expectedDailyRevenue = ($monthlyBudget / $daysInMonth) * $dayOfMonth;
        
        if ($expectedDailyRevenue > 0) {
            $metrics['budget_variance'] = (($metrics['mtd_revenue'] - $expectedDailyRevenue) / $expectedDailyRevenue) * 100;
        } else {
            $metrics['budget_variance'] = 0;
        }
        
        return $metrics;
    }
    
    /**
     * Calculate daily commission paid
     * 
     * @param string $date Date to calculate
     * @return float Commission amount
     */
    private function calculateDailyCommission($date)
    {
        $sql = "
            SELECT SUM(commission_amount) as total_commission
            FROM (
                SELECT 
                    td.sales_representative,
                    od.premium,
                    COALESCE(src.commission_amount, od.premium * 0.05) as commission_amount
                FROM order_details od
                JOIN transaction_details td ON od.transaction_id = td.id
                LEFT JOIN sales_rep_commissions src ON td.sales_representative = src.sales_rep_id 
                    AND DATE(od.sent_to_accounting_date) = DATE(src.calculation_date)
                WHERE DATE(od.sent_to_accounting_date) = ?
                AND od.status = 'closed'
                AND td.sales_representative IS NOT NULL
            ) commission_calc
        ";
        
        $query = $this->CI->db->query($sql, [$date]);
        $result = $query->row_array();
        
        return $result['total_commission'] ?: 0;
    }
    
    /**
     * Get revenue for a specific date
     * 
     * @param string $date Date in Y-m-d format
     * @return float Revenue amount
     */
    public function getDailyRevenue($date)
    {
        $cacheKey = "daily_revenue_{$date}";
        
        if ($this->cache_enabled) {
            $cached = $this->CI->cache->get($cacheKey);
            if ($cached !== false) {
                return $cached;
            }
        }
        
        $sql = "
            SELECT SUM(premium) as total_revenue
            FROM order_details od
            WHERE DATE(sent_to_accounting_date) = ?
            AND status = 'closed'
        ";
        
        $query = $this->CI->db->query($sql, [$date]);
        $result = $query->row_array();
        $revenue = $result['total_revenue'] ?: 0;
        
        if ($this->cache_enabled) {
            $this->CI->cache->save($cacheKey, $revenue, $this->cache_ttl);
        }
        
        return $revenue;
    }
    
    /**
     * Get month-to-date revenue
     * 
     * @param string $date Reference date
     * @return float MTD revenue
     */
    public function getMonthToDateRevenue($date = null)
    {
        $date = $date ?: date('Y-m-d');
        $monthStart = date('Y-m-01', strtotime($date));
        
        $cacheKey = "mtd_revenue_{$date}";
        
        if ($this->cache_enabled) {
            $cached = $this->CI->cache->get($cacheKey);
            if ($cached !== false) {
                return $cached;
            }
        }
        
        $sql = "
            SELECT SUM(premium) as mtd_revenue
            FROM order_details od
            WHERE DATE(sent_to_accounting_date) >= ?
            AND DATE(sent_to_accounting_date) <= ?
            AND status = 'closed'
        ";
        
        $query = $this->CI->db->query($sql, [$monthStart, $date]);
        $result = $query->row_array();
        $revenue = $result['mtd_revenue'] ?: 0;
        
        if ($this->cache_enabled) {
            $this->CI->cache->save($cacheKey, $revenue, $this->cache_ttl);
        }
        
        return $revenue;
    }
    
    /**
     * Get year-to-date revenue
     * 
     * @param string $date Reference date
     * @return float YTD revenue
     */
    public function getYearToDateRevenue($date = null)
    {
        $date = $date ?: date('Y-m-d');
        $yearStart = $this->getFiscalYearStart($date);
        
        $cacheKey = "ytd_revenue_{$date}";
        
        if ($this->cache_enabled) {
            $cached = $this->CI->cache->get($cacheKey);
            if ($cached !== false) {
                return $cached;
            }
        }
        
        $sql = "
            SELECT SUM(premium) as ytd_revenue
            FROM order_details od
            WHERE DATE(sent_to_accounting_date) >= ?
            AND DATE(sent_to_accounting_date) <= ?
            AND status = 'closed'
        ";
        
        $query = $this->CI->db->query($sql, [$yearStart, $date]);
        $result = $query->row_array();
        $revenue = $result['ytd_revenue'] ?: 0;
        
        if ($this->cache_enabled) {
            $this->CI->cache->save($cacheKey, $revenue, $this->cache_ttl);
        }
        
        return $revenue;
    }
    
    /**
     * Get revenue trends for a specified period
     * 
     * @param int $days Number of days to look back
     * @param string $granularity 'daily', 'weekly', or 'monthly'
     * @return array Trend data
     */
    public function getRevenueTrends($days = 30, $granularity = 'daily')
    {
        $cacheKey = "revenue_trends_{$days}_{$granularity}";
        
        if ($this->cache_enabled) {
            $cached = $this->CI->cache->get($cacheKey);
            if ($cached !== false) {
                return $cached;
            }
        }
        
        switch ($granularity) {
            case 'weekly':
                $groupBy = "YEARWEEK(summary_date)";
                $dateFormat = "%Y-W%u";
                break;
            case 'monthly':
                $groupBy = "DATE_FORMAT(summary_date, '%Y-%m')";
                $dateFormat = "%Y-%m";
                break;
            case 'daily':
            default:
                $groupBy = "DATE(summary_date)";
                $dateFormat = "%Y-%m-%d";
                break;
        }
        
        $sql = "
            SELECT 
                DATE_FORMAT(summary_date, '{$dateFormat}') as period_label,
                summary_date,
                SUM(total_revenue) as total_revenue,
                SUM(sales_revenue) as sales_revenue,
                SUM(refi_revenue) as refi_revenue,
                SUM(total_orders) as total_orders,
                AVG(avg_order_value) as avg_order_value
            FROM cfo_revenue_daily_summary 
            WHERE summary_date >= DATE_SUB(CURDATE(), INTERVAL {$days} DAY)
            GROUP BY {$groupBy}
            ORDER BY summary_date ASC
        ";
        
        $query = $this->CI->db->query($sql);
        $trends = $query->result_array();
        
        // Calculate growth rates
        for ($i = 1; $i < count($trends); $i++) {
            $current = $trends[$i]['total_revenue'];
            $previous = $trends[$i-1]['total_revenue'];
            
            if ($previous > 0) {
                $trends[$i]['growth_rate'] = (($current - $previous) / $previous) * 100;
            } else {
                $trends[$i]['growth_rate'] = 0;
            }
        }
        
        if (!empty($trends)) {
            $trends[0]['growth_rate'] = 0; // First period has no growth rate
        }
        
        if ($this->cache_enabled) {
            $this->CI->cache->save($cacheKey, $trends, $this->cache_ttl);
        }
        
        return $trends;
    }
    
    /**
     * Get sales rep performance data
     * 
     * @param string $period Period type ('monthly', 'quarterly', 'yearly')
     * @param string $date Reference date
     * @return array Sales rep performance data
     */
    public function getSalesRepPerformance($period = 'monthly', $date = null)
    {
        $date = $date ?: date('Y-m-d');
        list($periodStart, $periodEnd) = $this->getPeriodDates($period, $date);
        
        $cacheKey = "sales_rep_performance_{$period}_{$date}";
        
        if ($this->cache_enabled) {
            $cached = $this->CI->cache->get($cacheKey);
            if ($cached !== false) {
                return $cached;
            }
        }
        
        $sql = "
            SELECT 
                u.id as sales_rep_id,
                CONCAT(u.first_name, ' ', u.last_name) as sales_rep_name,
                SUM(od.premium) as total_revenue,
                SUM(CASE WHEN od.prod_type = 'sale' THEN od.premium ELSE 0 END) as sales_revenue,
                SUM(CASE WHEN od.prod_type = 'loan' THEN od.premium ELSE 0 END) as refi_revenue,
                COUNT(*) as total_orders,
                AVG(od.premium) as avg_order_value,
                SUM(COALESCE(src.commission_amount, od.premium * 0.05)) as commission_earned,
                (SUM(COALESCE(src.commission_amount, od.premium * 0.05)) / SUM(od.premium)) * 100 as commission_rate,
                COALESCE(sr.sales_rep_no_of_open_orders / 12, 0) as monthly_goal,
                (SUM(od.premium) / NULLIF(COALESCE(sr.sales_rep_no_of_open_orders / 12, 0), 0)) * 100 as goal_achievement_percent
            FROM users u
            JOIN transaction_details td ON u.id = td.sales_representative
            JOIN order_details od ON td.id = od.transaction_id
            LEFT JOIN sales_rep_commissions src ON u.id = src.sales_rep_id 
                AND DATE(od.sent_to_accounting_date) = DATE(src.calculation_date)
            LEFT JOIN sales_rep sr ON u.id = sr.user_id
            WHERE u.is_sales_rep = 1
            AND DATE(od.sent_to_accounting_date) >= ?
            AND DATE(od.sent_to_accounting_date) <= ?
            AND od.status = 'closed'
            GROUP BY u.id, u.first_name, u.last_name, sr.sales_rep_no_of_open_orders
            ORDER BY total_revenue DESC
        ";
        
        $query = $this->CI->db->query($sql, [$periodStart, $periodEnd]);
        $performance = $query->result_array();
        
        // Add ranking
        foreach ($performance as $index => $rep) {
            $performance[$index]['ranking_position'] = $index + 1;
            $performance[$index]['goals_met'] = $rep['goal_achievement_percent'] >= 100;
            
            // Calculate ROI
            if ($rep['commission_earned'] > 0) {
                $performance[$index]['roi_percentage'] = (($rep['total_revenue'] - $rep['commission_earned']) / $rep['commission_earned']) * 100;
            } else {
                $performance[$index]['roi_percentage'] = 0;
            }
        }
        
        if ($this->cache_enabled) {
            $this->CI->cache->save($cacheKey, $performance, $this->cache_ttl);
        }
        
        return $performance;
    }
    
    /**
     * Get executive summary data
     * 
     * @param string $date Reference date
     * @return array Executive summary
     */
    public function getExecutiveSummary($date = null)
    {
        $date = $date ?: date('Y-m-d');
        
        return [
            'mtd_revenue' => $this->getMonthToDateRevenue($date),
            'ytd_revenue' => $this->getYearToDateRevenue($date),
            'mtd_vs_previous' => $this->getMonthOverMonthGrowth($date),
            'projected_monthly' => $this->getProjectedMonthlyRevenue($date),
            'budget_variance' => $this->getBudgetVariance($date),
            'top_performers' => $this->getTopPerformers(5, $date),
            'revenue_by_product' => $this->getRevenueByProductType($date),
            'revenue_by_underwriter' => $this->getRevenueByUnderwriter($date)
        ];
    }
    
    /**
     * Get month-over-month growth percentage
     * 
     * @param string $date Reference date
     * @return float Growth percentage
     */
    public function getMonthOverMonthGrowth($date = null)
    {
        $date = $date ?: date('Y-m-d');
        $currentMtd = $this->getMonthToDateRevenue($date);
        
        // Get previous month same day
        $previousMonthDate = date('Y-m-d', strtotime($date . ' -1 month'));
        $previousMtd = $this->getMonthToDateRevenue($previousMonthDate);
        
        if ($previousMtd > 0) {
            return (($currentMtd - $previousMtd) / $previousMtd) * 100;
        }
        
        return 0;
    }
    
    /**
     * Get projected monthly revenue based on current trends
     * 
     * @param string $date Reference date
     * @return float Projected revenue
     */
    public function getProjectedMonthlyRevenue($date = null)
    {
        $date = $date ?: date('Y-m-d');
        $dayOfMonth = date('j', strtotime($date));
        $daysInMonth = date('t', strtotime($date));
        $mtdRevenue = $this->getMonthToDateRevenue($date);
        
        if ($dayOfMonth > 0) {
            return ($mtdRevenue / $dayOfMonth) * $daysInMonth;
        }
        
        return 0;
    }
    
    /**
     * Get budget variance percentage
     * 
     * @param string $date Reference date
     * @return float Variance percentage
     */
    public function getBudgetVariance($date = null)
    {
        $date = $date ?: date('Y-m-d');
        $monthlyBudget = $this->getMonthlyBudget(date('Y-m', strtotime($date)));
        $projectedRevenue = $this->getProjectedMonthlyRevenue($date);
        
        if ($monthlyBudget > 0) {
            return (($projectedRevenue - $monthlyBudget) / $monthlyBudget) * 100;
        }
        
        return 0;
    }
    
    /**
     * Get top performing sales reps
     * 
     * @param int $limit Number of top performers
     * @param string $date Reference date
     * @return array Top performers
     */
    public function getTopPerformers($limit = 5, $date = null)
    {
        $performance = $this->getSalesRepPerformance('monthly', $date);
        return array_slice($performance, 0, $limit);
    }
    
    /**
     * Get revenue breakdown by product type
     * 
     * @param string $date Reference date
     * @return array Revenue by product type
     */
    public function getRevenueByProductType($date = null)
    {
        $date = $date ?: date('Y-m-d');
        $monthStart = date('Y-m-01', strtotime($date));
        
        $sql = "
            SELECT 
                prod_type,
                SUM(premium) as revenue,
                COUNT(*) as orders,
                AVG(premium) as avg_value
            FROM order_details od
            WHERE DATE(sent_to_accounting_date) >= ?
            AND DATE(sent_to_accounting_date) <= ?
            AND status = 'closed'
            GROUP BY prod_type
        ";
        
        $query = $this->CI->db->query($sql, [$monthStart, $date]);
        return $query->result_array();
    }
    
    /**
     * Get revenue breakdown by underwriter
     * 
     * @param string $date Reference date
     * @return array Revenue by underwriter
     */
    public function getRevenueByUnderwriter($date = null)
    {
        $date = $date ?: date('Y-m-d');
        $monthStart = date('Y-m-01', strtotime($date));
        
        $sql = "
            SELECT 
                CASE 
                    WHEN underwriter = 'north_american' THEN 'natic'
                    ELSE underwriter
                END as underwriter,
                SUM(premium) as revenue,
                COUNT(*) as orders,
                AVG(premium) as avg_value
            FROM order_details od
            WHERE DATE(sent_to_accounting_date) >= ?
            AND DATE(sent_to_accounting_date) <= ?
            AND status = 'closed'
            GROUP BY underwriter
        ";
        
        $query = $this->CI->db->query($sql, [$monthStart, $date]);
        return $query->result_array();
    }
    
    /**
     * Update sales rep performance metrics
     * 
     * @param int $salesRepId Sales rep user ID
     * @param string $period Period type
     * @param string $date Reference date
     * @return bool Success status
     */
    public function updateSalesRepPerformance($salesRepId = null, $period = 'monthly', $date = null)
    {
        $date = $date ?: date('Y-m-d');
        list($periodStart, $periodEnd) = $this->getPeriodDates($period, $date);
        
        $whereClause = '';
        $params = [$periodStart, $periodEnd];
        
        if ($salesRepId) {
            $whereClause = 'AND u.id = ?';
            $params[] = $salesRepId;
        }
        
        $sql = "
            SELECT 
                u.id as sales_rep_id,
                SUM(od.premium) as total_revenue,
                SUM(CASE WHEN od.prod_type = 'sale' THEN od.premium ELSE 0 END) as sales_revenue,
                SUM(CASE WHEN od.prod_type = 'loan' THEN od.premium ELSE 0 END) as refi_revenue,
                COUNT(*) as total_orders,
                SUM(CASE WHEN od.prod_type = 'sale' THEN 1 ELSE 0 END) as sales_orders,
                SUM(CASE WHEN od.prod_type = 'loan' THEN 1 ELSE 0 END) as refi_orders,
                AVG(od.premium) as avg_order_value,
                SUM(COALESCE(src.commission_amount, od.premium * 0.05)) as commission_earned
            FROM users u
            JOIN transaction_details td ON u.id = td.sales_representative
            JOIN order_details od ON td.id = od.transaction_id
            LEFT JOIN sales_rep_commissions src ON u.id = src.sales_rep_id 
                AND DATE(od.sent_to_accounting_date) = DATE(src.calculation_date)
            WHERE u.is_sales_rep = 1
            AND DATE(od.sent_to_accounting_date) >= ?
            AND DATE(od.sent_to_accounting_date) <= ?
            AND od.status = 'closed'
            {$whereClause}
            GROUP BY u.id
        ";
        
        $query = $this->CI->db->query($sql, $params);
        $salesReps = $query->result_array();
        
        foreach ($salesReps as $rep) {
            $performanceData = [
                'sales_rep_id' => $rep['sales_rep_id'],
                'period_type' => $period,
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'total_revenue' => $rep['total_revenue'],
                'sales_revenue' => $rep['sales_revenue'],
                'refi_revenue' => $rep['refi_revenue'],
                'total_orders' => $rep['total_orders'],
                'sales_orders' => $rep['sales_orders'],
                'refi_orders' => $rep['refi_orders'],
                'avg_order_value' => $rep['avg_order_value'],
                'commission_earned' => $rep['commission_earned'],
                'last_calculated' => date('Y-m-d H:i:s')
            ];
            
            // Add additional calculated fields
            if ($rep['total_revenue'] > 0) {
                $performanceData['commission_rate'] = ($rep['commission_earned'] / $rep['total_revenue']) * 100;
            } else {
                $performanceData['commission_rate'] = 0;
            }
            
            if ($rep['commission_earned'] > 0) {
                $performanceData['roi_percentage'] = (($rep['total_revenue'] - $rep['commission_earned']) / $rep['commission_earned']) * 100;
            } else {
                $performanceData['roi_percentage'] = 0;
            }
            
            // Insert or update performance record
            $this->CI->db->replace('cfo_sales_rep_performance', $performanceData);
        }
        
        // Clear relevant caches
        $this->clearCache("sales_rep_performance_{$period}_{$date}");
        
        return true;
    }
    
    /**
     * Get period start and end dates
     * 
     * @param string $period Period type
     * @param string $date Reference date
     * @return array [start_date, end_date]
     */
    private function getPeriodDates($period, $date)
    {
        switch ($period) {
            case 'daily':
                return [$date, $date];
                
            case 'weekly':
                $weekStart = date('Y-m-d', strtotime('monday this week', strtotime($date)));
                $weekEnd = date('Y-m-d', strtotime('sunday this week', strtotime($date)));
                return [$weekStart, $weekEnd];
                
            case 'monthly':
                $monthStart = date('Y-m-01', strtotime($date));
                $monthEnd = date('Y-m-t', strtotime($date));
                return [$monthStart, $monthEnd];
                
            case 'quarterly':
                $quarter = ceil(date('n', strtotime($date)) / 3);
                $quarterStart = date('Y-m-01', mktime(0, 0, 0, ($quarter - 1) * 3 + 1, 1, date('Y', strtotime($date))));
                $quarterEnd = date('Y-m-t', mktime(0, 0, 0, $quarter * 3, 1, date('Y', strtotime($date))));
                return [$quarterStart, $quarterEnd];
                
            case 'yearly':
                $yearStart = $this->getFiscalYearStart($date);
                $yearEnd = date('Y-m-d', strtotime($yearStart . ' +1 year -1 day'));
                return [$yearStart, $yearEnd];
                
            default:
                return [$date, $date];
        }
    }
    
    /**
     * Get fiscal year start date
     * 
     * @param string $date Reference date
     * @return string Fiscal year start date
     */
    private function getFiscalYearStart($date)
    {
        $fiscalStart = $this->getSetting('fiscal_year_start', '01-01');
        list($month, $day) = explode('-', $fiscalStart);
        
        $currentYear = date('Y', strtotime($date));
        $fiscalStartThisYear = date('Y-m-d', mktime(0, 0, 0, $month, $day, $currentYear));
        
        if (strtotime($date) >= strtotime($fiscalStartThisYear)) {
            return $fiscalStartThisYear;
        } else {
            return date('Y-m-d', mktime(0, 0, 0, $month, $day, $currentYear - 1));
        }
    }
    
    /**
     * Get monthly budget target
     * 
     * @param string $month Month in Y-m format
     * @return float Budget amount
     */
    private function getMonthlyBudget($month)
    {
        return $this->getSetting('monthly_revenue_budget', 500000.00);
    }
    
    /**
     * Get dashboard setting value
     * 
     * @param string $settingName Setting name
     * @param mixed $default Default value
     * @return mixed Setting value
     */
    private function getSetting($settingName, $default = null)
    {
        $cacheKey = "dashboard_setting_{$settingName}";
        
        if ($this->cache_enabled) {
            $cached = $this->CI->cache->get($cacheKey);
            if ($cached !== false) {
                return $cached;
            }
        }
        
        $this->CI->db->where('setting_name', $settingName);
        $query = $this->CI->db->get('cfo_dashboard_settings');
        
        if ($query->num_rows() > 0) {
            $setting = $query->row_array();
            $value = $setting['setting_value'];
            
            // Convert based on type
            switch ($setting['setting_type']) {
                case 'integer':
                    $value = (int) $value;
                    break;
                case 'decimal':
                    $value = (float) $value;
                    break;
                case 'boolean':
                    $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
                    break;
                case 'json':
                    $value = json_decode($value, true);
                    break;
                case 'array':
                    $value = unserialize($value);
                    break;
            }
            
            if ($this->cache_enabled) {
                $this->CI->cache->save($cacheKey, $value, $this->cache_ttl);
            }
            
            return $value;
        }
        
        return $default;
    }
    
    /**
     * Clear cache for specific key
     * 
     * @param string $key Cache key
     */
    private function clearCache($key)
    {
        if ($this->cache_enabled) {
            $this->CI->cache->delete($key);
        }
    }
    
    /**
     * Clear all revenue analytics cache
     */
    public function clearAllCache()
    {
        if ($this->cache_enabled) {
            $this->CI->cache->clean();
        }
    }
}
