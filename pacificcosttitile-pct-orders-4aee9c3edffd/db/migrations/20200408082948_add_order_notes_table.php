<?php

use Phinx\Migration\AbstractMigration;

class AddOrderNotesTable extends AbstractMigration
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
        $table = $this->table('order_notes');
        $table->addColumn('note_id', 'integer', ['default' => 0])
                ->addColumn('user_id', 'integer', ['default' => 0])
                ->addColumn('order_id', 'integer', ['default' => 0])
                ->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime', ['null' => true])
                ->addIndex(['note_id', 'user_id', 'order_id'], ['unique' => true])
                ->create();
    }
}
