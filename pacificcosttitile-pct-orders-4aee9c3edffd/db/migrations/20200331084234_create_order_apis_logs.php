<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateOrderApisLogs extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
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
        $table = $this->table('pct_order_api_logs');
        $table->addColumn('user_id', 'integer', ['default' => 0, 'null' => true])
                ->addColumn('order_id', 'integer', ['default' => 0, 'null' => true])
                ->addColumn('api_type', 'string', ['limit' => 255])
                ->addColumn('request_type', 'string', ['limit' => 255])
                ->addColumn('request_url', 'text')
                ->addColumn('request_data', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true])
                ->addColumn('response_data', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true])
                ->addColumn('created', 'datetime')
                ->addColumn('updated', 'datetime', ['null' => true])
                ->create();
    }
}
