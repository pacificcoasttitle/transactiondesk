<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class AddDeniedReasonInVactionRequest extends AbstractMigration
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
        $table = $this->table('pct_hr_vacation_requests');
        $table->addColumn('denied_reason', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'after'=>'is_time_charged_vacation', 'null' => true, 'default' => null])
              ->update();
    }
}
