<?php
/**
 * CFO Dashboard Routes Configuration
 * 
 * URL routing configuration for CFO Dashboard component.
 * Add these routes to your main application/config/routes.php file.
 * 
 * @package    CFO Dashboard
 * @subpackage Configuration
 * @version    1.0.0
 */

// CFO Dashboard Main Routes
$route['admin/finance/cfo-dashboard'] = 'admin/finance/cfodashboard/index';
$route['admin/finance/cfo-dashboard/(:any)'] = 'admin/finance/cfodashboard/$1';

// CFO Dashboard Sub-pages
$route['admin/finance/cfo-dashboard/revenue-analytics'] = 'admin/finance/cfodashboard/revenue_analytics';
$route['admin/finance/cfo-dashboard/sales-performance'] = 'admin/finance/cfodashboard/sales_performance';
$route['admin/finance/cfo-dashboard/forecasting'] = 'admin/finance/cfodashboard/forecasting';
$route['admin/finance/cfo-dashboard/settings'] = 'admin/finance/cfodashboard/settings';

// Export Routes
$route['admin/finance/cfo-dashboard/export/(:any)'] = 'admin/finance/cfodashboard/export/$1';
$route['admin/finance/cfo-dashboard/export/(:any)/(:any)'] = 'admin/finance/cfodashboard/export/$1/$2';

// Real-time Data API Routes
$route['admin/finance/cfo-dashboard/get-realtime-data'] = 'admin/finance/cfodashboard/get_realtime_data';
$route['admin/finance/cfo-dashboard/get-revenue-trends/(:any)/(:num)'] = 'admin/finance/cfodashboard/get_revenue_trends/$1/$2';
$route['admin/finance/cfo-dashboard/get-sales-rep-performance/(:any)'] = 'admin/finance/cfodashboard/get_sales_rep_performance/$1';
$route['admin/finance/cfo-dashboard/generate-forecast/(:any)/(:num)/(:any)'] = 'admin/finance/cfodashboard/generate_forecast/$1/$2/$3';

// Maintenance/Cron Routes
$route['admin/finance/cfo-dashboard/update-revenue-summaries'] = 'admin/finance/cfodashboard/update_revenue_summaries';
$route['admin/finance/cfo-dashboard/update-sales-performance'] = 'admin/finance/cfodashboard/update_sales_performance';
$route['admin/finance/cfo-dashboard/generate-daily-forecasts'] = 'admin/finance/cfodashboard/generate_daily_forecasts';
$route['admin/finance/cfo-dashboard/check-revenue-alerts'] = 'admin/finance/cfodashboard/check_revenue_alerts';
$route['admin/finance/cfo-dashboard/cleanup-cache'] = 'admin/finance/cfodashboard/cleanup_cache';

// Setup/Testing Routes
$route['admin/finance/cfo-dashboard/sync-historical-data'] = 'admin/finance/cfodashboard/sync_historical_data';
$route['admin/finance/cfo-dashboard/test-database-connection'] = 'admin/finance/cfodashboard/test_database_connection';
$route['admin/finance/cfo-dashboard/performance-test'] = 'admin/finance/cfodashboard/performance_test';
$route['admin/finance/cfo-dashboard/verify-data-accuracy'] = 'admin/finance/cfodashboard/verify_data_accuracy';

// API Routes (RESTful endpoints)
$route['api/finance/revenue-summary'] = 'admin/finance/api/revenue_summary';
$route['api/finance/revenue-trends/(:any)/(:num)'] = 'admin/finance/api/revenue_trends/$1/$2';
$route['api/finance/sales-performance'] = 'admin/finance/api/sales_performance';
$route['api/finance/sales-rep-roi'] = 'admin/finance/api/sales_rep_roi';
$route['api/finance/revenue-forecast'] = 'admin/finance/api/revenue_forecast';
$route['api/finance/revenue-opportunities'] = 'admin/finance/api/revenue_opportunities';
$route['api/finance/product-mix'] = 'admin/finance/api/product_mix';
$route['api/finance/underwriter-analysis'] = 'admin/finance/api/underwriter_analysis';
$route['api/finance/dashboard-status'] = 'admin/finance/api/dashboard_status';
$route['api/finance/alerts'] = 'admin/finance/api/alerts';
$route['api/finance/export'] = 'admin/finance/api/export';
$route['api/finance/settings'] = 'admin/finance/api/settings';

// Webhook Routes
$route['webhooks/cfo-dashboard/softpro'] = 'admin/finance/webhooks/softpro';
$route['webhooks/cfo-dashboard/revenue-update'] = 'admin/finance/webhooks/revenue_update';

// Alternative short routes (optional)
$route['cfo'] = 'admin/finance/cfodashboard/index';
$route['cfo/(:any)'] = 'admin/finance/cfodashboard/$1';

/**
 * Route Examples and Usage:
 * 
 * Main Dashboard:
 * - /admin/finance/cfo-dashboard
 * - /cfo (short alias)
 * 
 * Sub-pages:
 * - /admin/finance/cfo-dashboard/revenue-analytics
 * - /admin/finance/cfo-dashboard/sales-performance
 * - /admin/finance/cfo-dashboard/forecasting
 * - /admin/finance/cfo-dashboard/settings
 * 
 * AJAX/API Calls:
 * - GET /admin/finance/cfo-dashboard/get-realtime-data
 * - GET /admin/finance/cfo-dashboard/get-revenue-trends/daily/30
 * - GET /api/finance/revenue-summary
 * - POST /api/finance/revenue-forecast
 * 
 * Exports:
 * - /admin/finance/cfo-dashboard/export/pdf
 * - /admin/finance/cfo-dashboard/export/excel/detailed
 * 
 * Cron Jobs:
 * - /admin/finance/cfo-dashboard/update-revenue-summaries
 * - /admin/finance/cfo-dashboard/generate-daily-forecasts
 * 
 * Testing:
 * - /admin/finance/cfo-dashboard/test-database-connection
 * - /admin/finance/cfo-dashboard/performance-test
 */
