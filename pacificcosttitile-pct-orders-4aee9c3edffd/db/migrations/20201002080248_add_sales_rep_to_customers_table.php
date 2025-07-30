<?php

use Phinx\Migration\AbstractMigration;

class AddSalesRepToCustomersTable extends AbstractMigration
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
        $rows = $this->fetchAll('SELECT * FROM pct_order_sales_rep ORDER BY id DESC');
        $data = array();
        if(isset($rows) && !empty($rows))
        {
            foreach ($rows as $key => $value) 
            {
                $full_name = $value['name'];
                $name = explode(" ", $full_name);
                $first_name = $name[0];
                unset($name[0]);
                $last_name = implode(" ", $name);
                $data[] = array(
                    'partner_id' => $value['partner_id'], 
                    'partner_type_id' => $value['partner_type_id'],
                    'first_name' => $first_name, 
                    'last_name' => $last_name, 
                    'title' => '', 
                    'telephone_no' => $value['telephone'], 
                    'email_address' => $value['email_address'], 
                    'password' => 'Pacific3', 
                    'random_password' => 'Pacific3', 
                    'company_name' => '', 
                    'street_address' => '', 
                    'city' => '', 
                    'zip_code' => '' , 
                    'is_escrow' => 0 , 
                    'lender_type' => null, 
                    'status' => $value['status'], 
                    'is_master' => 0, 
                    'is_sales_rep' => 1, 
                    'is_password_updated' => 1, 
                    'is_mail_notification' => $value['is_mail_notification'], 
                    'created_at' => date('Y-m-d H:i:s')
                );
            }
        }
        $table = $this->table('customer_basic_details');
        $table->insert($data);
        $table->saveData();
    }
}
