<?php
class Users_type_model extends MY_Model 
{
    public $_table = 'pct_hr_user_types';
    public $has_many = array( 'users' => array( 'model' => 'hr/users_model','primary_key' => 'user_type_id' ));

}
