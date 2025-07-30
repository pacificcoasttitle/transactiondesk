<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateBorrowerSellerInfoTable extends AbstractMigration
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
        $table = $this->table('pct_order_borrower_selller_info');
        $table->addColumn('order_id', 'integer')
            ->addColumn('first_name', 'string')
            ->addColumn('middle_name', 'string')
            ->addColumn('last_name', 'string')
            ->addColumn('phone_number', 'string')
            ->addColumn('phone_number_type', 'string')
            ->addColumn('foreign_resident', 'enum', ['values' => ['yes','no']])
            ->addColumn('co_seller', 'enum', ['values' => ['yes','no']])
            ->addColumn('co_seller_first_name', 'string', ['null' => true])
            ->addColumn('co_seller_middle_name', 'string', ['null' => true])
            ->addColumn('co_seller_last_name', 'string', ['null' => true])
            ->addColumn('co_seller_expiration_date', 'date', ['null' => true])
            ->addColumn('co_seller_marital_status', 'enum', ['null' => true, 'values' => ['single', 'married']])
            ->addColumn('co_seller_ssn', 'string', ['null' => true])
            ->addColumn('co_seller_email', 'string', ['null' => true])
            ->addColumn('co_seller_phone_number', 'string', ['null' => true])
            ->addColumn('co_seller_phone_number_type', 'string', ['null' => true])
            ->addColumn('co_seller_foreign_resident', 'enum', ['values' => ['yes','no'], 'null' => true])
            ->addColumn('attending', 'enum', ['values' => ['yes','no'], 'null' => true])
            ->addTimestamps()
            ->create();

        $table = $this->table('pct_order_borrower_selller_property_info');
        $table->addColumn('order_id', 'integer')
            ->addColumn('property_address', 'string')
            ->addColumn('is_correct_property_address', 'enum', ['values' => ['yes','no']])
            ->addColumn('property_street_address', 'string', ['null' => true])
            ->addColumn('property_city', 'string', ['null' => true])
            ->addColumn('property_state', 'string', ['null' => true])
            ->addColumn('property_zip_code', 'string', ['null' => true])
            ->addColumn('is_property_address_as_current_address', 'enum', ['values' => ['yes','no']])
            ->addColumn('current_street_address', 'string', ['null' => true])
            ->addColumn('current_city', 'string', ['null' => true])
            ->addColumn('current_state', 'string', ['null' => true])
            ->addColumn('current_zip_code', 'string', ['null' => true])
            ->addColumn('is_forwarding_address_different_from_current_address', 'enum', ['values' => ['yes','no']])
            ->addColumn('forwarding_street_address', 'string', ['null' => true])
            ->addColumn('forwarding_city', 'string', ['null' => true])
            ->addColumn('forwarding_state', 'string', ['null' => true])
            ->addColumn('forwarding_zip_code', 'string', ['null' => true])
            ->addColumn('residence', 'string')
            ->addColumn('is_insurance_policy', 'enum', ['values' => ['yes','no']])
            ->addColumn('insurance_policy_file_name', 'string', ['null' => true])
            ->addTimestamps()
            ->create();
        
        $table = $this->table('pct_order_borrower_selller_agent_info');
        $table->addColumn('order_id', 'integer')
            ->addColumn('is_real_estate', 'enum', ['values' => ['yes','no']])
            ->addColumn('agent_first_name', 'string', ['null' => true])
            ->addColumn('agent_middle_name', 'string', ['null' => true])
            ->addColumn('agent_last_name', 'string', ['null' => true])
            ->addColumn('agent_company', 'string', ['null' => true])
            ->addColumn('agent_company_address', 'string', ['null' => true])
            ->addColumn('agent_company_city', 'string', ['null' => true])
            ->addColumn('agent_company_state', 'string', ['null' => true])
            ->addColumn('agent_company_zip_code', 'string', ['null' => true])
            ->addColumn('amount_percent_commission', 'string', ['null' => true])
            ->addColumn('amount_deduction', 'string', ['null' => true])
            ->addColumn('agent_phone', 'string', ['null' => true])
            ->addColumn('agent_email', 'string', ['null' => true])
            ->addColumn('seller_invoices', 'string', ['null' => true])
            ->addTimestamps()
            ->create();
        
        $table = $this->table('pct_order_borrower_selller_mortgage_info');
        $table->addColumn('order_id', 'integer')
            ->addColumn('is_mortgage', 'enum', ['values' => ['yes','no']])
            ->addColumn('is_mortgage_credit', 'enum', ['values' => ['yes','no'], 'null' => true])
            ->addColumn('mortgage_holder', 'string', ['null' => true])
            ->addColumn('loan_amount', 'string', ['null' => true])
            ->addColumn('mortgage_phone', 'string', ['null' => true])
            ->addColumn('loan_number', 'string', ['null' => true])
            ->addColumn('loan_balance', 'string', ['null' => true])
            ->addColumn('account_holder_name', 'string', ['null' => true])
            ->addColumn('is_creditcard_lock', 'enum', ['values' => ['yes','no'], 'null' => true])
            ->addColumn('is_second_mortgage', 'enum', ['values' => ['yes','no'], 'null' => true])
            ->addColumn('is_second_mortgage_credit', 'enum', ['values' => ['yes','no'], 'null' => true])
            ->addColumn('second_mortgage_holder', 'string', ['null' => true])
            ->addColumn('second_loan_amount', 'string', ['null' => true])
            ->addColumn('second_mortgage_phone', 'string', ['null' => true])
            ->addColumn('second_loan_number', 'string', ['null' => true])
            ->addColumn('second_loan_balance', 'string', ['null' => true])
            ->addColumn('second_account_holder_name', 'string', ['null' => true])
            ->addColumn('is_second_creditcard_lock', 'enum', ['values' => ['yes','no'], 'null' => true])
            ->addTimestamps()
            ->create();

        $table = $this->table('pct_order_borrower_selller_other_info');
        $table->addColumn('order_id', 'integer')
            ->addColumn('is_exchange_residence', 'enum', ['values' => ['true','false']])
            ->addColumn('is_not_exchange_residence', 'enum', ['values' => ['true','false']])
            ->addColumn('is_former_spouse', 'enum', ['values' => ['true','false']])
            ->addColumn('is_married', 'enum', ['values' => ['true','false']])
            ->addColumn('is_period', 'enum', ['values' => ['true','false']])
            ->addColumn('is_revenue', 'enum', ['values' => ['true','false', 'n/a']])
            ->addColumn('is_attorney', 'enum', ['values' => ['yes','no']])
            ->addColumn('firm_name', 'string', ['null' => true])
            ->addColumn('firm_phone_number', 'string', ['null' => true])
            ->addColumn('attorney_name', 'string', ['null' => true])
            ->addColumn('attorney_phone_number', 'string', ['null' => true])
            ->addColumn('attorney_email', 'string', ['null' => true])
            ->addTimestamps()
            ->create();

        $table = $this->table('pct_order_borrower_selller_hoa_info');
        $table->addColumn('order_id', 'integer')
            ->addColumn('is_property_hoa', 'enum', ['values' => ['yes','no']])
            ->addColumn('hoa_management_company_name', 'string', ['null' => true])
            ->addColumn('hoa_contact_person', 'string', ['null' => true])
            ->addColumn('hoa_email', 'string', ['null' => true])
            ->addColumn('hoa_phone', 'string', ['null' => true])
            ->addColumn('hoa_dues', 'string', ['null' => true])
            ->addColumn('hoa_dues_per', 'string', ['null' => true])
            ->addColumn('hoa_notes', 'string', ['null' => true])
            ->addColumn('is_property_second_hoa', 'enum', ['values' => ['yes','no'], 'null' => true])
            ->addColumn('second_hoa_management_company_name', 'string', ['null' => true])
            ->addColumn('second_hoa_contact_person', 'string', ['null' => true])
            ->addColumn('second_hoa_email', 'string', ['null' => true])
            ->addColumn('second_hoa_phone', 'string', ['null' => true])
            ->addColumn('second_hoa_dues', 'string', ['null' => true])
            ->addColumn('second_hoa_dues_per', 'string', ['null' => true])
            ->addColumn('second_hoa_notes', 'string', ['null' => true])
            ->addTimestamps()
            ->create();
    }
}
