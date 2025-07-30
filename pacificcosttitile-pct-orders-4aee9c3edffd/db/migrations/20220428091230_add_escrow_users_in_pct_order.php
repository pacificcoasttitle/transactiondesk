<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class AddEscrowUsersInPctOrder extends AbstractMigration
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
        $rows = $this->fetchAll('SELECT * FROM `pct_hr_users` WHERE department_id = 4 AND user_type_id != 4');
        $data = array();
        if(isset($rows) && !empty($rows))
        {
            foreach ($rows as $key => $value) 
            {
                $partnerInfo = array();
                if ($value['position_id'] != 15) {
                    $partnerInfo = $this->fetchRow('SELECT partner_id FROM pct_order_partner_company_info WHERE email ="'.$value['email'].'"');
                }   
                $data[] = array(
                    'partner_id' => !empty($partnerInfo['partner_id']) ? $partnerInfo['partner_id'] : 0, 
                    'partner_type_id' => 10010,
                    'first_name' => $value['first_name'], 
                    'last_name' => $value['last_name'], 
                    'title' => '', 
                    'telephone_no' => $value['cell_phone'], 
                    'email_address' => $value['email'], 
                    'password' => $value['password'], 
                    'random_password' => 'Pacific1', 
                    'company_name' => '', 
                    'street_address' => $value['address'], 
                    'city' => $value['city'], 
                    'zip_code' => $value['zip'], 
                    'is_escrow' => 0, 
                    'lender_type' => null, 
                    'status' => 1, 
                    'is_master' => 0, 
                    'is_sales_rep' => 0, 
                    'is_password_updated' => 1, 
                    'is_password_required' => 1, 
                    'is_mail_notification' => 0,
                    'sales_rep_report_image' => '',
                    'is_escrow_assistant' => $value['position_id'] == 15 ? 1 : 0,
                    'is_escrow_officer' => $value['position_id'] != 15 ? 1 : 0,
                    'created_at' => date('Y-m-d H:i:s')
                );
            }
        }
        $table = $this->table('customer_basic_details');
        $table->insert($data);
        $table->saveData();
    }
}
