# CFO Dashboard Installation Guide

## 📋 Prerequisites

### **System Requirements**
- **PHP**: 7.4+ (8.0+ recommended)
- **MySQL**: 5.7+ or MariaDB 10.3+
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Memory**: Minimum 4GB RAM (8GB+ recommended)
- **Transaction Desk**: Existing installation required

### **Required PHP Extensions**
```bash
php-curl       # For API integrations
php-gd         # For chart image generation
php-json       # For data processing
php-mbstring   # For string manipulation
php-mysql      # For database operations
php-xml        # For data parsing
php-zip        # For export functionality
php-openssl    # For secure communications
```

### **Database Permissions**
- CREATE, ALTER, DROP table permissions
- INSERT, UPDATE, DELETE, SELECT permissions
- TRIGGER and PROCEDURE creation permissions
- INDEX creation permissions

## 🗄 Database Installation

### **Step 1: Create CFO Dashboard Tables**

Run the following migration files in order:

```bash
# Navigate to project root
cd /path/to/transaction-desk

# Run CFO Dashboard migrations
./vendor/bin/phinx migrate -e production -t 20241201000001
./vendor/bin/phinx migrate -e production -t 20241201000002
./vendor/bin/phinx migrate -e production -t 20241201000003
./vendor/bin/phinx migrate -e production -t 20241201000004
```

### **Step 2: Verify Database Schema**

```sql
-- Verify tables were created
SHOW TABLES LIKE 'cfo_%';

-- Expected tables:
-- cfo_revenue_daily_summary
-- cfo_sales_rep_performance
-- cfo_revenue_forecasts
-- cfo_dashboard_settings
-- cfo_alert_configurations
```

### **Step 3: Initialize Sample Data (Optional)**

```bash
# Seed with sample data for testing
./vendor/bin/phinx seed:run -e production -s CfoDashboardSeeder
```

## 📁 File Installation

### **Step 1: Copy Application Files**

```bash
# Copy controllers
cp -r CFO-Dashboard/application/controllers/admin/finance/ \
    /path/to/transaction-desk/application/modules/admin/controllers/

# Copy models
cp -r CFO-Dashboard/application/models/finance/ \
    /path/to/transaction-desk/application/modules/admin/models/

# Copy libraries
cp -r CFO-Dashboard/application/libraries/finance/ \
    /path/to/transaction-desk/application/libraries/

# Copy views
cp -r CFO-Dashboard/application/views/admin/finance/ \
    /path/to/transaction-desk/application/modules/admin/views/
```

### **Step 2: Copy Asset Files**

```bash
# Copy CSS files
cp CFO-Dashboard/assets/css/* \
    /path/to/transaction-desk/assets/backend/css/

# Copy JavaScript files
cp CFO-Dashboard/assets/js/* \
    /path/to/transaction-desk/assets/backend/js/

# Copy images
cp CFO-Dashboard/assets/images/* \
    /path/to/transaction-desk/assets/backend/images/
```

### **Step 3: Update Configuration Files**

#### **Add Routes** (`application/config/routes.php`)
```php
// CFO Dashboard Routes
$route['admin/finance/cfo-dashboard'] = 'admin/finance/cfodashboard/index';
$route['admin/finance/cfo-dashboard/(:any)'] = 'admin/finance/cfodashboard/$1';
$route['admin/finance/revenue-analytics'] = 'admin/finance/revenueanalytics/index';
$route['admin/finance/revenue-analytics/(:any)'] = 'admin/finance/revenueanalytics/$1';
$route['admin/finance/executive-reports'] = 'admin/finance/executivereports/index';
$route['admin/finance/executive-reports/(:any)'] = 'admin/finance/executivereports/$1';

// API Routes for real-time data
$route['api/finance/revenue-summary'] = 'admin/finance/api/revenuesummary';
$route['api/finance/sales-performance'] = 'admin/finance/api/salesperformance';
$route['api/finance/revenue-trends/(:any)'] = 'admin/finance/api/revenuetrends/$1';
$route['api/finance/alerts'] = 'admin/finance/api/alerts';

// Webhook endpoints
$route['webhooks/softpro'] = 'admin/finance/webhooks/softpro';
$route['webhooks/revenue-update'] = 'admin/finance/webhooks/revenueupdate';
```

#### **Update User Roles** (`application/config/user_roles.php`)
```php
// Add CFO Dashboard permissions
$config['user_roles'] = [
    'cfo' => [
        'finance.dashboard.view',
        'finance.dashboard.export',
        'finance.revenue.view_all',
        'finance.sales.view_all',
        'finance.reports.generate',
        'finance.alerts.configure'
    ],
    'finance_manager' => [
        'finance.dashboard.view',
        'finance.revenue.view_all',
        'finance.sales.view_limited',
        'finance.reports.view'
    ],
    'sales_manager' => [
        'finance.dashboard.view_limited',
        'finance.sales.view_team',
        'finance.reports.view_sales'
    ],
    'admin' => [
        'finance.*' // Full access to all finance features
    ]
];
```

## ⚙️ Configuration Setup

### **Step 1: Environment Variables**

Add the following to your `.env` file:

```env
# CFO Dashboard Configuration
CFO_DASHBOARD_ENABLED=true
CFO_DASHBOARD_UPDATE_INTERVAL=300
CFO_DASHBOARD_CACHE_TTL=3600

# Revenue Calculation Settings
REVENUE_CALCULATION_TIMEZONE=America/Los_Angeles
REVENUE_FISCAL_YEAR_START=01-01
REVENUE_BUDGET_CURRENCY=USD

# Alert System Configuration
ALERTS_EMAIL_ENABLED=true
ALERTS_SLACK_ENABLED=false
ALERTS_SMS_ENABLED=false

# Reporting Configuration
REPORTS_PDF_ENGINE=dompdf
REPORTS_EXCEL_ENGINE=phpspreadsheet
REPORTS_EXPORT_PATH=uploads/reports/finance/

# Performance Settings
DASHBOARD_ENABLE_CACHING=true
DASHBOARD_ENABLE_COMPRESSION=true
DASHBOARD_MAX_DATA_POINTS=1000

# SoftPro Integration Enhancement
SOFTPRO_ENHANCED_SYNC=true
SOFTPRO_WEBHOOK_SECRET=your_webhook_secret_here
SOFTPRO_SYNC_INTERVAL=3600

# Database Optimization
DB_REVENUE_QUERIES_CACHE=true
DB_ANALYTICS_READ_REPLICA=false
```

### **Step 2: Database Configuration**

#### **Create Revenue Analytics User** (Optional but recommended)
```sql
-- Create dedicated database user for analytics
CREATE USER 'cfo_analytics'@'localhost' IDENTIFIED BY 'secure_password_here';

-- Grant necessary permissions
GRANT SELECT, INSERT, UPDATE ON transaction_desk.cfo_* TO 'cfo_analytics'@'localhost';
GRANT SELECT ON transaction_desk.order_details TO 'cfo_analytics'@'localhost';
GRANT SELECT ON transaction_desk.transaction_details TO 'cfo_analytics'@'localhost';
GRANT SELECT ON transaction_desk.users TO 'cfo_analytics'@'localhost';
GRANT EXECUTE ON PROCEDURE transaction_desk.calculate_commission TO 'cfo_analytics'@'localhost';

FLUSH PRIVILEGES;
```

#### **Configure Database Indexes**
```sql
-- Optimize existing tables for CFO dashboard queries
CREATE INDEX idx_order_details_accounting_date ON order_details(sent_to_accounting_date);
CREATE INDEX idx_order_details_premium ON order_details(premium);
CREATE INDEX idx_order_details_prod_type ON order_details(prod_type);
CREATE INDEX idx_order_details_underwriter ON order_details(underwriter);

CREATE INDEX idx_transaction_details_sales_rep ON transaction_details(sales_representative);
CREATE INDEX idx_transaction_details_amounts ON transaction_details(loan_amount, sales_amount);

-- Composite indexes for common CFO dashboard queries
CREATE INDEX idx_order_composite_cfo ON order_details(sent_to_accounting_date, prod_type, premium, underwriter);
CREATE INDEX idx_transaction_composite_cfo ON transaction_details(sales_representative, created_at);
```

## 🔄 Data Synchronization Setup

### **Step 1: Configure SoftPro Webhook**

```bash
# Test SoftPro API connection
curl -X GET "https://your-softpro-server.com/api/orders" \
  -H "Authorization: Basic $(echo -n 'username:password' | base64)" \
  -H "Content-Type: application/json"

# Configure webhook endpoint
curl -X POST "https://your-softpro-server.com/api/webhooks" \
  -H "Authorization: Basic $(echo -n 'username:password' | base64)" \
  -H "Content-Type: application/json" \
  -d '{
    "url": "https://your-domain.com/webhooks/softpro",
    "events": ["order.closed", "order.updated", "order.cancelled"],
    "secret": "your_webhook_secret_here"
  }'
```

### **Step 2: Initialize Historical Data**

```bash
# Run historical data sync (one-time setup)
php index.php admin/finance/cfodashboard/sync_historical_data

# This will:
# - Import last 12 months of revenue data
# - Calculate historical performance metrics
# - Generate baseline forecasting data
# - Create initial dashboard cache
```

### **Step 3: Set Up Automated Tasks**

Add to your crontab (`crontab -e`):

```bash
# CFO Dashboard automated tasks

# Update revenue summaries every 5 minutes
*/5 * * * * /usr/bin/php /path/to/transaction-desk/index.php admin/finance/cfodashboard/update_revenue_summaries

# Calculate sales rep performance every hour
0 * * * * /usr/bin/php /path/to/transaction-desk/index.php admin/finance/cfodashboard/update_sales_performance

# Generate daily revenue forecasts at midnight
0 0 * * * /usr/bin/php /path/to/transaction-desk/index.php admin/finance/cfodashboard/generate_daily_forecasts

# Check revenue alerts every 15 minutes during business hours (8 AM - 6 PM)
*/15 8-18 * * 1-5 /usr/bin/php /path/to/transaction-desk/index.php admin/finance/cfodashboard/check_revenue_alerts

# Generate monthly executive reports on the 1st of each month
0 6 1 * * /usr/bin/php /path/to/transaction-desk/index.php admin/finance/executivereports/generate_monthly_report

# Clean up old cached data weekly
0 2 * * 0 /usr/bin/php /path/to/transaction-desk/index.php admin/finance/cfodashboard/cleanup_cache
```

## 🔐 Security Configuration

### **Step 1: Set File Permissions**

```bash
# Set proper file permissions
chmod 755 /path/to/transaction-desk/application/modules/admin/controllers/finance/
chmod 755 /path/to/transaction-desk/application/modules/admin/views/finance/
chmod 755 /path/to/transaction-desk/application/libraries/finance/

# Make reports directory writable
chmod 777 /path/to/transaction-desk/uploads/reports/finance/

# Secure sensitive configuration files
chmod 600 /path/to/transaction-desk/.env
chmod 600 /path/to/transaction-desk/application/config/database.php
```

### **Step 2: Configure Access Control**

```php
// application/libraries/finance/FinanceAuth.php
// Access control is automatically configured with role-based permissions

// Verify CFO dashboard access
if (!$this->financeauth->hasPermission('finance.dashboard.view')) {
    redirect('access-denied');
}
```

## ✅ Installation Verification

### **Step 1: Basic Functionality Test**

Navigate to: `https://your-domain.com/admin/finance/cfo-dashboard`

**Expected Results:**
- [ ] Dashboard loads within 3 seconds
- [ ] KPI cards display revenue data
- [ ] Charts render with sample data
- [ ] No JavaScript console errors
- [ ] Mobile responsive layout works

### **Step 2: Data Accuracy Test**

```bash
# Run data accuracy verification
php index.php admin/finance/cfodashboard/verify_data_accuracy

# This will:
# - Compare dashboard data with SoftPro source
# - Verify calculation accuracy
# - Check for data consistency
# - Generate accuracy report
```

### **Step 3: Performance Test**

```bash
# Run performance benchmarks
php index.php admin/finance/cfodashboard/performance_test

# Expected performance metrics:
# - Dashboard load time: < 3 seconds
# - API response time: < 500ms
# - Chart rendering: < 1 second
# - Database queries: < 100ms average
```

## 🐛 Troubleshooting

### **Common Installation Issues**

#### **Database Connection Error**
```bash
# Check database credentials
php index.php admin/finance/cfodashboard/test_database_connection

# Verify table creation
mysql -u username -p -e "SHOW TABLES LIKE 'cfo_%';" database_name
```

#### **Permission Denied Errors**
```bash
# Check file permissions
ls -la application/modules/admin/controllers/finance/
ls -la uploads/reports/finance/

# Fix permissions if needed
chmod -R 755 application/modules/admin/
chmod -R 777 uploads/reports/
```

#### **SoftPro API Connection Issues**
```bash
# Test API connectivity
php index.php admin/finance/cfodashboard/test_softpro_connection

# Check webhook configuration
curl -X GET "https://your-softpro-server.com/api/webhooks" \
  -H "Authorization: Basic $(echo -n 'username:password' | base64)"
```

#### **JavaScript/Chart Errors**
```html
<!-- Check browser console for errors -->
<!-- Verify Chart.js library is loaded -->
<script>
console.log('Chart.js version:', Chart.version);
</script>
```

### **Performance Issues**

#### **Slow Dashboard Loading**
```sql
-- Check for missing indexes
EXPLAIN SELECT * FROM cfo_revenue_daily_summary 
WHERE summary_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY);

-- Add missing indexes
CREATE INDEX idx_missing ON table_name(column_name);
```

#### **Memory Issues**
```php
// Increase PHP memory limit in php.ini
memory_limit = 512M

// Or set temporarily in dashboard
ini_set('memory_limit', '512M');
```

## 📞 Support

### **Log Files**
- **Application Logs**: `application/logs/`
- **CFO Dashboard Logs**: `application/logs/finance/`
- **Error Logs**: Check web server error logs

### **Debug Mode**
```php
// Enable debugging in .env
CFO_DASHBOARD_DEBUG=true
APP_DEBUG=true

// Check debug output in dashboard
```

### **Contact Information**
- **Technical Issues**: Check application logs first
- **Data Accuracy**: Verify SoftPro API connection
- **Performance**: Review database optimization
- **Feature Requests**: Document in project tracker

---

**Installation Complete!** 🎉

Your CFO Dashboard should now be fully functional. Access it at `/admin/finance/cfo-dashboard` using your admin credentials.

Next Steps:
1. Review the [User Guide](USER-GUIDE.md) for dashboard features
2. Configure alert thresholds in dashboard settings
3. Set up monthly report automation
4. Train finance team on new features
