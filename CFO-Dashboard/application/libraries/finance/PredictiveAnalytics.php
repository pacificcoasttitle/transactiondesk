<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Predictive Analytics Library
 * 
 * Advanced predictive analytics and forecasting engine for CFO Dashboard.
 * Provides revenue forecasting, trend analysis, and business intelligence.
 * 
 * @package    CFO Dashboard
 * @subpackage Libraries
 * @category   Finance
 * @author     Transaction Desk Development Team
 * @version    1.0.0
 */
class PredictiveAnalytics
{
    private $CI;
    private $revenueAnalytics;
    
    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->database();
        $this->CI->load->library('finance/revenueanalytics');
        $this->revenueAnalytics = $this->CI->revenueanalytics;
    }
    
    /**
     * Generate revenue forecast for specified period
     * 
     * @param string $period 'monthly', 'quarterly', 'yearly'
     * @param int $periodsAhead Number of periods to forecast
     * @param string $method Forecasting method ('linear', 'exponential', 'seasonal')
     * @return array Forecast data
     */
    public function forecastRevenue($period = 'monthly', $periodsAhead = 3, $method = 'linear')
    {
        // Get historical data for analysis
        $historicalData = $this->getHistoricalRevenueData($period, 12); // Last 12 periods
        
        if (empty($historicalData)) {
            return [];
        }
        
        $forecasts = [];
        
        switch ($method) {
            case 'exponential':
                $forecasts = $this->exponentialSmoothing($historicalData, $periodsAhead);
                break;
            case 'seasonal':
                $forecasts = $this->seasonalForecasting($historicalData, $periodsAhead, $period);
                break;
            case 'linear':
            default:
                $forecasts = $this->linearRegression($historicalData, $periodsAhead);
                break;
        }
        
        // Save forecasts to database
        $this->saveForecastsToDatabase($forecasts, $period, $method);
        
        return $forecasts;
    }
    
    /**
     * Linear regression forecasting
     * 
     * @param array $historicalData Historical revenue data
     * @param int $periodsAhead Number of periods to forecast
     * @return array Forecast data
     */
    private function linearRegression($historicalData, $periodsAhead)
    {
        $n = count($historicalData);
        if ($n < 2) {
            return [];
        }
        
        // Prepare data for regression
        $x = range(1, $n);
        $y = array_column($historicalData, 'total_revenue');
        
        // Calculate regression coefficients
        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumXY = 0;
        $sumX2 = 0;
        
        for ($i = 0; $i < $n; $i++) {
            $sumXY += $x[$i] * $y[$i];
            $sumX2 += $x[$i] * $x[$i];
        }
        
        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        $intercept = ($sumY - $slope * $sumX) / $n;
        
        // Calculate R-squared for confidence
        $yMean = $sumY / $n;
        $ssRes = 0;
        $ssTot = 0;
        
        for ($i = 0; $i < $n; $i++) {
            $predicted = $intercept + $slope * $x[$i];
            $ssRes += pow($y[$i] - $predicted, 2);
            $ssTot += pow($y[$i] - $yMean, 2);
        }
        
        $rSquared = 1 - ($ssRes / $ssTot);
        $confidence = min(0.95, max(0.5, $rSquared)); // Cap confidence between 50% and 95%
        
        // Generate forecasts
        $forecasts = [];
        $lastPeriod = end($historicalData)['period'];
        
        for ($i = 1; $i <= $periodsAhead; $i++) {
            $forecastValue = $intercept + $slope * ($n + $i);
            $forecastValue = max(0, $forecastValue); // Ensure non-negative
            
            $forecastPeriod = $this->getNextPeriod($lastPeriod, $i);
            
            $forecasts[] = [
                'forecast_period' => $forecastPeriod,
                'forecasted_revenue' => round($forecastValue, 2),
                'confidence_level' => round($confidence, 2),
                'forecast_method' => 'linear_regression',
                'trend_factor' => round($slope, 4),
                'seasonal_factor' => 1.0000,
                'market_factor' => 1.0000
            ];
        }
        
        return $forecasts;
    }
    
    /**
     * Exponential smoothing forecasting
     * 
     * @param array $historicalData Historical revenue data
     * @param int $periodsAhead Number of periods to forecast
     * @param float $alpha Smoothing parameter
     * @return array Forecast data
     */
    private function exponentialSmoothing($historicalData, $periodsAhead, $alpha = 0.3)
    {
        $n = count($historicalData);
        if ($n < 2) {
            return [];
        }
        
        $revenues = array_column($historicalData, 'total_revenue');
        
        // Initialize
        $smoothed = [$revenues[0]];
        
        // Calculate smoothed values
        for ($i = 1; $i < $n; $i++) {
            $smoothed[$i] = $alpha * $revenues[$i] + (1 - $alpha) * $smoothed[$i - 1];
        }
        
        // Calculate trend component (Holt's method)
        $trend = [0];
        $beta = 0.1; // Trend smoothing parameter
        
        for ($i = 1; $i < $n; $i++) {
            if ($i == 1) {
                $trend[$i] = $smoothed[$i] - $smoothed[$i - 1];
            } else {
                $trend[$i] = $beta * ($smoothed[$i] - $smoothed[$i - 1]) + (1 - $beta) * $trend[$i - 1];
            }
        }
        
        // Generate forecasts
        $forecasts = [];
        $lastSmoothed = end($smoothed);
        $lastTrend = end($trend);
        $lastPeriod = end($historicalData)['period'];
        
        // Calculate confidence based on forecast accuracy
        $confidence = $this->calculateForecastAccuracy($smoothed, $revenues);
        
        for ($i = 1; $i <= $periodsAhead; $i++) {
            $forecastValue = $lastSmoothed + $i * $lastTrend;
            $forecastValue = max(0, $forecastValue); // Ensure non-negative
            
            $forecastPeriod = $this->getNextPeriod($lastPeriod, $i);
            
            $forecasts[] = [
                'forecast_period' => $forecastPeriod,
                'forecasted_revenue' => round($forecastValue, 2),
                'confidence_level' => round($confidence, 2),
                'forecast_method' => 'exponential_smoothing',
                'trend_factor' => round($lastTrend, 4),
                'seasonal_factor' => 1.0000,
                'market_factor' => 1.0000
            ];
        }
        
        return $forecasts;
    }
    
    /**
     * Seasonal forecasting with trend and seasonality
     * 
     * @param array $historicalData Historical revenue data
     * @param int $periodsAhead Number of periods to forecast
     * @param string $period Period type for seasonality
     * @return array Forecast data
     */
    private function seasonalForecasting($historicalData, $periodsAhead, $period)
    {
        $n = count($historicalData);
        if ($n < 4) {
            return $this->linearRegression($historicalData, $periodsAhead);
        }
        
        $revenues = array_column($historicalData, 'total_revenue');
        
        // Determine seasonal period
        $seasonalPeriod = $this->getSeasonalPeriod($period);
        
        // Calculate trend using linear regression
        $x = range(1, $n);
        $trendCoeff = $this->calculateTrendCoefficient($x, $revenues);
        
        // Detrend the data
        $detrended = [];
        for ($i = 0; $i < $n; $i++) {
            $trendValue = $trendCoeff['intercept'] + $trendCoeff['slope'] * ($i + 1);
            $detrended[$i] = $revenues[$i] - $trendValue;
        }
        
        // Calculate seasonal factors
        $seasonalFactors = $this->calculateSeasonalFactors($detrended, $seasonalPeriod);
        
        // Generate forecasts
        $forecasts = [];
        $lastPeriod = end($historicalData)['period'];
        $confidence = $this->calculateSeasonalConfidence($revenues, $seasonalFactors, $seasonalPeriod);
        
        for ($i = 1; $i <= $periodsAhead; $i++) {
            // Calculate trend component
            $trendValue = $trendCoeff['intercept'] + $trendCoeff['slope'] * ($n + $i);
            
            // Apply seasonal factor
            $seasonalIndex = ($n + $i - 1) % $seasonalPeriod;
            $seasonalFactor = $seasonalFactors[$seasonalIndex] ?? 1.0;
            
            $forecastValue = ($trendValue + $seasonalFactor) * $this->getMarketFactor();
            $forecastValue = max(0, $forecastValue); // Ensure non-negative
            
            $forecastPeriod = $this->getNextPeriod($lastPeriod, $i);
            
            $forecasts[] = [
                'forecast_period' => $forecastPeriod,
                'forecasted_revenue' => round($forecastValue, 2),
                'confidence_level' => round($confidence, 2),
                'forecast_method' => 'seasonal_decomposition',
                'trend_factor' => round($trendCoeff['slope'], 4),
                'seasonal_factor' => round($seasonalFactor, 4),
                'market_factor' => round($this->getMarketFactor(), 4)
            ];
        }
        
        return $forecasts;
    }
    
    /**
     * Identify revenue opportunities and risks
     * 
     * @return array Opportunities and risks analysis
     */
    public function identifyRevenueOpportunities()
    {
        return [
            'underperforming_reps' => $this->getUnderperformingSalesReps(),
            'growth_markets' => $this->getGrowthOpportunities(),
            'seasonal_patterns' => $this->getSeasonalPatterns(),
            'product_mix_optimization' => $this->getProductMixRecommendations(),
            'risk_factors' => $this->identifyRiskFactors(),
            'market_opportunities' => $this->identifyMarketOpportunities()
        ];
    }
    
    /**
     * Get underperforming sales representatives
     * 
     * @param float $threshold Performance threshold percentage (default: 70%)
     * @return array Underperforming reps
     */
    public function getUnderperformingSalesReps($threshold = 70)
    {
        $performance = $this->revenueAnalytics->getSalesRepPerformance('monthly');
        
        $underperforming = [];
        foreach ($performance as $rep) {
            if ($rep['goal_achievement_percent'] < $threshold) {
                $rep['performance_gap'] = $threshold - $rep['goal_achievement_percent'];
                $rep['potential_revenue'] = ($rep['monthly_goal'] * ($threshold / 100)) - $rep['total_revenue'];
                $underperforming[] = $rep;
            }
        }
        
        // Sort by potential revenue impact
        usort($underperforming, function($a, $b) {
            return $b['potential_revenue'] <=> $a['potential_revenue'];
        });
        
        return $underperforming;
    }
    
    /**
     * Identify growth opportunities by market segment
     * 
     * @return array Growth opportunities
     */
    public function getGrowthOpportunities()
    {
        // Get revenue by underwriter trends
        $underwriterTrends = $this->getUnderwriterGrowthTrends();
        
        // Get product type trends
        $productTrends = $this->getProductTypeGrowthTrends();
        
        // Get geographic trends (if data available)
        $geographicTrends = $this->getGeographicGrowthTrends();
        
        return [
            'underwriter_opportunities' => $underwriterTrends,
            'product_opportunities' => $productTrends,
            'geographic_opportunities' => $geographicTrends,
            'recommendations' => $this->generateGrowthRecommendations($underwriterTrends, $productTrends)
        ];
    }
    
    /**
     * Analyze seasonal patterns in revenue
     * 
     * @return array Seasonal analysis
     */
    public function getSeasonalPatterns()
    {
        $monthlyData = $this->getMonthlySeasonalData();
        $quarterlyData = $this->getQuarterlySeasonalData();
        
        return [
            'monthly_patterns' => $monthlyData,
            'quarterly_patterns' => $quarterlyData,
            'peak_months' => $this->identifyPeakPeriods($monthlyData),
            'low_months' => $this->identifyLowPeriods($monthlyData),
            'seasonal_recommendations' => $this->generateSeasonalRecommendations($monthlyData)
        ];
    }
    
    /**
     * Analyze product mix for optimization opportunities
     * 
     * @return array Product mix analysis
     */
    public function getProductMixRecommendations()
    {
        $currentMix = $this->revenueAnalytics->getRevenueByProductType();
        $historicalMix = $this->getHistoricalProductMix();
        $profitabilityAnalysis = $this->analyzeProductProfitability();
        
        return [
            'current_mix' => $currentMix,
            'optimal_mix' => $this->calculateOptimalMix($profitabilityAnalysis),
            'mix_trends' => $historicalMix,
            'profitability' => $profitabilityAnalysis,
            'recommendations' => $this->generateMixRecommendations($currentMix, $profitabilityAnalysis)
        ];
    }
    
    /**
     * Calculate sales rep ROI analysis
     * 
     * @return array ROI analysis
     */
    public function calculateSalesRepROI()
    {
        $sql = "
            SELECT 
                u.id,
                CONCAT(u.first_name, ' ', u.last_name) as sales_rep_name,
                SUM(od.premium) as total_revenue,
                SUM(COALESCE(src.commission_amount, od.premium * 0.05)) as total_commission,
                (SUM(od.premium) - SUM(COALESCE(src.commission_amount, od.premium * 0.05))) as net_contribution,
                ((SUM(od.premium) - SUM(COALESCE(src.commission_amount, od.premium * 0.05))) / 
                 NULLIF(SUM(COALESCE(src.commission_amount, od.premium * 0.05)), 0)) * 100 as roi_percentage,
                COUNT(*) as total_orders,
                AVG(od.premium) as avg_order_value
            FROM users u
            JOIN transaction_details td ON u.id = td.sales_representative
            JOIN order_details od ON td.id = od.transaction_id
            LEFT JOIN sales_rep_commissions src ON u.id = src.sales_rep_id 
                AND DATE(od.sent_to_accounting_date) = DATE(src.calculation_date)
            WHERE u.is_sales_rep = 1
            AND DATE(od.sent_to_accounting_date) >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            AND od.status = 'closed'
            GROUP BY u.id, u.first_name, u.last_name
            HAVING total_revenue > 0
            ORDER BY roi_percentage DESC
        ";
        
        $query = $this->CI->db->query($sql);
        $roiData = $query->result_array();
        
        // Add performance categories
        foreach ($roiData as &$rep) {
            if ($rep['roi_percentage'] >= 400) {
                $rep['performance_category'] = 'Excellent';
            } elseif ($rep['roi_percentage'] >= 300) {
                $rep['performance_category'] = 'Good';
            } elseif ($rep['roi_percentage'] >= 200) {
                $rep['performance_category'] = 'Average';
            } else {
                $rep['performance_category'] = 'Below Average';
            }
        }
        
        return $roiData;
    }
    
    /**
     * Get historical revenue data for analysis
     * 
     * @param string $period Period type
     * @param int $periods Number of periods to retrieve
     * @return array Historical data
     */
    private function getHistoricalRevenueData($period, $periods)
    {
        switch ($period) {
            case 'monthly':
                $sql = "
                    SELECT 
                        DATE_FORMAT(summary_date, '%Y-%m') as period,
                        SUM(total_revenue) as total_revenue,
                        SUM(total_orders) as total_orders,
                        AVG(avg_order_value) as avg_order_value
                    FROM cfo_revenue_daily_summary 
                    WHERE summary_date >= DATE_SUB(CURDATE(), INTERVAL {$periods} MONTH)
                    GROUP BY DATE_FORMAT(summary_date, '%Y-%m')
                    ORDER BY period ASC
                ";
                break;
                
            case 'quarterly':
                $sql = "
                    SELECT 
                        CONCAT(YEAR(summary_date), '-Q', QUARTER(summary_date)) as period,
                        SUM(total_revenue) as total_revenue,
                        SUM(total_orders) as total_orders,
                        AVG(avg_order_value) as avg_order_value
                    FROM cfo_revenue_daily_summary 
                    WHERE summary_date >= DATE_SUB(CURDATE(), INTERVAL " . ($periods * 3) . " MONTH)
                    GROUP BY YEAR(summary_date), QUARTER(summary_date)
                    ORDER BY YEAR(summary_date), QUARTER(summary_date) ASC
                ";
                break;
                
            case 'yearly':
                $sql = "
                    SELECT 
                        YEAR(summary_date) as period,
                        SUM(total_revenue) as total_revenue,
                        SUM(total_orders) as total_orders,
                        AVG(avg_order_value) as avg_order_value
                    FROM cfo_revenue_daily_summary 
                    WHERE summary_date >= DATE_SUB(CURDATE(), INTERVAL {$periods} YEAR)
                    GROUP BY YEAR(summary_date)
                    ORDER BY YEAR(summary_date) ASC
                ";
                break;
                
            default:
                return [];
        }
        
        $query = $this->CI->db->query($sql);
        return $query->result_array();
    }
    
    /**
     * Save forecasts to database
     * 
     * @param array $forecasts Forecast data
     * @param string $period Period type
     * @param string $method Forecasting method
     */
    private function saveForecastsToDatabase($forecasts, $period, $method)
    {
        foreach ($forecasts as $forecast) {
            $forecastData = array_merge($forecast, [
                'forecast_type' => $period,
                'model_version' => '1.0',
                'is_active' => 1,
                'forecast_generated_at' => date('Y-m-d H:i:s')
            ]);
            
            // Check if forecast already exists
            $this->CI->db->where('forecast_period', $forecast['forecast_period']);
            $this->CI->db->where('forecast_type', $period);
            $existing = $this->CI->db->get('cfo_revenue_forecasts');
            
            if ($existing->num_rows() > 0) {
                // Update existing forecast
                $this->CI->db->where('forecast_period', $forecast['forecast_period']);
                $this->CI->db->where('forecast_type', $period);
                $this->CI->db->update('cfo_revenue_forecasts', $forecastData);
            } else {
                // Insert new forecast
                $this->CI->db->insert('cfo_revenue_forecasts', $forecastData);
            }
        }
    }
    
    /**
     * Get next period date
     * 
     * @param string $lastPeriod Last period
     * @param int $increment Period increment
     * @return string Next period
     */
    private function getNextPeriod($lastPeriod, $increment)
    {
        // Handle different period formats
        if (strlen($lastPeriod) == 7) { // Monthly format: YYYY-MM
            return date('Y-m', strtotime($lastPeriod . '-01 +' . $increment . ' month'));
        } elseif (strlen($lastPeriod) == 6) { // Quarterly format: YYYY-Q
            $year = substr($lastPeriod, 0, 4);
            $quarter = substr($lastPeriod, -1);
            $newQuarter = $quarter + $increment;
            $newYear = $year + floor(($newQuarter - 1) / 4);
            $newQuarter = (($newQuarter - 1) % 4) + 1;
            return $newYear . '-Q' . $newQuarter;
        } elseif (strlen($lastPeriod) == 4) { // Yearly format: YYYY
            return (string) ($lastPeriod + $increment);
        } else {
            // Default to monthly
            return date('Y-m', strtotime('+' . $increment . ' month'));
        }
    }
    
    /**
     * Calculate trend coefficient for linear regression
     * 
     * @param array $x X values
     * @param array $y Y values
     * @return array Slope and intercept
     */
    private function calculateTrendCoefficient($x, $y)
    {
        $n = count($x);
        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumXY = 0;
        $sumX2 = 0;
        
        for ($i = 0; $i < $n; $i++) {
            $sumXY += $x[$i] * $y[$i];
            $sumX2 += $x[$i] * $x[$i];
        }
        
        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        $intercept = ($sumY - $slope * $sumX) / $n;
        
        return ['slope' => $slope, 'intercept' => $intercept];
    }
    
    /**
     * Calculate seasonal factors
     * 
     * @param array $detrended Detrended data
     * @param int $seasonalPeriod Seasonal period length
     * @return array Seasonal factors
     */
    private function calculateSeasonalFactors($detrended, $seasonalPeriod)
    {
        $seasonalSums = array_fill(0, $seasonalPeriod, 0);
        $seasonalCounts = array_fill(0, $seasonalPeriod, 0);
        
        foreach ($detrended as $i => $value) {
            $seasonIndex = $i % $seasonalPeriod;
            $seasonalSums[$seasonIndex] += $value;
            $seasonalCounts[$seasonIndex]++;
        }
        
        $seasonalFactors = [];
        for ($i = 0; $i < $seasonalPeriod; $i++) {
            if ($seasonalCounts[$i] > 0) {
                $seasonalFactors[$i] = $seasonalSums[$i] / $seasonalCounts[$i];
            } else {
                $seasonalFactors[$i] = 0;
            }
        }
        
        return $seasonalFactors;
    }
    
    /**
     * Get seasonal period length
     * 
     * @param string $period Period type
     * @return int Seasonal period length
     */
    private function getSeasonalPeriod($period)
    {
        switch ($period) {
            case 'monthly':
                return 12; // 12 months in a year
            case 'quarterly':
                return 4;  // 4 quarters in a year
            case 'weekly':
                return 52; // 52 weeks in a year
            default:
                return 12;
        }
    }
    
    /**
     * Calculate forecast accuracy
     * 
     * @param array $predicted Predicted values
     * @param array $actual Actual values
     * @return float Accuracy percentage
     */
    private function calculateForecastAccuracy($predicted, $actual)
    {
        $n = min(count($predicted), count($actual));
        if ($n == 0) {
            return 0.5;
        }
        
        $mape = 0; // Mean Absolute Percentage Error
        $validPoints = 0;
        
        for ($i = 0; $i < $n; $i++) {
            if ($actual[$i] != 0) {
                $mape += abs(($actual[$i] - $predicted[$i]) / $actual[$i]);
                $validPoints++;
            }
        }
        
        if ($validPoints == 0) {
            return 0.5;
        }
        
        $mape = $mape / $validPoints;
        $accuracy = 1 - $mape;
        
        return max(0.5, min(0.95, $accuracy));
    }
    
    /**
     * Calculate seasonal confidence
     * 
     * @param array $revenues Revenue data
     * @param array $seasonalFactors Seasonal factors
     * @param int $seasonalPeriod Seasonal period length
     * @return float Confidence level
     */
    private function calculateSeasonalConfidence($revenues, $seasonalFactors, $seasonalPeriod)
    {
        // Calculate coefficient of variation for seasonal factors
        $seasonalMean = array_sum($seasonalFactors) / count($seasonalFactors);
        $seasonalVariance = 0;
        
        foreach ($seasonalFactors as $factor) {
            $seasonalVariance += pow($factor - $seasonalMean, 2);
        }
        
        $seasonalVariance = $seasonalVariance / count($seasonalFactors);
        $seasonalStdDev = sqrt($seasonalVariance);
        
        $coefficientOfVariation = $seasonalMean != 0 ? $seasonalStdDev / abs($seasonalMean) : 1;
        
        // Lower variation = higher confidence
        $confidence = max(0.6, min(0.9, 1 - $coefficientOfVariation));
        
        return $confidence;
    }
    
    /**
     * Get market factor (for external market conditions)
     * 
     * @return float Market adjustment factor
     */
    private function getMarketFactor()
    {
        // This could be enhanced to include real market indicators
        // For now, return a neutral factor
        return 1.0;
    }
    
    /**
     * Additional helper methods for opportunity analysis
     */
    
    private function getUnderwriterGrowthTrends()
    {
        $sql = "
            SELECT 
                underwriter,
                DATE_FORMAT(summary_date, '%Y-%m') as month,
                SUM(CASE WHEN underwriter = 'westcor' THEN total_revenue ELSE 0 END) as westcor_revenue,
                SUM(CASE WHEN underwriter = 'fnf' THEN total_revenue ELSE 0 END) as fnf_revenue,
                SUM(CASE WHEN underwriter = 'natic' THEN total_revenue ELSE 0 END) as natic_revenue
            FROM cfo_revenue_daily_summary 
            WHERE summary_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(summary_date, '%Y-%m')
            ORDER BY month ASC
        ";
        
        $query = $this->CI->db->query($sql);
        return $query->result_array();
    }
    
    private function getProductTypeGrowthTrends()
    {
        $sql = "
            SELECT 
                DATE_FORMAT(summary_date, '%Y-%m') as month,
                SUM(sales_revenue) as sales_revenue,
                SUM(refi_revenue) as refi_revenue,
                SUM(sales_orders) as sales_orders,
                SUM(refi_orders) as refi_orders
            FROM cfo_revenue_daily_summary 
            WHERE summary_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(summary_date, '%Y-%m')
            ORDER BY month ASC
        ";
        
        $query = $this->CI->db->query($sql);
        return $query->result_array();
    }
    
    private function getGeographicGrowthTrends()
    {
        // This would need geographic data in the order details
        // For now, return empty array
        return [];
    }
    
    private function generateGrowthRecommendations($underwriterTrends, $productTrends)
    {
        $recommendations = [];
        
        // Analyze trends and generate recommendations
        // This is a simplified version - could be much more sophisticated
        
        if (!empty($productTrends)) {
            $latestData = end($productTrends);
            $salesRatio = $latestData['sales_revenue'] / ($latestData['sales_revenue'] + $latestData['refi_revenue']);
            
            if ($salesRatio < 0.6) {
                $recommendations[] = [
                    'type' => 'product_mix',
                    'priority' => 'high',
                    'title' => 'Increase Sales Focus',
                    'description' => 'Sales transactions currently represent only ' . round($salesRatio * 100, 1) . '% of revenue. Consider increasing sales focus.',
                    'potential_impact' => 'high'
                ];
            }
        }
        
        return $recommendations;
    }
    
    private function getMonthlySeasonalData()
    {
        $sql = "
            SELECT 
                MONTH(summary_date) as month,
                MONTHNAME(summary_date) as month_name,
                AVG(total_revenue) as avg_revenue,
                STDDEV(total_revenue) as revenue_stddev,
                COUNT(*) as data_points
            FROM cfo_revenue_daily_summary 
            WHERE summary_date >= DATE_SUB(CURDATE(), INTERVAL 24 MONTH)
            GROUP BY MONTH(summary_date), MONTHNAME(summary_date)
            ORDER BY month ASC
        ";
        
        $query = $this->CI->db->query($sql);
        return $query->result_array();
    }
    
    private function getQuarterlySeasonalData()
    {
        $sql = "
            SELECT 
                QUARTER(summary_date) as quarter,
                AVG(total_revenue) as avg_revenue,
                STDDEV(total_revenue) as revenue_stddev,
                COUNT(*) as data_points
            FROM cfo_revenue_daily_summary 
            WHERE summary_date >= DATE_SUB(CURDATE(), INTERVAL 24 MONTH)
            GROUP BY QUARTER(summary_date)
            ORDER BY quarter ASC
        ";
        
        $query = $this->CI->db->query($sql);
        return $query->result_array();
    }
    
    private function identifyPeakPeriods($monthlyData)
    {
        if (empty($monthlyData)) {
            return [];
        }
        
        $avgRevenue = array_sum(array_column($monthlyData, 'avg_revenue')) / count($monthlyData);
        
        $peaks = [];
        foreach ($monthlyData as $month) {
            if ($month['avg_revenue'] > $avgRevenue * 1.1) { // 10% above average
                $peaks[] = $month;
            }
        }
        
        return $peaks;
    }
    
    private function identifyLowPeriods($monthlyData)
    {
        if (empty($monthlyData)) {
            return [];
        }
        
        $avgRevenue = array_sum(array_column($monthlyData, 'avg_revenue')) / count($monthlyData);
        
        $lows = [];
        foreach ($monthlyData as $month) {
            if ($month['avg_revenue'] < $avgRevenue * 0.9) { // 10% below average
                $lows[] = $month;
            }
        }
        
        return $lows;
    }
    
    private function generateSeasonalRecommendations($monthlyData)
    {
        $recommendations = [];
        $peaks = $this->identifyPeakPeriods($monthlyData);
        $lows = $this->identifyLowPeriods($monthlyData);
        
        if (!empty($lows)) {
            $recommendations[] = [
                'type' => 'seasonal_planning',
                'priority' => 'medium',
                'title' => 'Low Season Preparation',
                'description' => 'Prepare marketing campaigns for historically low months: ' . 
                              implode(', ', array_column($lows, 'month_name')),
                'potential_impact' => 'medium'
            ];
        }
        
        return $recommendations;
    }
    
    private function getHistoricalProductMix()
    {
        $sql = "
            SELECT 
                DATE_FORMAT(summary_date, '%Y-%m') as month,
                SUM(sales_revenue) / SUM(total_revenue) * 100 as sales_percentage,
                SUM(refi_revenue) / SUM(total_revenue) * 100 as refi_percentage
            FROM cfo_revenue_daily_summary 
            WHERE summary_date >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            AND total_revenue > 0
            GROUP BY DATE_FORMAT(summary_date, '%Y-%m')
            ORDER BY month ASC
        ";
        
        $query = $this->CI->db->query($sql);
        return $query->result_array();
    }
    
    private function analyzeProductProfitability()
    {
        // This would require cost data to be truly accurate
        // For now, use commission rates as a proxy for profitability
        
        $sql = "
            SELECT 
                od.prod_type,
                AVG(od.premium) as avg_revenue,
                AVG(COALESCE(src.commission_amount, od.premium * 0.05)) as avg_commission,
                (AVG(od.premium) - AVG(COALESCE(src.commission_amount, od.premium * 0.05))) as avg_profit,
                ((AVG(od.premium) - AVG(COALESCE(src.commission_amount, od.premium * 0.05))) / AVG(od.premium)) * 100 as profit_margin
            FROM order_details od
            JOIN transaction_details td ON od.transaction_id = td.id
            LEFT JOIN sales_rep_commissions src ON td.sales_representative = src.sales_rep_id 
                AND DATE(od.sent_to_accounting_date) = DATE(src.calculation_date)
            WHERE DATE(od.sent_to_accounting_date) >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            AND od.status = 'closed'
            GROUP BY od.prod_type
        ";
        
        $query = $this->CI->db->query($sql);
        return $query->result_array();
    }
    
    private function calculateOptimalMix($profitabilityAnalysis)
    {
        // Simple optimization: recommend more of the higher-margin product
        if (empty($profitabilityAnalysis)) {
            return ['sales' => 50, 'refi' => 50];
        }
        
        $sales = array_filter($profitabilityAnalysis, function($item) {
            return $item['prod_type'] == 'sale';
        });
        
        $refi = array_filter($profitabilityAnalysis, function($item) {
            return $item['prod_type'] == 'loan';
        });
        
        $salesMargin = !empty($sales) ? reset($sales)['profit_margin'] : 20;
        $refiMargin = !empty($refi) ? reset($refi)['profit_margin'] : 20;
        
        // Weight the mix based on profitability
        $totalMargin = $salesMargin + $refiMargin;
        
        if ($totalMargin > 0) {
            return [
                'sales' => round(($salesMargin / $totalMargin) * 100, 1),
                'refi' => round(($refiMargin / $totalMargin) * 100, 1)
            ];
        }
        
        return ['sales' => 50, 'refi' => 50];
    }
    
    private function generateMixRecommendations($currentMix, $profitabilityAnalysis)
    {
        $recommendations = [];
        $optimalMix = $this->calculateOptimalMix($profitabilityAnalysis);
        
        if (!empty($currentMix)) {
            $currentSalesPercent = ($currentMix[0]['revenue'] / array_sum(array_column($currentMix, 'revenue'))) * 100;
            
            if (abs($currentSalesPercent - $optimalMix['sales']) > 10) {
                $recommendations[] = [
                    'type' => 'product_mix',
                    'priority' => 'medium',
                    'title' => 'Product Mix Optimization',
                    'description' => 'Consider adjusting product mix towards optimal ratio: ' . 
                                  $optimalMix['sales'] . '% sales, ' . $optimalMix['refi'] . '% refinance',
                    'potential_impact' => 'medium'
                ];
            }
        }
        
        return $recommendations;
    }
    
    private function identifyRiskFactors()
    {
        $risks = [];
        
        // Market concentration risk
        $underwriterData = $this->revenueAnalytics->getRevenueByUnderwriter();
        if (!empty($underwriterData)) {
            $totalRevenue = array_sum(array_column($underwriterData, 'revenue'));
            foreach ($underwriterData as $underwriter) {
                $concentration = ($underwriter['revenue'] / $totalRevenue) * 100;
                if ($concentration > 60) {
                    $risks[] = [
                        'type' => 'concentration',
                        'severity' => 'high',
                        'description' => "High concentration risk: {$underwriter['underwriter']} represents {$concentration}% of revenue"
                    ];
                }
            }
        }
        
        // Performance volatility risk
        $trends = $this->revenueAnalytics->getRevenueTrends(30);
        if (count($trends) > 5) {
            $revenues = array_column($trends, 'total_revenue');
            $mean = array_sum($revenues) / count($revenues);
            $variance = array_sum(array_map(function($x) use ($mean) { return pow($x - $mean, 2); }, $revenues)) / count($revenues);
            $stddev = sqrt($variance);
            $coefficientOfVariation = $mean > 0 ? $stddev / $mean : 0;
            
            if ($coefficientOfVariation > 0.3) {
                $risks[] = [
                    'type' => 'volatility',
                    'severity' => 'medium',
                    'description' => 'High revenue volatility detected (CV: ' . round($coefficientOfVariation, 2) . ')'
                ];
            }
        }
        
        return $risks;
    }
    
    private function identifyMarketOpportunities()
    {
        $opportunities = [];
        
        // Growth trend opportunity
        $trends = $this->revenueAnalytics->getRevenueTrends(90);
        if (count($trends) >= 3) {
            $recentTrends = array_slice($trends, -3);
            $growthRates = array_column($recentTrends, 'growth_rate');
            $avgGrowth = array_sum($growthRates) / count($growthRates);
            
            if ($avgGrowth > 5) {
                $opportunities[] = [
                    'type' => 'growth_trend',
                    'potential' => 'high',
                    'description' => 'Strong growth trend detected (' . round($avgGrowth, 1) . '% average growth)'
                ];
            }
        }
        
        return $opportunities;
    }
}
