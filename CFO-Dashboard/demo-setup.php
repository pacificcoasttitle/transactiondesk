<?php
/**
 * CFO Dashboard Demo Setup Script
 * 
 * Quick setup script to create demo environment with dummy data.
 * Run this file to get the dashboard working immediately.
 */

echo "🚀 CFO Dashboard Demo Setup\n";
echo "==========================\n\n";

// Check if we're in the right directory
if (!file_exists('application') || !file_exists('CFO-Dashboard')) {
    echo "❌ Error: Please run this script from your Transaction Desk root directory\n";
    echo "   Make sure CFO-Dashboard folder is present\n\n";
    exit(1);
}

// Step 1: Copy files
echo "📁 Step 1: Copying CFO Dashboard files...\n";
copyDirectory('CFO-Dashboard/application', 'application');
copyDirectory('CFO-Dashboard/assets', 'assets/backend');

// Step 2: Add routes
echo "🔗 Step 2: Adding routes...\n";
addRoutes();

// Step 3: Create database tables
echo "🗄️  Step 3: Setting up database...\n";
setupDatabase();

// Step 4: Seed dummy data
echo "📊 Step 4: Adding dummy data...\n";
seedDummyData();

// Step 5: Setup permissions
echo "🔐 Step 5: Setting up permissions...\n";
setupPermissions();

echo "\n✅ Setup Complete!\n";
echo "================\n\n";
echo "🎯 Access your CFO Dashboard at:\n";
echo "   http://your-domain.com/admin/finance/cfo-dashboard\n";
echo "   or\n";
echo "   http://your-domain.com/cfo\n\n";
echo "📊 Demo Features Available:\n";
echo "   • 90 days of revenue data\n";
echo "   • 8 sales reps with performance data\n";
echo "   • Revenue forecasts\n";
echo "   • Interactive charts\n";
echo "   • Export functionality\n\n";
echo "🔑 Demo Login: Use any admin account\n\n";

/**
 * Copy directory recursively
 */
function copyDirectory($source, $destination) {
    if (!is_dir($source)) {
        echo "   ⚠️  Source directory not found: $source\n";
        return false;
    }
    
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }
    
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    
    foreach ($iterator as $item) {
        $destPath = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
        
        if ($item->isDir()) {
            if (!is_dir($destPath)) {
                mkdir($destPath, 0755, true);
            }
        } else {
            copy($item, $destPath);
        }
    }
    
    echo "   ✓ Copied $source to $destination\n";
    return true;
}

/**
 * Add routes to routes.php
 */
function addRoutes() {
    $routesFile = 'application/config/routes.php';
    
    if (!file_exists($routesFile)) {
        echo "   ❌ Routes file not found: $routesFile\n";
        return false;
    }
    
    $routesContent = file_get_contents($routesFile);
    
    // Check if routes already exist
    if (strpos($routesContent, 'cfo-dashboard') !== false) {
        echo "   ℹ️  CFO Dashboard routes already exist\n";
        return true;
    }
    
    // Add CFO Dashboard routes
    $newRoutes = "\n\n// CFO Dashboard Routes - Added by Demo Setup\n";
    $newRoutes .= "\$route['admin/finance/cfo-dashboard'] = 'admin/finance/cfodashboard/index';\n";
    $newRoutes .= "\$route['admin/finance/cfo-dashboard/(:any)'] = 'admin/finance/cfodashboard/\$1';\n";
    $newRoutes .= "\$route['api/finance/(:any)'] = 'admin/finance/api/\$1';\n";
    $newRoutes .= "\$route['cfo'] = 'admin/finance/cfodashboard/index';\n";
    
    // Insert before the closing PHP tag
    $routesContent = str_replace('?>', $newRoutes . '?>', $routesContent);
    
    if (file_put_contents($routesFile, $routesContent)) {
        echo "   ✓ Added CFO Dashboard routes\n";
        return true;
    } else {
        echo "   ❌ Failed to update routes file\n";
        return false;
    }
}

/**
 * Setup database tables
 */
function setupDatabase() {
    // Load database configuration
    require_once 'application/config/database.php';
    
    try {
        $pdo = new PDO(
            "mysql:host={$db['default']['hostname']};dbname={$db['default']['database']}",
            $db['default']['username'],
            $db['default']['password']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Create CFO Dashboard tables
        createTables($pdo);
        
        echo "   ✓ Database tables created successfully\n";
        return true;
        
    } catch (PDOException $e) {
        echo "   ❌ Database error: " . $e->getMessage() . "\n";
        echo "   💡 Tip: Check your database configuration in application/config/database.php\n";
        return false;
    }
}

/**
 * Create CFO Dashboard tables
 */
function createTables($pdo) {
    $tables = [
        'cfo_revenue_daily_summary' => "
            CREATE TABLE IF NOT EXISTS `cfo_revenue_daily_summary` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `summary_date` date NOT NULL,
                `total_revenue` decimal(12,2) DEFAULT 0.00,
                `sales_revenue` decimal(12,2) DEFAULT 0.00,
                `refi_revenue` decimal(12,2) DEFAULT 0.00,
                `total_orders` int(11) DEFAULT 0,
                `sales_orders` int(11) DEFAULT 0,
                `refi_orders` int(11) DEFAULT 0,
                `avg_order_value` decimal(10,2) DEFAULT 0.00,
                `westcor_revenue` decimal(12,2) DEFAULT 0.00,
                `fnf_revenue` decimal(12,2) DEFAULT 0.00,
                `natic_revenue` decimal(12,2) DEFAULT 0.00,
                `commission_paid` decimal(10,2) DEFAULT 0.00,
                `profit_margin` decimal(5,2) DEFAULT 0.00,
                `previous_day_revenue` decimal(12,2) DEFAULT NULL,
                `mtd_revenue` decimal(12,2) DEFAULT 0.00,
                `ytd_revenue` decimal(12,2) DEFAULT 0.00,
                `budget_variance` decimal(5,2) DEFAULT 0.00,
                `last_updated` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `idx_summary_date_unique` (`summary_date`),
                KEY `idx_date_revenue` (`summary_date`,`total_revenue`),
                KEY `idx_mtd_revenue` (`mtd_revenue`),
                KEY `idx_ytd_revenue` (`ytd_revenue`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Daily revenue summaries for CFO dashboard analytics';
        ",
        
        'cfo_sales_rep_performance' => "
            CREATE TABLE IF NOT EXISTS `cfo_sales_rep_performance` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `sales_rep_id` int(11) NOT NULL,
                `period_type` enum('daily','weekly','monthly','quarterly','yearly') DEFAULT 'monthly',
                `period_start` date NOT NULL,
                `period_end` date NOT NULL,
                `total_revenue` decimal(12,2) DEFAULT 0.00,
                `sales_revenue` decimal(12,2) DEFAULT 0.00,
                `refi_revenue` decimal(12,2) DEFAULT 0.00,
                `total_orders` int(11) DEFAULT 0,
                `sales_orders` int(11) DEFAULT 0,
                `refi_orders` int(11) DEFAULT 0,
                `avg_order_value` decimal(10,2) DEFAULT 0.00,
                `commission_earned` decimal(10,2) DEFAULT 0.00,
                `commission_rate` decimal(5,2) DEFAULT 0.00,
                `revenue_goal` decimal(12,2) DEFAULT NULL,
                `orders_goal` int(11) DEFAULT NULL,
                `goal_achievement_percent` decimal(5,2) DEFAULT 0.00,
                `goals_met` tinyint(1) DEFAULT 0,
                `ranking_position` int(11) DEFAULT NULL,
                `previous_period_revenue` decimal(12,2) DEFAULT NULL,
                `revenue_growth_percent` decimal(5,2) DEFAULT 0.00,
                `conversion_rate` decimal(5,2) DEFAULT 0.00,
                `customer_satisfaction` decimal(3,2) DEFAULT NULL,
                `roi_percentage` decimal(5,2) DEFAULT 0.00,
                `performance_notes` text DEFAULT NULL,
                `alert_triggered` tinyint(1) DEFAULT 0,
                `last_calculated` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_sales_rep_id` (`sales_rep_id`),
                KEY `idx_period_type_start` (`period_type`,`period_start`),
                UNIQUE KEY `idx_rep_period_unique` (`sales_rep_id`,`period_type`,`period_start`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Sales rep performance metrics for CFO dashboard analytics';
        ",
        
        'cfo_revenue_forecasts' => "
            CREATE TABLE IF NOT EXISTS `cfo_revenue_forecasts` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `forecast_period` date NOT NULL,
                `forecast_type` enum('daily','weekly','monthly','quarterly','yearly') DEFAULT 'monthly',
                `forecasted_revenue` decimal(12,2) NOT NULL,
                `forecasted_orders` int(11) DEFAULT NULL,
                `forecasted_avg_value` decimal(10,2) DEFAULT NULL,
                `actual_revenue` decimal(12,2) DEFAULT NULL,
                `actual_orders` int(11) DEFAULT NULL,
                `actual_avg_value` decimal(10,2) DEFAULT NULL,
                `variance_amount` decimal(12,2) DEFAULT NULL,
                `variance_percent` decimal(5,2) DEFAULT NULL,
                `forecast_accuracy` decimal(5,2) DEFAULT NULL,
                `confidence_level` decimal(3,2) DEFAULT 0.80,
                `forecast_method` varchar(50) DEFAULT 'linear_regression',
                `seasonal_factor` decimal(5,4) DEFAULT 1.0000,
                `trend_factor` decimal(5,4) DEFAULT 1.0000,
                `market_factor` decimal(5,4) DEFAULT 1.0000,
                `budget_target` decimal(12,2) DEFAULT NULL,
                `budget_variance` decimal(5,2) DEFAULT NULL,
                `forecast_notes` text DEFAULT NULL,
                `external_factors` json DEFAULT NULL,
                `model_version` varchar(20) DEFAULT '1.0',
                `is_active` tinyint(1) DEFAULT 1,
                `created_by` int(11) DEFAULT NULL,
                `forecast_generated_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `idx_forecast_period` (`forecast_period`),
                KEY `idx_forecast_type` (`forecast_type`),
                UNIQUE KEY `idx_period_type_unique` (`forecast_period`,`forecast_type`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Revenue forecasts and predictive analytics for CFO dashboard';
        ",
        
        'cfo_dashboard_settings' => "
            CREATE TABLE IF NOT EXISTS `cfo_dashboard_settings` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `setting_name` varchar(100) NOT NULL,
                `setting_value` text DEFAULT NULL,
                `setting_type` enum('string','integer','decimal','boolean','json','array') DEFAULT 'string',
                `category` varchar(50) DEFAULT 'general',
                `description` text DEFAULT NULL,
                `default_value` text DEFAULT NULL,
                `validation_rules` text DEFAULT NULL,
                `is_editable` tinyint(1) DEFAULT 1,
                `requires_restart` tinyint(1) DEFAULT 0,
                `created_by` int(11) DEFAULT NULL,
                `updated_by` int(11) DEFAULT NULL,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `idx_setting_name_unique` (`setting_name`),
                KEY `idx_category` (`category`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Configuration settings for CFO dashboard';
        "
    ];
    
    foreach ($tables as $tableName => $sql) {
        $pdo->exec($sql);
        echo "   ✓ Created table: $tableName\n";
    }
}

/**
 * Seed dummy data
 */
function seedDummyData() {
    require_once 'application/config/database.php';
    
    try {
        $pdo = new PDO(
            "mysql:host={$db['default']['hostname']};dbname={$db['default']['database']}",
            $db['default']['username'],
            $db['default']['password']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Clear existing demo data
        $pdo->exec("DELETE FROM cfo_revenue_daily_summary WHERE 1=1");
        $pdo->exec("DELETE FROM cfo_sales_rep_performance WHERE 1=1");
        $pdo->exec("DELETE FROM cfo_revenue_forecasts WHERE 1=1");
        
        // Seed revenue data (last 90 days)
        seedRevenueDailySummary($pdo);
        
        // Seed sales rep performance
        seedSalesRepPerformance($pdo);
        
        // Seed forecasts
        seedRevenueForecasts($pdo);
        
        // Seed settings
        seedDashboardSettings($pdo);
        
        echo "   ✓ Dummy data seeded successfully\n";
        return true;
        
    } catch (PDOException $e) {
        echo "   ❌ Seeding error: " . $e->getMessage() . "\n";
        return false;
    }
}

/**
 * Seed revenue daily summary data
 */
function seedRevenueDailySummary($pdo) {
    $stmt = $pdo->prepare("
        INSERT INTO cfo_revenue_daily_summary (
            summary_date, total_revenue, sales_revenue, refi_revenue, total_orders, 
            sales_orders, refi_orders, avg_order_value, westcor_revenue, fnf_revenue, 
            natic_revenue, commission_paid, profit_margin, mtd_revenue, ytd_revenue, 
            budget_variance, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    
    $ytdRevenue = 0;
    $mtdRevenue = 0;
    $lastMonth = '';
    
    for ($i = 89; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-{$i} days"));
        $currentMonth = date('Y-m', strtotime($date));
        
        // Reset MTD on new month
        if ($lastMonth !== $currentMonth) {
            $mtdRevenue = 0;
            $lastMonth = $currentMonth;
        }
        
        // Generate realistic revenue with seasonal patterns
        $baseRevenue = generateBaseRevenue($date);
        $salesRevenue = $baseRevenue * (0.65 + (rand(0, 15) / 100));
        $refiRevenue = $baseRevenue - $salesRevenue;
        
        $totalOrders = rand(20, 50);
        $salesOrders = round($totalOrders * 0.65);
        $refiOrders = $totalOrders - $salesOrders;
        
        $westcorRevenue = $baseRevenue * (0.45 + (rand(0, 15) / 100));
        $fnfRevenue = $baseRevenue * (0.35 + (rand(0, 10) / 100));
        $naticRevenue = $baseRevenue - $westcorRevenue - $fnfRevenue;
        
        $commissionPaid = $baseRevenue * 0.06;
        $profitMargin = (($baseRevenue - $commissionPaid) / $baseRevenue) * 100;
        
        $mtdRevenue += $baseRevenue;
        $ytdRevenue += $baseRevenue;
        
        $budgetVariance = rand(-15, 25) / 10;
        
        $stmt->execute([
            $date, $baseRevenue, $salesRevenue, $refiRevenue, $totalOrders,
            $salesOrders, $refiOrders, $baseRevenue / $totalOrders,
            $westcorRevenue, $fnfRevenue, $naticRevenue, $commissionPaid,
            $profitMargin, $mtdRevenue, $ytdRevenue, $budgetVariance
        ]);
    }
    
    echo "   ✓ Added 90 days of revenue data\n";
}

/**
 * Seed sales rep performance data
 */
function seedSalesRepPerformance($pdo) {
    $salesReps = [
        1 => 'John Smith', 2 => 'Sarah Johnson', 3 => 'Michael Brown', 4 => 'Emily Davis',
        5 => 'David Wilson', 6 => 'Lisa Garcia', 7 => 'Robert Martinez', 8 => 'Jennifer Anderson'
    ];
    
    $stmt = $pdo->prepare("
        INSERT INTO cfo_sales_rep_performance (
            sales_rep_id, period_type, period_start, period_end, total_revenue,
            sales_revenue, refi_revenue, total_orders, sales_orders, refi_orders,
            avg_order_value, commission_earned, commission_rate, revenue_goal,
            orders_goal, goal_achievement_percent, goals_met, ranking_position,
            roi_percentage, performance_notes, created_at, updated_at
        ) VALUES (?, 'monthly', ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");
    
    $monthStart = date('Y-m-01');
    $monthEnd = date('Y-m-t');
    
    foreach ($salesReps as $repId => $repName) {
        $baseRevenue = 70000 - ($repId * 8000) + rand(0, 20000); // Varied performance
        $salesRevenue = $baseRevenue * 0.7;
        $refiRevenue = $baseRevenue * 0.3;
        
        $totalOrders = rand(25, 45);
        $salesOrders = round($totalOrders * 0.7);
        $refiOrders = $totalOrders - $salesOrders;
        
        $commissionEarned = $baseRevenue * 0.06;
        $commissionRate = 6.0;
        $revenueGoal = 60000;
        $ordersGoal = 35;
        
        $goalAchievement = ($baseRevenue / $revenueGoal) * 100;
        $goalsMet = $goalAchievement >= 100 ? 1 : 0;
        $roi = (($baseRevenue - $commissionEarned) / $commissionEarned) * 100;
        
        $notes = $goalAchievement >= 100 ? 'Exceeding monthly targets' : 'Focus on lead conversion';
        
        $stmt->execute([
            $repId, $monthStart, $monthEnd, $baseRevenue, $salesRevenue, $refiRevenue,
            $totalOrders, $salesOrders, $refiOrders, $baseRevenue / $totalOrders,
            $commissionEarned, $commissionRate, $revenueGoal, $ordersGoal,
            $goalAchievement, $goalsMet, $repId, $roi, $notes
        ]);
    }
    
    echo "   ✓ Added sales rep performance data\n";
}

/**
 * Seed revenue forecasts
 */
function seedRevenueForecasts($pdo) {
    $stmt = $pdo->prepare("
        INSERT INTO cfo_revenue_forecasts (
            forecast_period, forecast_type, forecasted_revenue, forecasted_orders,
            confidence_level, forecast_method, budget_target, is_active,
            created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 1, NOW(), NOW())
    ");
    
    // Generate 6 months of forecasts
    for ($i = 1; $i <= 6; $i++) {
        $forecastDate = date('Y-m-01', strtotime("+{$i} month"));
        $baseRevenue = 850000 + rand(-100000, 200000);
        $orders = rand(450, 650);
        $confidence = 0.80 + (rand(0, 15) / 100);
        
        $stmt->execute([
            $forecastDate, 'monthly', $baseRevenue, $orders,
            $confidence, 'linear_regression', 800000
        ]);
    }
    
    echo "   ✓ Added revenue forecasts\n";
}

/**
 * Seed dashboard settings
 */
function seedDashboardSettings($pdo) {
    $settings = [
        ['dashboard_refresh_interval', '300', 'integer', 'performance', 'Dashboard auto-refresh interval in seconds'],
        ['revenue_currency', 'USD', 'string', 'display', 'Currency for revenue display'],
        ['monthly_revenue_budget', '800000.00', 'decimal', 'budget', 'Monthly revenue budget target'],
        ['enable_predictive_analytics', 'true', 'boolean', 'features', 'Enable predictive analytics and forecasting']
    ];
    
    $stmt = $pdo->prepare("
        INSERT INTO cfo_dashboard_settings (setting_name, setting_value, setting_type, category, description, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, NOW(), NOW())
    ");
    
    foreach ($settings as $setting) {
        $stmt->execute($setting);
    }
    
    echo "   ✓ Added dashboard settings\n";
}

/**
 * Setup permissions
 */
function setupPermissions() {
    // Create uploads directory if it doesn't exist
    $uploadsDir = 'uploads/reports/finance';
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0777, true);
        echo "   ✓ Created reports directory\n";
    }
    
    // Set permissions
    chmod('uploads', 0777);
    if (is_dir($uploadsDir)) {
        chmod($uploadsDir, 0777);
    }
    
    echo "   ✓ Permissions set\n";
}

/**
 * Generate realistic base revenue with patterns
 */
function generateBaseRevenue($date) {
    $baseAmount = 28000; // Base daily revenue
    $month = (int) date('n', strtotime($date));
    $dayOfWeek = date('N', strtotime($date));
    
    // Seasonal multiplier
    $seasonalFactors = [
        1 => 0.85, 2 => 0.90, 3 => 1.05, 4 => 1.15, 5 => 1.20, 6 => 1.15,
        7 => 1.10, 8 => 1.05, 9 => 1.00, 10 => 0.95, 11 => 0.85, 12 => 0.80
    ];
    
    // Weekday multiplier
    $weekdayMultiplier = $dayOfWeek >= 6 ? 0.4 : 1.0;
    
    // Random variation
    $randomVariation = 0.8 + (rand(0, 40) / 100);
    
    return $baseAmount * $seasonalFactors[$month] * $weekdayMultiplier * $randomVariation;
}

?>
