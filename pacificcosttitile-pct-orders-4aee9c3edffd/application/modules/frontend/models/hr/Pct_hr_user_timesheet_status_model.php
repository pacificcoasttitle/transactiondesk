<?php
class Pct_hr_user_timesheet_status_model extends MY_Model 
{
    public $_table = 'pct_hr_timeheet_status';

	public $belongs_to = array('user' => array( 'model' => 'hr/users_model','primary_key' => 'user_id' ),
								'updated_by_user'=> array( 'model' => 'hr/users_model','primary_key' => 'updated_by' ));
    
}
