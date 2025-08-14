<!-- CFO Revenue Dashboard -->
<div class="container-fluid cfo-dashboard">
    
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-line text-primary"></i>
            CFO Revenue Dashboard
        </h1>
        <div class="btn-group">
            <button type="button" class="btn btn-primary btn-sm" onclick="refreshDashboard()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <div class="btn-group" role="group">
                <button id="exportDropdown" type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-download"></i> Export
                </button>
                <div class="dropdown-menu" aria-labelledby="exportDropdown">
                    <a class="dropdown-item" href="<?php echo base_url('admin/finance/cfo-dashboard/export/pdf'); ?>">
                        <i class="fas fa-file-pdf"></i> Export to PDF
                    </a>
                    <a class="dropdown-item" href="<?php echo base_url('admin/finance/cfo-dashboard/export/excel'); ?>">
                        <i class="fas fa-file-excel"></i> Export to Excel
                    </a>
                    <a class="dropdown-item" href="<?php echo base_url('admin/finance/cfo-dashboard/export/csv'); ?>">
                        <i class="fas fa-file-csv"></i> Export to CSV
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Banner -->
    <div id="alert-banner" class="alert alert-warning alert-dismissible fade show d-none" role="alert">
        <strong>Alert:</strong> <span id="alert-message"></span>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <!-- KPI Cards Row -->
    <div class="row mb-4">
        <!-- MTD Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                MTD Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="mtd-revenue">
                                $<?php echo number_format($revenue_summary['mtd_revenue'] ?? 0, 2); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="small mt-1">
                        <span class="text-<?php echo ($revenue_summary['mtd_vs_previous'] ?? 0) >= 0 ? 'success' : 'danger'; ?>">
                            <i class="fas fa-arrow-<?php echo ($revenue_summary['mtd_vs_previous'] ?? 0) >= 0 ? 'up' : 'down'; ?>"></i>
                            <?php echo number_format(abs($revenue_summary['mtd_vs_previous'] ?? 0), 1); ?>%
                        </span>
                        vs Last Month
                    </div>
                </div>
            </div>
        </div>

        <!-- YTD Revenue Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                YTD Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="ytd-revenue">
                                $<?php echo number_format($revenue_summary['ytd_revenue'] ?? 0, 2); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="small mt-1 text-muted">
                        Year-to-Date Performance
                    </div>
                </div>
            </div>
        </div>

        <!-- Projected Monthly Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Projected Monthly
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="projected-monthly">
                                $<?php echo number_format($revenue_summary['projected_monthly'] ?? 0, 2); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="small mt-1 text-muted">
                        AI-Powered Forecast
                    </div>
                </div>
            </div>
        </div>

        <!-- Budget Variance Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Budget Variance
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="budget-variance">
                                <?php echo ($revenue_summary['budget_variance'] ?? 0) >= 0 ? '+' : ''; ?>
                                <?php echo number_format($revenue_summary['budget_variance'] ?? 0, 1); ?>%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-target fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="small mt-1 text-muted">
                        vs Budget Target
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Revenue Trends Chart -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Trends</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="revenueDropdown" 
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="revenueDropdown">
                            <div class="dropdown-header">View Period:</div>
                            <a class="dropdown-item" href="#" onclick="updateRevenueChart('daily', 30)">Last 30 Days</a>
                            <a class="dropdown-item" href="#" onclick="updateRevenueChart('weekly', 84)">Last 12 Weeks</a>
                            <a class="dropdown-item" href="#" onclick="updateRevenueChart('monthly', 365)">Last 12 Months</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="revenueTrendsChart" style="height: 320px;"></canvas>
                    </div>
                    <div class="mt-3 text-center text-muted">
                        <small>Click on legend items to toggle data series</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Performance Doughnut Chart -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue by Product Type</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="productMixChart" style="height: 245px;"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <?php if (!empty($revenue_by_product)): ?>
                            <?php foreach ($revenue_by_product as $product): ?>
                                <span class="mr-2">
                                    <i class="fas fa-circle text-<?php echo $product['prod_type'] == 'sale' ? 'primary' : 'success'; ?>"></i>
                                    <?php echo ucfirst($product['prod_type']); ?>: $<?php echo number_format($product['revenue'], 0); ?>
                                </span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables Row -->
    <div class="row mb-4">
        <!-- Top Performers Table -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Top Performing Sales Reps</h6>
                    <a href="<?php echo base_url('admin/finance/cfo-dashboard/sales-performance'); ?>" class="btn btn-sm btn-primary">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-body">
                    <?php if (!empty($top_performers)): ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Sales Rep</th>
                                        <th>Revenue</th>
                                        <th>Goal %</th>
                                        <th>ROI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($top_performers, 0, 5) as $index => $rep): ?>
                                        <tr>
                                            <td>
                                                <span class="badge badge-<?php echo $index < 3 ? 'success' : 'secondary'; ?>">
                                                    #<?php echo $index + 1; ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="font-weight-bold">
                                                    <?php echo htmlspecialchars($rep['sales_rep_name']); ?>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>$<?php echo number_format($rep['total_revenue'], 0); ?></strong>
                                            </td>
                                            <td>
                                                <span class="text-<?php echo $rep['goal_achievement_percent'] >= 100 ? 'success' : 'warning'; ?>">
                                                    <?php echo number_format($rep['goal_achievement_percent'], 1); ?>%
                                                </span>
                                            </td>
                                            <td>
                                                <span class="text-success">
                                                    <?php echo number_format($rep['roi_percentage'], 0); ?>%
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center">No performance data available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Revenue by Underwriter -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue by Underwriter</h6>
                </div>
                <div class="card-body">
                    <?php if (!empty($revenue_by_underwriter)): ?>
                        <?php foreach ($revenue_by_underwriter as $underwriter): ?>
                            <?php 
                                $total_revenue = array_sum(array_column($revenue_by_underwriter, 'revenue'));
                                $percentage = $total_revenue > 0 ? ($underwriter['revenue'] / $total_revenue) * 100 : 0;
                            ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="font-weight-bold">
                                        <?php echo strtoupper($underwriter['underwriter']); ?>
                                    </span>
                                    <span>
                                        $<?php echo number_format($underwriter['revenue'], 0); ?>
                                        <small class="text-muted">(<?php echo number_format($percentage, 1); ?>%)</small>
                                    </span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-<?php echo $this->getUnderwriterColor($underwriter['underwriter']); ?>" 
                                         role="progressbar" 
                                         style="width: <?php echo $percentage; ?>%" 
                                         aria-valuenow="<?php echo $percentage; ?>" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>
                                <small class="text-muted">
                                    <?php echo $underwriter['orders']; ?> orders
                                    | Avg: $<?php echo number_format($underwriter['avg_value'], 0); ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center">No underwriter data available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Row -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <a href="<?php echo base_url('admin/finance/cfo-dashboard/revenue-analytics'); ?>" 
                               class="btn btn-outline-primary btn-block">
                                <i class="fas fa-chart-area"></i>
                                Revenue Analytics
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?php echo base_url('admin/finance/cfo-dashboard/sales-performance'); ?>" 
                               class="btn btn-outline-success btn-block">
                                <i class="fas fa-users"></i>
                                Sales Performance
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?php echo base_url('admin/finance/cfo-dashboard/forecasting'); ?>" 
                               class="btn btn-outline-info btn-block">
                                <i class="fas fa-crystal-ball"></i>
                                Forecasting
                            </a>
                        </div>
                        <div class="col-md-3 mb-2">
                            <a href="<?php echo base_url('admin/finance/cfo-dashboard/settings'); ?>" 
                               class="btn btn-outline-warning btn-block">
                                <i class="fas fa-cog"></i>
                                Settings
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Row (if needed) -->
    <?php if (!empty($alerts)): ?>
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Alerts & Notifications</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <?php foreach ($alerts as $alert): ?>
                            <div class="alert alert-<?php echo $alert['priority'] == 'high' ? 'warning' : 'info'; ?> alert-dismissible fade show">
                                <strong><?php echo htmlspecialchars($alert['title']); ?></strong>
                                <p class="mb-0"><?php echo htmlspecialchars($alert['message']); ?></p>
                                <small class="text-muted">
                                    <i class="fas fa-clock"></i>
                                    <?php echo date('M j, Y g:i A', strtotime($alert['created_at'])); ?>
                                </small>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- Loading Overlay -->
<div id="loading-overlay" class="loading-overlay d-none">
    <div class="loading-spinner">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
        <p class="mt-2">Updating dashboard data...</p>
    </div>
</div>

<!-- JavaScript Data for Charts -->
<script type="text/javascript">
    // Dashboard configuration
    window.cfoDashboardConfig = {
        refreshInterval: <?php echo env('CFO_DASHBOARD_UPDATE_INTERVAL', 300000); ?>, // 5 minutes
        currency: '<?php echo env('REVENUE_CURRENCY', 'USD'); ?>',
        timezone: '<?php echo env('TIMEZONE', 'America/Los_Angeles'); ?>',
        
        // Chart data
        revenueTrends: <?php echo json_encode($revenue_trends ?? []); ?>,
        productMix: <?php echo json_encode($revenue_by_product ?? []); ?>,
        salesPerformance: <?php echo json_encode($top_performers ?? []); ?>,
        underwriterData: <?php echo json_encode($revenue_by_underwriter ?? []); ?>,
        
        // API endpoints
        endpoints: {
            realtimeData: '<?php echo base_url('admin/finance/cfo-dashboard/get-realtime-data'); ?>',
            revenueTrends: '<?php echo base_url('admin/finance/cfo-dashboard/get-revenue-trends'); ?>',
            salesPerformance: '<?php echo base_url('admin/finance/cfo-dashboard/get-sales-rep-performance'); ?>',
            generateForecast: '<?php echo base_url('admin/finance/cfo-dashboard/generate-forecast'); ?>'
        }
    };
    
    // Last update timestamp
    window.lastUpdateTime = '<?php echo date('Y-m-d H:i:s'); ?>';
</script>

<?php
// Helper function for underwriter colors (would be defined in controller)
if (!function_exists('getUnderwriterColor')) {
    function getUnderwriterColor($underwriter) {
        $colors = [
            'westcor' => 'primary',
            'fnf' => 'success',
            'natic' => 'warning',
            'default' => 'secondary'
        ];
        return $colors[$underwriter] ?? $colors['default'];
    }
}
?>
