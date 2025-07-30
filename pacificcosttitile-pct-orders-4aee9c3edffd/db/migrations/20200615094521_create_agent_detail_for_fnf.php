<?php

use Phinx\Migration\AbstractMigration;

class CreateAgentDetailForFnf extends AbstractMigration
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
        $table->addColumn('agent_number', 'string')
                ->addColumn('agent_status', 'string')
                ->addColumn('agent_account_type', 'string')
                ->addColumn('is_dba_name', 'boolean')
                ->addColumn('location_city', 'string')
                ->addColumn('underwriter_code', 'string')
                ->addColumn('underwriter', 'string')
                ->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime', ['null' => true])
                ->create();
    }
}

