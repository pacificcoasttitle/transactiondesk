<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class CreateMemoTabel extends AbstractMigration
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
        $table = $this->table('pct_hr_memos');
        $table->addColumn('subject', 'string')
            ->addColumn('description', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
            ->addColumn('date', 'date')
            ->addColumn('created_by', 'integer')
            ->addColumn('created_at', 'datetime')
            ->addColumn('status', 'boolean', ['default' => 0])
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex(['created_by'])  
            ->create();
    }
}
