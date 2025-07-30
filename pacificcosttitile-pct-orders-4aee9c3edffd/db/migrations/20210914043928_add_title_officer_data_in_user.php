<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddTitleOfficerDataInUser extends AbstractMigration
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
        $result = $this->fetchAll('SELECT * FROM `pct_order_title_officer` WHERE STATUS = 1');
        foreach($result as $res) {
            $name = explode(' ', $res['name']);
            $oldTitleOfficerId = $res['id'];
            $data = [
                [
                    'resware_user_id'  => 0,
                    'partner_id'  => $res['partner_id'],
                    'partner_type_id'  => $res['partner_type_id'],
                    'first_name'  => $name[0],
                    'last_name'  => $name[1],
                    'password'  => 'Pacific1',
                    'company_name'  => 'Title Officer Company',
                    'telephone_no'  => $res['phone'],
                    'email_address'  => $res['email_address'],
                    'sales_rep_report_image' => '',
                    'status'  => 1,
                    'is_password_updated'  => 1,
                    'is_title_officer'  => 1,
                    'created_at'  => date('Y-m-d H:i:s')
                ]
            ];
            $table = $this->table('customer_basic_details');
            $table->insert($data)->save();
            $id = $this->getAdapter()->getConnection()->lastInsertId();
            $this->execute("UPDATE transaction_details SET title_officer = $id WHERE title_officer = $oldTitleOfficerId"); 
        }
    }
}
