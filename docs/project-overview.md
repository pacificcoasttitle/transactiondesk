# Project Overview - Transaction Desk

## 🏢 Business Context

**Pacific Coast Title Company Transaction Desk** is a comprehensive order management system designed to streamline title insurance and escrow processes. The system serves as the central hub for processing real estate transactions from initial order input through final document delivery.

## 🎯 Primary Functions

### Order Management
- **Order Input**: Web-based forms for title insurance orders
- **CPL Processing**: Commitment for Title Insurance document creation
- **File Management**: Comprehensive order tracking and status management
- **Document Generation**: Automated creation of title documents and reports

### Data Integration
- **Property Research**: Automated gathering of property information via TitlePoint API
- **Title Processing**: Integration with SoftPro/Resware industry-standard systems
- **Document Storage**: AWS S3 cloud storage for all generated documents
- **Third-party APIs**: Integration with various title industry services

### Workflow Automation
- **Background Processing**: Automated document generation and processing
- **Notification System**: Email alerts and in-system notifications
- **Status Tracking**: Real-time order progress monitoring
- **Audit Logging**: Comprehensive activity and API call logging

## 🏗 System Architecture

### Technical Stack
- **Framework**: CodeIgniter 3 (PHP) with enterprise security extensions
- **Database**: MySQL with migration support and security audit tables
- **Frontend**: jQuery, Bootstrap, custom JavaScript with CSRF protection
- **Cloud Services**: AWS S3 for encrypted document storage
- **External APIs**: TitlePoint, SoftPro/Resware, HomeDocs (secure credential management)
- **Security**: Enterprise-grade authentication, RBAC, real-time monitoring
- **Analytics**: CFO Dashboard with revenue forecasting and predictive analytics

### Application Structure
```
Transaction Desk System
├── Frontend Module (User Interface)
│   ├── Order Processing
│   ├── Document Management
│   ├── User Dashboard
│   └── Reporting Interface
├── Admin Module (Management Interface)
│   ├── Order Administration
│   ├── User Management
│   ├── System Configuration
│   └── Analytics & Reports
├── Core Libraries
│   ├── Order Processing Engine
│   ├── API Integration Layer
│   ├── Document Generation
│   └── Notification System
└── External Integrations
    ├── TitlePoint API
    ├── SoftPro/Resware
    ├── AWS Services
    └── Email Systems
```

## 🔄 Business Workflow

### 1. Order Initiation
- Customer or staff submits order through web interface
- System validates input and checks for duplicates
- Initial order record created with unique file number

### 2. Property Research Phase
- Automated TitlePoint API calls gather property data:
  - Tax assessment information
  - Legal ownership records
  - Chain of title documents
  - Recording information
- Data stored in local database for processing

### 3. Order Processing
- Property data formatted for SoftPro integration
- Order details transmitted to AWS-hosted SoftPro server
- Transaction officially created in industry-standard system

### 4. Document Generation
- Automated generation of title documents:
  - Preliminary title reports
  - Commitment for title insurance
  - Legal vesting reports
  - Supporting documentation
- All documents uploaded to AWS S3 for secure storage

### 5. Notification & Completion
- Stakeholders notified via email and system alerts
- Order status updated throughout process
- Documents made available for download/review
- Complete audit trail maintained

## 👥 User Roles & Access

### End Users (Customers/Agents)
- Submit new orders
- Track order progress
- Access completed documents
- Upload supporting materials

### Escrow Officers
- Manage assigned orders
- Review and approve documents
- Coordinate with other parties
- Handle special requirements

### Title Officers
- Review title research
- Approve title commitments
- Handle complex title issues
- Quality control oversight

### Administrators
- System configuration
- User management
- Order oversight
- Performance monitoring

## 🔌 Integration Ecosystem

### TitlePoint Services
- **Tax Information**: Property tax data and assessment details
- **Legal Vesting**: Ownership information and legal descriptions
- **Recording Services**: Document recording and chain of title
- **Geographic Data**: Property mapping and boundary information

### SoftPro/Resware Platform
- **Order Management**: Industry-standard transaction processing
- **Document Templates**: Standardized title insurance forms
- **Compliance**: Regulatory requirement management
- **Reporting**: Transaction analytics and reporting

### AWS Cloud Services
- **S3 Storage**: Secure document storage and retrieval
- **Scalability**: Elastic storage capacity
- **Backup**: Automated data protection
- **Access Control**: Role-based document security

## 📊 Data Management

### Order Information
- Property details and legal descriptions
- Buyer/seller information
- Loan and transaction details
- Timeline and milestone tracking

### Document Management
- PDF generation and storage
- Version control and document history
- Access permissions and security
- Automated backup and archival

### Audit & Compliance
- Complete API call logging
- User activity tracking
- Document access logs
- Regulatory compliance reporting

## 🔐 Security & Compliance

### **ENTERPRISE SECURITY IMPLEMENTED (December 2024)**
- ✅ **bcrypt password hashing** (eliminated MD5 vulnerability)
- ✅ **Environment-based credential management** (hardcoded secrets removed)
- ✅ **CSRF protection** on all forms and AJAX requests
- ✅ **Real-time security monitoring** with threat detection
- ✅ **Role-based access control** (RBAC) with granular permissions
- ✅ **Advanced file upload security** with virus scanning
- ✅ **API rate limiting** with multiple algorithms
- ✅ **Secure session management** with HTTPS-only cookies

### Data Protection
- **Multi-layer encryption**: API communications, file storage, session data
- **Secure cloud storage**: AWS S3 with encrypted document storage
- **Advanced access control**: RBAC system with 10 roles and 50+ permissions
- **Comprehensive security audits**: Real-time monitoring and alerting

### Industry Compliance
- **PCI DSS**: Secure payment and credential handling
- **GDPR**: Privacy protection with secure data processing
- **SOX**: Comprehensive audit trails and access controls
- **NIST**: Cybersecurity framework compliance
- **Title industry standards**: Regulatory requirement adherence

### **Security Features Active**
- **Authentication Security**: Account lockout, rate limiting, secure sessions
- **Input Validation**: XSS protection, SQL injection prevention
- **File Security**: Malicious content detection, virus scanning, quarantine
- **API Security**: Rate limiting, authentication tokens, request monitoring
- **Monitoring**: Real-time threat detection, security alerts, incident response

## 📈 Performance & Scalability

### System Capabilities
- Concurrent order processing
- High-volume document generation
- Scalable cloud storage
- Background job processing

### Monitoring & Optimization
- API performance tracking
- System resource monitoring
- Error detection and alerting
- Performance optimization

## 🔄 Maintenance & Updates

### Regular Maintenance
- Database optimization
- File cleanup and archival
- Security updates
- Performance tuning

### Feature Development
- New API integrations
- Enhanced document templates
- Improved user interfaces
- Expanded reporting capabilities

This overview provides the foundation for understanding the Transaction Desk system's role in Pacific Coast Title Company's operations and its technical implementation.