<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddColumnsInBorrowerBuyerTable extends AbstractMigration
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
        $table = $this->table('pct_order_borrower_buyer_transfer_info');
		$table->addColumn('is_cotenant_death', 'enum', ['values' => ['yes','no'], 'after' => 'is_principal_residence'])
            ->removeColumn('bank_name')
            ->addColumn('lender_interest_reason', 'string', ['null' => true, 'after' => 'is_lender_interest'])
            ->addColumn('date_of_death_transfer', 'string')
            ->changeColumn('types_of_transfer', 'string', ['null' => true])
			->update();
    }
}
