<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddColumnsToHrUser extends AbstractMigration
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
		$table = $this->table('pct_hr_users');
        $table->addColumn('birth_date', 'date', ['default' => null,'null' => true])
				->addColumn('address', 'string', ['default' => null,'null' => true])
				->addColumn('city', 'string', ['default' => null,'null' => true])
				->addColumn('state', 'string', ['default' => null,'null' => true])
				->addColumn('zip', 'string', ['default' => null,'null' => true])
				->addColumn('home_phone', 'string', ['default' => null,'null' => true])
				->addColumn('cell_phone', 'string', ['default' => null,'null' => true])
                ->update();
    }
}
