<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddExtraInBorrowerInfo extends AbstractMigration
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
        $table = $this->table('pct_order_borrower_info');
        $table->addColumn('work_done_last_6_month', 'string', ['after' => 'type_of_property', 'null' => true])
              ->addColumn('previously_married', 'string', ['after' => 'work_done_last_6_month', 'null' => true])
              ->update();
    }
}
