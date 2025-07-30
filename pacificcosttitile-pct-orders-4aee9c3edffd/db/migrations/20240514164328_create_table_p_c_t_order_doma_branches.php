<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateTablePCTOrderDomaBranches extends AbstractMigration
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
        $table = $this->table('pct_order_doma_branches');
        $table->addColumn('unique_id', 'integer')
            ->addColumn('address', 'string')
            ->addColumn('address1', 'string', ['null' => true])
            ->addColumn('city', 'string')
            ->addColumn('state', 'string')
            ->addColumn('zip', 'integer')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex(['unique_id'], ['unique' => true])
            ->create();
    }
}
