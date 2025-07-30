<?php
class User_monthly_commission_model extends MY_Model 
{
    public $_table = 'pct_user_monthly_commission';
	public $belongs_to = array( 'sales_rep_obj' => array( 'model' => 'admin/order/customer_basic_details_model','primary_key' => 'user_id' ));
}
