<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class LabelsTable extends AbstractMigration
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
        $table = $this->table('pct_labels');
        $table->addColumn('sales_rep_id', 'integer')
                ->addColumn('file_name', 'string')
                ->addColumn('file_columns', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
                ->addColumn('original_file_name', 'string')
                ->addColumn('added_by', 'integer')
                ->addTimestamps()
                ->addForeignKey('sales_rep_id', 'customer_basic_details', 'id')
                ->addForeignKey('added_by', 'customer_basic_details', 'id')
                ->create();
    }
}
