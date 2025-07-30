<?php
declare(strict_types=1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class CreateUnderwriterTiers extends AbstractMigration
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

		$table = $this->table('pct_underwriter_tiers');
        $table->addColumn('underwriter', 'enum',['values' => ['westcor','natic','commonwealth']])
			  ->addColumn('title', 'string')
			  ->addColumn('description','text', ['limit' => MysqlAdapter::TEXT_LONG,'null' => true, 'default' => null])
			  ->addTimestamps()
              ->create();

    }
}
