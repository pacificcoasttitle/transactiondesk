<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class OptimizeOrderPartnerCompanyInfo extends AbstractMigration
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
        $table = $this->table('pct_order_partner_company_info');
        $table->changeColumn('partner_name', 'string', ['limit' => 150, 'null' => true])
            ->changeColumn('email', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('city', 'string', ['limit' => 100, 'null' => true])
            ->changeColumn('state', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('zip', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('sales_underwriter', 'string', ['limit' => 100, 'null' => true])
            ->changeColumn('loan_underwriter', 'string', ['limit' => 100, 'null' => true])
            ->update();
    }
}
