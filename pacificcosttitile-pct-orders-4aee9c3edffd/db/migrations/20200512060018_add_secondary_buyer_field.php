<?php

use Phinx\Migration\AbstractMigration;

class AddSecondaryBuyerField extends AbstractMigration
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
        $table = $this->table('order_details');
        $table->addColumn('westcor_secondary_buyer_id', 'integer',['null' => true, 'default' => 0, 'after' => 'westcor_seller_id'])
              ->addColumn('westcor_secondary_seller_id', 'integer',['null' => true, 'default' => 0, 'after' => 'westcor_secondary_buyer_id'])
              ->update();
    }
}
