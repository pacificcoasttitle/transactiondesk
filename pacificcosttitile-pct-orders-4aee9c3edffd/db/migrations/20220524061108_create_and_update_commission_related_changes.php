<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateAndUpdateCommissionRelatedChanges extends AbstractMigration
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
		$table = $this->table('pct_underwriter_users');
		$table->removeColumn('threshold_amount')
				->removeColumn('threshold_commission')
            ->update();

		$table = $this->table('pct_underwriter_users_threshold');
        $table->addColumn('underwriter_users_id', 'integer')
			->addColumn('threshold_amount_min', 'integer',['signed'=>FALSE])
			->addColumn('threshold_amount_max', 'integer',['signed'=>FALSE])
			->addColumn('threshold_commission', 'decimal', ['default' => 0 , 'null' => true, 'precision'=>5,'scale'=>2])
			->addForeignKey('underwriter_users_id', 'pct_underwriter_users', 'id',['delete'=> 'CASCADE', 'update'=> 'CASCADE'])
            ->create();

    }
}
