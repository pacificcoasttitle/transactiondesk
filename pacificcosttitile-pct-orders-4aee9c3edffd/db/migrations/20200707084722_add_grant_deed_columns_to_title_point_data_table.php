<?php

use Phinx\Migration\AbstractMigration;

class AddGrantDeedColumnsToTitlePointDataTable extends AbstractMigration
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
        $table = $this->table('pct_order_title_point_data');
        $table->addColumn('grant_deed_type', 'string', ['after' => 'cs4_recorded_date', 'null' => true])
            ->addColumn('grant_deed_message', 'string', ['after' => 'grant_deed_type','null' => true])
              ->update();
    }
}
