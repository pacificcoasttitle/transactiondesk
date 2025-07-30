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

## 📁 Critical Directories

```
pacificcosttitile-pct-orders-4aee9c3edffd/
├── application/                 # Main CodeIgniter application
│   ├── modules/frontend/        # User-facing controllers/views
│   ├── modules/admin/          # Administrative interface
│   ├── libraries/order/        # Core business logic
│   └── config/                 # Application configuration
├── cpl/                        # CPL form processing (standalone)
├── js/                         # Frontend JavaScript
├── db/migrations/              # Database schema changes
└── uploads/                    # Temporary file storage
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

### 3. Admin Interface
- **File**: `application/modules/admin/controllers/order/Home.php`
- **Route**: `/order/admin`
- **Purpose**: Administrative order management

## 🔌 Key Integrations

### TitlePoint API
- **Purpose**: Property data gathering (tax, legal, recording info)
- **Files**: `application/libraries/order/Titlepoint.php`
- **Endpoints**: Tax search, legal vesting, property records

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

## 🔄 Order Workflow (Simplified)

1. **Input** → Form submission with property details
2. **API Calls** → TitlePoint property data gathering
3. **Processing** → Data validation and formatting
4. **SoftPro** → Order creation on remote server
5. **Documents** → PDF generation and AWS upload
6. **Notifications** → Email and system alerts

## ⚠ Important Notes

### Environment Variables
- TitlePoint credentials: `TP_USERNAME`, `TP_PASSWORD`
- AWS settings: `AWS_BUCKET`, `AWS_ACCESS_KEY_ID`
- SoftPro endpoint: `RESWARE_ORDER_API`

### Common Gotchas
- **File paths**: Mixed forward/backward slashes in Windows environment
- **API timeouts**: TitlePoint calls can take 20+ seconds
- **Memory usage**: Large PDF processing requires adequate PHP memory
- **Background jobs**: Document generation runs via exec() calls

### Security Considerations
- API credentials stored in `.env` file
- User authentication through session management
- File uploads limited by type and size
- Database prepared statements prevent SQL injection

## 🚀 Getting Started Checklist

### For AI Agents:
- [ ] Review order workflow in `docs/order-management-workflow.md`
- [ ] Understand API integrations in `docs/api-integration-guide.md`
- [ ] Check database relationships in `docs/database-schema.md`
- [ ] Identify unused files in `docs/unused-files-analysis.md`

### For Developers:
- [ ] Set up local environment with CodeIgniter 3
- [ ] Configure database connection in `application/config/database.php`
- [ ] Set environment variables for API access
- [ ] Test TitlePoint API connectivity
- [ ] Verify AWS S3 access for document storage

## 📞 Need Help?

- **Workflow Issues**: See `docs/order-management-workflow.md`
- **API Problems**: Check `docs/api-integration-guide.md`
- **Database Questions**: Review `docs/database-schema.md`
- **File Cleanup**: Use `docs/unused-files-analysis.md`
- **Configuration**: Check `docs/environment-configuration.md`

## 🔍 Quick Code Locations

**Finding specific functionality:**
```bash
# Order processing logic
application/modules/frontend/controllers/order/Home.php (lines 26-1850)

# TitlePoint API calls
application/libraries/order/Titlepoint.php

# SoftPro integration
application/libraries/order/Resware.php

# Document generation
application/libraries/order/Order.php

# Database models
application/modules/frontend/models/order/
```

This guide provides the essential foundation. Dive deeper into specific areas using the detailed documentation files.