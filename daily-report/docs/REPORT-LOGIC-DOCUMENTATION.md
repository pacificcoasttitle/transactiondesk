# Pacific Coast Title - Report Logic Documentation

> **Purpose**: Document the business logic for Branch Analytics, R-14, and Title Officers reports
> **Created**: December 2024
> **For**: Development Team Debugging and Implementation

---

## 📊 **Data Sources Overview**

### **Excel Sheets Available:**
1. **Openings Sheet** - New orders opened
2. **Revenue Sheet** - Revenue data 
3. **Closed Files Sheet** - Completed transactions

### **Target Reports:**
1. **Branch Analytics** - Performance by branch location
2. **R-14 Report** - Sales representative performance analysis
3. **Title Officers Report** - Title officer production tracking

---

## 🏗️ **OPENINGS DATA LOGIC**

### **Business Requirements for Openings:**

#### **Branch Analytics - Openings Component:**
- **Metric**: Count of new orders opened today by branch
- **Grouping**: By branch location and division (Title/Escrow)
- **Filters**: 
  - Date = Today's date
  - Status != 'cancelled'
  - Valid branch assignment

#### **R-14 Report - Openings Component:**
- **Metric**: New openings assigned to each sales representative
- **Grouping**: By sales rep name and branch
- **Additional Data Needed**:
  - Sales rep closing ratio calculation
  - Transaction type breakdown (Title Resale, Title Refi, Escrow)

#### **Title Officers Report - Openings Component:**
- **Metric**: New orders assigned to title officers
- **Grouping**: By title officer name and branch
- **Filters**:
  - Only title-related transactions
  - Active title officers only

---

## 📋 **OPENINGS DATA STRUCTURE ANALYSIS**

### **Actual Fields in Your Excel Files:**

#### **Openings.xlsx (7,127 rows):**
```
✅ CONFIRMED COLUMNS:
- Order Number (File Number)
- Received Date (Opening Date)
- Settlement Date (Expected Closing)
- Profile (Branch - e.g., "Glendale Title", "Orange Title")
- Transaction Type (Purchase, Refinance, Other)
- Order Type (Title only, Title & Escrow, etc.)
- Product Type (Full ALTA, Short Form, etc.)
- Sales Rep (52 unique sales reps)
- Title Officer (7 officers: Eddie LasMarias, Rachel Barcena, etc.)
- Escrow Officer (7 officers: Sally Settlement, Christine Quintanar, etc.)
- Marketing Source
- Main Contact
```

#### **ClosedOrders.xlsx (2,678 rows):**
```
✅ CONFIRMED COLUMNS:
- Order Number
- Escrow Closed Date (Actual Closing Date)
- Profile (Branch Location)
- Transaction Type
- Order Type
- Product Type
- Sales Rep (46 unique sales reps)
- Title Officer (5 officers)
- Escrow Officer (5 officers)
```

#### **Revenue.xlsx (967 rows):**
```
✅ CONFIRMED COLUMNS:
- Order Number
- Transaction Date, Received Date, Escrow Closed Date
- Profile (Branch)
- Title Branch Code (OCT, GLT)
- Escrow Branch Code (OCT, GLT, PRV, New, ONT)
- Title Officer, Escrow Officer
- Sales Rep (34 unique)
- Amount (Revenue Amount)
- Fee, Liability
- Full Address, City, County, State, Zip
```

### **SQL Logic Using Your Actual Excel Data:**

```sql
-- BRANCH ANALYTICS - Openings by Branch (Using Openings.xlsx)
SELECT 
    Profile as 'Branch',
    COUNT(*) as 'New Openings Today',
    COUNT(CASE WHEN `Transaction Type` = 'Purchase' THEN 1 END) as 'Purchase Orders',
    COUNT(CASE WHEN `Transaction Type` = 'Refinance' THEN 1 END) as 'Refinance Orders',
    COUNT(CASE WHEN `Order Type` = 'Title only' THEN 1 END) as 'Title Only',
    COUNT(CASE WHEN `Order Type` = 'Title & Escrow' THEN 1 END) as 'Title & Escrow',
    COUNT(CASE WHEN `Order Type` = 'Escrow only' THEN 1 END) as 'Escrow Only'
FROM openings_data 
WHERE DATE(`Received Date`) = CURDATE()
AND Profile IS NOT NULL
GROUP BY Profile
ORDER BY COUNT(*) DESC;

-- R-14 REPORT - Sales Rep Performance (Using Openings.xlsx + ClosedOrders.xlsx)
SELECT 
    o.`Sales Rep` as 'Sales Representative',
    o.Profile as 'Branch',
    COUNT(o.`Order Number`) as 'New Openings Today',
    COUNT(c.`Order Number`) as 'Closings Today',
    ROUND(COUNT(c.`Order Number`) / COUNT(o.`Order Number`) * 100, 1) as 'Closing Ratio %',
    COUNT(CASE WHEN o.`Transaction Type` = 'Purchase' THEN 1 END) as 'Purchase Openings',
    COUNT(CASE WHEN o.`Transaction Type` = 'Refinance' THEN 1 END) as 'Refinance Openings'
FROM openings_data o
LEFT JOIN closed_orders_data c ON o.`Order Number` = c.`Order Number` 
    AND DATE(c.`Escrow Closed Date`) = CURDATE()
WHERE DATE(o.`Received Date`) = CURDATE()
AND o.`Sales Rep` IS NOT NULL
GROUP BY o.`Sales Rep`, o.Profile
ORDER BY o.Profile, COUNT(o.`Order Number`) DESC;

-- TITLE OFFICERS - Performance Analysis (Using Openings.xlsx + Revenue.xlsx)
SELECT 
    o.`Title Officer` as 'Title Officer',
    o.Profile as 'Branch',
    COUNT(o.`Order Number`) as 'New Orders Today',
    COUNT(CASE WHEN o.`Transaction Type` = 'Purchase' THEN 1 END) as 'Purchase Today',
    COUNT(CASE WHEN o.`Transaction Type` = 'Refinance' THEN 1 END) as 'Refinance Today',
    COALESCE(SUM(r.Amount), 0) as 'Revenue Generated Today'
FROM openings_data o
LEFT JOIN revenue_data r ON o.`Order Number` = r.`Order Number` 
    AND DATE(r.`Transaction Date`) = CURDATE()
WHERE DATE(o.`Received Date`) = CURDATE()
AND o.`Title Officer` IS NOT NULL
AND o.Profile LIKE '%Title%'  -- Focus on Title branches
GROUP BY o.`Title Officer`, o.Profile
ORDER BY o.Profile, COUNT(o.`Order Number`) DESC;

-- REVENUE ANALYSIS - Daily Revenue by Branch (Using Revenue.xlsx)
SELECT 
    Profile as 'Branch',
    `Title Branch Code` as 'Branch Code',
    COUNT(DISTINCT `Order Number`) as 'Orders with Revenue',
    SUM(Amount) as 'Total Revenue Today',
    AVG(Amount) as 'Average Revenue per Transaction',
    COUNT(CASE WHEN `Transaction Type` = 'Purchase' THEN 1 END) as 'Purchase Revenue Items',
    COUNT(CASE WHEN `Transaction Type` = 'Refinance' THEN 1 END) as 'Refinance Revenue Items'
FROM revenue_data
WHERE DATE(`Transaction Date`) = CURDATE()
GROUP BY Profile, `Title Branch Code`
ORDER BY SUM(Amount) DESC;
```

---

## 🔍 **DATA VALIDATION CHECKLIST**

### **Before Building SQL Queries:**

#### **Openings Data Validation:**
- [ ] Confirm actual column names in Excel sheet
- [ ] Identify date format used (MM/DD/YYYY, YYYY-MM-DD, etc.)
- [ ] Verify branch location naming conventions
- [ ] Check transaction type values and standardization
- [ ] Confirm sales rep name format (First Last, Last First, ID numbers)
- [ ] Verify title officer assignment method
- [ ] Check for null/empty value handling needs

#### **Business Logic Validation:**
- [ ] Confirm "today" definition (business day vs calendar day)
- [ ] Verify cancelled order exclusion rules
- [ ] Confirm branch grouping requirements
- [ ] Validate transaction type categorization
- [ ] Check division assignment logic (Title vs Escrow)

---

## 🛠️ **IMPLEMENTATION STEPS**

### **Phase 1: Openings Analysis (Current)**
1. **Data Discovery**:
   - [ ] Examine actual Excel column structure
   - [ ] Document data types and formats
   - [ ] Identify data quality issues
   - [ ] Map Excel fields to database fields

2. **Logic Development**:
   - [ ] Create SQL queries for each report
   - [ ] Test with sample data
   - [ ] Validate business logic accuracy
   - [ ] Document any assumptions made

3. **Integration**:
   - [ ] Add queries to sections.yml
   - [ ] Test report generation
   - [ ] Verify output formatting
   - [ ] Debug any issues

### **Phase 2: Revenue Analysis (Next)**
- Revenue data structure analysis
- Revenue calculation logic
- Integration with openings data

### **Phase 3: Closed Files Analysis (Final)**
- Closed files data structure analysis  
- Closing metrics calculation
- Complete report integration

---

## 🚨 **DEBUGGING GUIDE FOR DEV TEAM**

### **Common Issues and Solutions:**

#### **Issue 1: No Data Showing**
**Check:**
- Date filtering logic (timezone issues)
- Column name mismatches
- Null value handling
- Status filtering criteria

**Debug SQL:**
```sql
-- Test basic data existence
SELECT COUNT(*) FROM openings_data WHERE DATE(created_date) = CURDATE();

-- Check date format
SELECT created_date, DATE(created_date) FROM openings_data LIMIT 5;

-- Verify column names
DESCRIBE openings_data;
```

#### **Issue 2: Incorrect Grouping**
**Check:**
- Branch location standardization
- Sales rep name consistency
- Division assignment logic

**Debug SQL:**
```sql
-- Check unique values
SELECT DISTINCT branch_location FROM openings_data;
SELECT DISTINCT division FROM openings_data;
SELECT DISTINCT transaction_type FROM openings_data;
```

#### **Issue 3: Performance Issues**
**Check:**
- Add indexes on date columns
- Add indexes on grouping columns
- Optimize WHERE clauses

**Recommended Indexes:**
```sql
CREATE INDEX idx_openings_date ON openings_data(created_date);
CREATE INDEX idx_openings_branch ON openings_data(branch_location);
CREATE INDEX idx_openings_sales_rep ON openings_data(sales_rep_name);
```

---

## 📝 **NEXT STEPS**

### **Immediate Actions Needed:**
1. **Provide Excel Column Structure**: Share the actual column names from your openings Excel sheet
2. **Sample Data**: Provide 2-3 sample rows (with sensitive data removed)
3. **Business Rules Clarification**: Confirm any specific business logic requirements
4. **Database Mapping**: Identify how Excel data maps to existing database tables

### **Development Workflow:**
1. Update this documentation with actual Excel structure
2. Create test SQL queries using sample data
3. Implement in sections.yml
4. Test report generation
5. Move to revenue data analysis
6. Complete with closed files analysis

---

## 📞 **Questions for Business Team**

1. **Date Handling**: Should "today" be current calendar date or current business date?
2. **Branch Grouping**: Are there specific branch location naming standards?
3. **Transaction Types**: What are all possible transaction type values?
4. **Sales Rep Assignment**: How are sales reps assigned to orders?
5. **Title Officer Assignment**: How are title officers assigned to orders?
6. **Cancelled Orders**: What statuses should be excluded from reports?

---

*This document will be updated as we analyze each data source and build the complete reporting logic.*
