<?php

(defined('BASEPATH')) or exit('No direct script access allowed');

class SpecialDashboard extends MX_Controller
{

    // private $special_lender_js_version = '01';
    private $version;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url', 'form')
        );
        $this->version = strtotime(date('Y-m-d'));
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->library('order/template');
        $this->load->library('order/salesDashboardTemplate');
        $this->load->library('order/order');
        $this->load->library('order/common');
        $this->common->is_special_lender_user();
    }

    public function index()
    {
        $userdata = $this->session->userdata('user');
        $name = isset($userdata['name']) && !empty($userdata['name']) ? $userdata['name'] : '';
        $is_master = isset($userdata['is_master']) && !empty($userdata['is_master']) ? $userdata['is_master'] : '';
        $data['name'] = $name;
        $data['is_master'] = $is_master;
        $data['is_title_officer'] = $userdata['is_title_officer'] == 1 ? 1 : 0;
        $data['is_special_lender'] = $userdata['is_special_lender'] == 1 ? 1 : 0;
        $data['is_sales_rep'] = isset($userdata['is_sales_rep']) && !empty($userdata['is_sales_rep']) ? 1 : 0;
        $data['order_lists'] = $this->order->get_recent_orders();
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        // $this->template->addJS( base_url('assets/frontend/js/order/special_lender.js?v=special_lender_'.$this->special_lender_js_version));
        // $this->template->show("order", "special_lender_dashboard", $data);
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/special_lender.js?v=' . $this->version));
        $this->salesdashboardtemplate->show("order", "special_lender_dashboard", $data);
    }

    public function get_special_lenders_orders()
    {
        $params = array();
        $data = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 2;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $pageno = ($params['start'] / $params['length']) + 1;
            $order_lists = $this->order->get_special_lenders_orders($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $order_lists = $this->order->get_special_lenders_orders($params);
        }

        if (isset($order_lists['data']) && !empty($order_lists['data'])) {
            $i = $params['start'] + 1;
            foreach ($order_lists['data'] as $order) {
                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $order['file_number'];
                $nestedData[] = date("m/d/Y", strtotime($order['created_at']));
                $nestedData[] = $order['full_address'];
                $nestedData[] = $order['primary_owner'];
                $nestedData[] = $order['name'];
                $nestedData[] = $order['company_name'];
                $data[] = $nestedData;
                $i++;
            }
        }

        $json_data['recordsTotal'] = intval($order_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($order_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }
}
