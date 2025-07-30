<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddColumnsInBuyer extends AbstractMigration
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
        $table = $this->table('pct_order_borrower_buyer_info');
		$table->addColumn('marital_status', 'string', ['after' => 'is_main_buyer', 'null' => true])
            ->addColumn('married_to', 'integer', ['after' => 'marital_status', 'null' => true])
			->update();

        $table = $this->table('pct_order_borrower_buyer_info_wizard');
        $table->addColumn('property_vested', 'string', ['after' => 'annual_premium', 'null' => true])
            ->update();
    }
}
