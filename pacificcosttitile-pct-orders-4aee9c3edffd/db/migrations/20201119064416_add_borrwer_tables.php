<?php

use Phinx\Migration\AbstractMigration;

class AddBorrwerTables extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('pct_order_borrower_info');
        $table->addColumn('first_name', 'string', ['limit' => 255])
                ->addColumn('middle_name', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('last_name', 'string', ['limit' => 255])
                ->addColumn('telephone', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('mobile', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('date_of_birth', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('birthplace', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('ssn', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('dln', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('status', 'string', ['limit' => 255])
                ->addColumn('spouse_first_name', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('spouse_middle_name', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('spouse_last_name', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('spouse_telephone', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('spouse_mobile', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('spouse_date_of_birth', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('spouse_birthplace', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('spouse_ssn', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('spouse_dln', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('partner_first_name', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('partner_middle_name', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('partner_last_name', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('partner_telephone', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('partner_mobile', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('partner_date_of_birth', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('partner_birthplace', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('partner_ssn', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('partner_dln', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('order_id', 'integer', ['default' => 0])
                ->addColumn('partnership_status', 'string', ['limit' => 255])
                ->addColumn('prior_spouse_name', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('prior_spouse_reason', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('prior_spouse_end', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('current_spouse_prior_spouse_name', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('current_spouse_prior_spouse_reason', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('current_spouse_prior_spouse_end', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('street_address', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('buyer_intends_to_reside', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('land_is_unimproved', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('type_of_property', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('general_terms', 'boolean', ['default' => 0])
                ->addColumn('signature', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('spouse_signature', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('created_at', 'datetime')
                ->addColumn('updated_at', 'datetime', ['null' => true])
                ->create();
    }
}
