<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class OptimizeSalesRepTitleOfficer extends AbstractMigration
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
        $table = $this->table('pct_order_sales_rep');
        $table->changeColumn('name', 'string', ['limit' => 100, 'null' => true])
            ->changeColumn('email_address', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('telephone', 'string', ['limit' => 20, 'null' => true])
            ->update();

        $table = $this->table('pct_order_title_officer');
        $table->changeColumn('email_address', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('name', 'string', ['limit' => 100, 'null' => true])
            ->changeColumn('phone', 'string', ['limit' => 20, 'null' => true])
            ->update();
    }
}
