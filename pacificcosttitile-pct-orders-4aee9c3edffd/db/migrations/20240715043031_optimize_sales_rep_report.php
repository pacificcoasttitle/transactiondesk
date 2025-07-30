<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class OptimizeSalesRepReport extends AbstractMigration
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
        $table = $this->table('pct_sales_rep_report');
        $table->changeColumn('zip_code', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('sort_by', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('area_name', 'string', ['limit' => 100, 'null' => true])
            ->changeColumn('report_url', 'string', ['limit' => 100, 'null' => true])
            ->update();
    }
}
