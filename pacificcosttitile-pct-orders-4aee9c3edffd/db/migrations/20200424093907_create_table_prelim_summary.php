<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateTablePrelimSummary extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('pct_order_prelim_summary');
        $table->addColumn('file_number', 'string')
                ->addColumn('vesting', 'text', ['null' => true])
                ->addColumn('generated_date', 'datetime',['null' => true])
                ->addColumn('lien', 'text',['limit' => MysqlAdapter::TEXT_LONG, 'null' => true])
                ->addColumn('easement', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true])
                ->addColumn('requirements', 'text',['limit' => MysqlAdapter::TEXT_LONG, 'null' => true])
                ->addColumn('restrictions', 'text',['limit' => MysqlAdapter::TEXT_LONG, 'null' => true])
                ->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime', ['null' => true])
                ->create();
    }
}
