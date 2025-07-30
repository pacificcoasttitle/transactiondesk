<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddSellerInfoInOrderDetail extends AbstractMigration
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
        $table = $this->table('order_details');
        $table->addColumn('borrower_info_submitted_for_seller', 'boolean', ['default' => 0, 'after' => 'borrower_info_submitted'])
              ->addColumn('verification_code_for_seller', 'string', ['null' => true, 'after' => 'verification_code'])
              ->addColumn('code_created_at_for_seller', 'datetime', ['null' => true, 'after' => 'code_created_at'])
              ->addColumn('is_code_verified_for_seller', 'boolean', ['default' => 0, 'after' => 'is_code_verified'])
              ->addColumn('borrower_mobile_number_for_seller', 'string', ['null' => true, 'after' => 'borrower_mobile_number'])
              ->update();
    }
}
