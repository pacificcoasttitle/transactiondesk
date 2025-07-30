<?php
class Users_tasks_model extends MY_Model 
{
    public $_table = 'pct_hr_employee_task_list_complete';
	public $belongs_to = array( 'task' => array( 'model' => 'hr/task_list_model','primary_key' => 'task_id' ),
								'user' => array( 'model' => 'hr/users_model','primary_key' => 'employee_id' ));
    public function get_tasks($user_id)
	{
		$this->db->from($this->_table);
        $this->db->where('employee_id', $user_id);
		$this->db->select(['id','task_id']);
		return $this->db->get()->result_array();
		// return array_column($result,"task_id");
	}
}
