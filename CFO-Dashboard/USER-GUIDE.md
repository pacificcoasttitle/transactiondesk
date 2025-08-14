# CFO Dashboard User Guide

## 📖 Table of Contents

1. [Getting Started](#getting-started)
2. [Dashboard Overview](#dashboard-overview)
3. [Key Performance Indicators](#key-performance-indicators)
4. [Interactive Charts](#interactive-charts)
5. [Sales Performance Analysis](#sales-performance-analysis)
6. [Revenue Forecasting](#revenue-forecasting)
7. [Export & Reporting](#export--reporting)
8. [Settings & Configuration](#settings--configuration)
9. [Mobile Access](#mobile-access)
10. [Troubleshooting](#troubleshooting)

## 🚀 Getting Started

### Accessing the CFO Dashboard

1. **Login to Admin Panel**
   - Navigate to your Transaction Desk admin login
   - Use your administrator or CFO credentials

2. **Navigate to Dashboard**
   - Go to **Admin** > **Finance** > **CFO Dashboard**
   - Or use the direct URL: `/admin/finance/cfo-dashboard`

3. **Quick Access**
   - Bookmark the dashboard for easy access
   - Available shortcut: `/cfo` (if enabled)

### Required Permissions

To access the CFO Dashboard, you need one of the following roles:
- **CFO** - Full access to all features
- **Finance Manager** - Access to revenue data and reports
- **Admin** - System administrator access
- **Master User** - Super admin access

## 📊 Dashboard Overview

### Main Dashboard Layout

The CFO Dashboard is organized into several key sections:

#### **1. Header Section**
- **Title**: "CFO Revenue Dashboard"
- **Refresh Button**: Manually update data
- **Export Menu**: Download reports in PDF, Excel, or CSV format
- **Last Updated**: Timestamp of most recent data refresh

#### **2. KPI Cards Row**
Four primary metric cards displaying:
- **MTD Revenue**: Month-to-Date revenue with growth indicator
- **YTD Revenue**: Year-to-Date total revenue
- **Projected Monthly**: AI-powered revenue forecast for current month
- **Budget Variance**: Performance against budget targets (percentage)

#### **3. Charts Section**
- **Revenue Trends Chart**: Line chart showing revenue over time
- **Product Mix Chart**: Doughnut chart showing sales vs. refinance breakdown

#### **4. Data Tables**
- **Top Performing Sales Reps**: Ranking table with key metrics
- **Revenue by Underwriter**: Progress bars showing underwriter breakdown

#### **5. Quick Actions**
Navigation buttons to:
- Revenue Analytics (detailed analysis)
- Sales Performance (team metrics)
- Forecasting (predictive analytics)
- Settings (configuration)

## 💡 Key Performance Indicators

### MTD Revenue (Month-to-Date)
- **What it shows**: Total revenue generated from the 1st of the current month to today
- **Growth Indicator**: Green ↑ for positive growth, Red ↓ for decline
- **Calculation**: Sum of all closed order premiums in current month
- **Updates**: Real-time as orders close

### YTD Revenue (Year-to-Date)
- **What it shows**: Total revenue from fiscal year start to today
- **Fiscal Year**: Configurable (default: January 1st)
- **Purpose**: Track annual performance against yearly goals
- **Benchmark**: Compare against previous year performance

### Projected Monthly Revenue
- **What it shows**: AI-powered forecast of total month-end revenue
- **Methodology**: Based on current trends, seasonal patterns, and historical data
- **Confidence Level**: Displayed as accuracy percentage
- **Updates**: Recalculated daily with new data

### Budget Variance
- **What it shows**: How actual/projected performance compares to budget
- **Positive %**: Above budget (green)
- **Negative %**: Below budget (red)
- **Calculation**: ((Actual - Budget) / Budget) × 100
- **Alert Threshold**: Configurable variance levels

## 📈 Interactive Charts

### Revenue Trends Chart

#### **Features**
- **Multiple Data Series**: Total, Sales, and Refinance revenue
- **Interactive Legend**: Click to show/hide data series
- **Hover Tooltips**: Detailed values for each data point
- **Zoom Capability**: Click and drag to zoom into specific periods

#### **Time Period Options**
- **Last 30 Days**: Daily granularity
- **Last 12 Weeks**: Weekly granularity  
- **Last 12 Months**: Monthly granularity

#### **Using the Chart**
1. **Change Time Period**: Use dropdown menu in chart header
2. **Toggle Data Series**: Click legend items to show/hide lines
3. **View Details**: Hover over data points for exact values
4. **Export Chart**: Right-click for image export options

### Product Mix Chart

#### **What it Shows**
- Percentage breakdown of revenue by product type
- **Sales Transactions**: Typically higher value, lower volume
- **Refinance Transactions**: Often higher volume, steady revenue

#### **Interactive Features**
- **Hover for Details**: See exact revenue and percentage
- **Color Coding**: Consistent colors across all charts
- **Legend**: Bottom placement with percentage indicators

## 👥 Sales Performance Analysis

### Top Performers Table

#### **Metrics Displayed**
- **Rank**: Performance ranking (1-5 shown on main dashboard)
- **Sales Rep Name**: Full name of representative
- **Revenue**: Total revenue generated (current period)
- **Goal %**: Achievement percentage against individual goals
- **ROI**: Return on investment percentage

#### **Understanding the Data**
- **Green Badge**: Top 3 performers
- **Goal Achievement**: 
  - Green: 100%+ achievement
  - Yellow: 70-99% achievement
  - Red: Below 70% achievement

#### **Detailed Analysis**
Click "View All" to access the full Sales Performance page with:
- Complete ranking list
- Individual performance trends
- Commission analysis
- Goal setting and tracking

### Sales Rep Performance Insights

#### **Performance Categories**
- **Excellent**: ROI > 400%
- **Good**: ROI 300-400%
- **Average**: ROI 200-300%
- **Below Average**: ROI < 200%

#### **Key Metrics**
- **Total Revenue**: Lifetime or period revenue
- **Commission Earned**: Total commission for period
- **Order Count**: Number of orders processed
- **Average Order Value**: Revenue per order
- **Conversion Rate**: Lead to order percentage (if available)

## 🔮 Revenue Forecasting

### Accessing Forecasting

1. Navigate to **CFO Dashboard** > **Forecasting**
2. Or use direct link: `/admin/finance/cfo-dashboard/forecasting`

### Forecast Types

#### **Monthly Forecasts**
- **Purpose**: Next 3-6 months revenue prediction
- **Update Frequency**: Daily
- **Confidence Level**: Typically 80-95%
- **Methodology**: Linear regression, exponential smoothing, or seasonal analysis

#### **Quarterly Forecasts**
- **Purpose**: Strategic planning and budgeting
- **Horizon**: Next 4 quarters
- **Factors**: Seasonal trends, market conditions, team performance

#### **Yearly Forecasts**
- **Purpose**: Annual budget planning
- **Considerations**: Market growth, team expansion, economic factors

### Forecast Accuracy

#### **Accuracy Tracking**
- Historical forecast vs. actual performance
- Model performance improvement over time
- Confidence intervals and margin of error

#### **Factors Affecting Accuracy**
- **Market Volatility**: Economic conditions
- **Seasonal Patterns**: Real estate market cycles
- **Team Changes**: New hires, departures
- **External Events**: Interest rate changes, regulations

### Using Forecasts

#### **Strategic Planning**
- Budget allocation
- Resource planning
- Goal setting
- Market strategy

#### **Operational Decisions**
- Staffing levels
- Marketing spend
- Technology investments
- Process improvements

## 📊 Export & Reporting

### Export Options

#### **PDF Reports**
- **Executive Summary**: High-level KPIs and charts
- **Detailed Analysis**: Complete data tables and trends
- **Custom Reports**: Specific date ranges or metrics

#### **Excel Exports**
- **Raw Data**: For further analysis in Excel
- **Formatted Reports**: Professional layouts with charts
- **Pivot Tables**: Pre-configured analysis templates

#### **CSV Exports**
- **Data Integration**: Import into other systems
- **Database Loading**: For data warehousing
- **Custom Analysis**: Use with statistical software

### Generating Reports

#### **Quick Export**
1. Click **Export** button in dashboard header
2. Select format (PDF, Excel, CSV)
3. Choose report type (Summary, Detailed, Custom)
4. Download begins automatically

#### **Custom Reports**
1. Navigate to **Settings** > **Reports**
2. Select date range
3. Choose specific metrics
4. Configure formatting options
5. Generate and download

### Scheduled Reports

#### **Automated Delivery**
- **Daily**: Key metrics summary
- **Weekly**: Performance updates
- **Monthly**: Complete revenue analysis
- **Quarterly**: Strategic reports

#### **Recipients**
- Configure email lists
- Role-based distribution
- Executive notifications

## ⚙️ Settings & Configuration

### Accessing Settings

Navigate to **CFO Dashboard** > **Settings** or use `/admin/finance/cfo-dashboard/settings`

### Dashboard Settings

#### **Display Preferences**
- **Currency**: USD, EUR, GBP, etc.
- **Date Format**: MM/DD/YYYY or DD/MM/YYYY
- **Number Format**: Thousands separators, decimal places
- **Time Zone**: Local business timezone

#### **Refresh Settings**
- **Auto-refresh Interval**: 1-60 minutes
- **Real-time Updates**: Enable/disable
- **Cache Duration**: Performance optimization

#### **Chart Settings**
- **Animation**: Enable/disable chart animations
- **Color Scheme**: Default, colorblind-friendly, custom
- **Data Points**: Maximum points to display

### Alert Configuration

#### **Revenue Alerts**
- **Daily Revenue Threshold**: Minimum expected daily revenue
- **Monthly Variance**: Budget variance percentage trigger
- **Goal Achievement**: Sales rep performance thresholds

#### **Notification Methods**
- **Email Alerts**: Send to specific addresses
- **Dashboard Notifications**: In-app alerts
- **SMS Alerts**: For critical issues (if configured)

#### **Alert Recipients**
- **CFO**: All critical and high-priority alerts
- **Finance Manager**: Revenue and budget alerts
- **Sales Manager**: Performance-related alerts
- **Admin**: System and technical alerts

### Budget Configuration

#### **Monthly Targets**
- Set monthly revenue budgets
- Configure by product type
- Set seasonal adjustments

#### **Annual Goals**
- Fiscal year revenue targets
- Growth rate expectations
- Market expansion goals

### User Permissions

#### **Role Management**
- **CFO**: Full access to all features
- **Finance Manager**: Revenue data and standard reports
- **Sales Manager**: Sales performance data only
- **View Only**: Dashboard viewing without export/configuration

## 📱 Mobile Access

### Mobile-Responsive Design

The CFO Dashboard is fully optimized for mobile devices:

#### **Supported Devices**
- **Smartphones**: iOS and Android
- **Tablets**: iPad, Android tablets
- **Browsers**: Chrome, Safari, Firefox, Edge

#### **Mobile Features**
- **Touch-Friendly Interface**: Large buttons and touch targets
- **Responsive Charts**: Optimized for smaller screens
- **Swipe Navigation**: Gesture-based interaction
- **Offline Caching**: Limited offline functionality

### Mobile Usage Tips

#### **Best Practices**
- **Portrait Mode**: Optimal for dashboard viewing
- **WiFi Connection**: For faster data loading
- **Browser Bookmarks**: Save dashboard URL for quick access
- **Notifications**: Enable push notifications for alerts

#### **Limitations**
- **Export Functions**: Limited on mobile devices
- **Complex Charts**: May require horizontal scrolling
- **Settings**: Full configuration requires desktop access

## 🔧 Troubleshooting

### Common Issues

#### **Dashboard Not Loading**
1. **Check Internet Connection**: Ensure stable connectivity
2. **Clear Browser Cache**: Refresh cached data
3. **Check Permissions**: Verify user role has dashboard access
4. **Try Different Browser**: Test in Chrome, Firefox, or Safari

#### **Data Not Updating**
1. **Manual Refresh**: Click refresh button
2. **Check Data Source**: Verify SoftPro API connectivity
3. **Review Logs**: Check error logs for API failures
4. **Contact Support**: If issues persist

#### **Charts Not Displaying**
1. **Enable JavaScript**: Ensure browser JavaScript is enabled
2. **Disable Ad Blockers**: May interfere with chart libraries
3. **Update Browser**: Use latest browser version
4. **Check Console**: Look for JavaScript errors

#### **Slow Performance**
1. **Check Network Speed**: Ensure adequate bandwidth
2. **Close Other Tabs**: Reduce browser memory usage
3. **Reduce Data Range**: Use shorter time periods
4. **Contact Admin**: May need server optimization

### Error Messages

#### **"Access Denied"**
- **Cause**: Insufficient user permissions
- **Solution**: Contact administrator for role assignment

#### **"Data Unavailable"**
- **Cause**: API connectivity issues or missing data
- **Solution**: Wait for next data sync or contact support

#### **"Export Failed"**
- **Cause**: Server capacity or file generation issues
- **Solution**: Try smaller date range or different format

### Getting Help

#### **Support Channels**
1. **Documentation**: Check user guide and FAQ
2. **Internal IT**: Contact your IT department
3. **System Admin**: Reach out to dashboard administrator
4. **Technical Support**: For complex technical issues

#### **Before Contacting Support**
- Note exact error messages
- Document steps to reproduce issue
- Include browser and device information
- Check if issue affects multiple users

---

## 📋 Quick Reference

### Keyboard Shortcuts
- **Ctrl+R**: Refresh dashboard
- **Ctrl+E**: Open export menu
- **Ctrl+S**: Open settings (if accessible)

### Important URLs
- Main Dashboard: `/admin/finance/cfo-dashboard`
- Revenue Analytics: `/admin/finance/cfo-dashboard/revenue-analytics`
- Sales Performance: `/admin/finance/cfo-dashboard/sales-performance`
- Forecasting: `/admin/finance/cfo-dashboard/forecasting`
- Settings: `/admin/finance/cfo-dashboard/settings`

### Default Refresh Intervals
- **Dashboard**: 5 minutes
- **Charts**: Real-time
- **Tables**: 1 minute
- **Alerts**: 15 minutes

---

**Need additional help?** Contact your system administrator or refer to the technical documentation for advanced configuration options.
