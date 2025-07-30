<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateNewBuyerPacket extends AbstractMigration
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
        $table = $this->table('pct_order_borrower_buyer_info_wizard_2');
		$table->addColumn('order_id', 'integer')
                ->addColumn('is_used_another_last_name', 'string', ['null' => true, 'after'=>'hoa_contact_number'])
                ->addColumn('another_last_name', 'string', ['null' => true, 'after'=>'is_used_another_last_name'])
                ->addColumn('is_married_or_domestic_partner', 'string', ['null' => true, 'after'=>'another_last_name'])
                ->addColumn('marriage_or_domestic_day', 'string', ['null' => true, 'after'=>'is_married_or_domestic_partner'])
                ->addColumn('marriage_or_domestic_month', 'string', ['null' => true, 'after'=>'marriage_or_domestic_day'])
                ->addColumn('marriage_or_domestic_year', 'string', ['null' => true, 'after'=>'marriage_or_domestic_month'])
                ->addColumn('spouse_first_name', 'string', ['null' => true, 'after'=>'marriage_or_domestic_year'])
                ->addColumn('spouse_last_name', 'string', ['null' => true, 'after'=>'spouse_first_name'])
                ->addColumn('spouse_email', 'string', ['null' => true, 'after'=>'spouse_last_name'])
                ->addColumn('spouse_phone', 'string', ['null' => true, 'after'=>'spouse_email'])
                ->addColumn('spouse_birth_day', 'string', ['null' => true, 'after'=>'spouse_phone'])
                ->addColumn('spouse_birth_month', 'string', ['null' => true, 'after'=>'spouse_birth_day'])
                ->addColumn('spouse_birth_year', 'string', ['null' => true, 'after'=>'spouse_birth_month'])
                ->addColumn('spouse_ssn', 'string', ['null' => true, 'after'=>'spouse_birth_year'])
                ->addColumn('is_property_sell_2', 'string', ['null' => true, 'after'=>'spouse_ssn'])
                ->addColumn('another_property_sell', 'string', ['null' => true, 'after'=>'is_property_sell'])
                ->addColumn('from_date', 'string', ['null' => true, 'after'=>'hoa_contact_number'])
                ->addColumn('from_to', 'string', ['null' => true, 'after'=>'from_date'])
                ->addColumn('is_another_residence', 'string', ['null' => true, 'after'=>'from_to'])
                ->addColumn('another_residence', 'string', ['null' => true, 'after'=>'is_another_residence'])
                ->addColumn('another_from_date', 'string', ['null' => true, 'after'=>'another_residence'])
                ->addColumn('another_to_date', 'string', ['null' => true, 'after'=>'another_from_date'])
                ->addColumn('is_currently_employed', 'string', ['null' => true, 'after'=>'another_to_date'])
                ->addColumn('employee_company_name', 'string', ['null' => true, 'after'=>'is_currently_employed'])
                ->addColumn('from_employee_date', 'string', ['null' => true, 'after'=>'employee_company_name'])
                ->addColumn('to_employee_date', 'string', ['null' => true, 'after'=>'from_employee_date'])
                ->addColumn('is_add_another_occupation', 'string', ['null' => true, 'after'=>'to_employee_date'])
                ->addColumn('employee_another_company_name', 'string', ['null' => true, 'after'=>'is_add_another_occupation'])
                ->addColumn('another_from_employee_date', 'string', ['null' => true, 'after'=>'employee_another_company_name'])
                ->addColumn('another_to_employee_date', 'string', ['null' => true, 'after'=>'another_from_employee_date'])
                ->addColumn('is_spouse_domestic_partner_employed', 'string', ['null' => true, 'after'=>'another_to_employee_date'])
                ->addColumn('spouse_company_name', 'string', ['null' => true, 'after'=>'is_spouse_domestic_partner_employed'])
                ->addColumn('from_spouse_date', 'string', ['null' => true, 'after'=>'spouse_company_name'])
                ->addColumn('to_spouse_date', 'string', ['null' => true, 'after'=>'from_spouse_date'])
                ->addColumn('is_another_occupation_spouse_domestic', 'string', ['null' => true, 'after'=>'to_spouse_date'])
                ->addColumn('another_spouse_company_name', 'string', ['null' => true, 'after'=>'is_another_occupation_spouse_domestic'])
                ->addColumn('another_from_spouse_date', 'string', ['null' => true, 'after'=>'another_spouse_company_name'])
                ->addColumn('another_to_spouse_date', 'string', ['null' => true, 'after'=>'another_from_spouse_date'])
                ->addTimestamps()
                ->create();
    }
}
