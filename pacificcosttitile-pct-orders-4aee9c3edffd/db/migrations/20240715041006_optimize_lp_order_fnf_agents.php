<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class OptimizeLpOrderFnfAgents extends AbstractMigration
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
        $table = $this->table('pct_order_fnf_agents');
        $table->changeColumn('agent_number', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('agent_status', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('agent_account_type', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('location_city', 'string', ['limit' => 100, 'null' => true])
            ->changeColumn('state', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('zip', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('phone_number', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('underwriter_code', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('underwriter', 'string', ['limit' => 100, 'null' => true])
            ->update();
    }
}
