<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddFirstInThresholdInCustomerBasicDetails extends AbstractMigration
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
		$table = $this->table('customer_basic_details');
		$table->addColumn('first_in_threshold', 'decimal', ['default' => 0 , 'precision'=>15,'scale'=>2,'after'=>'commission_draw_value'])
			->update();
    }
}
