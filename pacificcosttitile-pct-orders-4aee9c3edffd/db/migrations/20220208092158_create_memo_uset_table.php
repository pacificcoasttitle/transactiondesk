<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateMemoUsetTable extends AbstractMigration
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
        $table = $this->table('pct_hr_assigned_memo_users');
        $table->addColumn('user_id', 'integer')
            ->addColumn('memo_id', 'integer')
            ->addColumn('is_read', 'boolean', ['default' => 0])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex(['user_id', 'memo_id'])  
            ->create();
    }
}
