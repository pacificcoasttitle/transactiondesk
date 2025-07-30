<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddColumnBorrowerInDocument extends AbstractMigration
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
        $table = $this->table('pct_order_notes');
        $table->removeIndexByName('note_id')
            ->save();

        $table = $this->table('pct_order_documents');
        $table->addColumn('is_uploaded_by_borrower', 'boolean', ['default' => 0 , 'after'=>'is_safewire_doc'])
            ->update();
    }
}
