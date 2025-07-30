<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateBorroweBuyerInfoTable extends AbstractMigration
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
		$table = $this->table('pct_order_borrower_buyer_info_wizard');
        $table->addColumn('order_id', 'integer')
            ->addColumn('first_name', 'string', ['null' => true])
			->addColumn('last_name', 'string', ['null' => true])
			->addColumn('email', 'string', ['null' => true])
            ->addColumn('phone', 'string', ['null' => true])
			->addColumn('birth_month', 'string', ['null' => true])
            ->addColumn('birth_date', 'string', ['null' => true])
			->addColumn('birth_year', 'string', ['null' => true])
            ->addColumn('ssn', 'string', ['null' => true])
			->addColumn('current_mailing_address', 'string', ['null' => true ])
            ->addColumn('mailing_address_port_closing', 'string', ['null' => true ])
			->addColumn('is_another_buyer', 'boolean', ['null' => true,'default'=>0 ])
            ->addColumn('second_first_name', 'string', ['null' => true])
			->addColumn('second_last_name', 'string', ['null' => true])
            ->addColumn('second_email', 'string', ['null' => true])
			->addColumn('second_phone', 'string', ['null' => true])
            ->addColumn('second_birth_month', 'string', ['null' => true])
			->addColumn('second_birth_date', 'string', ['null' => true])
            ->addColumn('second_birth_year', 'string', ['null' => true])
			->addColumn('second_ssn', 'string', ['null' => true])
            ->addColumn('second_current_mailing_address', 'string', ['null' => true])
			->addColumn('second_mailing_address_port_closing', 'string', ['null' => true])
			->addColumn('is_same_property', 'boolean', ['null' => true,'default'=>0 ])
			->addColumn('loan_amount', 'string', ['null' => true])
			->addColumn('lender_name', 'string', ['null' => true])
			->addColumn('loan_officer_name', 'string', ['null' => true])
			->addColumn('loan_officer_email', 'string', ['null' => true])
			->addColumn('loan_officer_phone', 'string', ['null' => true])
			->addColumn('is_loan_processor', 'boolean', ['null' => true,'default'=>0 ])
			->addColumn('loan_processor_name', 'string', ['null' => true])
			->addColumn('loan_processor_email', 'string', ['null' => true])
			->addColumn('loan_processor_phone', 'string', ['null' => true])
			->addColumn('is_home_ins', 'boolean', ['null' => true,'default'=>0 ])
			->addColumn('ins_agency_name', 'string', ['null' => true])
			->addColumn('ins_agent_name', 'string', ['null' => true])
			->addColumn('ins_agent_email', 'string', ['null' => true])
			->addColumn('ins_agent_phone', 'string', ['null' => true])
			->addColumn('annual_premium', 'string', ['null' => true])
			->addTimestamps()
            ->create();

    }
}
