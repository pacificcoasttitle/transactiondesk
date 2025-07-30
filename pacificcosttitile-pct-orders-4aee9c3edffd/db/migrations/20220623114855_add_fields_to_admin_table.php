<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddFieldsToAdminTable extends AbstractMigration
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
		$table = $this->table('admin');
		$table->addColumn('first_name', 'string', ['default' => null , 'null' => true,'after' => 'user_name'])
				->addColumn('last_name', 'string', ['default' => null , 'null' => true,'after' => 'first_name'])
				->addColumn('role_id', 'integer', ['default' => null , 'null' => true,'after' => 'email_id'])
				->removeColumn('user_name')
			->update();
		
		$password_hash = password_hash("dev123", PASSWORD_DEFAULT);

		$builder = $this->getQueryBuilder();
		$builder
			->update('admin')
			->set('first_name', 'Admin')
			->set('last_name', 'Admin')
			->set('role_id', 1)
			->set('password', $password_hash)
			->execute();
    }
}
