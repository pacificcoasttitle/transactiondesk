<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddThreshodCommissionsToUnderwriterUsers extends AbstractMigration
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
        $table->addColumn('allow_threshold', 'boolean',['default'=>0])
				->addColumn('threshold_amount', 'decimal',['default' => 0 , 'null' => true, 'precision'=>9,'scale'=>2])
				->addColumn('threshold_commission', 'decimal', ['default' => 0 , 'null' => true, 'precision'=>5,'scale'=>2])
            ->update();

    }
}
