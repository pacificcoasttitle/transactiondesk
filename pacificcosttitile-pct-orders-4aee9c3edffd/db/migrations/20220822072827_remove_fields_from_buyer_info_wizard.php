<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RemoveFieldsFromBuyerInfoWizard extends AbstractMigration
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
		$table->removeColumn('first_name')
			->removeColumn('last_name')
			->removeColumn('email')
			->removeColumn('phone')
			->removeColumn('birth_month')
			->removeColumn('birth_date')
			->removeColumn('birth_year')
			->removeColumn('ssn')
			->removeColumn('current_mailing_address')
			->removeColumn('mailing_address_port_closing')
			->removeColumn('is_another_buyer')
			->removeColumn('second_first_name')
			->removeColumn('second_last_name')
			->removeColumn('second_email')
			->removeColumn('second_phone')
			->removeColumn('second_birth_month')
			->removeColumn('second_birth_date')
			->removeColumn('second_birth_year')
			->removeColumn('second_ssn')
			->removeColumn('second_current_mailing_address')
			->removeColumn('second_mailing_address_port_closing')

			->update();
    }
}
