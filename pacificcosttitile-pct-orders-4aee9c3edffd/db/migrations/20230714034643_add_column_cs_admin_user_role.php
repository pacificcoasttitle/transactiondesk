<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddColumnCsAdminUserRole extends AbstractMigration
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
        $table = $this->table('pct_users_role');

		$builder = $this->getQueryBuilder();
		$builder->delete('pct_users_role')->where(['id' => 4])->execute();

		$rows = [
			[
				'id'    => 4,
				'title'  => 'CS Admin'
			]
		];

		$table->insert($rows)->saveData();
    }
}
