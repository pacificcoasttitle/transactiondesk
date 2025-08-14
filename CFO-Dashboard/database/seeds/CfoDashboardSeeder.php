<?php

use Phinx\Seed\AbstractSeed;

/**
 * CFO Dashboard Seeder
 * 
 * Seeds the CFO Dashboard tables with realistic dummy data for testing.
 * This allows immediate viewing of the dashboard with sample data.
 */
class CfoDashboardSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     */
    public function run()
    {
        // Clear existing data
        $this->execute('TRUNCATE TABLE cfo_revenue_daily_summary');
        $this->execute('TRUNCATE TABLE cfo_sales_rep_performance');
        $this->execute('TRUNCATE TABLE cfo_revenue_forecasts');
        
        // Seed revenue daily summary data
        $this->seedRevenueDailySummary();
        
        // Seed sales rep performance data
        $this->seedSalesRepPerformance();
        
        // Seed forecast data
        $this->seedRevenueForecasts();
        
        echo "CFO Dashboard seeded with dummy data successfully!\n";
    }
    
    /**
     * Seed revenue daily summary with 90 days of data
     */
    private function seedRevenueDailySummary()
    {
        $data = [];
        
        for ($i = 89; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            
            // Generate realistic revenue data with seasonal patterns
            $baseRevenue = $this->generateBaseRevenue($date);
            $salesRevenue = $baseRevenue * (0.6 + (rand(0, 20) / 100)); // 60-80% sales
            $refiRevenue = $baseRevenue - $salesRevenue;
            
            $totalOrders = rand(15, 45);
            $salesOrders = round($totalOrders * 0.65);
            $refiOrders = $totalOrders - $salesOrders;
            
            // Underwriter distribution
            $westcorRevenue = $baseRevenue * (0.4 + (rand(0, 20) / 100));
            $fnfRevenue = $baseRevenue * (0.3 + (rand(0, 15) / 100));
            $naticRevenue = $baseRevenue - $westcorRevenue - $fnfRevenue;
            
            $commissionPaid = $baseRevenue * 0.06; // 6% average commission
            $profitMargin = (($baseRevenue - $commissionPaid) / $baseRevenue) * 100;
            
            // Calculate MTD and YTD
            $mtdRevenue = $this->calculateMtdRevenue($date, $data);
            $ytdRevenue = $this->calculateYtdRevenue($date, $data);
            
            $data[] = [
                'summary_date' => $date,
                'total_revenue' => round($baseRevenue, 2),
                'sales_revenue' => round($salesRevenue, 2),
                'refi_revenue' => round($refiRevenue, 2),
                'total_orders' => $totalOrders,
                'sales_orders' => $salesOrders,
                'refi_orders' => $refiOrders,
                'avg_order_value' => round($baseRevenue / $totalOrders, 2),
                'westcor_revenue' => round($westcorRevenue, 2),
                'fnf_revenue' => round($fnfRevenue, 2),
                'natic_revenue' => round($naticRevenue, 2),
                'commission_paid' => round($commissionPaid, 2),
                'profit_margin' => round($profitMargin, 2),
                'previous_day_revenue' => $i < 89 ? round($this->generateBaseRevenue(date('Y-m-d', strtotime("-" . ($i + 1) . " days"))), 2) : null,
                'mtd_revenue' => round($mtdRevenue, 2),
                'ytd_revenue' => round($ytdRevenue, 2),
                'budget_variance' => round((rand(-15, 25) / 10), 2), // -1.5% to +2.5%
                'last_updated' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        
        $this->table('cfo_revenue_daily_summary')->insert($data)->save();
    }
    
    /**
     * Seed sales rep performance data
     */
    private function seedSalesRepPerformance()
    {
        $salesReps = [
            ['id' => 1, 'name' => 'John Smith'],
            ['id' => 2, 'name' => 'Sarah Johnson'],
            ['id' => 3, 'name' => 'Michael Brown'],
            ['id' => 4, 'name' => 'Emily Davis'],
            ['id' => 5, 'name' => 'David Wilson'],
            ['id' => 6, 'name' => 'Lisa Garcia'],
            ['id' => 7, 'name' => 'Robert Martinez'],
            ['id' => 8, 'name' => 'Jennifer Anderson'],
        ];
        
        $data = [];
        $currentMonth = date('Y-m-01');
        $monthEnd = date('Y-m-t');
        
        foreach ($salesReps as $index => $rep) {
            // Generate varied performance levels
            $baseRevenue = 45000 + (rand(0, 60000)); // $45K - $105K range
            $performanceMultiplier = 1 - ($index * 0.12); // Declining performance by rank
            $totalRevenue = $baseRevenue * $performanceMultiplier;
            
            $salesRevenue = $totalRevenue * (0.7 + (rand(0, 20) / 100));
            $refiRevenue = $totalRevenue - $salesRevenue;
            
            $totalOrders = rand(20, 50);
            $salesOrders = round($totalOrders * 0.7);
            $refiOrders = $totalOrders - $salesOrders;
            
            $commissionEarned = $totalRevenue * (0.05 + (rand(0, 3) / 100)); // 5-8%
            $revenueGoal = 60000; // Standard monthly goal
            $goalAchievement = ($totalRevenue / $revenueGoal) * 100;
            
            $data[] = [
                'sales_rep_id' => $rep['id'],
                'period_type' => 'monthly',
                'period_start' => $currentMonth,
                'period_end' => $monthEnd,
                'total_revenue' => round($totalRevenue, 2),
                'sales_revenue' => round($salesRevenue, 2),
                'refi_revenue' => round($refiRevenue, 2),
                'total_orders' => $totalOrders,
                'sales_orders' => $salesOrders,
                'refi_orders' => $refiOrders,
                'avg_order_value' => round($totalRevenue / $totalOrders, 2),
                'commission_earned' => round($commissionEarned, 2),
                'commission_rate' => round(($commissionEarned / $totalRevenue) * 100, 2),
                'revenue_goal' => $revenueGoal,
                'orders_goal' => 40,
                'goal_achievement_percent' => round($goalAchievement, 1),
                'goals_met' => $goalAchievement >= 100 ? 1 : 0,
                'ranking_position' => $index + 1,
                'previous_period_revenue' => round($totalRevenue * (0.9 + (rand(0, 20) / 100)), 2),
                'revenue_growth_percent' => round((rand(-20, 30) / 10), 1),
                'conversion_rate' => round(75 + (rand(0, 20)), 1),
                'customer_satisfaction' => round(4.0 + (rand(0, 10) / 10), 1),
                'roi_percentage' => round((($totalRevenue - $commissionEarned) / $commissionEarned) * 100, 1),
                'performance_notes' => $goalAchievement >= 100 ? 'Exceeding targets' : 'Focus on lead conversion',
                'alert_triggered' => $goalAchievement < 70 ? 1 : 0,
                'last_calculated' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        
        $this->table('cfo_sales_rep_performance')->insert($data)->save();
    }
    
    /**
     * Seed revenue forecasts
     */
    private function seedRevenueForecasts()
    {
        $data = [];
        
        // Generate monthly forecasts for next 6 months
        for ($i = 1; $i <= 6; $i++) {
            $forecastDate = date('Y-m-01', strtotime("+{$i} month"));
            $baseRevenue = 850000 + (rand(-100000, 150000)); // $750K - $1M range
            
            $data[] = [
                'forecast_period' => $forecastDate,
                'forecast_type' => 'monthly',
                'forecasted_revenue' => round($baseRevenue, 2),
                'forecasted_orders' => rand(450, 650),
                'forecasted_avg_value' => round($baseRevenue / 550, 2),
                'actual_revenue' => $i == 1 ? round($baseRevenue * (0.9 + (rand(0, 20) / 100)), 2) : null,
                'actual_orders' => $i == 1 ? rand(480, 580) : null,
                'actual_avg_value' => $i == 1 ? round(($baseRevenue * 0.95) / 530, 2) : null,
                'variance_amount' => $i == 1 ? round(rand(-50000, 75000), 2) : null,
                'variance_percent' => $i == 1 ? round((rand(-8, 12) / 10), 1) : null,
                'forecast_accuracy' => $i == 1 ? round(85 + (rand(0, 10)), 1) : null,
                'confidence_level' => round(0.80 + (rand(0, 15) / 100), 2),
                'forecast_method' => 'linear_regression',
                'seasonal_factor' => round(0.95 + (rand(0, 10) / 100), 4),
                'trend_factor' => round(1.00 + (rand(-5, 8) / 100), 4),
                'market_factor' => round(0.98 + (rand(0, 6) / 100), 4),
                'budget_target' => 800000,
                'budget_variance' => round((($baseRevenue - 800000) / 800000) * 100, 1),
                'forecast_notes' => 'Generated from historical trends and seasonal patterns',
                'external_factors' => json_encode([
                    'interest_rates' => 'stable',
                    'market_conditions' => 'favorable',
                    'competition' => 'moderate'
                ]),
                'model_version' => '1.0',
                'is_active' => 1,
                'created_by' => 1,
                'forecast_generated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        
        // Generate quarterly forecasts
        for ($i = 1; $i <= 4; $i++) {
            $quarter = ceil((date('n') + $i - 1) / 3);
            $year = date('Y') + floor((date('n') + $i - 1) / 12);
            $forecastDate = date('Y-m-d', mktime(0, 0, 0, ($quarter - 1) * 3 + 1, 1, $year));
            
            $baseRevenue = 2500000 + (rand(-300000, 500000)); // $2.2M - $3M range
            
            $data[] = [
                'forecast_period' => $forecastDate,
                'forecast_type' => 'quarterly',
                'forecasted_revenue' => round($baseRevenue, 2),
                'forecasted_orders' => rand(1300, 1800),
                'forecasted_avg_value' => round($baseRevenue / 1550, 2),
                'actual_revenue' => null,
                'actual_orders' => null,
                'actual_avg_value' => null,
                'variance_amount' => null,
                'variance_percent' => null,
                'forecast_accuracy' => null,
                'confidence_level' => round(0.75 + (rand(0, 15) / 100), 2),
                'forecast_method' => 'seasonal_decomposition',
                'seasonal_factor' => round(0.90 + (rand(0, 20) / 100), 4),
                'trend_factor' => round(1.02 + (rand(-3, 5) / 100), 4),
                'market_factor' => round(0.95 + (rand(0, 10) / 100), 4),
                'budget_target' => 2400000,
                'budget_variance' => round((($baseRevenue - 2400000) / 2400000) * 100, 1),
                'forecast_notes' => 'Quarterly forecast with seasonal adjustments',
                'external_factors' => json_encode([
                    'market_growth' => 'positive',
                    'economic_indicators' => 'stable',
                    'regulatory_changes' => 'minimal'
                ]),
                'model_version' => '1.0',
                'is_active' => 1,
                'created_by' => 1,
                'forecast_generated_at' => date('Y-m-d H:i:s'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        
        $this->table('cfo_revenue_forecasts')->insert($data)->save();
    }
    
    /**
     * Generate realistic base revenue with seasonal patterns
     */
    private function generateBaseRevenue($date)
    {
        $baseAmount = 25000; // Base daily revenue
        $seasonalMultiplier = $this->getSeasonalMultiplier($date);
        $randomVariation = 0.8 + (rand(0, 40) / 100); // 80% - 120% variation
        $weekdayMultiplier = $this->getWeekdayMultiplier($date);
        
        return $baseAmount * $seasonalMultiplier * $randomVariation * $weekdayMultiplier;
    }
    
    /**
     * Get seasonal multiplier based on month
     */
    private function getSeasonalMultiplier($date)
    {
        $month = (int) date('n', strtotime($date));
        
        // Real estate seasonal patterns
        $seasonalFactors = [
            1 => 0.85,  // January - slower
            2 => 0.90,  // February
            3 => 1.05,  // March - spring pickup
            4 => 1.15,  // April - peak spring
            5 => 1.20,  // May - peak
            6 => 1.15,  // June - strong
            7 => 1.10,  // July
            8 => 1.05,  // August
            9 => 1.00,  // September
            10 => 0.95, // October
            11 => 0.85, // November - slower
            12 => 0.80  // December - slowest
        ];
        
        return $seasonalFactors[$month];
    }
    
    /**
     * Get weekday multiplier (lower on weekends)
     */
    private function getWeekdayMultiplier($date)
    {
        $dayOfWeek = date('N', strtotime($date)); // 1 = Monday, 7 = Sunday
        
        if ($dayOfWeek >= 6) { // Weekend
            return 0.3; // Much lower weekend activity
        } elseif ($dayOfWeek == 1) { // Monday
            return 0.9;
        } elseif ($dayOfWeek == 5) { // Friday
            return 0.95;
        } else { // Tuesday - Thursday
            return 1.0;
        }
    }
    
    /**
     * Calculate MTD revenue up to given date
     */
    private function calculateMtdRevenue($date, $existingData)
    {
        $monthStart = date('Y-m-01', strtotime($date));
        $mtdRevenue = 0;
        
        foreach ($existingData as $dayData) {
            if ($dayData['summary_date'] >= $monthStart && $dayData['summary_date'] <= $date) {
                $mtdRevenue += $dayData['total_revenue'];
            }
        }
        
        return $mtdRevenue;
    }
    
    /**
     * Calculate YTD revenue up to given date
     */
    private function calculateYtdRevenue($date, $existingData)
    {
        $yearStart = date('Y-01-01', strtotime($date));
        $ytdRevenue = 0;
        
        foreach ($existingData as $dayData) {
            if ($dayData['summary_date'] >= $yearStart && $dayData['summary_date'] <= $date) {
                $ytdRevenue += $dayData['total_revenue'];
            }
        }
        
        return $ytdRevenue;
    }
}
