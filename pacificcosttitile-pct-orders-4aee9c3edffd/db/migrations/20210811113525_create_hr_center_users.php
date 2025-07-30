<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class CreateHrCenterUsers extends AbstractMigration
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
        $table->addColumn('first_name', 'string')
            ->addColumn('last_name', 'string')
            ->addColumn('email', 'string')
            ->addColumn('password', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
            ->addColumn('position_id', 'integer')
            ->addColumn('is_tmp_password', 'boolean', ['default' => 1])
            ->addColumn('status', 'boolean', ['default' => 0])
            ->addColumn('user_type_id', 'boolean', ['default' => 0])
            ->addColumn('hire_date', 'date')
            ->addColumn('hash', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex(['email'], ['unique' => true])
            ->addIndex(['user_type_id'])
            ->addIndex(['position_id'])  
            ->create();
    }
}
