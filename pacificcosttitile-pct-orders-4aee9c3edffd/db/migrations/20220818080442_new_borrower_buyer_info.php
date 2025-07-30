<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class NewBorrowerBuyerInfo extends AbstractMigration
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
        $table = $this->table('pct_order_borrower_buyer_info');
        $table->rename('pct_order_borrower_buyer_packet_info')->update();

        $table = $this->table('pct_order_borrower_buyer_info');
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
            ->addColumn('is_main_buyer', 'boolean', ['null' => true, 'default' => 0])
			->addTimestamps()
            ->create();
    }
}
