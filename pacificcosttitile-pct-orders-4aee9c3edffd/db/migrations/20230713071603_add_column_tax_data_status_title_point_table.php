<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddColumnTaxDataStatusTitlePointTable extends AbstractMigration
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
        $table = $this->table('pct_order_title_point_data');
        $table->addColumn('tax_data_status', 'string', ['after' => 'improvements', 'null' => true])
            ->update();
    }
}
