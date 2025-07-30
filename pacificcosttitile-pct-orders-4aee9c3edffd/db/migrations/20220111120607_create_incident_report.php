<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

final class CreateIncidentReport extends AbstractMigration
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
        $table = $this->table('pct_hr_incident_reports');
        $table->addColumn('user_id', 'integer')
            ->addColumn('employee_number', 'integer')
            ->addColumn('incident_date', 'date')
            ->addColumn('incident_reason', 'string')
            ->addColumn('incident_detail', 'text', ['limit' => MysqlAdapter::TEXT_LONG])
            ->addColumn('actions', 'string')
            ->addColumn('num_of_incidents', 'string')
            ->addColumn('created_at', 'datetime')
            ->addColumn('updated_at', 'datetime', ['null' => true])
            ->addIndex(['user_id'])  
            ->create();
    }
}
