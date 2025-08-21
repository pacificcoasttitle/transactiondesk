# Daily Report System - Deployment Guide

## 🚀 **DEPLOYMENT OVERVIEW**

This guide provides comprehensive instructions for deploying the Daily Report system to production. Follow these steps to ensure a successful deployment with full functionality.

---

## 📋 **PRE-DEPLOYMENT CHECKLIST**

### **✅ System Requirements**
- [ ] **Python 3.8+** installed on production server
- [ ] **Web server** (Apache, Nginx, or IIS) configured
- [ ] **Database access** to transaction data sources
- [ ] **File system permissions** for reading Excel files and writing HTML reports
- [ ] **Network access** to data sources (if remote)
- [ ] **Backup system** in place for data and reports

### **✅ Dependencies Verification**
```bash
# Install required Python packages
pip install -r requirements.txt

# Verify installations
python -c "import pandas, openpyxl; print('Dependencies OK')"
```

### **✅ File Structure Verification**
```
daily-report/
├── docs/                           # Documentation
├── mapping/                        # Mapping files
│   ├── SalesRepMapping.xlsx
│   └── TitleOfficerMapping.xlsx
├── SampleExcelFile/               # Data files location
│   ├── Openings.xlsx
│   ├── ClosedOrders.xlsx
│   └── Revenue.xlsx
├── templates/                     # HTML templates
├── build_report.py               # Main report generator
├── create_title_officer_mapping_only.py  # Title officer script
├── transforms.py                 # Data transformations
├── sections.yml                  # Configuration
├── requirements.txt              # Dependencies
└── env.example                   # Environment template
```

---

## 🔧 **DEPLOYMENT STEPS**

### **Step 1: Environment Setup**

#### **1.1 Create Production Environment File**
```bash
# Copy environment template
cp env.example .env

# Edit .env file with production values
nano .env
```

**Required Environment Variables**:
```bash
# Database Configuration
SQLALCHEMY_URL=mysql+pymysql://user:password@host:port/database

# File Paths (adjust for production)
DATA_PATH=/path/to/production/data/
MAPPING_PATH=/path/to/production/mapping/
OUTPUT_PATH=/path/to/web/reports/

# Report Configuration
DEFAULT_MONTH=current
TIMEZONE=America/Los_Angeles

# Security (if needed)
SECRET_KEY=your-secret-key-here
DEBUG=False
```

#### **1.2 Set File Permissions**
```bash
# Make scripts executable
chmod +x build_report.py
chmod +x create_title_officer_mapping_only.py

# Set appropriate permissions for data directories
chmod 755 SampleExcelFile/
chmod 644 SampleExcelFile/*.xlsx
chmod 755 mapping/
chmod 644 mapping/*.xlsx

# Set web server permissions for output
chmod 755 /path/to/web/reports/
```

### **Step 2: Data Source Configuration**

#### **2.1 Production Data Integration**
```python
# Update file paths in scripts for production data sources
# Option 1: Direct database connection
def load_production_data():
    """Load data directly from production database"""
    import sqlalchemy as sa
    
    engine = sa.create_engine(os.getenv('SQLALCHEMY_URL'))
    
    openings_df = pd.read_sql("""
        SELECT * FROM openings 
        WHERE received_date >= %s AND received_date <= %s
    """, engine, params=[start_date, end_date])
    
    return openings_df, closed_df, revenue_df

# Option 2: Automated Excel export
def export_to_excel():
    """Export current data to Excel files"""
    # Implementation depends on your data source
    pass
```

#### **2.2 Data Refresh Schedule**
```bash
# Create cron job for daily data refresh
# Edit crontab: crontab -e

# Daily at 6 AM - Export fresh data
0 6 * * * /path/to/export_data_script.py

# Daily at 7 AM - Generate reports
0 7 * * * /path/to/daily-report/build_report.py

# Monthly on 1st at 8 AM - Generate monthly reports
0 8 1 * * /path/to/daily-report/generate_monthly_reports.py
```

### **Step 3: Web Server Configuration**

#### **3.1 Apache Configuration**
```apache
# /etc/apache2/sites-available/daily-reports.conf
<VirtualHost *:80>
    ServerName reports.yourcompany.com
    DocumentRoot /var/www/daily-reports
    
    # Static files
    Alias /reports /var/www/daily-reports/output
    <Directory /var/www/daily-reports/output>
        Options Indexes FollowSymLinks
        AllowOverride None
        Require all granted
    </Directory>
    
    # CGI for dynamic reports
    ScriptAlias /cgi-bin/ /var/www/daily-reports/cgi-bin/
    <Directory /var/www/daily-reports/cgi-bin>
        Options ExecCGI
        AllowOverride None
        Require all granted
    </Directory>
    
    # Security headers
    Header always set X-Frame-Options DENY
    Header always set X-Content-Type-Options nosniff
    
    # Logging
    ErrorLog ${APACHE_LOG_DIR}/daily-reports_error.log
    CustomLog ${APACHE_LOG_DIR}/daily-reports_access.log combined
</VirtualHost>
```

#### **3.2 Nginx Configuration**
```nginx
# /etc/nginx/sites-available/daily-reports
server {
    listen 80;
    server_name reports.yourcompany.com;
    root /var/www/daily-reports;
    
    # Static reports
    location /reports/ {
        alias /var/www/daily-reports/output/;
        autoindex on;
        expires 1h;
    }
    
    # Dynamic report generation
    location /generate/ {
        proxy_pass http://127.0.0.1:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }
    
    # Security
    add_header X-Frame-Options DENY;
    add_header X-Content-Type-Options nosniff;
    
    # Logging
    access_log /var/log/nginx/daily-reports.access.log;
    error_log /var/log/nginx/daily-reports.error.log;
}
```

#### **3.3 IIS Configuration (Windows)**
```xml
<!-- web.config -->
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
    <system.webServer>
        <defaultDocument>
            <files>
                <clear />
                <add value="Branch-Analytics-Final-Improved.html" />
            </files>
        </defaultDocument>
        
        <staticContent>
            <mimeMap fileExtension=".html" mimeType="text/html" />
        </staticContent>
        
        <httpProtocol>
            <customHeaders>
                <add name="X-Frame-Options" value="DENY" />
                <add name="X-Content-Type-Options" value="nosniff" />
            </customHeaders>
        </httpProtocol>
    </system.webServer>
</configuration>
```

### **Step 4: Automated Report Generation**

#### **4.1 Create Report Generation Script**
```python
#!/usr/bin/env python3
"""
Production Report Generator
"""
import os
import sys
import logging
from datetime import datetime
import subprocess

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('/var/log/daily-reports.log'),
        logging.StreamHandler()
    ]
)

def generate_all_reports():
    """Generate all three production reports"""
    try:
        logging.info("Starting daily report generation")
        
        # Change to report directory
        os.chdir('/path/to/daily-report')
        
        # Generate Title Officer Report
        logging.info("Generating Title Officer Report")
        result = subprocess.run([
            'python', 'create_title_officer_mapping_only.py'
        ], capture_output=True, text=True)
        
        if result.returncode != 0:
            logging.error(f"Title Officer Report failed: {result.stderr}")
            return False
        
        # Generate other reports using main script
        logging.info("Generating Branch Analytics and R-14 Reports")
        result = subprocess.run([
            'python', 'build_report.py'
        ], capture_output=True, text=True)
        
        if result.returncode != 0:
            logging.error(f"Main reports failed: {result.stderr}")
            return False
        
        # Move reports to web directory
        reports = [
            'Branch-Analytics-Final-Improved.html',
            'R-14-Mapped-Report.html', 
            'Title-Officer-Clean-Report.html'
        ]
        
        for report in reports:
            if os.path.exists(report):
                subprocess.run([
                    'cp', report, '/var/www/daily-reports/output/'
                ])
                logging.info(f"Deployed {report}")
            else:
                logging.warning(f"Report not found: {report}")
        
        logging.info("Daily report generation completed successfully")
        return True
        
    except Exception as e:
        logging.error(f"Report generation failed: {e}")
        return False

if __name__ == "__main__":
    success = generate_all_reports()
    sys.exit(0 if success else 1)
```

#### **4.2 Create Monitoring Script**
```python
#!/usr/bin/env python3
"""
Report Health Monitor
"""
import os
import smtplib
from email.mime.text import MIMEText
from datetime import datetime, timedelta

def check_report_health():
    """Check if reports are current and accessible"""
    issues = []
    
    # Check file ages
    reports = [
        '/var/www/daily-reports/output/Branch-Analytics-Final-Improved.html',
        '/var/www/daily-reports/output/R-14-Mapped-Report.html',
        '/var/www/daily-reports/output/Title-Officer-Clean-Report.html'
    ]
    
    for report in reports:
        if not os.path.exists(report):
            issues.append(f"Missing report: {report}")
        else:
            # Check if file is older than 25 hours
            file_time = datetime.fromtimestamp(os.path.getmtime(report))
            if datetime.now() - file_time > timedelta(hours=25):
                issues.append(f"Stale report: {report} (last updated: {file_time})")
    
    # Check data files
    data_files = [
        '/path/to/daily-report/SampleExcelFile/Openings.xlsx',
        '/path/to/daily-report/SampleExcelFile/ClosedOrders.xlsx',
        '/path/to/daily-report/SampleExcelFile/Revenue.xlsx'
    ]
    
    for data_file in data_files:
        if not os.path.exists(data_file):
            issues.append(f"Missing data file: {data_file}")
    
    return issues

def send_alert(issues):
    """Send email alert for issues"""
    if not issues:
        return
    
    msg = MIMEText(f"Daily Report Issues:\n\n" + "\n".join(issues))
    msg['Subject'] = 'Daily Report System Alert'
    msg['From'] = 'reports@yourcompany.com'
    msg['To'] = 'it@yourcompany.com'
    
    try:
        smtp = smtplib.SMTP('localhost')
        smtp.send_message(msg)
        smtp.quit()
    except Exception as e:
        print(f"Failed to send alert: {e}")

if __name__ == "__main__":
    issues = check_report_health()
    if issues:
        send_alert(issues)
        print("Issues found:", issues)
    else:
        print("All reports healthy")
```

### **Step 5: Security Configuration**

#### **5.1 Access Control**
```apache
# Restrict access to internal network only
<Directory /var/www/daily-reports>
    Require ip 192.168.1.0/24
    Require ip 10.0.0.0/8
    # Add your office IP ranges
</Directory>

# Password protection for sensitive reports
<Directory /var/www/daily-reports/output>
    AuthType Basic
    AuthName "Daily Reports"
    AuthUserFile /etc/apache2/.htpasswd
    Require valid-user
</Directory>
```

#### **5.2 SSL/TLS Configuration**
```apache
# Enable HTTPS
<VirtualHost *:443>
    ServerName reports.yourcompany.com
    DocumentRoot /var/www/daily-reports
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    # Force HTTPS
    Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"
</VirtualHost>

# Redirect HTTP to HTTPS
<VirtualHost *:80>
    ServerName reports.yourcompany.com
    Redirect permanent / https://reports.yourcompany.com/
</VirtualHost>
```

---

## 📊 **PRODUCTION DATA INTEGRATION**

### **Option 1: Direct Database Connection**
```python
# Update build_report.py for database integration
import sqlalchemy as sa
from sqlalchemy import text

def load_from_database():
    """Load data directly from production database"""
    engine = sa.create_engine(os.getenv('SQLALCHEMY_URL'))
    
    # Define date range
    start_date = '2025-08-01'
    end_date = '2025-08-31'
    
    # Load openings
    openings_query = text("""
        SELECT 
            order_number as 'Order Number',
            profile as 'Profile',
            received_date as 'Received Date',
            transaction_type as 'Transaction Type',
            sales_rep as 'Sales Rep',
            title_officer as 'Title Officer'
        FROM openings 
        WHERE received_date BETWEEN :start_date AND :end_date
    """)
    
    openings_df = pd.read_sql(openings_query, engine, params={
        'start_date': start_date,
        'end_date': end_date
    })
    
    return openings_df, closed_df, revenue_df
```

### **Option 2: Automated Excel Export**
```python
# Create data export script
def export_production_data():
    """Export current production data to Excel files"""
    
    # Connect to production database
    engine = sa.create_engine(os.getenv('SQLALCHEMY_URL'))
    
    # Export to Excel files
    queries = {
        'Openings.xlsx': "SELECT * FROM openings WHERE received_date >= CURDATE() - INTERVAL 60 DAY",
        'ClosedOrders.xlsx': "SELECT * FROM closed_orders WHERE escrow_closed_date >= CURDATE() - INTERVAL 60 DAY",
        'Revenue.xlsx': "SELECT * FROM revenue WHERE transaction_date >= CURDATE() - INTERVAL 60 DAY"
    }
    
    for filename, query in queries.items():
        df = pd.read_sql(query, engine)
        df.to_excel(f'SampleExcelFile/{filename}', index=False)
        print(f"Exported {len(df)} records to {filename}")
```

---

## 🔍 **TESTING & VALIDATION**

### **Pre-Production Testing**
```bash
# Test report generation
cd /path/to/daily-report
python create_title_officer_mapping_only.py

# Verify output files
ls -la *.html

# Test web access
curl -I http://localhost/reports/Title-Officer-Clean-Report.html

# Test with sample data
python -c "
import pandas as pd
df = pd.read_excel('SampleExcelFile/Openings.xlsx')
print(f'Loaded {len(df)} records')
print(f'Date range: {df[\"Received Date\"].min()} to {df[\"Received Date\"].max()}')
"
```

### **Production Validation Checklist**
- [ ] All three reports generate without errors
- [ ] Reports display correctly in web browser
- [ ] Navigation between reports works
- [ ] Data totals match expected values
- [ ] File permissions are correct
- [ ] Automated generation runs successfully
- [ ] Monitoring alerts work
- [ ] Backup system captures reports

---

## 🚨 **TROUBLESHOOTING**

### **Common Issues**

#### **"Module not found" errors**
```bash
# Check Python path
python -c "import sys; print(sys.path)"

# Install missing packages
pip install pandas openpyxl jinja2

# Check virtual environment
which python
```

#### **"Permission denied" errors**
```bash
# Check file permissions
ls -la SampleExcelFile/
ls -la mapping/

# Fix permissions
chmod 644 SampleExcelFile/*.xlsx
chmod 644 mapping/*.xlsx
```

#### **"File not found" errors**
```bash
# Verify file paths
ls -la SampleExcelFile/
ls -la mapping/

# Check current directory
pwd

# Update paths in scripts if needed
```

#### **Reports not updating**
```bash
# Check cron jobs
crontab -l

# Check log files
tail -f /var/log/daily-reports.log

# Manual generation test
python create_title_officer_mapping_only.py
```

### **Performance Issues**
```python
# Add performance monitoring
import time

start_time = time.time()
# ... report generation code ...
end_time = time.time()

print(f"Report generation took {end_time - start_time:.2f} seconds")
```

---

## 📈 **MONITORING & MAINTENANCE**

### **Daily Monitoring**
- [ ] Check report generation logs
- [ ] Verify all three reports updated
- [ ] Test web access to reports
- [ ] Monitor server resources

### **Weekly Maintenance**
- [ ] Review error logs
- [ ] Check data file sizes and dates
- [ ] Verify backup systems
- [ ] Test alert systems

### **Monthly Maintenance**
- [ ] Review performance metrics
- [ ] Update documentation if needed
- [ ] Check for system updates
- [ ] Validate report accuracy with CFO

### **Log Rotation**
```bash
# /etc/logrotate.d/daily-reports
/var/log/daily-reports.log {
    daily
    rotate 30
    compress
    delaycompress
    missingok
    notifempty
    create 644 www-data www-data
}
```

---

## 🔄 **BACKUP & RECOVERY**

### **Backup Strategy**
```bash
#!/bin/bash
# Daily backup script

BACKUP_DIR="/backup/daily-reports/$(date +%Y-%m-%d)"
mkdir -p $BACKUP_DIR

# Backup reports
cp /var/www/daily-reports/output/*.html $BACKUP_DIR/

# Backup data files
cp -r /path/to/daily-report/SampleExcelFile $BACKUP_DIR/
cp -r /path/to/daily-report/mapping $BACKUP_DIR/

# Backup configuration
cp /path/to/daily-report/.env $BACKUP_DIR/
cp /path/to/daily-report/sections.yml $BACKUP_DIR/

# Compress backup
tar -czf $BACKUP_DIR.tar.gz $BACKUP_DIR
rm -rf $BACKUP_DIR

# Keep only last 30 days
find /backup/daily-reports/ -name "*.tar.gz" -mtime +30 -delete
```

### **Recovery Procedures**
```bash
# Restore from backup
RESTORE_DATE="2025-08-15"
cd /backup/daily-reports/
tar -xzf $RESTORE_DATE.tar.gz

# Copy files back
cp $RESTORE_DATE/*.html /var/www/daily-reports/output/
cp -r $RESTORE_DATE/SampleExcelFile /path/to/daily-report/
cp -r $RESTORE_DATE/mapping /path/to/daily-report/
```

---

## 📞 **SUPPORT & CONTACTS**

### **Deployment Team Contacts**
- **System Administrator**: admin@yourcompany.com
- **Database Administrator**: dba@yourcompany.com  
- **Web Administrator**: webadmin@yourcompany.com
- **Business Analyst**: analyst@yourcompany.com

### **Escalation Procedures**
1. **Level 1**: Check logs and restart services
2. **Level 2**: Contact system administrator
3. **Level 3**: Contact development team
4. **Level 4**: Contact business stakeholders

### **Emergency Contacts**
- **After Hours Support**: +1-555-SUPPORT
- **CFO (for business issues)**: cfo@yourcompany.com
- **IT Director**: itdirector@yourcompany.com

---

## ✅ **POST-DEPLOYMENT CHECKLIST**

### **Immediate (Day 1)**
- [ ] All reports generate successfully
- [ ] Web access works from all required locations
- [ ] Navigation between reports functions
- [ ] Data appears accurate and current
- [ ] Automated generation scheduled and tested

### **Short Term (Week 1)**
- [ ] Daily generation runs without issues
- [ ] Monitoring alerts are working
- [ ] Performance is acceptable
- [ ] Users can access reports successfully
- [ ] Backup system is capturing data

### **Long Term (Month 1)**
- [ ] Reports consistently accurate
- [ ] No performance degradation
- [ ] All stakeholders satisfied with functionality
- [ ] Documentation is current and accurate
- [ ] Support procedures are effective

---

**🚀 This deployment guide ensures a successful production deployment with full functionality, monitoring, and support procedures. Follow each step carefully and validate thoroughly before going live.**
