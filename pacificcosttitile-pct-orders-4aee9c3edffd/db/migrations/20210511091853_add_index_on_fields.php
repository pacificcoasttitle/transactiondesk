<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddIndexOnFields extends AbstractMigration
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
        $table->addIndex(['file_id'])
              ->addIndex(['file_number'])
              ->addIndex(['property_id'])
              ->addIndex(['transaction_id'])
              ->addIndex(['customer_id'])
              ->addIndex(['partner_api_log_id'])
              ->update();
    }
}
