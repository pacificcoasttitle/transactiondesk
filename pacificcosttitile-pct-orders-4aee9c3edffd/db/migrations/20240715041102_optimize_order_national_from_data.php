<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class OptimizeOrderNationalFromData extends AbstractMigration
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
        $table->changeColumn('buyer_name', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('buyer_email', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('buyer_mobile', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('title_hold_reason', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('ssn', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('estimated_closing_date', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('lender', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('loan_amount', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('loan_number', 'string', ['limit' => 100, 'null' => true])
            ->changeColumn('title_items_required_by', 'string', ['limit' => 150, 'null' => true])
            ->changeColumn('lender_clause', 'string', ['limit' => 150, 'null' => true])
            ->changeColumn('return_document_to', 'string', ['limit' => 150, 'null' => true])
            ->changeColumn('loan_officer', 'string', ['limit' => 150, 'null' => true])
            ->update();
    }
}
