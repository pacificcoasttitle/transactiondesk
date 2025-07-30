<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateTableForReswareLog extends AbstractMigration
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
        $table = $this->table('pct_resware_log');
        $table->addColumn('request_url', 'string', ['null' => false])
            ->addColumn('request_type', 'string', ['null' => false])
            ->addColumn('file_id', 'string', ['null' => true])
            ->addColumn('file_number', 'string', ['null' => true])
            ->addColumn('request', 'string', ['null' => false])
            ->addColumn('response', 'text', ['null' => false])
            ->addColumn('status', 'string', ['null' => true])
			->addTimestamps()
            ->create();
    }
}
