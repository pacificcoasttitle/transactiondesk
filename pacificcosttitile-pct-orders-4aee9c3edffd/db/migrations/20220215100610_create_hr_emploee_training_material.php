<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateHrEmploeeTrainingMaterial extends AbstractMigration
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
		$table = $this->table('pct_hr_employee_training_material');
        $table->addColumn('path', 'string')
				->addColumn('type', 'enum', ['values' => ['url', 'file']])
				->addColumn('training_id', 'integer')
                ->addTimestamps()
				->addForeignKey('training_id', 'pct_hr_employee_training', 'id')
                ->create();
    }
}
