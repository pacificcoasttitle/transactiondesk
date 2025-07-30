<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AddCommissionDetailsInMontlyCommissionTable extends AbstractMigration
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
		$builder->delete('pct_user_monthly_commission')
			->execute();

		$table = $this->table('pct_user_monthly_commission');
		$table->addColumn('commission_details', 'text', ['limit' => MysqlAdapter::TEXT_LONG,'default' => null , 'null' => true,'after' => 'commission'])
			->update();



    }
}
