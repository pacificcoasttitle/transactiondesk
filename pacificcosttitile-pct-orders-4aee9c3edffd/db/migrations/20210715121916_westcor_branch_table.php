<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class WestcorBranchTable extends AbstractMigration
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
        $table = $this->table('pct_order_westcore_branches');
        $table->addColumn('agency_name', 'string')
            ->addColumn('address', 'string')
            ->addColumn('city', 'string')
            ->addColumn('state', 'string')
            ->addColumn('zip', 'integer')
            ->addColumn('phone', 'string', ['null' => true])
            ->addColumn('agent_number', 'string')
            ->addColumn('is_proposed_branch', 'boolean', ['default' => 0])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex(['agent_number'], ['unique' => true])
            ->create();
    }
}
