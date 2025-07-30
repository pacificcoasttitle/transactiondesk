<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateSalesSnapShot extends AbstractMigration
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
        $table = $this->table('pct_sales_snap_shot_report');
        $table->addColumn('sales_rep', 'integer')
            ->addColumn('area_name', 'string')
            ->addColumn('report_url', 'string', ['null' => true])
            ->addColumn('added_by', 'integer')
            ->addColumn('month_option', 'string')
			->addTimestamps()
            ->create();
    }
}
