<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddTwoColumsInInstrment extends AbstractMigration
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
        $table->addColumn('type', 'string', ['after' => 'is_display','null' => true])
            ->addColumn('sub_type', 'string', ['after' => 'type','null' => true])
            ->addColumn('order_number', 'string', ['after' => 'sub_type','null' => true])
			->update();
    }
}
