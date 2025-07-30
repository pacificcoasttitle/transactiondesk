<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class OptimizeTitlePointDocumentRecords extends AbstractMigration
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
        $table = $this->table('pct_title_point_document_records');
        $table->changeColumn('document_name', 'string', ['limit' => 100, 'null' => true])
            ->changeColumn('document_type', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('loan_amount', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('type', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('sub_type', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('order_number', 'string', ['limit' => 150, 'null' => true])
            ->changeColumn('color_coding', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('icon_text', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('document_sub_type', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('coupling', 'string', ['limit' => 10, 'null' => true])
            ->changeColumn('amount', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('recorded_date', 'string', ['limit' => 20, 'null' => true, 'null' => true])
            ->changeColumn('instrument', 'string', ['limit' => 100, 'null' => true])
            ->changeColumn('display_in_section', 'string', ['limit' => 5, 'null' => true])
            ->update();
    }
}
