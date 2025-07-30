<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddMailSentFlagForBuyerSeller extends AbstractMigration
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
        $table->addColumn('is_buyer_packet_mail_sent', 'boolean', ['after' => 'lp_report_status', 'default' => 0])
                ->addColumn('is_seller_packet_mail_sent', 'boolean', ['after' => 'lp_report_status', 'default' => 0])
                ->update();
    }
}
