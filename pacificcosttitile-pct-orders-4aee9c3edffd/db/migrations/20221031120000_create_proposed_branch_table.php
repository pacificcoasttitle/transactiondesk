<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateProposedBranchTable extends AbstractMigration
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
        $table = $this->table('pct_order_proposed_branches');
        $table->addColumn('address', 'string')
            ->addColumn('city', 'string')
            ->addColumn('state', 'string')
            ->addColumn('zip', 'string')
            ->addTimestamps()
            ->create();
        
        $rows = [
            [
                'id'    => 3,
                'address'  => '516 Burchett St. Ste. 100',
                'city'  => 'Glendale',
                'state'  => 'CA',
                'zip'  => '91203'
            ],
            [
                'id'    => 4,
                'address'  => '1111 E. Katella Avenue, #120',
                'city'  => 'Orange',
                'state'  => 'CA',
                'zip'  => '92867'
            ],
            [
                'id'    => 5,
                'address'  => '1000 Town Center Drive, #300',
                'city'  => 'Oxnard',
                'state'  => 'CA',
                'zip'  => '93036'
            ],
            [
                'id'    => 6,
                'address'  => '2655 Camino Del Rio North, #210 ',
                'city'  => 'San Diego',
                'state'  => 'CA',
                'zip'  => '92108'
            ]
        ];

        $table->insert($rows)->saveData();
		$table = $this->table('pct_order_westcore_branches');
    }
}
