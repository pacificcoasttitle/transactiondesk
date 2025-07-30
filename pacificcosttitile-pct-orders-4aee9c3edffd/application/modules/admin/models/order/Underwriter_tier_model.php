<?php
class Underwriter_tier_model extends MY_Model 
{
    public $_table = 'pct_underwriter_tiers';
	public $has_many = array( 'commision_range_obj' => array( 'model' => 'admin/order/commission_range_model','primary_key' => 'underwriter_tier' ));
}
