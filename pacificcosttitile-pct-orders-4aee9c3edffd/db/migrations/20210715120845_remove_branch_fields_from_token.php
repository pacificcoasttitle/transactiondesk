<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RemoveBranchFieldsFromToken extends AbstractMigration
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
        $table->removeColumn('agency_name')
              ->removeColumn('address')
              ->removeColumn('city')
              ->removeColumn('state')
              ->removeColumn('zip')
              ->removeColumn('phone')
              ->removeColumn('agent_number')
              ->removeColumn('is_proposed_branch')
              ->save();
    }
}
