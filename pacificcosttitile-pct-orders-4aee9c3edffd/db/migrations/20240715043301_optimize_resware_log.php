<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class OptimizeReswareLog extends AbstractMigration
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
        $table = $this->table('pct_resware_log');
        $table->changeColumn('request_type', 'string', ['limit' => 100, 'null' => true])
            ->changeColumn('file_id', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('file_number', 'string', ['limit' => 50, 'null' => true])
            ->update();
    }
}
