<?php
class Users_model extends MY_Model 
{
    public $_table = 'pct_hr_users';
    public $has_many = array( 'tasks' => array( 'model' => 'hr/pct_hr_employee_task_list_complete','primary_key' => 'employee_id' ));
	public $belongs_to = array( 'type' => array( 'model' => 'hr/users_type_model','primary_key' => 'user_type_id' ),
								'position' => array( 'model' => 'hr/users_position','primary_key' => 'position_id' ),
                                'branch' => array( 'model' => 'hr/branches','primary_key' => 'branch_id' ),
                                'department' => array( 'model' => 'hr/users_department','primary_key' => 'department_id' ),
                            );
}
