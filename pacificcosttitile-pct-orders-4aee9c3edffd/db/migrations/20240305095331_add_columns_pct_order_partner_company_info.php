<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class AddColumnsPctOrderPartnerCompanyInfo extends AbstractMigration
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
        $table->addColumn('sales_rep_id', 'integer', ['after' => 'partner_name', 'null' => true])
            ->addColumn('title_officer_id', 'integer', ['after' => 'partner_name', 'null' => true])
            ->update();
    }
}
