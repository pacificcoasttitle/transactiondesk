<?php
declare (strict_types = 1);

use Phinx\Migration\AbstractMigration;

final class OptimizeLpDocumentType extends AbstractMigration
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
        $table->changeColumn('doc_type', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('doc_sub_type', 'string', ['limit' => 20, 'null' => true])
            ->changeColumn('display_in_section', 'string', ['limit' => 5, 'null' => true])
            ->changeColumn('map_in_section', 'string', ['limit' => 20, 'null' => true])
            ->update();

        $table = $this->table('pct_lp_document_types');
        $table->addIndex(['subtype_flag', 'is_display', 'display_in_section', 'doc_type'])
            ->update();
    }
}
