<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddPartnerEmployeeId extends AbstractMigration
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
        $table = $this->table('agents');
        $table->addColumn('partner_employee_id', 'integer', ['default' => 0, 'after' => 'partner_id'])
              ->addColumn('is_listing_agent', 'boolean', ['default' => 0, 'after' => 'telephone_no'])
              ->addIndex(['partner_employee_id', 'partner_id'], ['unique' => true])
              ->update();
    }
}
