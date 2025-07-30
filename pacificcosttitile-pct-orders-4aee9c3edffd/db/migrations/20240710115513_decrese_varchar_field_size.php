<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class DecreseVarcharFieldSize extends AbstractMigration
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
        $table = $this->table('property_details');
        $table->changeColumn('city', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('state', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('zip', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('property_type', 'string', ['limit' => 100, 'null' => true])
            ->changeColumn('apn', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('county', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('primary_owner', 'string', ['limit' => 100, 'null' => true])
            ->changeColumn('secondary_owner', 'string', ['limit' => 100, 'null' => true])
            ->changeColumn('cpl_proposed_property_city', 'string', ['limit' => 100, 'null' => true])
            ->changeColumn('cpl_proposed_property_state', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('cpl_proposed_property_zip', 'string', ['limit' => 50, 'null' => true])
            ->changeColumn('unit_number', 'string', ['limit' => 20, 'null' => true])
            ->update();

    }
}
