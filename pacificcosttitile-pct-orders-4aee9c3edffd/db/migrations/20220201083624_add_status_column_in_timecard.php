<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddStatusColumnInTimecard extends AbstractMigration
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
        $table = $this->table('pct_hr_time_cards')
                ->addColumn('status', 'enum', ['values' => ['approved', 'denied'], 'after' => 'user_id'])
                ->addColumn('action_taken_user_id', 'integer', ['null' => true, 'after' => 'status'])
                ->update(); 
    }
}
