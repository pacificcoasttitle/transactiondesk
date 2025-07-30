<?php
class Pct_hr_employee_allowed_ot_model extends MY_Model 
{
    public $_table = 'pct_hr_employee_allowed_ot';

	public $belongs_to = array('user' => array( 'model' => 'hr/users_model','primary_key' => 'employee_id' ));
    
}
