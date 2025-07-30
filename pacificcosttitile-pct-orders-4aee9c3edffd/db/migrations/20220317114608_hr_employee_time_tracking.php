<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class HrEmployeeTimeTracking extends AbstractMigration
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
		$table = $this->table('pct_hr_employee_time_tracking');
        $table->addColumn('employee_id', 'integer')
				->addColumn('time_in','timestamp', ['default' => 'CURRENT_TIMESTAMP'])
				->addColumn('time_out','timestamp', ['null' => true])
				->addForeignKey('employee_id', 'pct_hr_users', 'id',['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
                ->create();

    }
}
