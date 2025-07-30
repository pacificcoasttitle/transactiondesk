<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddEscrowOrderTaskStatus extends AbstractMigration
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
        $table = $this->table('pct_escrow_order_completed_task');
        $table->addColumn('order_id', 'integer')
            ->addColumn('task_id', 'integer')
            ->addColumn('completed_by', 'integer', ['default' => 0, 'null' => true] )
            ->addTimestamps()
            ->create();
    }
}
