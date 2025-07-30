<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class CreateVacationRequest extends AbstractMigration
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
        $table->addColumn('user_id', 'integer')
            ->addColumn('from_date', 'date')
            ->addColumn('to_date', 'date')
            ->addColumn('comment', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
            ->addColumn('is_salary_deduction', 'boolean', ['default' => 0])
            ->addColumn('is_time_charged_vacation', 'boolean', ['default' => 0])
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex(['user_id'])  
            ->create();
    }
}
