# Launch Setup Guide - Transaction Desk

## 🚀 Complete Server Setup for Testing Environment

This guide provides step-by-step instructions for deploying the Transaction Desk system on a separate server for testing purposes.

## 📋 Prerequisites & Requirements

### **Server Requirements**
- **Operating System**: Linux (Ubuntu 20.04+ recommended) or Windows Server
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **PHP**: Version 7.4+ (PHP 8.0+ recommended)
- **Database**: MySQL 5.7+ or MariaDB 10.3+
- **Memory**: Minimum 4GB RAM (8GB+ recommended)
- **Storage**: 20GB+ available space
- **Network**: Outbound HTTPS access for API calls

### **PHP Extensions Required**
```bash
# Essential PHP extensions
php-curl
php-gd
php-json
php-mbstring
php-mysql
php-xml
php-zip
php-openssl
php-fileinfo
php-tokenizer
```

### **External Service Access**
- **TitlePoint API**: Credentials and network access
- **AWS S3**: Bucket access and credentials
- **SoftPro/Resware**: API endpoint access
- **SMTP Server**: For email notifications

## 🔧 Step 1: Environment Setup

### **1.1 Install LAMP/LEMP Stack**

#### For Ubuntu/Debian:
```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install Apache, MySQL, PHP
sudo apt install apache2 mysql-server php8.1 php8.1-apache2 php8.1-mysql -y

# Install required PHP extensions
sudo apt install php8.1-curl php8.1-gd php8.1-json php8.1-mbstring \
                 php8.1-xml php8.1-zip php8.1-openssl php8.1-fileinfo \
                 php8.1-tokenizer -y

# Enable Apache modules
sudo a2enmod rewrite
sudo a2enmod ssl
sudo systemctl restart apache2
```

#### For CentOS/RHEL:
```bash
# Install EPEL repository
sudo yum install epel-release -y

# Install Apache, MySQL, PHP
sudo yum install httpd mariadb-server php php-mysql -y

# Install PHP extensions
sudo yum install php-curl php-gd php-json php-mbstring \
                 php-xml php-zip php-openssl -y

# Start services
sudo systemctl start httpd mariadb
sudo systemctl enable httpd mariadb
```

### **1.2 Configure PHP Settings**

Edit PHP configuration (`/etc/php/8.1/apache2/php.ini`):
```ini
# Memory and execution limits
memory_limit = 512M
max_execution_time = 300
max_input_time = 300
post_max_size = 64M
upload_max_filesize = 32M

# Error reporting (for testing environment)
display_errors = On
error_reporting = E_ALL & ~E_NOTICE

# Session settings
session.gc_maxlifetime = 7200
session.cookie_lifetime = 0

# Enable required extensions
extension=curl
extension=gd
extension=mbstring
extension=openssl
extension=fileinfo
```

## 🗄 Step 2: Database Setup

### **2.1 MySQL/MariaDB Configuration**

```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```

```sql
-- Create database
CREATE DATABASE transaction_desk CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create dedicated user
CREATE USER 'td_user'@'localhost' IDENTIFIED BY 'secure_password_here';

-- Grant permissions
GRANT ALL PRIVILEGES ON transaction_desk.* TO 'td_user'@'localhost';
FLUSH PRIVILEGES;

-- Verify database creation
SHOW DATABASES;
USE transaction_desk;
EXIT;
```

### **2.2 Database Performance Tuning**

Edit MySQL configuration (`/etc/mysql/mysql.conf.d/mysqld.cnf`):
```ini
[mysqld]
# Performance settings
innodb_buffer_pool_size = 1G
innodb_log_file_size = 256M
query_cache_type = 1
query_cache_size = 128M
max_connections = 200

# Character set
character-set-server = utf8mb4
collation-server = utf8mb4_unicode_ci

# Logging (for testing)
general_log = 1
general_log_file = /var/log/mysql/mysql.log
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow.log
long_query_time = 2
```

Restart MySQL:
```bash
sudo systemctl restart mysql
```

## 📁 Step 3: Code Deployment

### **3.1 Download and Extract Code**

```bash
# Navigate to web root
cd /var/www/html

# Clone or extract repository
# Option 1: Git clone
git clone [repository-url] transaction-desk

# Option 2: Extract archive
sudo tar -xzf transaction-desk.tar.gz
sudo mv pacificcosttitile-pct-orders-4aee9c3edffd transaction-desk

# Set ownership and permissions
sudo chown -R www-data:www-data transaction-desk/
sudo chmod -R 755 transaction-desk/
sudo chmod -R 777 transaction-desk/uploads/
sudo chmod -R 777 transaction-desk/cpl/uploads/
```

### **3.2 Directory Structure Verification**

Ensure the following structure exists:
```
/var/www/html/transaction-desk/
├── application/
├── assets/
├── cpl/
├── db/
├── js/
├── system/
├── uploads/
├── index.php
└── .env (to be created)
```

## ⚙️ Step 4: Configuration Setup

### **4.1 Environment Variables**

Create `.env` file in project root:
```bash
sudo nano /var/www/html/transaction-desk/.env
```

```env
# Database Configuration
DB_HOST=localhost
DB_DATABASE=transaction_desk
DB_USERNAME=td_user
DB_PASSWORD=secure_password_here

# Application Settings
APP_ENV=testing
APP_DEBUG=true
BASE_URL=http://your-server-domain.com/transaction-desk/

# TitlePoint API Configuration
TP_USERNAME=your_titlepoint_username
TP_PASSWORD=your_titlepoint_password
TP_REQUEST_SUMMARY_ENDPOINT=https://api.titlepoint.com/RequestSummary

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

# Security
ENCRYPTION_KEY=your_32_character_encryption_key_here
```

### **4.2 CodeIgniter Configuration**

#### Database Configuration
Edit `application/config/database.php`:
```php
<?php
$db['default'] = array(
    'dsn'    => '',
    'hostname' => env('DB_HOST'),
    'username' => env('DB_USERNAME'),
    'password' => env('DB_PASSWORD'),
    'database' => env('DB_DATABASE'),
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => env('APP_DEBUG'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8mb4',
    'dbcollat' => 'utf8mb4_unicode_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
```

#### Base URL Configuration
Edit `application/config/config.php`:
```php
$config['base_url'] = env('BASE_URL');
$config['encryption_key'] = env('ENCRYPTION_KEY');
$config['log_threshold'] = env('APP_DEBUG') ? 4 : 1;
```

### **4.3 CPL Module Configuration**

Edit `cpl/config/database.php`:
```php
<?php
$server = env('DB_HOST');
$username = env('DB_USERNAME');
$password = env('DB_PASSWORD');
$database = env('DB_DATABASE');

$connection = mysqli_connect($server, $username, $password, $database);
if (!$connection) {
    die('Connection failed: ' . mysqli_connect_error());
}
```

## 🔨 Step 5: Database Migration

### **5.1 Install Phinx (Migration Tool)**

```bash
# Install Composer if not already installed
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Navigate to project directory
cd /var/www/html/transaction-desk

# Install Phinx
composer require robmorgan/phinx
```

### **5.2 Configure Phinx**

Create `phinx.yml` in project root:
```yaml
paths:
    migrations: db/migrations
    seeds: db/seeds

environments:
    default_migration_table: phinxlog
    default_database: testing
    testing:
        adapter: mysql
        host: localhost
        name: transaction_desk
        user: td_user
        pass: 'secure_password_here'
        port: 3306
        charset: utf8mb4
```

### **5.3 Run Migrations**

```bash
# Run all migrations
./vendor/bin/phinx migrate -e testing

# Verify migration status
./vendor/bin/phinx status -e testing
```

## 🌐 Step 6: Web Server Configuration

### **6.1 Apache Virtual Host**

Create virtual host configuration:
```bash
sudo nano /etc/apache2/sites-available/transaction-desk.conf
```

```apache
<VirtualHost *:80>
    ServerName your-server-domain.com
    DocumentRoot /var/www/html/transaction-desk
    
    <Directory /var/www/html/transaction-desk>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    # Enable PHP
    <FilesMatch \.php$>
        SetHandler application/x-httpd-php
    </FilesMatch>
    
    # Logging
    ErrorLog ${APACHE_LOG_DIR}/transaction-desk-error.log
    CustomLog ${APACHE_LOG_DIR}/transaction-desk-access.log combined
</VirtualHost>
```

Enable the site:
```bash
sudo a2ensite transaction-desk.conf
sudo systemctl reload apache2
```

### **6.2 Nginx Configuration (Alternative)**

If using Nginx, create configuration:
```bash
sudo nano /etc/nginx/sites-available/transaction-desk
```

```nginx
server {
    listen 80;
    server_name your-server-domain.com;
    root /var/www/html/transaction-desk;
    index index.php index.html;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.ht {
        deny all;
    }
}
```

## 🔐 Step 7: Security Configuration

### **7.1 File Permissions**

```bash
# Set proper ownership
sudo chown -R www-data:www-data /var/www/html/transaction-desk/

# Set directory permissions
sudo find /var/www/html/transaction-desk/ -type d -exec chmod 755 {} \;

# Set file permissions
sudo find /var/www/html/transaction-desk/ -type f -exec chmod 644 {} \;

# Writable directories
sudo chmod -R 777 /var/www/html/transaction-desk/uploads/
sudo chmod -R 777 /var/www/html/transaction-desk/cpl/uploads/
sudo chmod 666 /var/www/html/transaction-desk/.env
```

### **7.2 Secure Environment File**

```bash
# Protect .env file
sudo chown root:www-data /var/www/html/transaction-desk/.env
sudo chmod 640 /var/www/html/transaction-desk/.env

# Add .htaccess protection
echo "deny from all" | sudo tee /var/www/html/transaction-desk/.htaccess
```

### **7.3 Firewall Configuration**

```bash
# Configure UFW firewall
sudo ufw allow 22    # SSH
sudo ufw allow 80    # HTTP
sudo ufw allow 443   # HTTPS
sudo ufw allow 3306  # MySQL (if needed for remote access)
sudo ufw enable
```

## 🔧 Step 8: External Service Setup

### **8.1 AWS S3 Test Bucket**

```bash
# Install AWS CLI
sudo apt install awscli -y

# Configure AWS credentials
aws configure
# Enter your AWS Access Key ID
# Enter your AWS Secret Access Key
# Enter your region (e.g., us-west-2)

# Create test bucket
aws s3 mb s3://your-test-transaction-desk-bucket

# Set bucket policy for web access
aws s3api put-bucket-policy --bucket your-test-transaction-desk-bucket --policy '{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Principal": "*",
      "Action": "s3:GetObject",
      "Resource": "arn:aws:s3:::your-test-transaction-desk-bucket/*"
    }
  ]
}'
```

### **8.2 Email Configuration Test**

```bash
# Install mail utilities for testing
sudo apt install mailutils -y

# Test SMTP configuration
echo "Test email from Transaction Desk" | mail -s "Test Subject" your-email@domain.com
```

## ✅ Step 9: Testing & Verification

### **9.1 Basic Connectivity Tests**

```bash
# Test database connection
mysql -u td_user -p transaction_desk -e "SELECT 1;"

# Test PHP
echo "<?php phpinfo(); ?>" | sudo tee /var/www/html/transaction-desk/test.php
```

### **9.2 Application Testing**

1. **Access Main Application**:
   ```
   http://your-server-domain.com/transaction-desk/
   ```

2. **Test CPL Module**:
   ```
   http://your-server-domain.com/transaction-desk/cpl/
   ```

3. **Test Admin Interface**:
   ```
   http://your-server-domain.com/transaction-desk/order/admin
   ```

### **9.3 API Connectivity Tests**

Create test script (`test-apis.php`):
```php
<?php
// Test TitlePoint API
$tp_test = curl_init();
curl_setopt($tp_test, CURLOPT_URL, env('TP_REQUEST_SUMMARY_ENDPOINT'));
curl_setopt($tp_test, CURLOPT_RETURNTRANSFER, true);
$tp_response = curl_exec($tp_test);
echo "TitlePoint API: " . (curl_getinfo($tp_test, CURLINFO_HTTP_CODE) == 200 ? "✅ Connected" : "❌ Failed") . "\n";
curl_close($tp_test);

// Test AWS S3
try {
    $s3 = new Aws\S3\S3Client([
        'region' => env('AWS_REGION'),
        'credentials' => [
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
        ],
    ]);
    $result = $s3->listObjects(['Bucket' => env('AWS_BUCKET')]);
    echo "AWS S3: ✅ Connected\n";
} catch (Exception $e) {
    echo "AWS S3: ❌ Failed - " . $e->getMessage() . "\n";
}
?>
```

## 🐛 Step 10: Troubleshooting

### **Common Issues & Solutions**

#### **Database Connection Issues**
```bash
# Check MySQL service
sudo systemctl status mysql

# Test connection manually
mysql -u td_user -p -h localhost transaction_desk

# Check error logs
sudo tail -f /var/log/mysql/error.log
```

#### **Permission Issues**
```bash
# Reset permissions
sudo chown -R www-data:www-data /var/www/html/transaction-desk/
sudo chmod -R 755 /var/www/html/transaction-desk/
sudo chmod -R 777 /var/www/html/transaction-desk/uploads/
```

#### **PHP Errors**
```bash
# Check PHP error log
sudo tail -f /var/log/apache2/error.log

# Test PHP configuration
php -m | grep -E '(curl|gd|mysql|openssl)'
```

#### **Apache/Nginx Issues**
```bash
# Check Apache status
sudo systemctl status apache2

# Test configuration
sudo apache2ctl configtest

# Check error logs
sudo tail -f /var/log/apache2/error.log
```

## 🔄 Step 11: Maintenance & Monitoring

### **11.1 Log Monitoring**

```bash
# Monitor application logs
sudo tail -f /var/log/apache2/transaction-desk-access.log
sudo tail -f /var/log/apache2/transaction-desk-error.log

# Monitor MySQL logs
sudo tail -f /var/log/mysql/mysql.log
sudo tail -f /var/log/mysql/slow.log
```

### **11.2 Backup Setup**

```bash
# Create backup script
sudo nano /usr/local/bin/backup-transaction-desk.sh
```

```bash
#!/bin/bash
BACKUP_DIR="/backup/transaction-desk"
DATE=$(date +%Y%m%d_%H%M%S)

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
mysqldump -u td_user -p'secure_password_here' transaction_desk > $BACKUP_DIR/db_$DATE.sql

# File backup
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/html/transaction-desk/

# AWS S3 backup (optional)
aws s3 sync /var/www/html/transaction-desk/uploads/ s3://your-backup-bucket/uploads_$DATE/

echo "Backup completed: $DATE"
```

Make executable and schedule:
```bash
sudo chmod +x /usr/local/bin/backup-transaction-desk.sh

# Add to crontab for daily backups
echo "0 2 * * * /usr/local/bin/backup-transaction-desk.sh" | sudo crontab -
```

## 📋 Deployment Checklist

### **Pre-Launch Verification**

- [ ] Web server (Apache/Nginx) running and configured
- [ ] PHP 7.4+ installed with all required extensions
- [ ] MySQL/MariaDB running with database created
- [ ] All environment variables configured in `.env`
- [ ] Database migrations completed successfully
- [ ] File permissions set correctly
- [ ] AWS S3 bucket created and accessible
- [ ] TitlePoint API credentials configured and tested
- [ ] SoftPro/Resware API endpoint accessible
- [ ] Email configuration tested
- [ ] Firewall configured for security
- [ ] SSL certificate installed (for production)
- [ ] Backup system configured
- [ ] Monitoring and logging enabled
- [ ] Application accessible via web browser
- [ ] CPL module functioning
- [ ] Admin interface accessible
- [ ] API connectivity verified

### **Performance Optimization**

- [ ] PHP OpCache enabled
- [ ] MySQL query cache enabled
- [ ] Apache/Nginx compression enabled
- [ ] Static file caching configured
- [ ] Database indexes optimized
- [ ] Log rotation configured

This comprehensive setup guide ensures a complete, secure, and functional testing environment for the Transaction Desk system. Follow each step carefully and verify functionality at each stage to ensure successful deployment.