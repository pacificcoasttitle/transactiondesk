<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddEscrowOfficerInOrder extends AbstractMigration
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
        $table = $this->table('order_details');
        $table->addColumn('escrow_officer_id', 'integer', ['null' => true, 'after' => 'borrower_mobile_number'])
              ->update();
    }
}
