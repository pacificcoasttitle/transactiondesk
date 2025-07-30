<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class User extends MX_Controller 
{
	function __construct() 
    {
        parent::__construct();
		$this->load->helper(
            array('file', 'url','form')
        );
        $this->load->library('session');
		$this->load->library('form_validation');
		$this->load->library('hr/template');
        $this->load->library('hr/common');
        $this->load->model('hr/hr'); 
        $this->common->is_user();
	}
	
	function index()
	{
        $data['errors'] = array();
		$data['success'] = array();
		if ($this->session->userdata('errors')) {
			$data['errors'] = $this->session->userdata('errors');
			$this->session->unset_userdata('errors');
		}
		if ($this->session->userdata('success')) {
			$data['success'] = $this->session->userdata('success');
			$this->session->unset_userdata('success');
		}
		$data['title'] = 'HR-Center Employee Prolife';
        $userdata = $this->session->userdata('hr_user');
        $data['userInfo'] = $this->hr->getUserInfo($userdata['id']);
        $this->template->show("hr/employee", "profile", $data);
	}
	public function updateProfile()
    {
		$userdata = $this->session->userdata('hr_user');
		$errors = array();
        $success = array();
		// var_dump($this->input->post());die;
		if(!empty($userdata)) {
			$user_id = $userdata['id'];
			$update_data = array();
			$update_data['address']=$this->input->post('address');
			$update_data['city']=$this->input->post('city');
			$update_data['state']=$this->input->post('state');
			$update_data['zip']=$this->input->post('zip');
			$update_data['cell_phone']=$this->input->post('cell_phone');
			$update_data['home_phone']=$this->input->post('home_phone');
			$update_data['birth_date']=$this->input->post('birth_date');
			$update_data = array_filter($update_data);
			if(count($update_data)) {

				$this->load->model('hr/users_model');
				$this->users_model->update($user_id,$update_data);

				$success[] = "Profile Updates successfully.";
                
			}
			else {
				$errors[] = "Profile data not updated please try again";
			}
		}
		else {
			$errors[] = "Profile data not updated please try again";
		}
		$data = array(
			"errors" =>  $errors,
			"success" => $success
		);
		$this->session->set_userdata($data);
		redirect(base_url('hr/profile'));
	}
    public function updatePassword()
    {
        $userdata = $this->session->userdata('hr_user');
        $errors = array();
        $success = array();

        if ($this->input->post()) {
            $this->form_validation->set_rules('password', 'Password', 'required', array('required'=> 'Enter your password'));
            $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required', array('required'=> 'Please enter confirm password'));

            if ($this->form_validation->run($this) == FALSE) {
                $data['pwd_err_msg'] = form_error('password');
                $data['confirm_pwd_err_msg'] = form_error('confirm_password');
                $this->template->show("hr/employee", "profile", $data);
            } else {
                $password = $this->input->post('password');
                $this->hr->update(array('password' => password_hash($password, PASSWORD_DEFAULT)), array('id' => $userdata['id']), 'pct_hr_users');
                $success[] = "Password updated successfully.";
                $data = array(
                    "errors" =>  $errors,
                    "success" => $success
                );
                $this->session->set_userdata($data);
                redirect(base_url().'hr/profile');
            }
        } else {
            redirect(base_url().'hr/profile');
        }
    }

    public function uploadProfilePic()
    {
        $userdata = $this->session->userdata('hr_user');
        $errors = array();
        $success = array();
        $config['upload_path'] = './uploads/hr/user/';
        $config['allowed_types'] = 'gif|jpg|png';   
        $config['max_size'] = 12000;
        $this->load->library('upload', $config);
        if (!is_dir('/uploads/hr/user')) {
			mkdir('./uploads/hr/user', 0777, TRUE);
		}
       
        if (!empty($_FILES['profile_img']['name'])) {
            if (! $this->upload->do_upload('profile_img')) {
                $data['profile_img_error_msg'] = $this->upload->display_errors();
                $this->template->show("hr/employee", "profile", $data);
            } else { 
                $data = $this->upload->data();
                $document_name = date('YmdHis')."_".$data['file_name'];
                rename(FCPATH."/uploads/hr/user/".$data['file_name'], FCPATH."/uploads/hr/user/".$document_name);
                $this->common->uploadDocumentOnAwsS3($document_name, 'hr/user');
                $this->hr->update(array('profile_img' => $document_name), array('id' => $userdata['id']), 'pct_hr_users');
                $success[] = "Profile image updated successfully.";
                $data = array(
                    "errors" =>  $errors,
                    "success" => $success
                );
                $this->session->set_userdata($data);
                redirect(base_url().'hr/profile');
            } 
        } else {
            redirect(base_url().'hr/profile');
        }   
    }

    public function updateUserInfo()
    {
        $userdata = $this->session->userdata('hr_user');
        $errors = array();
        $success = array();
       
       
        
        $data = array(
            "errors" =>  $errors,
            "success" => $success
        );
        $this->session->set_userdata($data);
        redirect(base_url().'hr/incident-reports');
    }
}
