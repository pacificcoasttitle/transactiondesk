<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddProposedBranch extends AbstractMigration
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
        $table = $this->table('pct_order_westcore_token');
        $table->addColumn('is_proposed_branch', 'boolean', ['default' => 0, 'after' => 'original_agent_number'])
              ->update();

        $table = $this->table('order_details');
        $table->addColumn('proposed_branch_id', 'integer', ['null' => true, 'after' => 'underwriter'])
            ->update();      
    }
}
