<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateTimesheetStatus extends AbstractMigration
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
		$table = $this->table('pct_hr_timeheet_status');
        $table->addColumn('user_id', 'integer')
            ->addColumn('start_date', 'date')
            ->addColumn('status',  'enum', ['values' => ['submitted','approved', 'denied'], 'default' => 'submitted'])
			->addColumn('updated_by', 'integer',['null'=>TRUE,'default'=>NULL])
			->addColumn('denied_reason', 'text', ['limit' => MysqlAdapter::TEXT_LONG,  'null' => true, 'default' => null])
			->addTimestamps()
			->addForeignKey('user_id', 'pct_hr_users', 'id',['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
			->addForeignKey('updated_by', 'pct_hr_users', 'id',['delete'=> 'SET_NULL', 'update'=> 'CASCADE'])
            ->create();


    }
}
