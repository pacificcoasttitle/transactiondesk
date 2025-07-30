<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTablePmaData extends AbstractMigration
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
        $table = $this->table('pct_pma_data');
        $table->addColumn('sales_rep', 'integer', ['null' => true])
                ->addColumn('address', 'string')
                ->addColumn('apn', 'string')
                ->addColumn('city', 'string')
                ->addColumn('link', 'string')
                ->addColumn('runDate', 'datetime')
                ->addColumn('cost', 'decimal')
                ->addColumn('added_by', 'integer')
                ->addForeignKey('sales_rep', 'customer_basic_details', 'id',['delete'=> 'SET_NULL', 'update'=> 'CASCADE'])
                ->addForeignKey('added_by', 'customer_basic_details', 'id',['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
                ->create();
    }
}
