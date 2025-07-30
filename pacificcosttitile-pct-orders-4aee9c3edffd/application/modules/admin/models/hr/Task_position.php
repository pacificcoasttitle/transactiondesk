<?php
class Task_position extends MY_Model 
{
    public $_table = 'pct_hr_employee_task_list_positions';
    public $belongs_to = array( 'position' => array( 'model' => 'hr/users_position','primary_key' => 'position_id' ));

    
}
