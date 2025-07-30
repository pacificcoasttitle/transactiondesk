<?php
class Training_model extends MY_Model 
{
    public $_table = 'pct_hr_employee_training';
    public $belongs_to = array( 'department' => array( 'model' => 'hr/users_department','primary_key' => 'department_id' ),
								'position' => array( 'model' => 'hr/users_position','primary_key' => 'position_id' ));
	public $has_many = array( 'materials' => array( 'model' => 'hr/training_material_model','primary_key' => 'training_id' ),
                                'users' => array( 'model' => 'hr/training_status_model','primary_key' => 'training_id' ));
   
}
