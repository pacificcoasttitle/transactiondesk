<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class UpdateUnderwriterUserTable extends AbstractMigration
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
		$builder = $this->getQueryBuilder();
		$builder->delete('pct_underwriter_users')
			->execute();

		$table = $this->table('pct_underwriter_users');
        $table->addColumn('product_type', 'enum',['values' => ['loan','sale'],'after'=>'user_id'])
			  ->addColumn('underwrier', 'enum',['values' => ['westcor','natic','commonwealth'],'after'=>'product_type'])
			  ->addColumn('fix_commission', 'decimal', ['default' => 0 , 'null' => true, 'precision'=>5,'scale'=>2])
			  ->changeColumn('underwriter_tier_id', 'integer', ['null' => true])
              ->update();


			

		

    }
}
