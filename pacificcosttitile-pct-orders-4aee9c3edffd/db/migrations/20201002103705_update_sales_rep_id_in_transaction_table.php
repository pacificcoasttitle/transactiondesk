<?php

use Phinx\Migration\AbstractMigration;

class UpdateSalesRepIdInTransactionTable extends AbstractMigration
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
        $rows = $this->fetchAll('SELECT * FROM pct_order_sales_rep');
        
        if(isset($rows) && !empty($rows))
        {
            foreach ($rows as $key => $value) 
            {
                $email = $value['email_address'];

                $row = $this->fetchRow('SELECT id FROM customer_basic_details WHERE email_address ="'.$email.'"');
                $sales_rep_id = $row['id'];
                $this->execute('UPDATE transaction_details SET sales_representative = '.$sales_rep_id.' WHERE sales_representative ='.$value['id']);
            }
        }
    }
}
