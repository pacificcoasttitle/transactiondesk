# Daily Report Module for Pacific Coast Title

Generate automated daily executive reports covering openings, closings, and revenue across all branches and divisions.

## Overview

This module creates comprehensive daily reports for company executives showing:
- **New Openings**: Orders opened today by division and branch
- **Closings Completed**: Orders closed today with revenue data
- **Executive Summary**: Key performance indicators and metrics
- **Branch Performance**: Revenue breakdown by location and division

## Quick Start

1. **Setup Environment**:
   ```bash
   cd daily-report
   cp env.example .env
   # Edit .env with your database credentials
   ```

2. **Install Dependencies**:
   ```bash
   pip install -r requirements.txt
   ```

3. **Configure Database Connection**:
   Edit `.env` file with your database connection string:
   ```
   SQLALCHEMY_URL=mysql+pymysql://username:password@localhost:3306/database_name
   ```

4. **Run Report**:
   ```bash
   python build_report.py
   ```

## File Structure

```
daily-report/
├── build_report.py          # Main report generator
├── requirements.txt         # Python dependencies
├── env.example             # Environment template
├── sections.yml            # Report configuration & SQL queries
├── transforms.py           # Data transformation functions
├── ARCHITECT.md           # Development rules
├── Daily-Report-README.md # This file
└── templates/
    ├── report.html        # Main HTML template
    └── _macros.html       # Table rendering macros
```

## Configuration

### Environment Variables (.env)

- `SQLALCHEMY_URL`: Database connection string
- `OUTPUT_HTML`: Output filename (default: DailyReport.html)
- `DAY_BANNER`: Banner text for report header

### Report Sections (sections.yml)

The report is configured via `sections.yml` which defines:
- **Meta information**: Title, banner settings
- **Sections**: Report sections (Title Division, Escrow Division, etc.)
- **Subtables**: Individual data tables with SQL queries
- **Transforms**: Data processing functions

## Database Schema

The module expects these main tables:
- `pct_order_details`: Order information
- `customer_basic_details`: Customer information
- `sales_reps`: Sales representative data with division/branch info

Key fields used:
- `file_number`, `full_address`, `sales_price`
- `transaction_type`, `created_at`, `closing_date`
- `status`, `customer_id`, `sales_rep`
- `division`, `branch_location`

## Report Sections

### 1. Title Division
- New openings for title orders
- Completed closings for title orders
- Filtered by `sr.division = 'Title'`

### 2. Escrow Division  
- New openings for escrow orders
- Completed closings for escrow orders
- Filtered by `sr.division = 'Escrow'`

### 3. Executive Summary
- Total openings today
- Total closings today
- Total revenue today
- Average transaction value

### 4. Branch Performance
- Revenue breakdown by branch location
- Division-specific performance
- Sorted by highest revenue

## Customization

### Adding New Sections

Edit `sections.yml` to add new sections:

```yaml
sections:
  - name: 'New Section'
    subtables:
      - id: 'new_table'
        title: 'New Table Title'
        sql: |
          SELECT column1, column2 
          FROM your_table 
          WHERE your_conditions;
        transforms: []
```

### Adding Data Transformations

Add new transform functions to `transforms.py`:

```python
def your_transform(df: pd.DataFrame) -> pd.DataFrame:
    # Your transformation logic
    return df

TRANSFORMS = {
    'drop_pii': drop_pii,
    'your_transform': your_transform,
}
```

Then reference in `sections.yml`:
```yaml
transforms: ['your_transform']
```

### Styling

The HTML template uses minimal, print-friendly CSS. Modify `templates/report.html` to adjust styling.

## Automation

### Daily Scheduling

Set up automated daily execution using:

**Windows Task Scheduler**:
```batch
cd C:\path\to\transactiondesk\daily-report
python build_report.py
```

**Linux Cron**:
```bash
0 8 * * * cd /path/to/transactiondesk/daily-report && python build_report.py
```

### Email Integration

For automated email delivery, extend `build_report.py` with email functionality or integrate with your existing email system.

## Troubleshooting

### Common Issues

1. **Database Connection Errors**:
   - Verify SQLALCHEMY_URL format
   - Check database credentials and network access
   - Ensure required database drivers are installed

2. **SQL Query Errors**:
   - Verify table names match your database schema
   - Check column names in sections.yml
   - Test queries directly in your database client

3. **Template Rendering Issues**:
   - Ensure templates directory exists
   - Check Jinja2 syntax in templates
   - Verify macro imports

### Testing

Test with sample data by modifying the SQL queries in `sections.yml` to use static data:

```sql
SELECT 'Sample File #' as 'File Number', 'Sample Address' as 'Property Address'
UNION ALL SELECT 'File-001', '123 Main St';
```

## Development

### Architecture Rules

- Keep all SQL in `sections.yml` (no hardcoded queries)
- Use `_macros.html` for consistent table rendering  
- Maintain minimal, print-friendly CSS
- Do not reorder SQL query columns

### Dependencies

- **pandas**: Data manipulation and SQL query results
- **SQLAlchemy**: Database connectivity
- **Jinja2**: HTML template rendering
- **PyYAML**: Configuration file parsing
- **python-dotenv**: Environment variable management

## Integration

This module is designed to integrate seamlessly with the Transaction Desk system:

- Uses existing database schema
- Respects division/branch structure
- Compatible with current sales rep assignments
- Follows established file numbering conventions

## Support

For technical issues or feature requests, refer to the main Transaction Desk documentation in the `docs/` folder or consult the system administrator.
