<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCfoRevenueDailySummary extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Create the CFO revenue daily summary table for aggregated daily revenue metrics.
     * This table stores pre-calculated daily summaries for fast dashboard loading.
     */
    public function change(): void
    {
        $table = $this->table('cfo_revenue_daily_summary');
        
        $table->addColumn('summary_date', 'date', [
                'null' => false,
                'comment' => 'Date for this revenue summary'
            ])
            ->addColumn('total_revenue', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Total revenue for the day'
            ])
            ->addColumn('sales_revenue', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Sales transaction revenue'
            ])
            ->addColumn('refi_revenue', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Refinance transaction revenue'
            ])
            ->addColumn('total_orders', 'integer', [
                'default' => 0,
                'comment' => 'Total number of orders'
            ])
            ->addColumn('sales_orders', 'integer', [
                'default' => 0,
                'comment' => 'Number of sales orders'
            ])
            ->addColumn('refi_orders', 'integer', [
                'default' => 0,
                'comment' => 'Number of refinance orders'
            ])
            ->addColumn('avg_order_value', 'decimal', [
                'precision' => 10,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Average order value for the day'
            ])
            ->addColumn('westcor_revenue', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Revenue from Westcor underwriter'
            ])
            ->addColumn('fnf_revenue', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Revenue from FNF underwriter'
            ])
            ->addColumn('natic_revenue', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Revenue from NATIC underwriter'
            ])
            ->addColumn('commission_paid', 'decimal', [
                'precision' => 10,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Total commission paid to sales reps'
            ])
            ->addColumn('profit_margin', 'decimal', [
                'precision' => 5,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Profit margin percentage'
            ])
            ->addColumn('previous_day_revenue', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'null' => true,
                'comment' => 'Previous day revenue for comparison'
            ])
            ->addColumn('mtd_revenue', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Month-to-date revenue cumulative'
            ])
            ->addColumn('ytd_revenue', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Year-to-date revenue cumulative'
            ])
            ->addColumn('budget_variance', 'decimal', [
                'precision' => 5,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Variance from budget percentage'
            ])
            ->addColumn('last_updated', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP',
                'comment' => 'Last time this record was updated'
            ])
            ->addTimestamps()
            
            // Indexes for performance
            ->addIndex(['summary_date'], ['unique' => true, 'name' => 'idx_summary_date_unique'])
            ->addIndex(['summary_date', 'total_revenue'], ['name' => 'idx_date_revenue'])
            ->addIndex(['mtd_revenue'], ['name' => 'idx_mtd_revenue'])
            ->addIndex(['ytd_revenue'], ['name' => 'idx_ytd_revenue'])
            ->addIndex(['created_at'], ['name' => 'idx_created_at'])
            
            ->create();
            
        // Add table comment
        $this->execute("ALTER TABLE cfo_revenue_daily_summary COMMENT = 'Daily revenue summaries for CFO dashboard analytics'");
    }
}
