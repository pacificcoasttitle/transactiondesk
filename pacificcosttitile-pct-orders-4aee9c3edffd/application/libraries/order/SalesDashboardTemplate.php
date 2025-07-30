<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class SalesDashboardTemplate
{
    private $data;
    private $js_file;
    private $css_file;
    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->helper('url');
        $this->addJS(base_url('assets/backend/js/jquery-ui.min.js'));
    }

    public function show($folder, $page, $data = null)
    {
        if (!file_exists('application/modules/frontend/views/' . $folder . '/' . $page . '.php')) {
            show_404();
        } else {
            $this->load_JS_and_css();
            $data['notifications'] = $this->getNotifications(5);
            $data['unreadNotificationCount'] = $this->getUnreadNotificationCount(5);
            $data['sidebar'] = true;
            $this->data['header'] = $this->CI->load->view('order/admin-layout/header.php', $data, true);
            if (isset($data['displaySidebar']) && !$data['displaySidebar']) {
                $data['sidebar'] = false;
            }
            $this->data['sidebar'] = $this->CI->load->view('order/admin-layout/sidebar.php', $data, true);
            $this->data['content'] = $this->CI->load->view($folder . '/' . $page . '.php', $data, true);
            $this->data['footer'] = $this->CI->load->view('order/admin-layout/footer.php', $data, true);
            $this->CI->load->view('order/sales_dashboard_template.php', $this->data);
        }
    }

    public function addJS($name)
    {
        $js = new stdClass();
        $js->file = $name;
        $this->js_file[] = $js;
    }

    public function addCSS($name)
    {
        $css = new stdClass();
        $css->file = $name;
        $this->css_file[] = $css;
    }

    private function load_JS_and_css()
    {
        $this->data['css_files'] = '';
        $this->data['js_files'] = '';

        if ($this->css_file) {
            foreach ($this->css_file as $css) {
                $this->data['css_files'] .= "<link rel='stylesheet' type='text/css' href=" . $css->file . ">" . "\n";
            }
        }

        if ($this->js_file) {
            foreach ($this->js_file as $js) {
                $this->data['js_files'] .= "<script type='text/javascript' src=" . $js->file . "></script>" . "\n";
            }
        }
    }

    public function getNotifications($limit)
    {
        $userdata = $this->CI->session->userdata('user');
        $this->CI->db->select('*');
        $this->CI->db->where('sent_user_id', $userdata['id']);
        $this->CI->db->where('is_read', 0);
        $query = $this->CI->db->get('pct_order_notifications');
        $this->CI->db->order_by('pct_order_notifications.id', 'desc');
        //$this->CI->db->limit($limit);
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

    public function getUnreadNotificationCount()
    {
        $userdata = $this->CI->session->userdata('user');
        $this->CI->db->select('count(*) as total_unread_count');
        $this->CI->db->where('sent_user_id', $userdata['id']);
        $this->CI->db->where('is_read', 0);
        $query = $this->CI->db->get('pct_order_notifications');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return array();
        }
    }
}
