<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class OrderPayOff extends AbstractMigration
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
        $table = $this->table('customer_basic_details');
        $table->addColumn('is_payoff_user', 'boolean', ['default' => 0, 'after' => 'is_primary_mortgage_user'])
                ->update(); 
        $data = [
            [
                'resware_user_id'  => 0,
                'partner_id'  => 0,
                'partner_type_id'  => 0,
                'first_name'  => '',
                'last_name'  => '',
                'password'  => 'Pacific1',
                'company_name'  => 'Pay Off Company',
                'telephone_no'  => '',
                'email_address'  => 'payoff@pct.com',
                'sales_rep_report_image' => '',
                'status'  => 1,
                'is_password_updated'  => 1,
                'is_payoff_user'  => 1,
                'created_at'  => date('Y-m-d H:i:s')
            ]
        ];
        $table = $this->table('customer_basic_details');
        $table->insert($data)->save();
        $table = $this->table('order_details');
        $table->addColumn('is_payoff_order', 'boolean', ['default' => 0, 'after' => 'premium'])
                ->update(); 
    }
}
