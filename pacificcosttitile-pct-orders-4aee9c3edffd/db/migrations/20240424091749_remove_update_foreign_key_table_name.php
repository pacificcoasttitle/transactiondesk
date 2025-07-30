<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class RemoveUpdateForeignKeyTableName extends AbstractMigration
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
        $table->dropForeignKey('sales_rep')
            ->dropForeignKey('added_by')
            ->save();

        $table = $this->table('pct_sales_rep_report');
        $table->addForeignKey('sales_rep', 'customer_basic_details', 'id')
            ->addForeignKey('added_by', 'customer_basic_details', 'id')
            ->update();
    }
}
