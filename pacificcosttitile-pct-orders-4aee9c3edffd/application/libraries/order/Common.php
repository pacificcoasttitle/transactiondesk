<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Common
{
    public static $CI;

    public function __construct($params = array())
    {
        $this->CI = &get_instance();
        $this->CI->load->database();
        $this->CI->load->library('email');
        $this->CI->load->library('session');
        self::$CI = $this->CI;
    }

    public function is_admin()
    {
        $userdata = $this->CI->session->userdata('admin');
        if (!empty($userdata['id']) && $userdata['is_admin'] == 1) {
            return true;
        } else {
            redirect(base_url() . 'order/admin');
        }
    }

    public function is_super_admin()
    {
        $userdata = $this->CI->session->userdata('admin');
        if (!empty($userdata['id']) && $userdata['is_admin'] == 1 && $userdata['role_id'] == 1) {
            return true;
        } else {
            redirect(base_url() . 'order/admin');
        }
    }
    public function if_super_admin()
    {
        $userdata = $this->CI->session->userdata('admin');
        if (!empty($userdata['id']) && $userdata['is_admin'] == 1 && $userdata['role_id'] == 1) {
            return true;
        } else {
            return false;
            // redirect(base_url().'order/admin');
        }
    }

    public function is_title_officer_user()
    {
        $userdata = $this->CI->session->userdata('user');
        if (!empty($userdata['id'])) {
            if ($userdata['is_sales_rep'] == 1) {
                redirect(base_url() . 'sales-dashboard/' . $userdata['id']);
            } else if ($userdata['is_special_lender'] == 1) {
                redirect(base_url() . 'special-lender-dashboard');
            } else if ($userdata['is_payoff_user'] == 1) {
                redirect(base_url() . 'pay-off-dashboard');
            } else if ($userdata['is_escrow_officer'] == 1 || $userdata['is_escrow_assistant'] == 1) {
                redirect(base_url() . 'escrow-dashboard');
            } else if ($userdata['is_title_officer'] == 0) {
                redirect(base_url() . 'dashboard');
            }
        } else {
            redirect(base_url() . 'order/login');
        }
    }

    public function is_sales_user()
    {
        $userdata = $this->CI->session->userdata('user');
        if (!empty($userdata['id'])) {
            if ($userdata['is_title_officer'] == 1) {
                redirect(base_url() . 'title-officer-dashboard');
            } else if ($userdata['is_special_lender'] == 1) {
                redirect(base_url() . 'special-lender-dashboard');
            } else if ($userdata['is_payoff_user'] == 1) {
                redirect(base_url() . 'pay-off-dashboard');
            } else if ($userdata['is_escrow_officer'] == 1 || $userdata['is_escrow_assistant'] == 1) {
                redirect(base_url() . 'escrow-dashboard');
            } else if ($userdata['is_sales_rep'] == 0) {
                redirect(base_url() . 'dashboard');
            }
        } else {
            redirect(base_url() . 'order/login');
        }
    }

    public function is_special_lender_user()
    {
        $userdata = $this->CI->session->userdata('user');
        if (!empty($userdata['id'])) {
            if ($userdata['is_title_officer'] == 1) {
                redirect(base_url() . 'title-officer-dashboard');
            } else if ($userdata['is_sales_rep'] == 1) {
                redirect(base_url() . 'sales-dashboard/' . $userdata['id']);
            } else if ($userdata['is_payoff_user'] == 1) {
                redirect(base_url() . 'pay-off-dashboard');
            } else if ($userdata['is_escrow_officer'] == 1 || $userdata['is_escrow_assistant'] == 1) {
                redirect(base_url() . 'escrow-dashboard');
            } else if ($userdata['is_special_lender'] == 0) {
                redirect(base_url() . 'dashboard');
            }
        } else {
            redirect(base_url() . 'order/login');
        }
    }

    public function is_pay_off_user()
    {
        $userdata = $this->CI->session->userdata('user');
        if (!empty($userdata['id'])) {
            if ($userdata['is_title_officer'] == 1) {
                redirect(base_url() . 'title-officer-dashboard');
            } else if ($userdata['is_sales_rep'] == 1) {
                redirect(base_url() . 'sales-dashboard/' . $userdata['id']);
            } else if ($userdata['is_special_lender'] == 1) {
                redirect(base_url() . 'special-lender-dashboard');
            } else if ($userdata['is_escrow_assistant'] == 1) {
                // } else if ($userdata['is_escrow_officer'] == 1 || $userdata['is_escrow_assistant'] == 1) {
                redirect(base_url() . 'escrow-dashboard');
            } else if ($userdata['is_payoff_user'] == 0 && $userdata['is_escrow_officer'] == 0) {
                redirect(base_url() . 'dashboard');
            }
        } else {
            redirect(base_url() . 'order/login');
        }
    }

    public function is_escrow_user()
    {
        $userdata = $this->CI->session->userdata('user');
        if (!empty($userdata['id'])) {
            if ($userdata['is_title_officer'] == 1) {
                redirect(base_url() . 'title-officer-dashboard');
            } else if ($userdata['is_sales_rep'] == 1) {
                redirect(base_url() . 'sales-dashboard/' . $userdata['id']);
            } else if ($userdata['is_special_lender'] == 1) {
                redirect(base_url() . 'special-lender-dashboard');
            } else if ($userdata['is_payoff_user'] == 1) {
                redirect(base_url() . 'pay-off-dashboard');
            } else if ($userdata['is_escrow_officer'] == 0 && $userdata['is_escrow_assistant'] == 0) {
                redirect(base_url() . 'dashboard');
            }
        } else {
            redirect(base_url() . 'order/login');
        }
    }

    public function getEscrowOfficerInfoBasedOnIdFromOrder($partner_id)
    {
        $this->CI->db->select('*');
        $this->CI->db->from('pct_order_partner_company_info');
        $this->CI->db->where('partner_id', $partner_id);
        $this->CI->db->where('status', 1);
        $query = $this->CI->db->get();
        $partnerInfo = $query->row_array();

        $this->CI->db->select('*');
        $this->CI->db->from('customer_basic_details');
        $this->CI->db->where('email_address', $partnerInfo['email']);
        $this->CI->db->where('is_escrow_officer', 1);
        $this->CI->db->where('status', 1);
        $query = $this->CI->db->get();
        return $query->row_array();
    }

    public function getAssistantUsers($emails)
    {
        $this->CI->db->select('*');
        $this->CI->db->from('customer_basic_details');
        $this->CI->db->where_in('email_address', $emails);
        $this->CI->db->where('is_escrow_assistant', 1);
        $this->CI->db->where('status', 1);
        $query = $this->CI->db->get();
        return $query->result_array();
    }

    public function updateCommisssionCalculation()
    {
        $command = "php " . FCPATH . "index.php frontend/order/common update_commisssion_calculation";
        if (substr(php_uname(), 0, 7) == "Windows") {
            pclose(popen("start /B " . $command, "r"));
        } else {
            exec($command . " > /dev/null &");
        }
    }

    public function logAdminActivity($activity)
    {
        $userdata = $this->CI->session->userdata('admin');
        $data = array(
            'user_id' => $userdata['id'],
            'message' => $activity,
            'created_at' => date("Y-m-d H:i:s"),
        );
        $this->CI->db->insert('pct_admin_activity_logs', $data);
    }

    public function getRoleList()
    {
        $this->CI->db->select('id, title');
        $this->CI->db->from('pct_users_role');
        $query = $this->CI->db->get();
        $getRoleList = $query->result_array();
        $roleList = [];
        foreach ($getRoleList as $role) {
            $roleList[$role['id']] = $role['title'];
        }
        return $roleList;
    }

    public function checkRoleAccess()
    {
        $userdata = $this->CI->session->userdata('admin');
        if (!empty($userdata)) {
            if ($userdata['role_id'] == 5) {
                redirect(base_url() . 'order/admin/transactees-list');
            } else {
                return true;
            }
        }
    }

    public function convertTimezone($dateTime, $format = 'm/d/Y h:i:s A', $to_timezone = '')
    {
        $default_timezone = $to_timezone = 'America/Los_Angeles';
        if (!empty($_COOKIE['user_timezone']) && $to_timezone == '') {
            $to_timezone = $_COOKIE['user_timezone'];
        } else if ($to_timezone == '') {
            $to_timezone = 'America/Los_Angele';
        }
        $date = new DateTime($dateTime);
        try {
            $date->setTimezone(new DateTimeZone($to_timezone));
        } catch (\Throwable $th) {
            $date->setTimezone(new DateTimeZone($default_timezone));
        }
        return $date->format($format);
    }
}
