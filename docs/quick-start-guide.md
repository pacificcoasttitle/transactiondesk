# Quick Start Guide - Transaction Desk

> **For AI Agents & New Developers**: This guide provides essential information to quickly understand and work with the Transaction Desk system.

## 🎯 System Purpose

**Transaction Desk** is a comprehensive order management system for Pacific Coast Title Company that:
- Processes title insurance orders and commitments
- Integrates with external APIs for property data
- Communicates with AWS-hosted SoftPro servers
- Manages document generation and storage
- Handles notifications and workflow automation

## 🏗 Architecture Overview

```
Frontend (CodeIgniter PHP) → TitlePoint API → SoftPro/Resware → AWS S3
                           ↓
                    MySQL Database ← Background Jobs
```

**Key Technologies:**
- **Backend**: PHP 7+ with CodeIgniter 3 Framework
- **Database**: MySQL with migrations
- **External APIs**: TitlePoint, SoftPro/Resware, AWS S3
- **Document Processing**: PDF generation, AWS S3 storage
- **Background Jobs**: PHP exec() for document generation
- **Security**: Enterprise-grade authentication, RBAC, rate limiting

## 📁 Critical Directories

```
pacificcosttitile-pct-orders-4aee9c3edffd/
├── application/                 # Main CodeIgniter application
│   ├── modules/frontend/        # User-facing controllers/views
│   ├── modules/admin/          # Administrative interface
│   ├── libraries/order/        # Core business logic
│   ├── libraries/security/     # NEW: Security components
│   └── config/                 # Application configuration
├── cpl/                        # CPL form processing (standalone)
│   ├── php/admin/              # UPDATED: Secure admin interface
│   └── php/admin/security/     # NEW: Security libraries
├── js/                         # Frontend JavaScript
├── db/migrations/              # Database schema changes
├── uploads/                    # Temporary file storage
├── logs/                       # NEW: Security and system logs
└── CFO-Dashboard/              # NEW: CFO Dashboard component
```

## 🔑 Core Entry Points

### 1. Main Order Processing
- **File**: `application/modules/frontend/controllers/order/Home.php`
- **Route**: `/order`
- **Purpose**: Primary order input and processing

### 2. CPL Form Processing
- **File**: `cpl/index.php` → `cpl/php/smartprocess2.php`
- **Route**: `/cpl/`
- **Purpose**: Commitment for Title Insurance forms

### 3. **NEW: Secure Admin Interface**
- **File**: `cpl/php/admin/secure_login.php` (REPLACES old login.php)
- **Route**: `/cpl/php/admin/secure_login.php`
- **Purpose**: Enterprise-secure administrative access

### 4. **NEW: CFO Dashboard**
- **File**: `CFO-Dashboard/application/controllers/admin/finance/CfoDashboard.php`
- **Route**: `/admin/finance/cfo-dashboard`
- **Purpose**: Financial oversight and revenue analytics

## 🔌 Key Integrations

### TitlePoint API
- **Purpose**: Property data gathering (tax, legal, recording info)
- **Files**: `application/libraries/order/Titlepoint.php`
- **Endpoints**: Tax search, legal vesting, property records
- **⚠️ SECURITY UPDATE**: Credentials moved to environment variables

### SoftPro/Resware Server
- **Purpose**: Title industry standard order management
- **Files**: `application/libraries/order/Resware.php`
- **Integration**: AWS-hosted SoftPro server communication

### AWS S3
- **Purpose**: Document storage and management
- **Files**: `application/libraries/order/Order.php::uploadDocumentOnAwsS3()`
- **Usage**: PDF storage, document retrieval

## 🗄 Database Essentials

**Primary Tables:**
- `order_details` - Main order information
- `pct_order_title_point_data` - TitlePoint API data
- `pct_api_logs` - API call logging
- `pct_order_notifications` - User notifications

**NEW Security Tables:**
- `security_events` - Security monitoring and incidents
- `user_roles` - Role-based access control
- `user_role_assignments` - User permission assignments
- `rate_limit_requests` - API rate limiting tracking
- `csrf_tokens` - CSRF protection tokens

## 🔄 Order Workflow (Simplified)

1. **Input** → Form submission with property details
2. **API Calls** → TitlePoint property data gathering
3. **Processing** → Data validation and formatting
4. **SoftPro** → Order creation on remote server
5. **Documents** → PDF generation and AWS upload
6. **Notifications** → Email and system alerts

## ⚠ Important Notes

### **🚨 CRITICAL: Environment Variables Update Required**

Your `.env` file MUST be updated with these critical variables:

```env
# CRITICAL: TitlePoint API (hardcoded credentials removed from source)
TP_USERNAME=your_titlepoint_username
TP_PASSWORD=your_secure_titlepoint_password
TP_REQUEST_SUMMARY_ENDPOINT=https://api.titlepoint.com/RequestSummary

# CRITICAL: Security Configuration
ENCRYPTION_KEY=your_32_character_encryption_key_here
SECURITY_ALERT_EMAIL=security@pacificcoasttitle.com

# Database Configuration
DB_HOST=localhost
DB_DATABASE=transaction_desk
DB_USERNAME=td_user
DB_PASSWORD=secure_password_here

# AWS S3 Configuration
AWS_BUCKET=your-secure-s3-bucket
AWS_REGION=us-west-2
AWS_ACCESS_KEY_ID=your_aws_access_key
AWS_SECRET_ACCESS_KEY=your_aws_secret_key

# CFO Dashboard Security
CFO_DASHBOARD_ENABLED=true
CFO_DASHBOARD_UPDATE_INTERVAL=300

# Security Features
SESSION_ENCRYPTION=true
CSRF_PROTECTION=true
RATE_LIMITING=true
```

### **🔐 Security Updates Implemented**

- ✅ **MD5 password hashing ELIMINATED** (replaced with bcrypt)
- ✅ **Hardcoded API credentials REMOVED** (moved to environment variables)
- ✅ **Enterprise security libraries** added
- ✅ **CSRF protection** on all forms
- ✅ **Rate limiting** for API endpoints
- ✅ **Role-based access control** (RBAC)
- ✅ **Real-time security monitoring**

### Common Gotchas
- **File paths**: Mixed forward/backward slashes in Windows environment
- **API timeouts**: TitlePoint calls can take 20+ seconds
- **Memory usage**: Large PDF processing requires adequate PHP memory
- **Background jobs**: Document generation runs via exec() calls
- **⚠️ OLD LOGIN SYSTEM**: Replace `cpl/php/admin/login.php` with `secure_login.php`

### **🛡️ Security Features Now Active**

- **Authentication**: bcrypt hashing, account lockout, rate limiting
- **Input Validation**: XSS protection, SQL injection prevention
- **File Uploads**: Virus scanning, malicious content detection
- **Sessions**: Secure cookies, IP validation, CSRF protection
- **Monitoring**: Real-time threat detection, security alerts
- **Access Control**: Role-based permissions, granular access

## 🚀 Getting Started Checklist

### For AI Agents:
- [ ] Review order workflow in `docs/order-management-workflow.md`
- [ ] Understand API integrations in `docs/api-integration-guide.md`
- [ ] **NEW**: Check security implementation in `SECURITY-IMPLEMENTATION-GUIDE.md`
- [ ] **NEW**: Review CFO Dashboard in `CFO-Dashboard/README.md`
- [ ] Check database relationships in `docs/database-schema.md`
- [ ] Identify unused files in `docs/unused-files-analysis.md`

### For Developers:
- [ ] Set up local environment with CodeIgniter 3
- [ ] **CRITICAL**: Update `.env` file with all new variables
- [ ] Configure database connection in `application/config/database.php`
- [ ] **NEW**: Test secure login at `/cpl/php/admin/secure_login.php`
- [ ] Test TitlePoint API connectivity (now using environment variables)
- [ ] Verify AWS S3 access for document storage
- [ ] **NEW**: Test security features (rate limiting, CSRF protection)
- [ ] **NEW**: Access CFO Dashboard with proper permissions

## 📞 Need Help?

- **Workflow Issues**: See `docs/order-management-workflow.md`
- **API Problems**: Check `docs/api-integration-guide.md`
- **Database Questions**: Review `docs/database-schema.md`
- **File Cleanup**: Use `docs/unused-files-analysis.md`
- **Configuration**: Check `docs/environment-configuration.md`
- **⚠️ SECURITY**: See `SECURITY-IMPLEMENTATION-GUIDE.md`
- **⚠️ CFO Dashboard**: Check `CFO-Dashboard/README.md`

## 🔍 Quick Code Locations

**Finding specific functionality:**
```bash
# Order processing logic
application/modules/frontend/controllers/order/Home.php (lines 26-1850)

# TitlePoint API calls (UPDATED: now uses environment variables)
application/libraries/order/Titlepoint.php

# SoftPro integration
application/libraries/order/Resware.php

# Document generation
application/libraries/order/Order.php

# NEW: Security libraries
application/libraries/security/SecurityValidator.php
application/libraries/security/RoleBasedAuth.php
application/libraries/security/CSRFProtection.php

# NEW: Secure authentication
cpl/php/admin/secure_login.php (REPLACES login.php)

# NEW: CFO Dashboard
CFO-Dashboard/application/controllers/admin/finance/CfoDashboard.php

# Database models
application/modules/frontend/models/order/
```

## 🚨 CRITICAL DEPLOYMENT NOTES

### **1. Environment File Update (REQUIRED)**
- Your existing `.env` file MUST be updated with new security variables
- TitlePoint credentials have been removed from source code
- Missing environment variables will cause system failures

### **2. Security System Activation**
- New secure login system replaces vulnerable MD5 authentication
- All forms now include CSRF protection
- Rate limiting is active on API endpoints
- Security monitoring logs all activities

### **3. CFO Dashboard Available**
- New financial dashboard with real-time revenue analytics
- Role-based access control determines visibility
- Requires proper user permissions to access

### **4. Database Schema Updates**
- New security-related tables will be created automatically
- Existing data remains untouched
- Migration scripts handle all database changes

This guide provides the essential foundation with critical security updates. **Your system security has been transformed from CRITICAL RISK to ENTERPRISE SECURE.** Dive deeper into specific areas using the detailed documentation files.