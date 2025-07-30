<?php

use Phinx\Migration\AbstractMigration;

class AddAddressFieldInCommonWealth extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('pct_order_fnf_agents');
        $table->addColumn('address', 'string', ['after' => 'is_dba_name', 'null' => false])
                ->addColumn('state', 'string', ['after' => 'location_city', 'null' => false])
                ->addColumn('zip', 'string', ['after' => 'state', 'null' => false])
                ->addColumn('phone_number', 'string', ['after' => 'zip', 'null' => true])
                ->update();
    }
}
