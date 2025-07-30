<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MX_Controller {

	public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url','form')
        );
        $this->load->library('form_validation');
        $this->load->model('hr/hr'); 
        $this->load->library('hr/common');
    }

    public function login()
	{
       
        $data = array();
        $userdata = $this->session->userdata('hr_admin');
        if (!empty($userdata['id']) && $userdata['is_hr_admin'] == 1) {
            redirect(base_url().'hr/admin/dashboard');
        } else {
            $data['msg'] = $this->session->userdata('msg');
            $this->session->unset_userdata('msg');
            $this->load->view('hr/login', $data);
        }		
	}

    public function do_login()
    {
    	if($this->input->post()) {
    		$email_address = $this->input->post('email_address');
        	$password      = $this->input->post('password');
            $admin =  $this->common->get_hr_user(array('email' => $email_address, 'status' => 1));
            if (!empty($admin) && ($admin['user_type_id'] == 1 || $admin['user_type_id'] == 2 || $admin['user_type_id'] == 4)) {
                if($admin['is_tmp_password'] == 1) {
                    if (password_verify($password, $admin['password'])) {
                        $randomString = $this->common->randomPassword();
                        $hash = md5($admin['id'] . $admin['email'] .$randomString);
                        $this->hr->update(array('hash' => $hash), array('id' => $admin['id']), 'pct_hr_users');
                        redirect(base_url().'hr/change-password/'.$hash);
                    } else {
                        $this->session->set_userdata('msg', 'Incorrect email or password');
                        redirect(base_url().'hr/admin');
                    }
                } else {
                    if (password_verify($password, $admin['password'])) {
                        $session_data = array(
                            "id" => isset($admin['id']) && !empty($admin['id']) ? $admin['id'] : '',
                            "name" => isset($admin['first_name']) && !empty($admin['first_name']) ? $admin['first_name']." ".$admin['last_name'] : '',
                            "email" => isset($admin['email']) && !empty($admin['email']) ? $admin['email'] : '',
                            "user_type_id" => isset($admin['user_type_id']) && !empty($admin['user_type_id']) ? $admin['user_type_id'] : '',
                            "department_id" => isset($admin['department_id']) && !empty($admin['department_id']) ? $admin['department_id'] : '',
                            "position_id" => isset($admin['position_id']) && !empty($admin['position_id']) ? $admin['position_id'] : '',
                            "user_type" => isset($admin['user_type']) && !empty($admin['user_type']) ? $admin['user_type'] : '',
                            "is_hr_admin" => 1
                        );
                        $this->session->set_userdata('hr_admin', $session_data);
                        redirect(base_url().'hr/admin/dashboard');
                    } else {
                        $this->session->set_userdata('msg', 'Incorrect email or password');
                        redirect(base_url().'hr/admin');
                    }
                }
            } else {
                $this->session->set_userdata('msg', 'Incorrect email or password');
                redirect(base_url().'hr/admin');
            }
    	}
    }
}