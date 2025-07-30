<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class AddIndexOnTitlePointTableFields extends AbstractMigration
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
        $table->addIndex(['lp_file_number', 'resware_status', 'prod_type'])
            ->update();

        $table = $this->table('customer_basic_details');
        $table->addIndex(['is_password_updated'])
            ->update();

        $table = $this->table('pct_title_point_document_records');
        $table->addIndex(['title_point_id', 'is_ves_display'])
            ->update();
    }
}
