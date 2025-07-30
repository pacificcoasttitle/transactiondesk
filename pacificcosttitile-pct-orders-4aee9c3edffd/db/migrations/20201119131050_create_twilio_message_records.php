<?php

use Phinx\Migration\AbstractMigration;

class CreateTwilioMessageRecords extends AbstractMigration
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
        $table = $this->table('pct_order_twilio_message_records');
        $table->addColumn('message', 'string')
                ->addColumn('sent_from', 'string')
                ->addColumn('sent_to', 'string')
                ->addColumn('status', 'string')
                ->addColumn('message_sid', 'string')
                ->addColumn('error_code', 'string', ['null' => true])
                ->addColumn('error_message', 'string', ['null' => true])
                ->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime', ['null' => true])
                ->create();
    }
}
