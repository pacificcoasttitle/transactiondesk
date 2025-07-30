<?php

use Phinx\Migration\AbstractMigration;

class AddTokenRelatedFieldsForWestcor extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
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
        $table = $this->table('pct_order_westcore_token');
        $table->addColumn('first_name', 'string', ['limit' => 255, 'null' => true, 'after' => 'token'])
            ->addColumn('last_name', 'string', ['limit' => 255, 'null' => true, 'after' => 'first_name'])
            ->addColumn('email', 'string', ['limit' => 255, 'null' => true, 'after' => 'last_name'])
            ->addColumn('agency_name', 'string', ['limit' => 255, 'null' => true, 'after' => 'email'])
            ->addColumn('address', 'string', ['limit' => 255, 'null' => true, 'after' => 'agency_name'])
            ->addColumn('city', 'string', ['limit' => 255, 'null' => true, 'after' => 'address'])
            ->addColumn('state', 'string', ['limit' => 255, 'null' => true, 'after' => 'city'])
            ->addColumn('zip', 'integer', ['null' => true, 'after' => 'state'])
            ->addColumn('role', 'string', ['limit' => 255, 'null' => true, 'after' => 'zip'])
            ->addColumn('servername', 'string', ['limit' => 255, 'null' => true, 'after' => 'role'])
            ->addColumn('phone', 'string', ['limit' => 255, 'null' => true, 'after' => 'servername'])
            ->addIndex(['agent_number'], ['unique' => true])
            ->update();
    }
}
