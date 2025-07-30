<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateEscrowInstruction extends AbstractMigration
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
        $table = $this->table('pct_order_escrow_instruction');
        $table->addColumn('order_number', 'integer')
            ->addColumn('name', 'string')
			->addTimestamps()
            ->create();

        $rows = [
			[
				'order_number'    => 1,
				'name'  => 'Escrow Number Custom'
            ],
            [
				'order_number'    => 2,
				'name'  => "Select Broker's Addendum Items"
			],
            [
				'order_number'    => 3,
				'name'  => 'Select Counter Offer to Addendum(s) to Purchase Agreement'
			],
            [
				'order_number'    => 4,
				'name'  => 'Select Escrow Instructions Clause I'
			],
            [
				'order_number'    => 5,
				'name'  => 'Select Escrow Instructions Clause II'
			],
            [
				'order_number'    => 6,
				'name'  => 'Select Escrow Instructions Clause III'
			],
            [
				'order_number'    => 7,
				'name'  => 'Select Escrow Instructions Clause IV'
			],
            [
				'order_number'    => 8,
				'name'  => 'Select Escrow Instructions Clause V'
			],
            [
				'order_number'    => 9,
				'name'  => 'Select Escrow Instructions Clause VI'
			],
            [
				'order_number'    => 10,
				'name'  => 'Select Escrow Instructions Clause VII'
			],
            [
				'order_number'    => 11,
				'name'  => 'Select Escrow Instructions Clause VIII'
			],
            [
				'order_number'    => 12,
				'name'  => 'Select Escrow Instructions Clause IX'
			],
            [
				'order_number'    => 13,
				'name'  => 'Select Escrow Instructions Clause X'
			],
            [
				'order_number'    => 14,
				'name'  => 'Select if Taxes are due and payable'
			],
            [
				'order_number'    => 15,
				'name'  => 'Select Proration Type'
			],
            [
				'order_number'    => 16,
				'name'  => 'Select Proration Type (Part 2) I'
			],
            [
				'order_number'    => 17,
				'name'  => 'Select Proration Type (Part 2) II'
			],
            [
				'order_number'    => 18,
				'name'  => 'Select Proration Type (Part 2) III'
			],
            [
				'order_number'    => 19,
				'name'  => 'Select Unincorporated Area of City'
			],
            [
				'order_number'    => 20,
				'name'  => 'Title Number Custom'
			]
		];
		$table->insert($rows)->saveData();
    }
}
