# Environment Configuration Guide - Transaction Desk

> **CRITICAL UPDATE:** December 1, 2024 - Environment variables have been completely restructured for enterprise security implementation.

## 🚨 IMMEDIATE ACTION REQUIRED

**Your `.env` file MUST be updated before deploying the security fixes. This is not optional.**

### **What Changed:**
- **TitlePoint credentials removed** from source code
- **New security variables required** for enterprise features
- **CFO Dashboard configuration** added
- **Session security settings** mandatory

---

## 📋 COMPLETE .ENV TEMPLATE

### **Production Environment (.env)**

```env
#################################################################
# TRANSACTION DESK ENVIRONMENT CONFIGURATION
# Updated: December 1, 2024
# Security Level: Enterprise
# Status: PRODUCTION
#################################################################

#################################################################
# DATABASE CONFIGURATION
#################################################################
DB_HOST=localhost
DB_DATABASE=transaction_desk_prod
DB_USERNAME=td_prod_user
DB_PASSWORD=your_ultra_secure_production_password

#################################################################
# APPLICATION SETTINGS
#################################################################
APP_ENV=production
APP_DEBUG=false
BASE_URL=https://transactiondesk.pacificcoasttitle.com/

#################################################################
# CRITICAL: TITLEPOINT API CONFIGURATION
# WARNING: These credentials were previously hardcoded in source
# They are now REQUIRED environment variables for system operation
#################################################################
TP_USERNAME=your_production_titlepoint_username
TP_PASSWORD=your_production_titlepoint_password
TP_REQUEST_SUMMARY_ENDPOINT=https://api.titlepoint.com/RequestSummary

#################################################################
# CRITICAL: ENTERPRISE SECURITY CONFIGURATION
# Required for security features implemented December 2024
# DO NOT use default values in production
#################################################################
ENCRYPTION_KEY=your_production_32_char_encryption_key
SECURITY_ALERT_EMAIL=security@pacificcoasttitle.com
SESSION_ENCRYPTION=true
CSRF_PROTECTION=true
RATE_LIMITING=true

#################################################################
# SOFTPRO/RESWARE CONFIGURATION
#################################################################
RESWARE_ORDER_API=https://your-production-softpro-server.com/api/

#################################################################
# AWS S3 CONFIGURATION
#################################################################
AWS_BUCKET=pct-production-documents
AWS_REGION=us-west-2
AWS_ACCESS_KEY_ID=your_production_aws_access_key
AWS_SECRET_ACCESS_KEY=your_production_aws_secret_key
AWS_PATH=https://pct-production-documents.s3.amazonaws.com/
AWS_ENABLE_FLAG=1

#################################################################
# EMAIL CONFIGURATION
#################################################################
FROM_EMAIL=noreply@pacificcoasttitle.com
SMTP_HOST=smtp.pacificcoasttitle.com
SMTP_PORT=587
SMTP_USER=noreply@pacificcoasttitle.com
SMTP_PASS=your_production_email_password

#################################################################
# CFO DASHBOARD CONFIGURATION
# Financial analytics and revenue monitoring component
#################################################################
CFO_DASHBOARD_ENABLED=true
CFO_DASHBOARD_UPDATE_INTERVAL=300
CFO_DASHBOARD_CACHE_TTL=3600

# Revenue Settings
REVENUE_CALCULATION_TIMEZONE=America/Los_Angeles
REVENUE_FISCAL_YEAR_START=01-01
REVENUE_BUDGET_CURRENCY=USD

# Alert Configuration
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
# HOMEDOCS INTEGRATION
#################################################################
HOMEDOCS_URL=https://api.homedocs.com/

#################################################################
# PDF PROCESSING
#################################################################
PDF_TO_DOC_CLIENT_ID=your_production_aspose_client_id
PDF_TO_DOC_SECRET_KEY=your_production_aspose_secret_key

#################################################################
# ENHANCED SECURITY SETTINGS
# Additional enterprise security configuration
#################################################################
SOFTPRO_ENHANCED_SYNC=true
SOFTPRO_WEBHOOK_SECRET=your_production_webhook_secret
SOFTPRO_SYNC_INTERVAL=3600
DB_REVENUE_QUERIES_CACHE=true
DB_ANALYTICS_READ_REPLICA=false

#################################################################
# SSL/HTTPS CONFIGURATION
#################################################################
FORCE_HTTPS=true
SSL_CERTIFICATE_PATH=/etc/ssl/certs/pacificcoasttitle.crt
SSL_PRIVATE_KEY_PATH=/etc/ssl/private/pacificcoasttitle.key

#################################################################
# BACKUP CONFIGURATION
#################################################################
BACKUP_ENABLED=true
BACKUP_SCHEDULE=daily
BACKUP_S3_BUCKET=pct-production-backups
BACKUP_RETENTION_DAYS=90

#################################################################
# MONITORING & LOGGING
#################################################################
LOG_LEVEL=warning
LOG_MAX_FILES=10
LOG_MAX_SIZE=10MB
SENTRY_DSN=https://your-sentry-dsn@sentry.io/project-id

#################################################################
# RATE LIMITING CONFIGURATION
#################################################################
RATE_LIMIT_ENABLED=true
RATE_LIMIT_REQUESTS_PER_MINUTE=100
RATE_LIMIT_REQUESTS_PER_HOUR=2000
RATE_LIMIT_REQUESTS_PER_DAY=20000

#################################################################
# SESSION CONFIGURATION
#################################################################
SESSION_DRIVER=database
SESSION_LIFETIME=7200
SESSION_ENCRYPT=true
SESSION_DOMAIN=.pacificcoasttitle.com
SESSION_SECURE=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
```

---

## 🔧 TESTING ENVIRONMENT (.env)

### **Testing/Staging Environment**

```env
#################################################################
# TRANSACTION DESK TESTING ENVIRONMENT
# Updated: December 1, 2024
# Security Level: Enterprise
# Status: TESTING
#################################################################

#################################################################
# DATABASE CONFIGURATION
#################################################################
DB_HOST=localhost
DB_DATABASE=transaction_desk_test
DB_USERNAME=td_test_user
DB_PASSWORD=test_secure_password_123

#################################################################
# APPLICATION SETTINGS
#################################################################
APP_ENV=testing
APP_DEBUG=true
BASE_URL=http://test-transactiondesk.pacificcoasttitle.com/

#################################################################
# CRITICAL: TITLEPOINT API CONFIGURATION
# Using test credentials for non-production environment
#################################################################
TP_USERNAME=your_test_titlepoint_username
TP_PASSWORD=your_test_titlepoint_password
TP_REQUEST_SUMMARY_ENDPOINT=https://test-api.titlepoint.com/RequestSummary

#################################################################
# CRITICAL: SECURITY CONFIGURATION
# Test environment with security features enabled
#################################################################
ENCRYPTION_KEY=test_encryption_key_32_characters
SECURITY_ALERT_EMAIL=dev-security@pacificcoasttitle.com
SESSION_ENCRYPTION=true
CSRF_PROTECTION=true
RATE_LIMITING=true

#################################################################
# SOFTPRO/RESWARE CONFIGURATION
#################################################################
RESWARE_ORDER_API=https://test-softpro-server.com/api/

#################################################################
# AWS S3 CONFIGURATION
#################################################################
AWS_BUCKET=pct-test-documents
AWS_REGION=us-west-2
AWS_ACCESS_KEY_ID=your_test_aws_access_key
AWS_SECRET_ACCESS_KEY=your_test_aws_secret_key
AWS_PATH=https://pct-test-documents.s3.amazonaws.com/
AWS_ENABLE_FLAG=1

#################################################################
# EMAIL CONFIGURATION
#################################################################
FROM_EMAIL=test-noreply@pacificcoasttitle.com
SMTP_HOST=smtp-test.pacificcoasttitle.com
SMTP_PORT=587
SMTP_USER=test-noreply@pacificcoasttitle.com
SMTP_PASS=test_email_password

#################################################################
# CFO DASHBOARD CONFIGURATION
#################################################################
CFO_DASHBOARD_ENABLED=true
CFO_DASHBOARD_UPDATE_INTERVAL=60
CFO_DASHBOARD_CACHE_TTL=600
REVENUE_CALCULATION_TIMEZONE=America/Los_Angeles
REVENUE_FISCAL_YEAR_START=01-01
REVENUE_BUDGET_CURRENCY=USD
ALERTS_EMAIL_ENABLED=true
REPORTS_PDF_ENGINE=dompdf
REPORTS_EXCEL_ENGINE=phpspreadsheet
REPORTS_EXPORT_PATH=uploads/reports/finance/
DASHBOARD_ENABLE_CACHING=false
DASHBOARD_ENABLE_COMPRESSION=false
DASHBOARD_MAX_DATA_POINTS=500

#################################################################
# ENHANCED SECURITY SETTINGS
#################################################################
SOFTPRO_ENHANCED_SYNC=true
SOFTPRO_WEBHOOK_SECRET=test_webhook_secret_123
SOFTPRO_SYNC_INTERVAL=1800
DB_REVENUE_QUERIES_CACHE=false
DB_ANALYTICS_READ_REPLICA=false

#################################################################
# TESTING SPECIFIC SETTINGS
#################################################################
FORCE_HTTPS=false
LOG_LEVEL=debug
RATE_LIMIT_REQUESTS_PER_MINUTE=200
RATE_LIMIT_REQUESTS_PER_HOUR=5000
SESSION_DRIVER=file
SESSION_LIFETIME=3600
```

---

## 🔑 ENVIRONMENT VARIABLE REFERENCE

### **Database Variables**
| Variable | Required | Description | Example |
|----------|----------|-------------|---------|
| `DB_HOST` | Yes | Database server hostname | `localhost` |
| `DB_DATABASE` | Yes | Database name | `transaction_desk` |
| `DB_USERNAME` | Yes | Database username | `td_user` |
| `DB_PASSWORD` | Yes | Database password | `secure_password` |

### **🚨 Critical Security Variables**
| Variable | Required | Description | Example |
|----------|----------|-------------|---------|
| `TP_USERNAME` | **YES** | TitlePoint API username | `your_tp_username` |
| `TP_PASSWORD` | **YES** | TitlePoint API password | `your_tp_password` |
| `ENCRYPTION_KEY` | **YES** | 32-character encryption key | `a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6` |
| `SECURITY_ALERT_EMAIL` | **YES** | Security alerts recipient | `security@yourdomain.com` |

### **Application Variables**
| Variable | Required | Description | Example |
|----------|----------|-------------|---------|
| `APP_ENV` | Yes | Application environment | `production`, `testing` |
| `APP_DEBUG` | Yes | Debug mode | `true`, `false` |
| `BASE_URL` | Yes | Application base URL | `https://yourdomain.com/` |

### **AWS S3 Variables**
| Variable | Required | Description | Example |
|----------|----------|-------------|---------|
| `AWS_BUCKET` | Yes | S3 bucket name | `your-documents-bucket` |
| `AWS_REGION` | Yes | AWS region | `us-west-2` |
| `AWS_ACCESS_KEY_ID` | Yes | AWS access key | `AKIA...` |
| `AWS_SECRET_ACCESS_KEY` | Yes | AWS secret key | `abcd1234...` |

### **CFO Dashboard Variables**
| Variable | Required | Description | Default |
|----------|----------|-------------|---------|
| `CFO_DASHBOARD_ENABLED` | No | Enable CFO Dashboard | `true` |
| `CFO_DASHBOARD_UPDATE_INTERVAL` | No | Update interval (seconds) | `300` |
| `REVENUE_CALCULATION_TIMEZONE` | No | Timezone for calculations | `America/Los_Angeles` |
| `REVENUE_BUDGET_CURRENCY` | No | Currency for budget display | `USD` |

### **Security Feature Variables**
| Variable | Required | Description | Default |
|----------|----------|-------------|---------|
| `SESSION_ENCRYPTION` | No | Encrypt session data | `true` |
| `CSRF_PROTECTION` | No | Enable CSRF protection | `true` |
| `RATE_LIMITING` | No | Enable rate limiting | `true` |
| `FORCE_HTTPS` | No | Force HTTPS redirect | `true` |

---

## 🔧 ENVIRONMENT SETUP PROCEDURES

### **Step 1: Generate Secure Keys**

#### **Encryption Key Generation**
```bash
# Method 1: Using PHP
php -r "echo bin2hex(random_bytes(16)) . PHP_EOL;"

# Method 2: Using OpenSSL
openssl rand -hex 16

# Method 3: Using /dev/urandom (Linux)
head -c 16 /dev/urandom | xxd -p | tr -d '\n' && echo
```

#### **Webhook Secret Generation**
```bash
# Generate secure webhook secret
php -r "echo bin2hex(random_bytes(32)) . PHP_EOL;"
```

### **Step 2: Credential Verification**

#### **TitlePoint API Test**
```bash
# Test TitlePoint connectivity
curl -X POST "https://api.titlepoint.com/test" \
  -u "your_username:your_password" \
  -H "Content-Type: application/json"
```

#### **AWS S3 Test**
```bash
# Test S3 access
aws s3 ls s3://your-bucket-name/ \
  --profile your-aws-profile
```

### **Step 3: File Permissions**

```bash
# Set secure permissions for .env file
chmod 600 .env
chown www-data:www-data .env

# Verify permissions
ls -la .env
# Should show: -rw------- 1 www-data www-data
```

---

## 🚨 SECURITY REQUIREMENTS

### **Production Security Checklist**

#### **Required for Production:**
- [ ] Unique encryption keys (not default values)
- [ ] Strong database passwords (12+ characters)
- [ ] HTTPS enabled (`FORCE_HTTPS=true`)
- [ ] Debug mode disabled (`APP_DEBUG=false`)
- [ ] Secure session settings configured
- [ ] Security alerts configured
- [ ] Rate limiting enabled
- [ ] CSRF protection enabled

#### **Environment File Security:**
```bash
# Create secure backup
cp .env .env.backup.$(date +%Y%m%d_%H%M%S)

# Set restrictive permissions
chmod 600 .env

# Add to .gitignore
echo ".env" >> .gitignore
echo ".env.*" >> .gitignore
```

### **Key Rotation Schedule**
- **Encryption Keys**: Every 90 days
- **API Credentials**: Every 180 days
- **Database Passwords**: Every 365 days
- **Webhook Secrets**: Every 90 days

---

## 🔍 ENVIRONMENT VALIDATION

### **Validation Script**

Create `validate-env.php` for testing:

```php
<?php
/**
 * Environment Validation Script
 * Tests all required environment variables
 */

require_once 'vendor/autoload.php';

// Load environment
try {
    $dotenv = Dotenv\Dotenv::createImmutable('.');
    $dotenv->load();
    echo "✅ Environment file loaded successfully\n";
} catch (Exception $e) {
    echo "❌ Failed to load .env file: " . $e->getMessage() . "\n";
    exit(1);
}

// Required variables
$required = [
    'DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD',
    'TP_USERNAME', 'TP_PASSWORD', 'ENCRYPTION_KEY',
    'SECURITY_ALERT_EMAIL', 'BASE_URL'
];

$missing = [];
foreach ($required as $var) {
    if (empty($_ENV[$var])) {
        $missing[] = $var;
    } else {
        echo "✅ {$var}: SET\n";
    }
}

if (!empty($missing)) {
    echo "\n❌ Missing required variables:\n";
    foreach ($missing as $var) {
        echo "   - {$var}\n";
    }
    exit(1);
}

// Test database connection
try {
    $pdo = new PDO(
        "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_DATABASE']}",
        $_ENV['DB_USERNAME'],
        $_ENV['DB_PASSWORD']
    );
    echo "✅ Database connection: SUCCESS\n";
} catch (PDOException $e) {
    echo "❌ Database connection: FAILED - " . $e->getMessage() . "\n";
}

// Test encryption key length
if (strlen($_ENV['ENCRYPTION_KEY']) < 32) {
    echo "⚠️  WARNING: Encryption key should be 32+ characters\n";
} else {
    echo "✅ Encryption key: VALID LENGTH\n";
}

echo "\n🎉 Environment validation complete!\n";
?>
```

Run validation:
```bash
php validate-env.php
```

---

## 🆘 TROUBLESHOOTING

### **Common Issues**

#### **"TP_PASSWORD not found" Error**
```bash
# Check TitlePoint variables
grep -E "^TP_" .env
```
**Solution**: Ensure TP_USERNAME and TP_PASSWORD are in .env without quotes

#### **"Database connection failed" Error**
```bash
# Test database connection
mysql -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD $DB_DATABASE -e "SELECT 1;"
```
**Solution**: Verify database credentials and server accessibility

#### **"Permission denied" on .env**
```bash
# Fix file permissions
chmod 600 .env
chown www-data:www-data .env
```

#### **"ENCRYPTION_KEY too short" Warning**
```bash
# Generate new 32-character key
php -r "echo bin2hex(random_bytes(16)) . PHP_EOL;"
```

### **Emergency Recovery**

#### **Restore from Backup**
```bash
# If .env backup exists
cp .env.backup.YYYYMMDD_HHMMSS .env

# Or restore from template
cp .env.example .env
# Then edit with your actual values
```

#### **Quick Test Setup**
```bash
# Minimal working .env for testing
cat > .env << EOF
DB_HOST=localhost
DB_DATABASE=transaction_desk
DB_USERNAME=root
DB_PASSWORD=
TP_USERNAME=test
TP_PASSWORD=test123
ENCRYPTION_KEY=12345678901234567890123456789012
SECURITY_ALERT_EMAIL=admin@localhost
BASE_URL=http://localhost/transaction-desk/
APP_ENV=testing
APP_DEBUG=true
CFO_DASHBOARD_ENABLED=true
SESSION_ENCRYPTION=true
CSRF_PROTECTION=true
RATE_LIMITING=true
EOF
```

---

## 📞 SUPPORT CONTACTS

### **Environment Configuration Issues:**
- **Security Team**: security@pacificcoasttitle.com
- **DevOps Team**: devops@pacificcoasttitle.com
- **System Admin**: admin@pacificcoasttitle.com

### **External Service Support:**
- **TitlePoint**: support@titlepoint.com
- **AWS Support**: Your AWS support plan
- **SoftPro**: support@softprocorp.com

---

**⚠️ REMEMBER: The environment file contains sensitive credentials. Protect it like you would protect the keys to your building!**

**Last Updated:** December 1, 2024  
**Security Level:** Enterprise  
**Review Schedule:** Monthly
