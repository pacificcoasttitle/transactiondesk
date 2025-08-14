<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateCfoRevenueForecasts extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Create the CFO revenue forecasts table for predictive analytics and budget planning.
     * This table stores forecast data and tracks accuracy over time.
     */
    public function change(): void
    {
        $table = $this->table('cfo_revenue_forecasts');
        
        $table->addColumn('forecast_period', 'date', [
                'null' => false,
                'comment' => 'Period for which forecast is made'
            ])
            ->addColumn('forecast_type', 'enum', [
                'values' => ['daily', 'weekly', 'monthly', 'quarterly', 'yearly'],
                'default' => 'monthly',
                'comment' => 'Type of forecast period'
            ])
            ->addColumn('forecasted_revenue', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'null' => false,
                'comment' => 'Predicted revenue amount'
            ])
            ->addColumn('forecasted_orders', 'integer', [
                'null' => true,
                'comment' => 'Predicted number of orders'
            ])
            ->addColumn('forecasted_avg_value', 'decimal', [
                'precision' => 10,
                'scale' => 2,
                'null' => true,
                'comment' => 'Predicted average order value'
            ])
            ->addColumn('actual_revenue', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'null' => true,
                'comment' => 'Actual revenue (filled after period ends)'
            ])
            ->addColumn('actual_orders', 'integer', [
                'null' => true,
                'comment' => 'Actual number of orders'
            ])
            ->addColumn('actual_avg_value', 'decimal', [
                'precision' => 10,
                'scale' => 2,
                'null' => true,
                'comment' => 'Actual average order value'
            ])
            ->addColumn('variance_amount', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'null' => true,
                'comment' => 'Difference between forecast and actual'
            ])
            ->addColumn('variance_percent', 'decimal', [
                'precision' => 5,
                'scale' => 2,
                'null' => true,
                'comment' => 'Variance percentage'
            ])
            ->addColumn('forecast_accuracy', 'decimal', [
                'precision' => 5,
                'scale' => 2,
                'null' => true,
                'comment' => 'Forecast accuracy percentage'
            ])
            ->addColumn('confidence_level', 'decimal', [
                'precision' => 3,
                'scale' => 2,
                'default' => 0.80,
                'comment' => 'Confidence level of forecast (0-1)'
            ])
            ->addColumn('forecast_method', 'varchar', [
                'limit' => 50,
                'default' => 'linear_regression',
                'comment' => 'Method used for forecasting'
            ])
            ->addColumn('seasonal_factor', 'decimal', [
                'precision' => 5,
                'scale' => 4,
                'default' => 1.0000,
                'comment' => 'Seasonal adjustment factor'
            ])
            ->addColumn('trend_factor', 'decimal', [
                'precision' => 5,
                'scale' => 4,
                'default' => 1.0000,
                'comment' => 'Trend adjustment factor'
            ])
            ->addColumn('market_factor', 'decimal', [
                'precision' => 5,
                'scale' => 4,
                'default' => 1.0000,
                'comment' => 'Market condition adjustment factor'
            ])
            ->addColumn('budget_target', 'decimal', [
                'precision' => 12,
                'scale' => 2,
                'null' => true,
                'comment' => 'Budget target for comparison'
            ])
            ->addColumn('budget_variance', 'decimal', [
                'precision' => 5,
                'scale' => 2,
                'null' => true,
                'comment' => 'Variance from budget percentage'
            ])
            ->addColumn('forecast_notes', 'text', [
                'null' => true,
                'comment' => 'Notes about forecast assumptions'
            ])
            ->addColumn('external_factors', 'json', [
                'null' => true,
                'comment' => 'External factors affecting forecast'
            ])
            ->addColumn('model_version', 'varchar', [
                'limit' => 20,
                'default' => '1.0',
                'comment' => 'Version of forecasting model used'
            ])
            ->addColumn('is_active', 'boolean', [
                'default' => true,
                'comment' => 'Whether this forecast is active/current'
            ])
            ->addColumn('created_by', 'integer', [
                'null' => true,
                'comment' => 'User who created the forecast'
            ])
            ->addColumn('forecast_generated_at', 'timestamp', [
                'default' => 'CURRENT_TIMESTAMP',
                'comment' => 'When forecast was generated'
            ])
            ->addTimestamps()
            
            // Indexes for performance
            ->addIndex(['forecast_period'], ['name' => 'idx_forecast_period'])
            ->addIndex(['forecast_type'], ['name' => 'idx_forecast_type'])
            ->addIndex(['forecast_period', 'forecast_type'], ['unique' => true, 'name' => 'idx_period_type_unique'])
            ->addIndex(['forecasted_revenue'], ['name' => 'idx_forecasted_revenue'])
            ->addIndex(['actual_revenue'], ['name' => 'idx_actual_revenue'])
            ->addIndex(['forecast_accuracy'], ['name' => 'idx_forecast_accuracy'])
            ->addIndex(['is_active'], ['name' => 'idx_is_active'])
            ->addIndex(['created_by'], ['name' => 'idx_created_by'])
            ->addIndex(['forecast_generated_at'], ['name' => 'idx_forecast_generated_at'])
            
            // Foreign key to users table for created_by
            ->addForeignKey('created_by', 'users', 'id', [
                'delete' => 'SET_NULL',
                'update' => 'CASCADE'
            ])
            
            ->create();
            
        // Add table comment
        $this->execute("ALTER TABLE cfo_revenue_forecasts COMMENT = 'Revenue forecasts and predictive analytics for CFO dashboard'");
    }
}
