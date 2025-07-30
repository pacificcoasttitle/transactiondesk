<?php

use Phinx\Migration\AbstractMigration;

class AddAgentAddressFields extends AbstractMigration
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
        $table = $this->table('agents');
        $table->addColumn('address', 'string', ['after' => 'company', 'null' => true])
                ->addColumn('city', 'string', ['after' => 'address', 'null' => true])
                ->addColumn('zipcode', 'string', ['after' => 'city', 'null' => true])
                ->addColumn('list_unit', 'integer', ['after' => 'zipcode', 'null' => true])
                ->addColumn('list_volume', 'integer',['after' => 'list_unit', 'null' => true])
                ->addColumn('selected_revenue', 'integer', ['after' => 'list_volume', 'null' => true])
                ->update();
    }
}
