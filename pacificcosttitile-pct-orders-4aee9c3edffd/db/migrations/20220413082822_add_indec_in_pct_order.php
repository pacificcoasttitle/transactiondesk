<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddIndecInPctOrder extends AbstractMigration
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
        $table = $this->table('order_details');
        $table->addIndex(['created_by'])
              ->addIndex(['proposed_insured_document_name'])
              ->addIndex(['prelim_summary_id'])
              ->addIndex(['fnf_agent_id'])
              ->addIndex(['escrow_amount'])
              ->addIndex(['partner_api_log_id'])
              ->update();

        $table = $this->table('property_details');
        $table->addIndex(['escrow_lender_id'])
            ->addIndex(['buyer_agent_id'])
            ->update();

        $table = $this->table('transaction_details');
        $table->addIndex(['sales_representative'])
            ->addIndex(['title_officer'])
            ->addIndex(['purchase_type'])
            ->update();

        $table = $this->table('pct_order_product_types');
        $table->addIndex(['product_type_id'])
            ->addIndex(['status'])
            ->update();

        $table = $this->table('pct_order_documents');
        $table->addIndex(['order_id'])
            ->addIndex(['is_grant_doc'])
            ->addIndex(['is_lv_doc'])
            ->addIndex(['is_tax_doc'])
            ->update();

        $table = $this->table('pct_order_api_logs');
        $table->addIndex(['user_id'])
            ->addIndex(['order_id'])
            ->update();

        $table = $this->table('pct_order_partner_company_info');
        $table->addIndex(['partner_id'])
            ->update();

        $table = $this->table('pct_order_title_officers_forms');
        $table->addIndex(['form_id'])
            ->update();
    }
}
