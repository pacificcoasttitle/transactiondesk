<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class CreateCPLErrorLogsTable extends AbstractMigration
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
        $table = $this->table('pct_order_cpl_api_logs');
        $table->addColumn('user_id', 'integer', ['default' => 0, 'null' => true])
                ->addColumn('order_id', 'integer', ['default' => 0])
                ->addColumn('file_number', 'string', ['limit' => 255])
                ->addColumn('cpl_page', 'string', ['limit' => 255])
                ->addColumn('error', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true])
                ->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime', ['null' => true])
                ->create();
    }
}
