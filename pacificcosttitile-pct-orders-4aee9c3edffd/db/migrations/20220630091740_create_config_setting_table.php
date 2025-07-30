<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateConfigSettingTable extends AbstractMigration
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
		$table = $this->table('pct_configs');
        $table->addColumn('title', 'string')
			->addColumn('slug', 'string')
			->addColumn('value', 'text',['limit' => MysqlAdapter::TEXT_LONG])
			->addIndex(['slug'], ['unique' => true])
			->addTimestamps()
            ->create();

			


			$rows = [
				[
					'title'	=> 'Escrow Commission',
					'slug'	=> 'escrow_commission',
					'value'	=> 10
				],
				
			];
	
			$table->insert($rows)->saveData();

    }
}
