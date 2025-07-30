<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class Login extends MX_Controller {

    function __construct() 
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url','form')
        );
        $this->load->library('form_validation');
        $this->load->model('hr/hr'); 
        $this->load->library('hr/common');
    }

    function index() 
    {
        $userdata = $this->session->userdata('hr_user');
        if (!empty($userdata['id'])) {
            redirect(base_url().'hr/dashboard');
        } else {
            redirect(base_url().'hr/login');
        }
    }

    function login() 
    {
        $userdata = $this->session->userdata('hr_user');
        if (!empty($userdata['id']) && $userdata['is_admin'] == 0) {
            redirect(base_url().'hr/dashboard');
        } 
        $data = array();

        if ($this->input->post()) {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', array('required'=> 'Please enter Email', 'valid_email' => 'Enter a Valid email address'));
            $this->form_validation->set_rules('password', 'Password', 'required', array('required'=> 'Please enter password'));

            if ($this->form_validation->run($this) == FALSE) {
                $data['email_error_msg'] = form_error('email');
                $data['password_error_msg'] = form_error('password');
            } else {
                $email = $this->input->post('email');
                $password = $this->input->post('password');
                $user =  $this->common->get_hr_user(array('email' => $email, 'status' => 1));
                if (!empty($user) && ($user['user_type_id'] != 1 && $user['user_type_id'] != 2 && $user['user_type_id'] != 4 && $user['user_type_id'] != 6)) {
                    if($user['is_tmp_password'] == 1) {
                        if (password_verify($password, $user['password'])) {
                            $randomString = $this->common->randomPassword();
                            $hash = md5($user['id'] . $user['email'] .$randomString);
                            $this->hr->update(array('hash' => $hash), array('id' => $user['id']), 'pct_hr_users');
                            redirect(base_url().'hr/change-password/'.$hash);
                        } else {
                            $data['password_error_msg'] = 'Please enter the correct login details.';
                        }
                    } else {
                        if (password_verify($password, $user['password'])) {
                            $session_data = array(
                                "id" => isset($user['id']) && !empty($user['id']) ? $user['id'] : '',
                                "name" => isset($user['first_name']) && !empty($user['first_name']) ? $user['first_name']." ".$user['last_name'] : '',
                                "email" => isset($user['email']) && !empty($user['email']) ? $user['email'] : '',
                                "user_type_id" => isset($user['user_type_id']) && !empty($user['user_type_id']) ? $user['user_type_id'] : '',
                                "department_id" => isset($user['department_id']) && !empty($user['department_id']) ? $user['department_id'] : '',
                                "position_id" => isset($user['position_id']) && !empty($user['position_id']) ? $user['position_id'] : '',
                                "branch_id" => isset($user['branch_id']) && !empty($user['branch_id']) ? $user['branch_id'] : '',
                                "user_type" => isset($user['user_type']) && !empty($user['user_type']) ? $user['user_type'] : '',
                            );
                            $this->session->set_userdata('hr_user', $session_data);
                            redirect(base_url().'hr/dashboard');
                        } else {
                            $data['password_error_msg'] = 'Please enter the correct login details.';
                        }
                    }
                } else {
                    $data['password_error_msg'] = 'Please enter the correct login details.';
                }
            }
    	} 
        $this->load->view('hr/login', $data);	
    }

	function forgot_password() 
    {
        $userdata = $this->session->userdata('hr_user');
        if (!empty($userdata['id']) && $userdata['is_admin'] == 0) {
            redirect(base_url().'hr/dashboard');
        } 
        $data = array();

        if ($this->input->post()) {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', array('required'=> 'Please enter Email', 'valid_email' => 'Enter a Valid email address'));
            // $this->form_validation->set_rules('password', 'Password', 'required', array('required'=> 'Please enter password'));

            if ($this->form_validation->run($this) == FALSE) {
                $data['email_error_msg'] = form_error('email');
                // $data['password_error_msg'] = form_error('password');
            } else {
                $email = $this->input->post('email');
                $user =  $this->common->get_hr_user(array('email' => $email, 'status' => 1));
                if (!empty($user)) {
					$this->load->library('hr/common');
					$randomPassword = $this->common->randomPassword();
					$update_user = [
						'password' => password_hash($randomPassword, PASSWORD_DEFAULT),
						'is_tmp_password' => 1
					];
					
                    $this->hr->update($update_user, array('id' => $user['id']), 'pct_hr_users');
					$successMsg = 'Please check your email for new password';

					$from_name = 'Pacific Coast Title Company';
					$from_mail = getenv('FROM_EMAIL');
					$message_body = "Hi ".$user['first_name']." ".$user['last_name'].", <br><br>";
					$message_body .= "You have requested to redet password for the Pacific Coast Title HR center. Please login with tempoary password and change your password.<br><br>";
					$message_body .= "Tempoary password: ".$randomPassword. "<br><br>";
                    if ($user['user_type_id'] == 1 || $user['user_type_id'] == 2 || $user['user_type_id'] == 4 || $user['user_type_id'] == 6) {
					    $message_body .= "Please click on the link below.<br><br> <a href=".base_url('hr/admin').">".base_url('hr/admin')."</a>";
                    } else {
                        $message_body .= "Please click on the link below.<br><br> <a href=".base_url('hr/login').">".base_url('hr/login')."</a>";
                    }
					$subject = 'Reset Password For Pacific Coast Title HR Center';
					$to = $this->input->post('email');
					$this->load->helper('sendemail');
					send_email($from_mail, $from_name, $to, $subject, $message_body);
					$this->session->set_flashdata('success',$successMsg);

                    if ($user['user_type_id'] == 1 || $user['user_type_id'] == 2 || $user['user_type_id'] == 4 || $user['user_type_id'] == 6) {
					    redirect(base_url('hr/admin'));
                    } else {
                        redirect(base_url('hr/login'));
                    }
                } else {
                    $data['password_error_msg'] = "We can't find a user with that email address.";
                }
            }
    	} 
        $this->load->view('hr/forgot_password', $data);	
    }

    function change_password()
    {
        $hash = $this->uri->segment(3); 
        $user =  $this->common->get_hr_user(array('hash' => $hash));
        if (!empty($user)) {
            if ($this->input->post()) {
                $this->form_validation->set_rules('password', 'Password', 'required', array('required'=> 'Enter your password'));
                $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required', array('required'=> 'Please enter confirm password'));

                if ($this->form_validation->run($this) == FALSE) {
                    $data['pwd_err_msg'] = form_error('password');
                    $data['confirm_pwd_err_msg'] = form_error('confirm_password');
                } else {
                    $password = $this->input->post('password');
                    $this->hr->update(array('password' => password_hash($password, PASSWORD_DEFAULT), 'is_tmp_password' => 0, 'hash' => ''), array('id' => $user['id']), 'pct_hr_users');
                    $session_data = array(
                        "id" => isset($user['id']) && !empty($user['id']) ? $user['id'] : '',
                        "name" => isset($user['first_name']) && !empty($user['first_name']) ? $user['first_name']." ".$user['last_name'] : '',
                        "email" => isset($user['email']) && !empty($user['email']) ? $user['email'] : '',
                        "user_type_id" => isset($user['user_type_id']) && !empty($user['user_type_id']) ? $user['user_type_id'] : '',
						"user_type" => isset($user['user_type']) && !empty($user['user_type']) ? $user['user_type'] : '',
                    );
                    if ($user['user_type_id'] == 1 || $user['user_type_id'] == 2 || $user['user_type_id'] == 4 || $user['user_type_id'] == 6) {
                        $session_data['is_hr_admin'] = 1;
                        $this->session->set_userdata('hr_admin', $session_data);
                        redirect(base_url().'hr/admin/dashboard');
                    } else {
                        $session_data['is_hr_admin'] = 0;
                        $this->session->set_userdata('hr_user', $session_data);
                        redirect(base_url().'hr/dashboard');
                    }
                }
            } else {
                $data['hash'] = $hash;
                $this->load->view('hr/change_password', $data);
            }
        } else {
            $data['change_password_error_msg'] = 'Invalid Link.';
            redirect(base_url().'hr/login');
        }
    }
}
