<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddPremiumToCommissionRangeTable extends AbstractMigration
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
		$builder->delete('pct_commission_range')
			->execute();

		$table = $this->table('pct_commission_range');
        $table->removeColumn('additional_threshold')
		->addColumn('premium', 'integer', ['after'=>'underwriter_tier'])
        ->update();

    }
}
