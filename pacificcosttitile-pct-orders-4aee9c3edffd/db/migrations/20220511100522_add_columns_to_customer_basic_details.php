<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddColumnsToCustomerBasicDetails extends AbstractMigration
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
        $table->addColumn('loan_westcor_commission', 'decimal', ['default' => 0 , 'null' => true, 'precision'=>5,'scale'=>2,'after'=>'is_escrow_officer'])
        	  ->addColumn('loan_natic_commission', 'decimal', ['default' => 0 , 'null' => true, 'precision'=>5,'scale'=>2,'after'=>'loan_westcor_commission'])
        	  ->addColumn('sale_westcor_commission', 'decimal', ['default' => 0 , 'null' => true, 'precision'=>5,'scale'=>2,'after'=>'loan_natic_commission'])
            ->update();

    }
}
