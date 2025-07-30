<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ChangeInSellerTable extends AbstractMigration
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
        $table = $this->table('pct_order_borrower_seller_info');
		$table->removeColumn('ssn1')
            ->removeColumn('ssn2')
            ->removeColumn('ssn3')
            ->removeColumn('second_ssn1')
            ->removeColumn('second_ssn2')
            ->removeColumn('second_ssn3')
            ->addColumn('ssn', 'string', ['after' => 'birth_year', 'null' => true])
            ->addColumn('second_ssn', 'string', ['after' => 'second_birth_year', 'null' => true])
			->update();
    }
}
