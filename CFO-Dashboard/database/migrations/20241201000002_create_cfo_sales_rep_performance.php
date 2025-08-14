<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCfoSalesRepPerformance extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Create the CFO sales rep performance table for tracking individual and team performance.
     * This table stores performance metrics by time period for detailed analytics.
     */
    public function change(): void
    {
        $table = $this->table('cfo_sales_rep_performance');
        
        $table->addColumn('sales_rep_id', 'integer', [
                'null' => false,
                'comment' => 'Reference to sales rep user ID'
            ])
            ->addColumn('period_type', 'enum', [
                'values' => ['daily', 'weekly', 'monthly', 'quarterly', 'yearly'],
                'default' => 'monthly',
                'comment' => 'Type of performance period'
            ])
            ->addColumn('period_start', 'date', [
                'null' => false,
                'comment' => 'Start date of performance period'
            ])
            ->addColumn('period_end', 'date', [
                'null' => false,
                'comment' => 'End date of performance period'
            ])
            ->addColumn('total_revenue', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Total revenue generated'
            ])
            ->addColumn('sales_revenue', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Revenue from sales transactions'
            ])
            ->addColumn('refi_revenue', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Revenue from refinance transactions'
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
                'comment' => 'Average order value'
            ])
            ->addColumn('commission_earned', 'decimal', [
                'precision' => 10,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Total commission earned'
            ])
            ->addColumn('commission_rate', 'decimal', [
                'precision' => 5,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Average commission rate percentage'
            ])
            ->addColumn('revenue_goal', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'null' => true,
                'comment' => 'Revenue goal for this period'
            ])
            ->addColumn('orders_goal', 'integer', [
                'null' => true,
                'comment' => 'Orders goal for this period'
            ])
            ->addColumn('goal_achievement_percent', 'decimal', [
                'precision' => 5,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Goal achievement percentage'
            ])
            ->addColumn('goals_met', 'boolean', [
                'default' => false,
                'comment' => 'Whether goals were met'
            ])
            ->addColumn('ranking_position', 'integer', [
                'null' => true,
                'comment' => 'Ranking among all sales reps'
            ])
            ->addColumn('previous_period_revenue', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'null' => true,
                'comment' => 'Previous period revenue for comparison'
            ])
            ->addColumn('revenue_growth_percent', 'decimal', [
                'precision' => 5,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Revenue growth percentage'
            ])
            ->addColumn('conversion_rate', 'decimal', [
                'precision' => 5,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Lead to order conversion rate'
            ])
            ->addColumn('customer_satisfaction', 'decimal', [
                'precision' => 3,
                'scale' => 2,
                'null' => true,
                'comment' => 'Customer satisfaction score'
            ])
            ->addColumn('roi_percentage', 'decimal', [
                'precision' => 5,
                'scale' => 2,
                'default' => 0.00,
                'comment' => 'Return on investment percentage'
            ])
            ->addColumn('performance_notes', 'text', [
                'null' => true,
                'comment' => 'Performance notes and observations'
            ])
            ->addColumn('alert_triggered', 'boolean', [
                'default' => false,
                'comment' => 'Whether performance alert was triggered'
            ])
            ->addColumn('last_calculated', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'update' => 'CURRENT_TIMESTAMP',
                'comment' => 'Last time metrics were calculated'
            ])
            ->addTimestamps()
            
            // Indexes for performance
            ->addIndex(['sales_rep_id'], ['name' => 'idx_sales_rep_id'])
            ->addIndex(['period_type', 'period_start'], ['name' => 'idx_period_type_start'])
            ->addIndex(['sales_rep_id', 'period_type', 'period_start'], ['unique' => true, 'name' => 'idx_rep_period_unique'])
            ->addIndex(['total_revenue'], ['name' => 'idx_total_revenue'])
            ->addIndex(['goal_achievement_percent'], ['name' => 'idx_goal_achievement'])
            ->addIndex(['ranking_position'], ['name' => 'idx_ranking_position'])
            ->addIndex(['goals_met'], ['name' => 'idx_goals_met'])
            ->addIndex(['alert_triggered'], ['name' => 'idx_alert_triggered'])
            ->addIndex(['created_at'], ['name' => 'idx_created_at'])
            
            // Foreign key to users table
            ->addForeignKey('sales_rep_id', 'users', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            
            ->create();
            
        // Add table comment
        $this->execute("ALTER TABLE cfo_sales_rep_performance COMMENT = 'Sales rep performance metrics for CFO dashboard analytics'");
    }
}
