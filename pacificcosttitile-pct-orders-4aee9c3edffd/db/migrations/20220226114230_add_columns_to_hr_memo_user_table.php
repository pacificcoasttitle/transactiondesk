<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddColumnsToHrMemoUserTable extends AbstractMigration
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
		$this->table('pct_hr_assigned_memo_users')
            ->addColumn('mail_sent', 'boolean', ['default' => 0, 'after' => 'is_read'])
            ->addColumn('is_acknowledge', 'boolean', ['default' => 0, 'after' => 'mail_sent'])
            ->save();
    }
}
