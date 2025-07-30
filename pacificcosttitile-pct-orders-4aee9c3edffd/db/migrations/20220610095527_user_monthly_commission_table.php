<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UserMonthlyCommissionTable extends AbstractMigration
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
		$table = $this->table('pct_user_monthly_commission');
        $table->addColumn('user_id', 'integer')
			->addColumn('commission', 'decimal',['default' => 0 , 'null' => true, 'precision'=>10,'scale'=>2])
			->addColumn('commission_month','integer')
			->addColumn('commission_year','integer')
			->addColumn('commisssion_pdf','string',['default' => null , 'null' => true])
			->addColumn('pdf_name','string',['default' => null , 'null' => true])
			->addTimestamps()
			->addForeignKey('user_id', 'customer_basic_details', 'id',['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
			->addIndex(['user_id', 'commission_month','commission_year'], ['unique' => true])
			->create();
    }
}
