<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class ChangeTablePctLpDocumentTypes extends AbstractMigration
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
        $table = $this->table('pct_lp_document_types');
        $table->removeColumn('category')
            ->removeColumn('description')
            ->addColumn('doc_sub_type_description', 'text', ['after' => 'doc_type','null' => false])
            ->addColumn('doc_type_description', 'text', ['after' => 'doc_type','null' => false])
			->update();
    }
}
