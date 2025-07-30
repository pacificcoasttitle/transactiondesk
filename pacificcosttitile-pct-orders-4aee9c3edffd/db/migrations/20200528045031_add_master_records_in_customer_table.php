<?php

use Phinx\Migration\AbstractMigration;

class AddMasterRecordsInCustomerTable extends AbstractMigration
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
        $data = [
            ['first_name' => 'Master', 'last_name' => 'User', 'telephone_no' => '(714) 516-6700', 'email_address' => 'cs@pct.com', 'password' => '82e23cb4cb797d12ebbbc47690622b5d', 'company_name' => 'Master Company', 'street_address' => '1111 E. Katella Ave Ste.120', 'city' => 'Orange', 'zip_code' => '92867' , 'is_escrow' => 0 , 'lender_type' => null, 'status' => 1, 'is_master' => 1, 'sales_rep_report_image' => '', 'created_at' => date('Y-m-d H:i:s')]
        ];
        $table = $this->table('customer_basic_details');
        $table->insert($data);
        $table->saveData();   
    }
}
