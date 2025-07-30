<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateSellerInterviewInfo extends AbstractMigration
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
        $table = $this->table('pct_order_borrower_seller_info');
        $table->addColumn('order_id', 'integer')
            ->addColumn('first_name', 'string', ['null' => true])
			->addColumn('last_name', 'string', ['null' => true])
			->addColumn('email', 'string', ['null' => true])
            ->addColumn('phone', 'string', ['null' => true])
			->addColumn('birth_month', 'string', ['null' => true])
            ->addColumn('birth_date', 'string', ['null' => true])
			->addColumn('birth_year', 'string', ['null' => true])
            ->addColumn('ssn1', 'string', ['null' => true])
			->addColumn('ssn2', 'string', ['null' => true])
            ->addColumn('ssn3', 'string', ['null' => true])
			->addColumn('current_mailing_address', 'string', ['null' => true ])
            ->addColumn('mailing_address_port_closing', 'string', ['null' => true ])
			->addColumn('is_another_seller', 'string', ['null' => true ])
            ->addColumn('second_first_name', 'string', ['null' => true])
			->addColumn('second_last_name', 'string', ['null' => true])
            ->addColumn('second_email', 'string', ['null' => true])
			->addColumn('second_phone', 'string', ['null' => true])
            ->addColumn('second_birth_month', 'string', ['null' => true])
			->addColumn('second_birth_date', 'string', ['null' => true])
            ->addColumn('second_birth_year', 'string', ['null' => true])
			->addColumn('second_ssn1', 'string', ['null' => true])
            ->addColumn('second_ssn2', 'string', ['null' => true])
			->addColumn('second_ssn3', 'string', ['null' => true])
            ->addColumn('second_current_mailing_address', 'string', ['null' => true])
			->addColumn('second_mailing_address_port_closing', 'string', ['null' => true])
            ->addColumn('is_trustee', 'string', ['null' => true])
			->addColumn('current_trustees', 'string', ['null' => true])
            ->addColumn('is_original_trustees', 'string', ['null' => true])
			->addColumn('is_limited_company', 'string', ['null' => true])
            ->addColumn('is_married', 'string', ['null' => true])
            ->addColumn('is_property_sell', 'string', ['null' => true])
			->addColumn('is_property_owned_free_clear', 'string', ['null' => true])
            ->addColumn('lender_name', 'string', ['null' => true])
			->addColumn('lender_address', 'string', ['null' => true])
            ->addColumn('loan_number', 'string', ['null' => true])
			->addColumn('lender_phone_number', 'string', ['null' => true])
            ->addColumn('unpaid_balance', 'string', ['null' => true])
			->addColumn('payment_due_date', 'string', ['null' => true])
            ->addColumn('loan_type', 'string', ['null' => true])
			->addColumn('is_impound_account', 'string', ['null' => true])
            ->addColumn('is_another_loan', 'string', ['null' => true])
            ->addColumn('second_lender_name', 'string', ['null' => true])
			->addColumn('second_lender_address', 'string', ['null' => true])
            ->addColumn('second_loan_number', 'string', ['null' => true])
			->addColumn('second_lender_phone_number', 'string', ['null' => true])
            ->addColumn('second_unpaid_balance', 'string', ['null' => true])
			->addColumn('second_payment_due_date', 'string', ['null' => true])
            ->addColumn('second_loan_type', 'string', ['null' => true])
			->addColumn('second_is_impound_account', 'text', ['null' => true])
            ->addColumn('second_tax_status', 'string', ['null' => true])
			->addColumn('second_is_paid_impound', 'string', ['null' => true])
            ->addColumn('is_private_water_company', 'string', ['null' => true])
            ->addColumn('water_company', 'string', ['null' => true])
			->addColumn('water_company_address', 'string', ['null' => true])
            ->addColumn('water_account_number', 'string', ['null' => true])
            ->addColumn('water_phone_number', 'string', ['null' => true])
            ->addColumn('is_hoa', 'string', ['null' => true])
			->addColumn('hoa_company', 'string', ['null' => true])
            ->addColumn('hoa_company_address', 'string', ['null' => true])
			->addColumn('hoa_contact_person', 'string', ['null' => true])
            ->addColumn('hoa_contact_number', 'string', ['null' => true])
			->addTimestamps()
            ->create();
    }
}
