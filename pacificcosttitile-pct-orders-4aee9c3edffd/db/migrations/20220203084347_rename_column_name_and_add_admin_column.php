<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class RenameColumnNameAndAddAdminColumn extends AbstractMigration
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
        $table = $this->table('pct_hr_time_cards');
        $table->renameColumn('action_taken_user_id', 'approved_by_user_id')
              ->addColumn('approved_by_admin_user_id', 'integer', ['default' => 0, 'after' => 'approved_by_user_id'])
              ->save();

        $table = $this->table('pct_hr_vacation_requests');
        $table->renameColumn('action_taken_user_id', 'approved_by_user_id')
            ->addColumn('approved_by_admin_user_id', 'integer', ['default' => 0, 'after' => 'approved_by_user_id'])
            ->save();

        $table = $this->table('pct_hr_incident_reports');
        $table->renameColumn('action_taken_user_id', 'approved_by_user_id')
                ->addColumn('approved_by_admin_user_id', 'integer', ['default' => 0, 'after' => 'approved_by_user_id'])
                ->save();

    }
}
