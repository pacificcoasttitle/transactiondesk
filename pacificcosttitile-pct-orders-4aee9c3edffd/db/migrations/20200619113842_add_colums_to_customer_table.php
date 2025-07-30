<?php

use Phinx\Migration\AbstractMigration;

class AddColumsToCustomerTable extends AbstractMigration
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
        $table = $this->table('customer_basic_details');
        $table->addColumn('street_address_2', 'string', ['after' => 'street_address','null' => true])
            ->addColumn('state', 'string', ['after' => 'city','null' => true])
            ->addColumn('title', 'string', ['after' => 'last_name','null' => true])
            ->update();
    }
}
