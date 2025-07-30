<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class CreateVendorTable extends AbstractMigration
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
        $table = $this->table('pct_vendors');
        $table->addColumn('transctee_name', 'string', ['null' => true])
            ->addColumn('file_number', 'string', ['null' => true])
            ->addColumn('account_number', 'string', ['null' => true])
            ->addColumn('aba', 'string', ['null' => true])
            ->addColumn('bank_name', 'string', ['null' => true])
            ->addColumn('submitted', 'string', ['null' => true])
            ->addColumn('document_original_names', 'json', ['null' => true])
            ->addColumn('document_names', 'json', ['null' => true])
            ->addColumn('notes', 'string', ['null' => true])
            ->addColumn('admin_notes', 'string', ['null' => true])
            ->addColumn('approved_by', 'string', ['null' => true])
            ->addColumn('is_approved', 'boolean', ['default' => 0])
            ->addTimestamps()
            ->create();
    }
}
