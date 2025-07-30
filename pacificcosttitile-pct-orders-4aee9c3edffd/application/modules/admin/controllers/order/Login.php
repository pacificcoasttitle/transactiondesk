<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url', 'form')
        );
        $this->load->library('form_validation');
        $this->load->model('order/home_model');
    }

    public function login()
    {
        $data = array();
        $userdata = $this->session->userdata('admin');
        if (!empty($userdata['id']) && $userdata['is_admin'] == 1) {
            if (!empty($userdata['role_id']) && $userdata['role_id'] == 3) {

                redirect(base_url() . 'order/admin/orders');
            } else {
                redirect(base_url() . 'order/admin/dashboard');
            }
        } else {
            $data['msg'] = $this->session->userdata('msg');
            $this->session->unset_userdata('msg');
            $this->load->view('order/home/login', $data);
        }
    }

    public function do_login()
    {
        if ($this->input->post()) {
            $email_address = $this->input->post('email_address');
            $password = $this->input->post('password');
            $admin = $this->home_model->get_admin_user($email_address, $password);

            if ($admin) {
                $session_data = array(
                    "id" => isset($admin['id']) && !empty($admin['id']) ? $admin['id'] : '',
                    "name" => isset($admin['first_name']) && !empty($admin['first_name']) ? $admin['first_name'] . ' ' . $admin['last_name'] : '',
                    "email_address" => isset($admin['email_id']) && !empty($admin['email_id']) ? $admin['email_id'] : '',
                    "is_admin" => 1,
                    "role_id" => isset($admin['role_id']) && !empty($admin['role_id']) ? $admin['role_id'] : '',
                );

                $this->session->set_userdata('admin', $session_data);

                $data = array(
                    'user_id' => $admin['id'],
                    'message' => 'Logged',
                    'created_at' => date("Y-m-d H:i:s"),
                );
                $this->db->insert('pct_admin_activity_logs', $data);

                if ($this->input->is_ajax_request()) {
                    $result = array('status' => 'success');
                    echo json_encode($result);exit;
                } else {
                    if ($admin['email_id'] == 'upwork@pct.com') {
                        redirect(base_url() . 'order/admin/credentials-check');
                    } else if ($session_data['role_id'] == 3) {
                        redirect(base_url() . 'order/admin/orders');
                    } else {
                        redirect(base_url() . 'order/admin/dashboard');
                    }
                }
            } else {
                if ($this->input->is_ajax_request()) {
                    $result = array('status' => 'error', 'msg' => 'Incorrect email or password');
                    echo json_encode($result);exit;
                } else {
                    $this->session->set_userdata('msg', 'Incorrect email or password');
                    // $result['msg'] = "Incorrect email or password";
                    redirect(base_url() . 'order/admin');
                }
            }
        }
    }
}
