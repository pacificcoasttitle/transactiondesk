<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateHrEmploeeTraining extends AbstractMigration
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
		$table = $this->table('pct_hr_employee_training');
        $table->addColumn('name', 'string')
                ->addColumn('description',  'text', ['limit' => MysqlAdapter::TEXT_LONG,'null' => true])
				->addColumn('department_id', 'integer')
				->addColumn('position_id', 'integer')
				->addColumn('status', 'boolean', ['default' => 0])
                ->addTimestamps()
				->addForeignKey('department_id', 'pct_hr_departments', 'id')
				->addForeignKey('position_id', 'pct_hr_position', 'id')
                ->create();
    }
}
