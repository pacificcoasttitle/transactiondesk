<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class Timecardstable extends AbstractMigration
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
        $table = $this->table('pct_hr_time_cards');
        $table->addColumn('user_id', 'integer')
            ->addColumn('exception_date', 'date')
            ->addColumn('reg_hours', 'integer')
            ->addColumn('ot_hours', 'integer')
            ->addColumn('double_ot', 'integer')
            ->addColumn('total_hours', 'integer')
            ->addColumn('comment', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex(['user_id'])  
            ->create();
    }
}
