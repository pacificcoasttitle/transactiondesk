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
        $this->load->model('order/home_model'); 
    }

    function index() 
    {
        $userdata = $this->session->userdata('user');
        if (!empty($userdata['id']) && $userdata['is_admin'] == 0) {
            redirect(base_url().'order');
        } else {
            $data = array();
            if ($this->session->userdata('success')) {
                $data['change_pwd_success'] = $this->session->userdata('success');
                $this->session->unset_userdata('success');
            }
            $this->load->view('order/login', $data);	
        }
    }

    function loginTest() 
    {
        $userdata = $this->session->userdata('user');
        if (!empty($userdata['id']) && $userdata['is_admin'] == 0) {
            redirect(base_url().'order');
        } else {
            $data = array();
            if ($this->session->userdata('success')) {
                $data['change_pwd_success'] = $this->session->userdata('success');
                $this->session->unset_userdata('success');
            }
            $this->load->view('order/login_test', $data);	
        }
    }

    function do_login() 
    {
        if ($this->input->post()) {
            $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email');
            $is_password_field_show = $this->input->post('is_password_field_show');

            if ($is_password_field_show == 1) {
                $this->form_validation->set_rules('pwd', 'Password', 'required', array('required'=> 'Please enter password'));
            }

            if ($this->form_validation->run($this) == FALSE) {
                if ($is_password_field_show == 1) {
                    $response = array('status'=>'error', 'email_err_msg'=> form_error('email_address'), 'password_err_msg'=> form_error('pwd'));
                    echo json_encode($response); exit;
                } else {
                    $response = array('status'=>'error', 'email_err_msg'=> 'Enter a Valid email address.');
                    echo json_encode($response); exit;
                }
            } else {
                $email = $this->input->post('email_address');
                $user =  $this->home_model->get_user(array('email_address' => $email, 'is_password_updated' => 1, 'status' => 1));
                
                if (!empty($user)) {
                    if ($user['is_password_required'] == 1 && $is_password_field_show == 0 && $user['is_tmp_password'] == 1) {
                        $response = array('status'=>'error', 'password_err_msg'=> "Plese enter a password that is sent in email. If you don't receive any email then please contact us administrator.", 'is_password_field_show' => 1);
                        echo json_encode($response); exit;
                    } else if  ($user['is_password_required'] == 1 && $is_password_field_show == 0 && $user['is_tmp_password'] == 0) { 
                        $response = array('status'=>'error', 'password_err_msg'=> "Plese enter password and try to login.", 'is_password_field_show' => 1);
                        echo json_encode($response); exit;
                    }
                    else if($user['is_password_required'] == 1 && $is_password_field_show == 1)  {
                        $password = $this->input->post('pwd');
                        if($user['is_tmp_password'] == 1) {
                            if (password_verify($password, $user['password'])) {
                                $this->load->library('order/order');
                                $randomString = $this->order->randomPassword();
                                $hash = md5($user['id'] . $user['email_address'] .$randomString);
                                $this->home_model->update(array('hash' => $hash), array('id' => $user['id']));
                                $response = array('status'=>'success', 'message'=> '', 'url' => 'change-password/'.$hash);
                                echo json_encode($response); exit;
                            } else {
                                $response = array('status'=>'error', 'email_err_msg' => '', 'password_err_msg'=> '<p>Please enter the correct login details.</p>');
                                echo json_encode($response); exit;
                            }
                        } else {
                            if (password_verify($password, $user['password'])) {
                                $session_data = array(
                                    "id" => isset($user['id']) && !empty($user['id']) ? $user['id'] : '',
                                    "name" => isset($user['first_name']) && !empty($user['first_name']) ? $user['first_name']." ".$user['last_name'] : '',
                                    "email" => isset($user['email_address']) && !empty($user['email_address']) ? $user['email_address'] : '',
                                    "random_password" => isset($user['random_password']) && !empty($user['random_password']) ? $user['random_password'] : '',
                                    "is_admin" => 0,
                                    "is_master" =>  $user['is_master'],
                                    "is_sales_rep" =>  $user['is_sales_rep'],
                                    "is_sales_rep_manager" =>  $user['is_sales_rep_manager'],
                                    "is_title_officer" =>  $user['is_title_officer'],
                                    "is_payoff_user" =>  $user['is_payoff_user'],
                                    "is_escrow_officer" =>  $user['is_escrow_officer'],
                                    "is_escrow_assistant" =>  $user['is_escrow_assistant'],
                                    "partner_companies" =>  $user['partner_companies'],
                                    "is_special_lender" =>  isset($user['is_special_lender']) && !empty($user['is_special_lender']) ? $user['is_special_lender'] : '',
                                );
                                $this->session->set_userdata('user', $session_data);
                            } else {
                                $response = array('status'=>'error', 'email_err_msg' => '', 'password_err_msg'=> '<p>Please enter the correct login details.</p>');
                                echo json_encode($response); exit;
                            }
                        }
                    } else {
                        $session_data = array(
                            "id" => isset($user['id']) && !empty($user['id']) ? $user['id'] : '',
                            "name" => isset($user['first_name']) && !empty($user['first_name']) ? $user['first_name']." ".$user['last_name'] : '',
                            "email" => isset($user['email_address']) && !empty($user['email_address']) ? $user['email_address'] : '',
                            "random_password" => isset($user['random_password']) && !empty($user['random_password']) ? $user['random_password'] : '',
                            "is_admin" => 0,
                            "is_master" =>  $user['is_master'],
                            "is_sales_rep" =>  $user['is_sales_rep'],
                            "is_sales_rep_manager" =>  $user['is_sales_rep_manager'],
                            "is_title_officer" =>  $user['is_title_officer'],
                            "is_payoff_user" =>  $user['is_payoff_user'],
                            "is_escrow_officer" =>  $user['is_escrow_officer'],
                            "is_escrow_assistant" =>  $user['is_escrow_assistant'],
                            "partner_companies" =>  $user['partner_companies'],
                            "is_special_lender" =>  isset($user['is_special_lender']) && !empty($user['is_special_lender']) ? $user['is_special_lender'] : '',
                        );
                        $this->session->set_userdata('user', $session_data);
                    }
                    if ($user['is_title_officer'] == 1) {
                        $response = array('status'=>'success', 'message'=> '', 'url' => 'title-officer-dashboard');
                    } else if ($user['is_sales_rep'] == 1) {
                        $response = array('status'=>'success', 'message'=> '', 'url' => 'sales-dashboard/'.$user['id']);
                    } else if ($user['is_special_lender'] == 1) {
                        $response = array('status'=>'success', 'message'=> '', 'url' => 'special-lender-dashboard');
                    } else if ($user['is_payoff_user'] == 1) {
                        $response = array('status'=>'success', 'message'=> '', 'url' => 'pay-off-dashboard');
                    } else if ($user['is_escrow_officer'] == 1 || $user['is_escrow_assistant'] == 1) {
                        $response = array('status'=>'success', 'message'=> '', 'url' => 'escrow-dashboard');
                    } else {
                        $response = array('status'=>'success', 'message'=> '', 'url' => 'dashboard');
                    }
                    echo json_encode($response); exit;
                } else {
                    $response = array('status'=>'error', 'email_err_msg'=> 'Please enter the correct email address.');
					echo json_encode($response); exit;
                }
            }
    	}
    }

    function do_login_test() 
    {
        if ($this->input->post()) {
            
            $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email', array('required'=> 'Please enter Email', 'valid_email' => 'Enter a Valid email address'));
            $this->form_validation->set_rules('pwd', 'Password', 'required', array('required'=> 'Please enter password'));

            if ($this->form_validation->run($this) == FALSE) {
                $response = array('status'=>'error', 'email_err_msg'=> form_error('email_address'), 'password_err_msg'=> form_error('pwd'));
                echo json_encode($response); exit;
            } else {
                $email = $this->input->post('email_address');
                $password = $this->input->post('pwd');
                $user =  $this->home_model->get_user(array('email_address' => $email, 'is_password_updated' => 1));
                if (!empty($user)) {
                    if($user['is_tmp_password'] == 1) {
                        if (password_verify($password, $user['password'])) {
                            $this->load->library('order/order');
                            $randomString = $this->order->randomPassword();
                            $hash = md5($user['id'] . $user['email_address'] .$randomString);
                            $this->home_model->update(array('hash' => $hash), array('id' => $user['id']));
                            $response = array('status'=>'success', 'message'=> '', 'url' => 'change-password/'.$hash);
                            echo json_encode($response); exit;
                        } else {
                            $response = array('status'=>'error', 'email_err_msg' => '', 'password_err_msg'=> '<p>Please enter the correct login details.</p>');
					        echo json_encode($response); exit;
                        }
                    } else {
                        if (password_verify($password, $user['password'])) {
                            $session_data = array(
                                "id" => isset($user['id']) && !empty($user['id']) ? $user['id'] : '',
                                "name" => isset($user['first_name']) && !empty($user['first_name']) ? $user['first_name'].$user['last_name'] : '',
                                "email" => isset($user['email_address']) && !empty($user['email_address']) ? $user['email_address'] : '',
                                "random_password" => isset($user['random_password']) && !empty($user['random_password']) ? $user['random_password'] : '',
                                "is_admin" => 0,
                                "is_master" =>  $user['is_master'],
                                "is_sales_rep" =>  $user['is_sales_rep'],
                                "is_special_lender" =>  isset($user['is_special_lender']) && !empty($user['is_special_lender']) ? $user['is_special_lender'] : '',
                            );
                            $this->session->set_userdata('user', $session_data);
                            $response = array('status'=>'success', 'message'=> '', 'url' => 'dashboard');
                            echo json_encode($response); exit;
                        } else {
                            $response = array('status'=>'error', 'email_err_msg' => '', 'password_err_msg'=> '<p>Please enter the correct login details.</p>');
					        echo json_encode($response); exit;
                        }
                    }
                } else {
                    $response = array('status'=>'error', 'email_err_msg' => '', 'password_err_msg'=> '<p>Please enter the correct login details.</p>');
					echo json_encode($response); exit;
                }
            }
    	}
    }

    function change_password()
    {
        $hash = $this->uri->segment(2); 
        $user =  $this->home_model->get_user(array('hash' => $hash));
        if (!empty($user)) {
            if ($this->input->post()) {
                $this->form_validation->set_rules('password', 'Password', 'required', array('required'=> 'Enter your password'));
                $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required', array('required'=> 'Please enter confirm password'));

                if ($this->form_validation->run($this) == FALSE) {
                    $response = array('status'=>'error', 'pwd_err_msg'=> form_error('password'), 'confirm_pwd_err_msg'=> form_error('confirm_password'));
                    echo json_encode($response); exit;
                } else {
                    $password = $this->input->post('password');
                    $this->home_model->update(array('password' => password_hash($password, PASSWORD_DEFAULT), 'is_tmp_password' => 0, 'hash' => ''), array('id' => $user['id']));
                    $data = array(
						"success" => 'Password updated successfully.'
					);
					$this->session->set_userdata($data);
                    $response = array('status'=>'success', 'message'=> '', 'url' => 'order/login');
                    echo json_encode($response); exit;
                }
            } else {
                $data['hash'] = $hash;
                $this->load->view('order/change_password', $data);
            }
        } else {
            $data['change_password_error_msg'] = 'Invalid Link.';
            $this->load->view('order/login_test', $data);
        }
    }
}