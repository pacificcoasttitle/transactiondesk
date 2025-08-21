# Data Lineage - Complete Data Flow Documentation

## 🔄 **DATA FLOW OVERVIEW**

```
Excel Files → Python Processing → HTML Reports
     ↓              ↓                ↓
Source Data → Calculations → Display
```

---

## 📊 **SOURCE DATA FILES**

### **1. Openings.xlsx**
**Purpose**: Transaction openings/new business
**Key Columns**:
- `Order Number` (Unique Identifier) 
- `Profile` (Column B - Branch Assignment)
- `Received Date` (Date Filter)
- `Transaction Type` (Purchase/Refinance/Other)
- `Sales Rep` (Rep Assignment)
- `Title Officer` (Officer Assignment)

**Sample Row**:
```
Order Number: 2025-08-001
Profile: Glendale Title
Received Date: 2025-08-15
Transaction Type: Purchase
Sales Rep: Lopez Team
Title Officer: Rachel Barcena
```

### **2. ClosedOrders.xlsx**
**Purpose**: Completed transactions
**Key Columns**:
- `Order Number` (Unique Identifier)
- `Profile` (Column B - Branch Assignment) 
- `Escrow Closed Date` (Date Filter)
- `Transaction Type` (Purchase/Refinance/Other)
- `Sales Rep` (Rep Assignment)
- `Title Officer` (Officer Assignment)

**Sample Row**:
```
Order Number: 2025-08-001
Profile: Glendale Title  
Escrow Closed Date: 2025-08-25
Transaction Type: Purchase
Sales Rep: Lopez Team
Title Officer: Rachel Barcena
```

### **3. Revenue.xlsx**
**Purpose**: Financial transactions and fees
**Key Columns**:
- `Order Number` (Unique Identifier)
- `Profile` (Column B - Branch Assignment)
- `Transaction Date` (Date Filter)
- `Amount` (Revenue Value)
- `Transaction Type` (Purchase/Refinance/Other)
- `Sales Rep` (Rep Assignment)
- `Title Officer` (Officer Assignment)

**Sample Row**:
```
Order Number: 2025-08-001
Profile: Glendale Title
Transaction Date: 2025-08-25
Amount: 1,250.00
Transaction Type: Purchase
Sales Rep: Lopez Team
Title Officer: Rachel Barcena
```

### **4. SalesRepMapping.xlsx**
**Purpose**: Sales rep to branch assignments
**Columns**:
- `Sales Rep` (Rep Name)
- `Home Branch` (Primary Branch Assignment)

**Sample Rows**:
```
Sales Rep: Lopez Team → Home Branch: Glendale
Sales Rep: Jim Jean → Home Branch: Orange
Sales Rep: Sandra Millar → Home Branch: Orange
```

### **5. TitleOfficerMapping.xlsx**
**Purpose**: Title officer to branch assignments
**Columns**:
- `Title Officer` (Officer Name)
- `Branch` (Primary Branch Assignment)

**Sample Rows**:
```
Title Officer: Rachel Barcena → Branch: Glendale
Title Officer: Jim Jean → Branch: Orange
Title Officer: Clive Virata → Branch: Orange
```

---

## 🔄 **DATA PROCESSING FLOW**

### **Step 1: Data Loading**
```python
# Load Excel files
openings_df = pd.read_excel('Openings.xlsx')
closed_df = pd.read_excel('ClosedOrders.xlsx') 
revenue_df = pd.read_excel('Revenue.xlsx')
sales_mapping_df = pd.read_excel('SalesRepMapping.xlsx')
title_mapping_df = pd.read_excel('TitleOfficerMapping.xlsx')
```

### **Step 2: Date Conversion**
```python
# Convert date columns to datetime
openings_df['Received Date'] = pd.to_datetime(openings_df['Received Date'])
closed_df['Escrow Closed Date'] = pd.to_datetime(closed_df['Escrow Closed Date'])
revenue_df['Transaction Date'] = pd.to_datetime(revenue_df['Transaction Date'])
```

### **Step 3: Date Filtering**
```python
# Filter to August 2025
august_openings = openings_df[
    (openings_df['Received Date'].dt.month == 8) & 
    (openings_df['Received Date'].dt.year == 2025)
]

august_closings = closed_df[
    (closed_df['Escrow Closed Date'].dt.month == 8) & 
    (closed_df['Escrow Closed Date'].dt.year == 2025)
]

august_revenue = revenue_df[
    (revenue_df['Transaction Date'].dt.month == 8) & 
    (revenue_df['Transaction Date'].dt.year == 2025)
]
```

### **Step 4: Deduplication**
```python
# Remove duplicate Order Numbers
august_openings = august_openings.drop_duplicates(subset=['Order Number'])
august_closings = august_closings.drop_duplicates(subset=['Order Number'])
august_revenue = august_revenue.drop_duplicates(subset=['Order Number'])
```

### **Step 5: Branch Mapping**
```python
# Define branch to profile mapping
branch_profiles = {
    'Glendale': ['Glendale Title', 'Glendale Escrow'],
    'Orange': ['Orange Title', 'Orange Escrow'], 
    'Inland Empire': ['Inland Empire Escrow'],
    'Porterville': ['Porterville Escrow'],
    'TSG': ['TSG'],
    'Production': ['Production\\Payoff']
}
```

---

## 📊 **BRANCH ANALYTICS DATA FLOW**

### **Input Processing**
```python
# For each branch, filter data by profile
glendale_openings = august_openings[
    august_openings['Profile'].isin(['Glendale Title', 'Glendale Escrow'])
]

glendale_revenue = august_revenue[
    august_revenue['Profile'].isin(['Glendale Title', 'Glendale Escrow'])
]
```

### **Service Type Breakdown**
```python
# Escrow transactions
escrow_openings = glendale_openings[
    glendale_openings['Profile'] == 'Glendale Escrow'
]

# Title Resale transactions  
title_resale_openings = glendale_openings[
    (glendale_openings['Profile'] == 'Glendale Title') &
    (glendale_openings['Transaction Type'] == 'Purchase')
]

# Title Refinance transactions
title_refi_openings = glendale_openings[
    (glendale_openings['Profile'] == 'Glendale Title') &
    (glendale_openings['Transaction Type'] == 'Refinance')
]
```

### **Calculation Examples**
```python
# Count calculations
escrow_count = len(escrow_openings)
title_resale_count = len(title_resale_openings)
title_refi_count = len(title_refi_openings)

# Revenue calculations
escrow_revenue = glendale_revenue[
    glendale_revenue['Profile'] == 'Glendale Escrow'
]['Amount'].sum()

title_resale_revenue = glendale_revenue[
    (glendale_revenue['Profile'] == 'Glendale Title') &
    (glendale_revenue['Transaction Type'] == 'Purchase')
]['Amount'].sum()
```

---

## 📋 **R-14 DATA FLOW**

### **Sales Rep Assignment**
```python
# Step 1: Primary assignment from mapping
for rep_name, assigned_branch in sales_rep_mapping.items():
    # Add rep to their mapped branch
    branch_reps[assigned_branch][rep_name] = calculate_performance(rep_name, assigned_branch)

# Step 2: Secondary assignment from transactions
for rep_name in all_transaction_reps:
    for branch_name in all_branches:
        if has_transactions_in_branch(rep_name, branch_name):
            # Add rep to additional branch
            branch_reps[branch_name][rep_name] = calculate_performance(rep_name, branch_name)
```

### **4-Month Closing Ratio**
```python
# Get 4-month date range
end_date = pd.to_datetime('2025-08-31')
start_date = end_date - pd.DateOffset(months=4)  # 2025-04-30

# Filter 4-month data
four_month_openings = openings_df[
    (openings_df['Received Date'] >= start_date) & 
    (openings_df['Received Date'] <= end_date)
]

four_month_closings = closed_df[
    (closed_df['Escrow Closed Date'] >= start_date) & 
    (closed_df['Escrow Closed Date'] <= end_date)
]

# Calculate ratio for each rep
rep_4m_openings = four_month_openings[
    (four_month_openings['Sales Rep'] == rep_name) & 
    (four_month_openings['Profile'].isin(branch_profiles))
]

rep_4m_closings = four_month_closings[
    (four_month_closings['Sales Rep'] == rep_name) & 
    (four_month_closings['Profile'].isin(branch_profiles))
]

closing_ratio = (len(rep_4m_closings) / len(rep_4m_openings) * 100) if len(rep_4m_openings) > 0 else 0
```

---

## 📜 **TITLE OFFICER DATA FLOW**

### **Officer Assignment (Mapping Only)**
```python
# ONLY use mapping file - no cross-branch logic
for officer_name, assigned_branch in title_officer_mapping.items():
    # Calculate performance ONLY in assigned branch
    performance = calculate_officer_performance(officer_name, assigned_branch)
    branch_officers[assigned_branch][officer_name] = performance
```

### **Performance Calculation**
```python
# Get officer's transactions in assigned branch only
officer_closings = august_closings[
    (august_closings['Title Officer'] == officer_name) & 
    (august_closings['Profile'].isin(assigned_branch_profiles))
]

officer_revenue = august_revenue[
    (august_revenue['Title Officer'] == officer_name) & 
    (august_revenue['Profile'].isin(assigned_branch_profiles))
]

# Calculate metrics
total_policies = len(officer_closings)
total_revenue = officer_revenue['Amount'].sum()

# Break down by transaction type
resale_policies = len(officer_closings[officer_closings['Transaction Type'] == 'Purchase'])
refi_policies = len(officer_closings[officer_closings['Transaction Type'] == 'Refinance'])
commercial_policies = len(officer_closings[~officer_closings['Transaction Type'].isin(['Purchase', 'Refinance'])])
```

---

## 🔍 **DATA VALIDATION POINTS**

### **Critical Validation Checks**
1. **Order Number Uniqueness**:
   ```python
   # Check for duplicates before processing
   duplicates = df[df.duplicated(subset=['Order Number'], keep=False)]
   if len(duplicates) > 0:
       print(f"WARNING: {len(duplicates)} duplicate Order Numbers found")
   ```

2. **Date Range Validation**:
   ```python
   # Verify date filters
   min_date = df['Date_Column'].min()
   max_date = df['Date_Column'].max()
   print(f"Date range: {min_date} to {max_date}")
   ```

3. **Profile Mapping Validation**:
   ```python
   # Check for unmapped profiles
   all_profiles = df['Profile'].unique()
   mapped_profiles = [p for profiles in branch_profiles.values() for p in profiles]
   unmapped = set(all_profiles) - set(mapped_profiles)
   if unmapped:
       print(f"WARNING: Unmapped profiles: {unmapped}")
   ```

4. **Revenue Validation**:
   ```python
   # Check for negative or zero revenues where unexpected
   negative_revenue = df[df['Amount'] < 0]
   zero_revenue = df[df['Amount'] == 0]
   ```

### **Cross-Report Validation**
```python
# Verify same Order Numbers appear across files
openings_orders = set(openings_df['Order Number'])
closings_orders = set(closings_df['Order Number'])
revenue_orders = set(revenue_df['Order Number'])

# Check for orders that opened but didn't close
unclosed_orders = openings_orders - closings_orders

# Check for revenue without corresponding transactions
orphaned_revenue = revenue_orders - (openings_orders | closings_orders)
```

---

## 📈 **OUTPUT GENERATION**

### **HTML Report Creation**
```python
# Generate HTML with calculated values
html_content = f"""
<td class="number">{escrow_count}</td>
<td class="currency">${escrow_revenue:,.0f}</td>
<td class="percentage">{closing_ratio:.1f}%</td>
"""

# Write to file
with open('Report-Name.html', 'w', encoding='utf-8') as f:
    f.write(html_content)
```

### **Data Traceability**
Each number in the HTML can be traced back through:
1. **HTML Element** → Shows the displayed value
2. **Python Variable** → Shows the calculation
3. **DataFrame Filter** → Shows the data selection
4. **Excel Source** → Shows the original data

**Example Trace**:
```
HTML: <td class="number">27</td>
Python: escrow_count = len(glendale_escrow_openings)
Filter: glendale_escrow_openings = august_openings[august_openings['Profile'] == 'Glendale Escrow']
Source: Openings.xlsx, rows where Profile = 'Glendale Escrow' AND Received Date in August 2025
```

---

## 🛠️ **DEBUGGING WORKFLOW**

### **When Numbers Don't Match**
1. **Check Source Data**: Verify Excel files haven't changed
2. **Check Date Filters**: Confirm month/year selections
3. **Check Deduplication**: Verify Order Numbers are unique
4. **Check Profile Mapping**: Confirm Column B values
5. **Check Calculations**: Trace through Python logic step by step

### **Validation Queries**
```python
# Quick validation checks
print(f"Total unique openings: {len(august_openings)}")
print(f"Total unique closings: {len(august_closings)}")
print(f"Total revenue records: {len(august_revenue)}")
print(f"Date range: {august_openings['Received Date'].min()} to {august_openings['Received Date'].max()}")
print(f"Profiles found: {sorted(august_openings['Profile'].unique())}")
```

---

**📋 This document provides complete data lineage from Excel source files through Python processing to HTML output. Every number can be traced back to its source with full transparency.**
