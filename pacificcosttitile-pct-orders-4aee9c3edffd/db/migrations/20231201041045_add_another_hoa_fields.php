<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddAnotherHoaFields extends AbstractMigration
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
        $table = $this->table('pct_order_borrower_seller_packet_info');
        $table ->addColumn('second_hoa_company', 'string', ['null' => true])
            ->addColumn('second_hoa_company_address', 'string', ['null' => true])
            ->addColumn('second_hoa_contact_person', 'string', ['null' => true])
            ->addColumn('second_hoa_contact_number', 'string', ['null' => true])
            ->update();
    }
}
