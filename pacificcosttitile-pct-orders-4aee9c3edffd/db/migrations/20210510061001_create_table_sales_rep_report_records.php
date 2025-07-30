<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableSalesRepReportRecords extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {

        $table = $this->table('pct_sales_rep_report_records');
        $table->addColumn('report_id', 'integer')
                ->addColumn('carrier_route', 'string')
                ->addColumn('avg_price', 'double')
                ->addColumn('turnover_rate', 'double')
                ->addColumn('total_sales', 'double')
                ->addColumn('NOO_ratio', 'double')
                ->addColumn('avg_yr_owned', 'double')
                ->addColumn('total_units', 'double')
                ->addColumn('sa_site_zip', 'string')
                ->addColumn('sa_site_city', 'string')
                ->addForeignKey('report_id', 'pct_sales_rep_report', 'id',['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
                ->create();

    }
}
