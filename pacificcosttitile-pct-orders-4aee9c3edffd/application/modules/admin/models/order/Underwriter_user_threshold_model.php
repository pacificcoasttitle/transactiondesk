<?php
class Underwriter_user_threshold_model extends MY_Model 
{
    public $_table = 'pct_underwriter_users_threshold';
	public $belongs_to = array( 'underwriter_tier_user_obj' => array( 'model' => 'admin/order/underwriter_user_model','primary_key' => 'underwriter_users_id' ));
}
