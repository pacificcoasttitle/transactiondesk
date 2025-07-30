<?php
class Commission_range_model extends MY_Model 
{
    public $_table = 'pct_commission_range';
	public $belongs_to = array( 'underwriter_tier_obj' => array( 'model' => 'admin/order/underwriter_tier_model','primary_key' => 'underwriter_tier' ));
}
