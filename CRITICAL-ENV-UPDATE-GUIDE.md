# 🚨 CRITICAL: ENVIRONMENT FILE UPDATE REQUIRED

## ⚠️ IMMEDIATE ACTION REQUIRED

**Your `.env` file MUST be updated before deploying the security fixes. Failure to update environment variables will cause system failures and security vulnerabilities.**

---

## 📋 WHAT CHANGED

### **🔴 Critical Changes Made:**
1. **Hardcoded TitlePoint credentials REMOVED** from source code
2. **Security libraries require new environment variables**
3. **CFO Dashboard needs configuration settings**
4. **Session security requires encryption keys**

### **💥 Impact if NOT Updated:**
- ❌ **TitlePoint API will fail** (no credentials)
- ❌ **Security features won't work** (missing encryption keys)
- ❌ **CFO Dashboard won't load** (missing configuration)
- ❌ **System may crash** (undefined environment variables)

---

## 🆘 IMMEDIATE FIX: UPDATE YOUR .ENV FILE

### **Step 1: Backup Current .env File**
```bash
cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
```

### **Step 2: Add Critical Variables**

**Add these REQUIRED variables to your `.env` file:**

```env
#################################################################
# CRITICAL: SECURITY IMPLEMENTATION VARIABLES
#################################################################

# TitlePoint API Configuration (REQUIRED - was hardcoded in source)
TP_USERNAME=your_titlepoint_username
TP_PASSWORD=your_titlepoint_password
TP_REQUEST_SUMMARY_ENDPOINT=https://api.titlepoint.com/RequestSummary

# Security Configuration (REQUIRED for new security features)
ENCRYPTION_KEY=your_32_character_encryption_key_here
SECURITY_ALERT_EMAIL=security@pacificcoasttitle.com

# Session Security (REQUIRED)
SESSION_ENCRYPTION=true
CSRF_PROTECTION=true
RATE_LIMITING=true

#################################################################
# CFO DASHBOARD CONFIGURATION
#################################################################

# CFO Dashboard Settings
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

#################################################################
# EXISTING CONFIGURATION (VERIFY THESE EXIST)
#################################################################

# Database Configuration
DB_HOST=localhost
DB_DATABASE=transaction_desk
DB_USERNAME=td_user
DB_PASSWORD=secure_password_here

# Application Settings
APP_ENV=testing
APP_DEBUG=true
BASE_URL=http://your-server-domain.com/transaction-desk/

# SoftPro/Resware Configuration
RESWARE_ORDER_API=https://your-softpro-server.com/api/

# AWS S3 Configuration
AWS_BUCKET=your-test-s3-bucket
AWS_REGION=us-west-2
AWS_ACCESS_KEY_ID=your_aws_access_key
AWS_SECRET_ACCESS_KEY=your_aws_secret_key
AWS_PATH=https://your-test-s3-bucket.s3.amazonaws.com/
AWS_ENABLE_FLAG=1

# Email Configuration
FROM_EMAIL=noreply@your-domain.com
SMTP_HOST=your-smtp-server.com
SMTP_PORT=587
SMTP_USER=your-smtp-username
SMTP_PASS=your-smtp-password

# HomeDocs Integration
HOMEDOCS_URL=https://api.homedocs.com/

# PDF Processing
PDF_TO_DOC_CLIENT_ID=your_aspose_client_id
PDF_TO_DOC_SECRET_KEY=your_aspose_secret_key
```

---

## 🔧 STEP-BY-STEP SETUP GUIDE

### **1. Generate Secure Encryption Key**

**Option A: Using PHP (Recommended)**
```bash
php -r "echo bin2hex(random_bytes(16)) . PHP_EOL;"
```

**Option B: Using OpenSSL**
```bash
openssl rand -hex 16
```

**Option C: Online Generator**
- Use a secure key generator to create a 32-character encryption key
- Example: `a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6`

### **2. Update TitlePoint Credentials**

**CRITICAL**: Replace these placeholders with your actual TitlePoint credentials:

```env
# Replace with your actual TitlePoint credentials
TP_USERNAME=YOUR_ACTUAL_TITLEPOINT_USERNAME
TP_PASSWORD=YOUR_ACTUAL_TITLEPOINT_PASSWORD
```

**Where to find your credentials:**
- Check your TitlePoint account dashboard
- Contact your TitlePoint account manager
- Look in existing documentation or contracts

### **3. Configure Security Alert Email**

```env
# Replace with your actual security team email
SECURITY_ALERT_EMAIL=security@yourdomain.com
```

### **4. Verify Database Settings**

Make sure these match your current database setup:

```env
DB_HOST=localhost                    # Your database host
DB_DATABASE=transaction_desk         # Your database name
DB_USERNAME=td_user                  # Your database username
DB_PASSWORD=your_actual_db_password  # Your actual database password
```

### **5. Update Base URL**

```env
# Replace with your actual domain
BASE_URL=http://your-actual-domain.com/transaction-desk/
```

---

## ✅ VERIFICATION CHECKLIST

After updating your `.env` file, verify these items:

### **Environment Variables Check:**
- [ ] TP_USERNAME is set to your actual TitlePoint username
- [ ] TP_PASSWORD is set to your actual TitlePoint password
- [ ] ENCRYPTION_KEY is a 32-character random string
- [ ] SECURITY_ALERT_EMAIL is set to a valid email address
- [ ] BASE_URL matches your actual domain
- [ ] Database credentials are correct

### **File Permissions Check:**
```bash
# Ensure .env file has proper permissions
chmod 600 .env
chown www-data:www-data .env
```

### **Syntax Check:**
```bash
# Test if PHP can read the .env file
php -r "
require_once 'vendor/autoload.php';
\$dotenv = Dotenv\Dotenv::createImmutable('.');
\$dotenv->load();
echo 'Environment file loaded successfully!' . PHP_EOL;
echo 'TP_USERNAME: ' . ($_ENV['TP_USERNAME'] ?? 'NOT SET') . PHP_EOL;
echo 'ENCRYPTION_KEY: ' . (isset($_ENV['ENCRYPTION_KEY']) ? 'SET' : 'NOT SET') . PHP_EOL;
"
```

---

## 🚨 TROUBLESHOOTING

### **Issue: "TP_PASSWORD not found" Error**
```bash
# Check if TitlePoint variables are set
grep -E "^TP_" .env
```
**Solution**: Ensure TP_USERNAME and TP_PASSWORD are in your .env file without quotes

### **Issue: "ENCRYPTION_KEY not found" Error**
```bash
# Check if encryption key is set
grep "ENCRYPTION_KEY" .env
```
**Solution**: Generate and add a 32-character encryption key

### **Issue: Database Connection Fails**
```bash
# Test database connection
mysql -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE -e "SELECT 1;"
```
**Solution**: Verify database credentials in .env file

### **Issue: Permission Denied on .env File**
```bash
# Fix file permissions
chmod 600 .env
chown www-data:www-data .env
```

### **Issue: CFO Dashboard Won't Load**
```bash
# Check CFO Dashboard variables
grep -E "^CFO_DASHBOARD_" .env
```
**Solution**: Ensure CFO_DASHBOARD_ENABLED=true is set

---

## 📋 COMPLETE .ENV TEMPLATE

Here's a complete `.env` file template with all required variables:

```env
#################################################################
# TRANSACTION DESK ENVIRONMENT CONFIGURATION
# Updated: December 1, 2024
# Security Level: Enterprise
#################################################################

#################################################################
# DATABASE CONFIGURATION
#################################################################
DB_HOST=localhost
DB_DATABASE=transaction_desk
DB_USERNAME=td_user
DB_PASSWORD=your_secure_database_password

#################################################################
# APPLICATION SETTINGS
#################################################################
APP_ENV=production
APP_DEBUG=false
BASE_URL=https://your-domain.com/transaction-desk/

#################################################################
# CRITICAL: TITLEPOINT API CONFIGURATION
# WARNING: These were hardcoded in source code and have been moved here
#################################################################
TP_USERNAME=your_titlepoint_username
TP_PASSWORD=your_titlepoint_password
TP_REQUEST_SUMMARY_ENDPOINT=https://api.titlepoint.com/RequestSummary

#################################################################
# CRITICAL: SECURITY CONFIGURATION
# These are required for the new security features
#################################################################
ENCRYPTION_KEY=your_32_character_encryption_key_here
SECURITY_ALERT_EMAIL=security@yourdomain.com
SESSION_ENCRYPTION=true
CSRF_PROTECTION=true
RATE_LIMITING=true

#################################################################
# SOFTPRO/RESWARE CONFIGURATION
#################################################################
RESWARE_ORDER_API=https://your-softpro-server.com/api/

#################################################################
# AWS S3 CONFIGURATION
#################################################################
AWS_BUCKET=your-production-s3-bucket
AWS_REGION=us-west-2
AWS_ACCESS_KEY_ID=your_aws_access_key
AWS_SECRET_ACCESS_KEY=your_aws_secret_key
AWS_PATH=https://your-production-s3-bucket.s3.amazonaws.com/
AWS_ENABLE_FLAG=1

#################################################################
# EMAIL CONFIGURATION
#################################################################
FROM_EMAIL=noreply@yourdomain.com
SMTP_HOST=your-smtp-server.com
SMTP_PORT=587
SMTP_USER=your-smtp-username
SMTP_PASS=your-smtp-password

#################################################################
# CFO DASHBOARD CONFIGURATION
#################################################################
CFO_DASHBOARD_ENABLED=true
CFO_DASHBOARD_UPDATE_INTERVAL=300
CFO_DASHBOARD_CACHE_TTL=3600
REVENUE_CALCULATION_TIMEZONE=America/Los_Angeles
REVENUE_FISCAL_YEAR_START=01-01
REVENUE_BUDGET_CURRENCY=USD
ALERTS_EMAIL_ENABLED=true
REPORTS_PDF_ENGINE=dompdf
REPORTS_EXCEL_ENGINE=phpspreadsheet
REPORTS_EXPORT_PATH=uploads/reports/finance/
DASHBOARD_ENABLE_CACHING=true
DASHBOARD_ENABLE_COMPRESSION=true
DASHBOARD_MAX_DATA_POINTS=1000

#################################################################
# HOMEDOCS INTEGRATION
#################################################################
HOMEDOCS_URL=https://api.homedocs.com/

#################################################################
# PDF PROCESSING
#################################################################
PDF_TO_DOC_CLIENT_ID=your_aspose_client_id
PDF_TO_DOC_SECRET_KEY=your_aspose_secret_key

#################################################################
# ADDITIONAL SECURITY SETTINGS
#################################################################
SOFTPRO_ENHANCED_SYNC=true
SOFTPRO_WEBHOOK_SECRET=your_webhook_secret_here
SOFTPRO_SYNC_INTERVAL=3600
DB_REVENUE_QUERIES_CACHE=true
DB_ANALYTICS_READ_REPLICA=false
```

---

## 🎯 PRODUCTION VS TESTING ENVIRONMENTS

### **Testing Environment (.env)**
```env
APP_ENV=testing
APP_DEBUG=true
BASE_URL=http://test.yourdomain.com/transaction-desk/
AWS_BUCKET=your-test-s3-bucket
```

### **Production Environment (.env)**
```env
APP_ENV=production
APP_DEBUG=false
BASE_URL=https://yourdomain.com/transaction-desk/
AWS_BUCKET=your-production-s3-bucket
```

---

## 🔐 SECURITY BEST PRACTICES

### **1. Environment File Security**
```bash
# Set restrictive permissions
chmod 600 .env

# Ensure proper ownership
chown www-data:www-data .env

# Add to .gitignore
echo ".env" >> .gitignore
```

### **2. Regular Key Rotation**
- Change ENCRYPTION_KEY every 90 days
- Rotate TitlePoint credentials annually
- Update AWS keys every 180 days

### **3. Environment File Backup**
```bash
# Create encrypted backup
gpg -c .env
mv .env.gpg secure-backup-location/
```

---

## 📞 EMERGENCY SUPPORT

### **If System Fails After Update:**

1. **Restore from backup:**
   ```bash
   cp .env.backup.YYYYMMDD_HHMMSS .env
   ```

2. **Check error logs:**
   ```bash
   tail -f logs/error.log
   tail -f /var/log/apache2/error.log
   ```

3. **Test individual components:**
   ```bash
   php -r "echo getenv('TP_USERNAME') ? 'TitlePoint OK' : 'TitlePoint MISSING';"
   php -r "echo getenv('ENCRYPTION_KEY') ? 'Encryption OK' : 'Encryption MISSING';"
   ```

4. **Contact support with:**
   - Error messages from logs
   - List of environment variables set
   - Steps taken to resolve

---

## ✅ POST-UPDATE VERIFICATION

After updating your `.env` file, run these tests:

```bash
# Test 1: Environment loading
php -r "var_dump(getenv('TP_USERNAME'));"

# Test 2: TitlePoint connectivity
curl -s "https://api.titlepoint.com/health" || echo "TitlePoint unreachable"

# Test 3: Application access
curl -s -o /dev/null -w "%{http_code}" http://localhost/transaction-desk/

# Test 4: CFO Dashboard access
curl -s -o /dev/null -w "%{http_code}" http://localhost/transaction-desk/admin/finance/cfo-dashboard

# Test 5: Secure login page
curl -s -o /dev/null -w "%{http_code}" http://localhost/transaction-desk/cpl/php/admin/secure_login.php
```

**Expected Results:**
- Test 1: Should return your TitlePoint username
- Test 2: Should connect successfully  
- Test 3: Should return 200
- Test 4: Should return 200 (with proper authentication)
- Test 5: Should return 200

---

## 🎉 SUCCESS CONFIRMATION

Once your `.env` file is updated correctly, you should see:

- ✅ **TitlePoint API working** (no hardcoded credential errors)
- ✅ **Security features active** (login security, CSRF protection)
- ✅ **CFO Dashboard accessible** (if permissions allow)
- ✅ **No environment variable errors** in logs
- ✅ **System fully operational** with enterprise security

**Your Transaction Desk system is now ready for enterprise-secure operation!**

---

**⚠️ REMEMBER: This environment file update is CRITICAL and REQUIRED for the security fixes to work properly. Do not skip this step!**
