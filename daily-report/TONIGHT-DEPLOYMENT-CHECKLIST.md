# 🚨 TONIGHT'S DEPLOYMENT - CRITICAL CHECKLIST

**FOR IMMEDIATE PRODUCTION DEPLOYMENT**

## ⏰ TIMELINE: 2-4 HOURS

---

## 🔴 STEP 1: BACKUP (15 minutes)
**CRITICAL - DO THIS FIRST**

```bash
# 1. Database backup
mysqldump -u root -p pct_database > backup_$(date +%Y%m%d_%H%M).sql

# 2. File backup
cp -r daily-report/ backup_daily_report_$(date +%Y%m%d_%H%M)/
```

---

## 🔴 STEP 2: UPLOAD FILES (10 minutes)

Upload these 4 files to your `daily-report/` folder:
- `R-14-Demo.html`
- `Escrow-Report-Demo.html` 
- `Title-Officer-Report-Demo.html`
- `sections.yml` (updated)
- `IMPLEMENTATION-GUIDE.md`

**Set permissions:**
```bash
chmod 644 daily-report/*.html
chmod 755 daily-report/
```

---

## 🔴 STEP 3: DATABASE SETUP (20 minutes)

Run these SQL commands:

```sql
-- Create escrow officers table
CREATE TABLE IF NOT EXISTS `escrow_officers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `branch_location` varchar(150) DEFAULT NULL,
  `closing_efficiency` decimal(5,2) DEFAULT 85.0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

-- Create title officers table  
CREATE TABLE IF NOT EXISTS `title_officers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `branch_location` varchar(150) DEFAULT NULL,
  `quality_rating` decimal(5,2) DEFAULT 90.0,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);

-- Insert real staff data
INSERT INTO `escrow_officers` (`first_name`, `last_name`, `branch_location`) VALUES
('Linda', 'Ruiz', 'Orange County Escrow'),
('Lisa', 'Lee', 'Orange County Escrow'),
('Hugo', 'Lopez', 'San Diego Escrow'),
('Kim', 'Buchok', 'Glendale Escrow');

INSERT INTO `title_officers` (`first_name`, `last_name`, `branch_location`) VALUES
('Clive', 'Virata', 'Orange County Title'),
('Jim', 'Jean', 'San Diego Title'),
('Rachel', 'Barcena', 'Glendale Title');
```

---

## 🔴 STEP 4: TEST REPORTS (15 minutes)

**Test each URL:**
1. `http://yourdomain.com/daily-report/R-14-Demo.html`
2. `http://yourdomain.com/daily-report/Escrow-Report-Demo.html`
3. `http://yourdomain.com/daily-report/Title-Officer-Report-Demo.html`

**Check:**
- [ ] Pages load without errors
- [ ] Navigation tabs work
- [ ] All Pacific Coast Title names appear
- [ ] Tables display properly
- [ ] Mobile view works

---

## 🔴 STEP 5: PYTHON SETUP (30 minutes)

**If using automated generation:**

```bash
# 1. Install packages
pip install sqlalchemy pandas jinja2 python-dotenv PyMySQL

# 2. Set environment variable
export SQLALCHEMY_URL="mysql://username:password@localhost:3306/pct_database"

# 3. Test report generation
cd daily-report/
python build_report.py
```

---

## 🔴 STEP 6: ADD TO NAVIGATION (20 minutes)

**Find your main menu file** (likely `application/views/layout/header.php`):

```php
<!-- Add these links to your admin menu -->
<li><a href="<?php echo base_url(); ?>daily-report/R-14-Demo.html">📋 R-14 Report</a></li>
<li><a href="<?php echo base_url(); ?>daily-report/Escrow-Report-Demo.html">🏦 Escrow Report</a></li>
<li><a href="<?php echo base_url(); ?>daily-report/Title-Officer-Report-Demo.html">📜 Title Officer Report</a></li>
```

---

## 🔴 STEP 7: FINAL TESTING (15 minutes)

- [ ] Click all navigation links
- [ ] Test on mobile device
- [ ] Verify real staff names appear
- [ ] Check all branch names are correct
- [ ] Confirm no broken links
- [ ] Test collapsible sections

---

## ⚠️ ROLLBACK PLAN (if issues)

```bash
# 1. Remove new files
rm daily-report/R-14-Demo.html
rm daily-report/Escrow-Report-Demo.html  
rm daily-report/Title-Officer-Report-Demo.html

# 2. Restore old sections.yml
cp backup_daily_report_*/sections.yml daily-report/

# 3. Remove database tables
DROP TABLE escrow_officers;
DROP TABLE title_officers;

# 4. Remove navigation links
```

---

## 📞 EMERGENCY CONTACTS

- **Technical Issues**: [Your Tech Lead]
- **Database Problems**: [Your DBA]
- **Server Issues**: [Your DevOps]

---

## ✅ SUCCESS CHECKLIST

**DEPLOYMENT IS SUCCESSFUL WHEN:**
- [ ] All 3 report URLs work
- [ ] Real Pacific Coast Title staff names display
- [ ] Navigation between reports works
- [ ] No errors in browser console
- [ ] Mobile access works
- [ ] Reports show actual branch locations:
  - Orange County Branch (Main Office)
  - San Diego Branch
  - Glendale Branch

---

## 🎯 EXPECTED RESULTS

**You should see:**
- **Sales Reps**: Angeline Ahn, Mike Johnson, David Gomez, Malay Wadhwa, Bethany Cummins, Edgar Rivas, Scott Smith
- **Escrow Officers**: Linda Ruiz, Lisa Lee, Hugo Lopez, Kim Buchok, Nelson Torres, Cibeli Tregembo
- **Title Officers**: Clive Virata, Jim Jean, Rachel Barcena, Rick Cervantez, Nick Watt, Richard Bohn
- **Branches**: Orange County, San Diego, Glendale

---

**🚀 TOTAL TIME: 2-4 hours maximum**

**📋 START TIME: ___:___ PM**
**📋 END TIME: ___:___ PM**

*Keep this checklist handy during deployment. Check off each item as completed.*
