<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class OptimizeVendors extends AbstractMigration
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
        $table = $this->table('pct_vendors');
        $table->changeColumn('transctee_name', 'string', ['limit' => 100, 'null' => true])
            ->changeColumn('file_number', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('account_number', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('aba', 'string', ['limit' => 100, 'null' => true])
            ->changeColumn('bank_name', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('submitted', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('approved_date', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('created_by', 'string', ['limit' => 20, 'null' => true])
            ->update();
    }
}
