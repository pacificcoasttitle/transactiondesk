<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddMissingColumnInstatementTable extends AbstractMigration
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
        $table = $this->table('pct_order_borrower_buyer_statement_of_info');
		$table->addColumn('date_and_place_marriage', 'string', ['null' => true, 'after' => 'is_married'])
            ->addColumn('firm_or_business_name', 'string', ['after' => 'second_residence_from_date_to_date'])
            ->addColumn('business_address', 'string', ['after' => 'firm_or_business_name'])
            ->addColumn('business_from_date_to_date', 'string', ['after' => 'business_address'])
            ->addColumn('second_firm_or_business_name', 'string', ['after' => 'business_from_date_to_date', 'null' => true])
            ->addColumn('second_business_address', 'string', ['after' => 'second_firm_or_business_name', 'null' => true])
            ->addColumn('second_business_from_date_to_date', 'string', ['after' => 'second_business_address', 'null' => true])
			->update();
    }
}
