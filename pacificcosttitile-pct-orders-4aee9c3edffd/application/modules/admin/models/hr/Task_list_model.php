<?php
class Task_list_model extends MY_Model 
{
    public $_table = 'pct_hr_employee_task_list';
    public $belongs_to = array( 'category' => array( 'model' => 'hr/task_list_category','primary_key' => 'category_id' ));
	public $has_many = array( 'users' => array( 'model' => 'hr/users_tasks_model','primary_key' => 'task_id' ),
							'positions'=>array( 'model' => 'hr/task_position','primary_key' => 'task_id' ));
}
