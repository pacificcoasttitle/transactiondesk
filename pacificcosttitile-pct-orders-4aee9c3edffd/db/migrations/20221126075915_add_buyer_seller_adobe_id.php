<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddBuyerSellerAdobeId extends AbstractMigration
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
        $table = $this->table('pct_order_documents');
        $table->addColumn('is_buyer_pdf_adobe_doc', 'boolean', ['null' => true, 'default'=>0])
            ->addColumn('is_seller_pdf_adobe_doc', 'boolean', ['null' => true,'default'=>0])
            ->addColumn('is_buyer_pdf_sign', 'boolean', ['null' => true, 'default'=>0])
            ->addColumn('is_seller_pdf_sign', 'boolean', ['null' => true, 'default'=>0])
            ->update();
    }
}
