<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateTablePCTOrderNationalFormData extends AbstractMigration
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
        $table = $this->table('pct_order_national_form_data');
        $table->addColumn('buyer_name', 'string', ['null' => true])
            ->addColumn('buyer_current_address', 'string', ['null' => true])
            ->addColumn('buyer_email', 'string', ['null' => true])
            ->addColumn('buyer_mobile', 'string', ['null' => true])
            ->addColumn('buyer_property_address', 'string', ['null' => true])
            ->addColumn('title_hold_reason', 'string', ['null' => true])
            ->addColumn('ssn', 'string', ['null' => true])
            ->addColumn('estimated_closing_date', 'string', ['null' => true])
            ->addColumn('lender', 'string', ['null' => true])
            ->addColumn('loan_amount', 'string', ['null' => true])
            ->addColumn('loan_number', 'string', ['null' => true])
            ->addColumn('type_of_loan', 'string', ['null' => true])
            ->addColumn('title_items_required_by', 'string', ['null' => true])
            ->addColumn('lender_clause', 'string', ['null' => true])
            ->addColumn('return_document_to', 'string', ['null' => true])
            ->addColumn('main_lender_contact', 'string', ['null' => true])
            ->addColumn('loan_officer', 'string', ['null' => true])
            ->addTimestamps()
            ->create();
    }
}
