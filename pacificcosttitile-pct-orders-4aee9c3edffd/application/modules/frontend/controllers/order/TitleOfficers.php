<?php

(defined('BASEPATH')) or exit('No direct script access allowed');

class TitleOfficers extends MX_Controller
{
    // private $title_officer_dashboard_js_version = '03';
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
        $this->load->model('order/apiLogs');
        $this->load->model('order/reviewPrelimData');
        $this->load->model('order/titleOfficer');
        $this->load->model('order/home_model');
        $this->load->library('order/resware');
        $this->load->library('order/common');
        $this->common->is_title_officer_user();
    }

    public function index()
    {
        $userdata = $this->session->userdata('user');
        $name = isset($userdata['name']) && !empty($userdata['name']) ? $userdata['name'] : '';
        $data['name'] = $name;
        $data['user_email'] = $userdata['email'];
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $con = array('id' => $userdata['id']);
        $user_info = $this->order->getSalesRep($con);
        $data['user_info'] = $user_info;
        // $this->template->addJS( base_url('assets/frontend/js/order/title_officer_dashboard.js?v=title_officer_dashboard_'.$this->title_officer_dashboard_js_version) );
        // $this->template->show("order/title_officer", "dashboard", $data);
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/title_officer_dashboard.js?v=' . $this->version));
        $this->salesdashboardtemplate->show("order/title_officer", "dashboard", $data);
    }

    public function get_title_officer_orders()
    {
        $params = array();
        $data = array();
        $status = $this->input->post('status');
        $month = $this->input->post('month') ? $this->input->post('month') : '';
        $order_type = $this->input->post('order_type');
        $params['status'] = isset($status) && !empty($status) ? $status : 'open';
        $params['month'] = isset($month) && !empty($month) ? $month : date('m');
        $params['order_type'] = isset($order_type) && !empty($order_type) ? $order_type : '';

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 2;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $pageno = ($params['start'] / $params['length']) + 1;
            $order_lists = $this->order->get_orders($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $order_lists = $this->order->get_orders($params);
        }

        if (isset($order_lists['data']) && !empty($order_lists['data'])) {
            $i = $params['start'] + 1;
            foreach ($order_lists['data'] as $order) {
                $nestedData = array();
                $nestedData[] = (empty($order['file_number']) ? $order['lp_file_number'] : ((empty($order['lp_file_number'])) ? $order['file_number'] : $order['file_number'] . '&nbsp; <i class="fa fa-info-circle" aria-hidden="true"></i> <span class="tooltiptext">It\'s LP order ' . $order['lp_file_number'] . ' and it\'s converted into normal order </span>'));
                $nestedData[] = date("m/d/Y", strtotime($order['created_at']));
                $nestedData[] = $order['full_address'];
                if ($order['file_number'] == 0 && !empty($order['lp_file_number'])) {
                    $nestedData[] = ucfirst($order['lp_report_status']);
                } else {
                    $nestedData[] = ucfirst($order['resware_status']);
                }

                $action = '<div class="dropdown"><a class="btn dropdown-toggle click-action-type" type="button" data-toggle="dropdown" href="#">Click Action Type <span class="caret"></span></a><ul class="dropdown-menu">';
                if ($order['prelim_summary_id'] != 0) {
                    $action .= "<li  class='text-center'><a href='" . base_url() . "review-file/" . $order['file_id'] . "'><button class='btn btn-grad-2a button-color' type='button'>Review File</button></a></li>";
                } else {
                    $action .= "<li class='text-center'><a href='javascript:void(0);'><button class='btn btn-grad-2a' style='background: #d35411;padding: 5px 30px;' type='button'>Not Ready</button></a></li>";
                }

                if (!empty($order['file_number'])) {
                    $action .= "<li  class='text-center'><a href='javascript:void(0);'><button class='btn btn-grad-2a button-color' type='button' onclick='getPartners(" . $order['file_id'] . ");'>View Partners</button></a></li>";
                }

                if ($order['file_number'] == 0 && !empty($order['lp_file_number']) && $order['lp_report_status'] == 'approved') {
                    $documentUrl = env('AWS_PATH') . "pre-listing-doc/" . $order['lp_file_number'] . '.pdf';
                    $reportDocumentUrl = env('AWS_PATH') . "pre-listing-doc/pre_listing_report_" . $order['lp_file_number'] . '.pdf';
                    $action .= "<li><a target='_blank' href='$documentUrl'><button class='btn btn-grad-2a button-color' type='button' style='margin-top:10px;'>View Pre List Doc</button></a></li><li><a target='_blank' href='$reportDocumentUrl'><button class='btn btn-grad-2a button-color' type='button' style='margin-top:10px;'>View LP Report</button></a></li>";
                }
                $action .= "</ul></div>";
                $nestedData[] = $action;

                // if ($order['prelim_summary_id'] != 0) {
                //     $action = "<a href='".base_url()."review-file/".$order['file_id']."'><button class='btn btn-grad-2a button-color' type='button'>REVIEW FILE</button></a>";
                // } else {
                //     $action = "<a href='javascript:void(0);'><button class='btn btn-grad-2a' style='background: #d35411;' type='button'>Not Ready</button></a>";
                // }

                // if (!empty($order['file_number'])) {
                //     $action .= "<a href='javascript:void(0);'><button class='btn btn-grad-2a button-color' type='button' onclick='getPartners(".$order['file_id'].");'>VIEW Partners</button></a>";
                // }

                // if ($order['file_number'] == 0 && !empty($order['lp_file_number']) && $order['lp_report_status'] == 'approved') {
                //     $documentUrl = env('AWS_PATH')."pre-listing-doc/".$order['lp_file_number'].'.pdf';
                //     $reportDocumentUrl = env('AWS_PATH')."pre-listing-doc/pre_listing_report_".$order['lp_file_number'].'.pdf';
                //     $action .= "<a target='_blank' href='$documentUrl'><button class='btn btn-grad-2a button-color' type='button' style='margin-top:10px;'>View Pre List Doc</button></a><a target='_blank' href='$reportDocumentUrl'><button class='btn btn-grad-2a button-color' type='button' style='margin-top:10px;'>View LP Report</button></a>";
                // }

                // $nestedData[] = $action;
                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($order_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($order_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function notes()
    {
        $data['title'] = 'Notes | Pacific Coast Title Company';
        // $this->template->addJS( base_url('assets/frontend/js/order/title_officer_dashboard.js?v='.$this->version) );
        // $this->template->show("order/title_officer", "notes", $data);
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/title_officer_dashboard.js?v=' . $this->version));
        $this->salesdashboardtemplate->show("order/title_officer", "notes", $data);
    }

    public function get_notes_orders()
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
            $order_lists = $this->order->get_orders($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $order_lists = $this->order->get_orders($params);
        }

        if (isset($order_lists['data']) && !empty($order_lists['data'])) {
            $i = $params['start'] + 1;
            foreach ($order_lists['data'] as $order) {
                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $order['file_number'];
                $nestedData[] = $order['full_address'];
                $nestedData[] = '<a href="' . base_url() . 'get-notes/' . $order['file_id'] . '" class="btn btn-success btn-icon-split"><span class="icon text-white-50"><i class="fas fa-eye"></i></span><span class="text"> View / Add Notes </span></a>';
                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($order_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($order_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function uploadFileDocument()
    {
        $data['title'] = 'Smart Dashboard | Upload FIle';
        // $this->template->addJS( base_url('assets/frontend/js/order/title_officer_dashboard.js?v=title_officer_dashboard_'.$this->title_officer_dashboard_js_version) );
        // $this->template->show("order/title_officer", "attach_files", $data);
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/title_officer_dashboard.js?v=' . $this->version));
        $this->salesdashboardtemplate->show("order/title_officer", "attach_files", $data);
    }

    public function getFileDocument()
    {
        $this->load->model('order/fileDocument_model');
        $userdata = $this->session->userdata('user');
        $files_data = $this->fileDocument_model->get_forms();

        $tableData = array();
        foreach ($files_data as $key => $file_data) {
            $tmp_array = array();
            $tmp_array[] = ($key + 1);
            $tmp_array[] = $file_data->name;
            $tmp_array[] = $file_data->description;
            $tmp_array[] = date('m/d/Y', strtotime($file_data->created_at));
            $documentName = $file_data->file_path;
            if (env('AWS_ENABLE_FLAG') == 1) {
                $documentUrl = env('AWS_PATH') . "file_document/" . $documentName;
                $action = "<a href='javascript:void(0);' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"' . $documentName . '"' . ");' class='btn btn-success btn-icon-split'><span class='icon text-white-50'><i class='fas fa-download'></i></span><span class='text'> Download </span></a>";
            } else {
                $documentUrl = FCPATH . 'uploads/file_document/' . $documentName;
                $action = '<a href="' . $documentUrl . '" download class="btn btn-success btn-icon-split"><span class="icon text-white-50"><i class="fas fa-download"></i></span><span class="text"> Download </span></a>';
            }
            $tmp_array[] = $action;
            $tableData[] = $tmp_array;
        }

        $json_data['recordsTotal'] = count($tableData);
        $json_data['recordsFiltered'] = count($tableData);
        $json_data['data'] = $tableData;
        echo json_encode($json_data);
    }

    public function downloadAwsDocument()
    {
        $url = $this->input->post('url');
        $binaryData = base64_encode(file_get_contents($url));
        echo $binaryData;exit;
    }
}
