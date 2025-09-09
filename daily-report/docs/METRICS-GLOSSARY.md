## Daily Report Metrics Glossary

Purpose: One-page reference for Branch Analytics and R-14 report terms and formulas.

### Core timeframes
- **MTD (Month-to-Date)**: Cumulative from the 1st of the current month through today for the metric shown (e.g., openings, closings, revenue). Resets each new month.
- **Prior Month Total (Prior)**: The total for the previous calendar month for the same metric.

### Branch Analytics metrics
- **Count for Day**: For demos, simulated as 5% of the current MTD value.
- **Count for Month**: Actual count measured within the current month.
- **Average Per Day**: `MTD ÷ days_in_month` (e.g., 31 for August).
- **Projected For Month**: `(current_day_of_month / days_elapsed) × MTD`.

### R-14 metrics
- **4‑Month Closing Ratio**: `(Closings ÷ Openings) × 100`, computed over the most recent 4 months for each sales rep. Typically formatted to one decimal place.
- **August (current month) Performance**: Per-rep closings and revenue by production type within the current month.
- **Service Type Breakdown**: Title Only – Resale (Purchase), Title Only – Refinance (Refinance), Escrow Orders (Escrow profiles).

### Assignment logic nuances
- **R‑14 Cross‑Branch Visibility**: A sales rep appears in their mapped primary branch and also in other branches where they have transactions (secondary assignment based on `Profile`, Column B).
- **Title Officer Report (contrast)**: Officers appear only in their mapped branch; no cross‑branch transactions are shown.

### Data sources and IDs (high level)
- Openings: `Openings.xlsx` (Order Number unique key)
- Closings: `ClosedOrders.xlsx` (Order Number unique key)
- Revenue: `Revenue.xlsx` (Order Number unique key)
- Deduplication: Count each `Order Number` only once per metric/section.

### Notes
- Prior Month and Count for Day can be simulated values in demo contexts; production runs should use actual historicals and daily totals.
- See `REPORT-LOGIC-DOCUMENTATION.md` and `CALCULATION-MAPPING.md` for SQL/logic details and validation queries; see `DATA-LINEAGE.md` for source-to-report flow.


