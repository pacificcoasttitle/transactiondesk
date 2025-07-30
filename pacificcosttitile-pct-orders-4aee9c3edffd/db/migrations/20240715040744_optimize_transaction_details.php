<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class OptimizeTransactionDetails extends AbstractMigration
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
        $table = $this->table('transaction_details');
        $table->changeColumn('sales_amount', 'string', ['limit' => 30, 'null' => true])
            ->changeColumn('escrow_number', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('additional_email_1', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('additional_email_2', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('borrower', 'string', ['limit' => 150, 'null' => true])
            ->changeColumn('secondary_borrower', 'string', ['limit' => 150, 'null' => true])
            ->update();
    }
}
