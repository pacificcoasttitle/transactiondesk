<?php
class Task_list_category extends MY_Model 
{
    public $_table = 'pct_hr_employee_task_list_category';
	public $has_many = array( 'tasks' => array( 'model' => 'hr/task_list_model','primary_key' => 'category_id' ));
}
