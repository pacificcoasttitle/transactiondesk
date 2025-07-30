<?php
class Admin_user_model extends MY_Model 
{
    public $_table = 'admin';
	public $belongs_to = array( 'role_obj' => array( 'model' => 'admin/order/users_roles_model','primary_key' => 'role_id' ));
}
