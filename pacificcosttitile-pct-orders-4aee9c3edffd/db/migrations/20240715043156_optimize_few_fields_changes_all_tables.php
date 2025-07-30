<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class OptimizeFewFieldsChangesAllTables extends AbstractMigration
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
        $table = $this->table('pct_order_api_logs');
        $table->changeColumn('api_type', 'string', ['limit' => 100, 'null' => true])
            ->update();

        $table = $this->table('pct_order_code_book');
        $table->changeColumn('code', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('type', 'string', ['limit' => 50, 'null' => true])
            ->update();

        $table = $this->table('pct_order_counties');
        $table->changeColumn('county', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('fips', 'string', ['limit' => 50, 'null' => true])
            ->update();

        $table = $this->table('pct_order_cpl_api_logs');
        $table->changeColumn('file_number', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('cpl_page', 'string', ['limit' => 50, 'null' => true])
            ->update();

        $table = $this->table('pct_order_fees');
        $table->changeColumn('transaction_type', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('name', 'string', ['limit' => 100, 'null' => true])
            ->update();

        $table = $this->table('pct_order_fees_types');
        $table->changeColumn('name', 'string', ['limit' => 100, 'null' => true])
            ->update();

        $table = $this->table('pct_order_natic_branches');
        $table->changeColumn('city', 'string', ['limit' => 100, 'null' => true])
            ->changeColumn('state', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('address1', 'string', ['limit' => 150, 'null' => true])
            ->update();

        $table = $this->table('pct_order_notifications');
        $table->changeColumn('type', 'string', ['limit' => 50, 'null' => true])
            ->update();

        $table = $this->table('pct_order_proposed_branches');
        $table->changeColumn('city', 'string', ['limit' => 100, 'null' => true])
            ->changeColumn('state', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('zip', 'string', ['limit' => 50, 'null' => true])
            ->update();

        $table = $this->table('pct_order_recordings_monthly_sync');
        $table->changeColumn('month', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('day', 'string', ['limit' => 20, 'null' => true])
            ->update();

    }
}
