<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Customer extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url', 'form')
        );
        $this->load->library('order/adminTemplate');
        $this->load->library('form_validation');
        $this->load->model('order/customer_model');
        $this->load->library('order/common');
        $this->load->library('order/order');
        $this->common->is_admin();
    }

    public function index()
    {
        $data = array();
        $data['title'] = 'PCT Order: Credentials Check';
        $this->admintemplate->show("order/home", "customers", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/customers', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_customer_list()
    {
        $params = array();
        $params['credentials_check'] = isset($_POST['credentials_check']) ? $_POST['credentials_check'] : '';

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['where']['status'] = 1;

            $pageno = ($params['start'] / $params['length']) + 1;

            $customer_lists = $this->customer_model->get_customers($params);
            // $cnt = ($pageno == 1) ? ($params['start']+1) : (($pageno - 1) * $params['length']) + 1;

            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $customer_lists = $this->customer_model->get_customers($params);
        }
        $data = array();

        if (isset($customer_lists['data']) && !empty($customer_lists['data'])) {
            foreach ($customer_lists['data'] as $key => $value) {
                $nestedData = array();
                /*$nestedData[] = $value['customer_number'];*/
                $nestedData[] = $value['first_name'];
                $nestedData[] = $value['last_name'];
                $nestedData[] = $value['email_address'];
                $nestedData[] = $value['company_name'];
                $nestedData[] = $value['street_address'] . ", " . $value['city'] . ", " . $value['zip_code'];
                $nestedData[] = $value['random_password'];

                $type = isset($value['is_escrow']) && !empty($value['is_escrow']) ? 'Escrow' : 'Lender';
                $nestedData[] = $type;
                if ($value['is_password_updated'] == 1) {
                    $nestedData[] = 'Correct';
                } else if ($value['is_password_updated'] == 0 && !empty($value['random_password'])) {
                    $nestedData[] = 'Incorrect';
                } else {
                    $nestedData[] = 'Duplicate Email';
                }
                $nestedData[] = $value['resware_error_msg'];
                $nestedData[] = "<a href='javascript:void(0);' onclick='changePassword(" . $value['id'] . ")' class='btn btn-action'  title='Reset Password'><span class='fas fa-key' aria-hidden='true'></span></a>";

                $data[] = $nestedData;
                // $cnt++;
            }
        }
        $json_data['recordsTotal'] = intval($customer_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($customer_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function make_request($http_method, $endpoint, $body_params = '', $login_details)
    {
        $details = json_decode($login_details, true);
        $login = $details['email'];

        if ($login == 'ghernandez@pct.com') {
            $password = 'Alpha637#';
        } elseif ($login == 'teamrestine@eatonescrow.com') {
            $password = 'Pacific12';
        } else {
            $password = 'Pacific2';
        }

        $ch = curl_init(env('RESWARE_ORDER_API') . $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http_method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body_params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($body_params))
        );
        $error_msg = curl_error($ch);
        $result = curl_exec($ch);
        return $result;
    }

    public function delete_unapproved_user()
    {
        $this->db->from('customer_basic_details');
        $this->db->where('(is_password_updated = 0 and random_password != "")');
        $this->db->like('resware_error_msg', 'Login Failed. User is not approved');
        // $this->db->where('status', 1);
        // $this->db->where('is_master', 0);
        // echo "<pre>";
        // print_r($this->db->get()->result_array());die;
        $this->db->delete();
        echo $this->db->affected_rows() . " users deleted which have resware error status 'Login Failed. User is not approved.'";
        die;
    }

}
