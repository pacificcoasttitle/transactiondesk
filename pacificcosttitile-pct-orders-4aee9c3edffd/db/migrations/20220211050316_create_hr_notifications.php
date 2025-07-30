<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateHrNotifications extends AbstractMigration
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
        $table = $this->table('pct_hr_notifications');
        $table->addColumn('sent_user_id', 'integer')
            ->addColumn('message', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
            ->addColumn('is_admin', 'boolean', ['default' => 0])
            ->addColumn('is_read', 'boolean', ['default' => 0])
            ->addColumn('is_admin_read', 'boolean', ['default' => 0])
            ->addColumn('type', 'string', ['null' => true])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex(['sent_user_id', 'is_read', 'is_admin'])  
            ->create();
    }
}
