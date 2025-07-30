<?php
declare (strict_types = 1);

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;

final class AddValueForTitlePointShutOff extends AbstractMigration
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
        $table->removeColumn('is_lp_enable');
        $table->addColumn('is_enable', 'boolean', ['after' => 'value', 'default' => 0]);
        $table->changeColumn('value', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true])
            ->update();

        $rows = [
            [
                'title' => 'Title Point Shut Off',
                'slug' => 'title_point_shut_off',
                'is_enable' => 0,
            ],

        ];

        $table->insert($rows)->saveData();
    }
}
