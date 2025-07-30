<?php
defined('BASEPATH') or exit('No direct script access allowed');

class TitlePoint extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url', 'form')
        );
        $this->load->library('order/adminTemplate');
        $this->load->library('form_validation');
        $this->load->library('order/order');
        $this->load->model('order/titlePoint_model');
        $this->load->library('order/common');
        $this->common->is_admin();
    }

    public function index()
    {
        $data = array();
        $data['title'] = 'PCT Order: LV Log';
        $this->admintemplate->show("order/home", "lv_logs", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/lv_logs', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function preListing()
    {
        $data = array();
        $data['title'] = 'PCT Order: Pre Listing Log';
        $this->admintemplate->show("order/home", "pre_listing_logs", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/pre_listing_logs', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function lpXmlLogs()
    {
        $data = array();
        $data['title'] = 'PCT Order: Pre Listing Log';
        $this->admintemplate->addJS(base_url('assets/backend/js/lp-xml-log.js'));
        $this->admintemplate->show("order/home", "lp_xml_log", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/lp_xml_log', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_logs()
    {
        $params = array();

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['dateRange'] = isset($_POST['dateRange']) && !empty($_POST['dateRange']) ? $_POST['dateRange'] : '';
            $params['lvLog'] = isset($_POST['lvLog']) && !empty($_POST['lvLog']) ? $_POST['lvLog'] : '';
            // $params['status']['cs4_result_id_status'] = 'Success';

            $pageno = ($params['start'] / $params['length']) + 1;

            $logs_list = $this->titlePoint_model->getLvLogs($params);

            // $cnt = ($pageno == 1) ? ($params['start']+1) : (($pageno - 1) * $params['length']) + 1;

            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $params['dateRange'] = isset($_POST['dateRange']) && !empty($_POST['dateRange']) ? $_POST['dateRange'] : '';
            $params['lvLog'] = isset($_POST['lvLog']) && !empty($_POST['lvLog']) ? $_POST['lvLog'] : '';
            $logs_list = $this->titlePoint_model->getLvLogs($params);
        }
        $data = array();

        if (isset($logs_list['data']) && !empty($logs_list['data'])) {
            $count = $params['start'] + 1;
            foreach ($logs_list['data'] as $key => $value) {
                $file_id = isset($value['file_id']) && !empty($value['file_id']) ? $value['file_id'] : '';
                if (isset($file_id) && !empty($file_id)) {
                    // $order_details = $this->titlePoint_model->get_order_details($file_id);

                    $nestedData = array();

                    $nestedData[] = $count;
                    $nestedData[] = $value['file_number'];
                    $nestedData[] = $value['full_address'];

                    if ($this->order->fileExistOrNotOnS3('legal-vesting/' . $value['file_number'] . '.pdf')) {
                        $nestedData[] = 'Success';
                    } else if ((strtolower($value['lv_file_status']) != 'success') && !empty($value['lv_file_status'])) {
                        $nestedData[] = $value['lv_file_status'];
                    } else if (empty($value['lv_file_status']) && (strtolower($value['cs4_message']) == 'success')) {
                        $nestedData[] = 'Failed';
                    } else {
                        $nestedData[] = $value['cs4_message'];
                    }

                    // $nestedData[] = date("m/d/Y h:i:s A", strtotime($value['created_at']));
                    $nestedData[] = convertTimezone($value['created_at']);

                    $data[] = $nestedData;
                    $count++;
                }
            }
        }
        $json_data['recordsTotal'] = intval($logs_list['recordsTotal']);
        $json_data['recordsFiltered'] = intval($logs_list['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function get_pre_listing_logs()
    {
        $params = array();

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['dateRange'] = isset($_POST['dateRange']) && !empty($_POST['dateRange']) ? $_POST['dateRange'] : '';
            $params['preListingLog'] = isset($_POST['preListingLog']) && !empty($_POST['preListingLog']) ? $_POST['preListingLog'] : '';

            $pageno = ($params['start'] / $params['length']) + 1;
            $logs_list = $this->titlePoint_model->getPreListingLogs($params);
            // echo "<pre>";
            // print_r($logs_list);die;

            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $params['dateRange'] = isset($_POST['dateRange']) && !empty($_POST['dateRange']) ? $_POST['dateRange'] : '';
            $params['preListingLog'] = isset($_POST['preListingLog']) && !empty($_POST['preListingLog']) ? $_POST['preListingLog'] : '';

            $logs_list = $this->titlePoint_model->getPreListingLogs($params);
        }
        $data = array();

        if (isset($logs_list['data']) && !empty($logs_list['data'])) {
            $count = $params['start'] + 1;
            foreach ($logs_list['data'] as $key => $value) {
                $file_id = isset($value['file_id']) && !empty($value['file_id']) ? $value['file_id'] : '';
                if (isset($file_id) && !empty($file_id)) {
                    // $order_details = $this->titlePoint_model->get_order_details($file_id);

                    $nestedData = array();

                    $nestedData[] = $count;
                    $nestedData[] = $value['file_number'];
                    $nestedData[] = $value['full_address'];

                    if ($this->order->fileExistOrNotOnS3('pre-listing-doc/' . $value['file_number'] . '.pdf')) {
                        $nestedData[] = 'Success';
                    } else if ((strtolower($value['geo_file_status']) != 'success') && !empty($value['geo_file_status'])) {
                        $nestedData[] = $value['geo_file_status'] . ' : ' . $value['geo_file_message'];
                    } else if (empty($value['geo_file_status']) && (strtolower($value['geo_file_status']) == 'success')) {
                        $nestedData[] = 'Failed';
                    } else {
                        $nestedData[] = $value['geo_file_status'];
                    }

                    // $nestedData[] = date("m/d/Y h:i:s A", strtotime($value['created_at']));
                    $nestedData[] = convertTimezone($value['created_at']);

                    $data[] = $nestedData;
                    $count++;
                }
            }
        }
        $json_data['recordsTotal'] = intval($logs_list['recordsTotal']);
        $json_data['recordsFiltered'] = intval($logs_list['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function getLpXmlLogs()
    {
        $params = array();

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['dateRange'] = isset($_POST['dateRange']) && !empty($_POST['dateRange']) ? $_POST['dateRange'] : '';
            $params['preListingLog'] = isset($_POST['preListingLog']) && !empty($_POST['preListingLog']) ? $_POST['preListingLog'] : '';

            $pageno = ($params['start'] / $params['length']) + 1;
            $logs_list = $this->titlePoint_model->getLPOrderLogs($params);
            // echo "<pre>";
            // print_r($logs_list);die;

            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $params['dateRange'] = isset($_POST['dateRange']) && !empty($_POST['dateRange']) ? $_POST['dateRange'] : '';

            $logs_list = $this->titlePoint_model->getLPOrderLogs($params);
        }
        $data = array();

        if (isset($logs_list['data']) && !empty($logs_list['data'])) {
            $count = $params['start'] + 1;
            foreach ($logs_list['data'] as $key => $value) {
                $file_id = isset($value['file_id']) && !empty($value['file_id']) ? $value['file_id'] : '';
                if (isset($file_id) && !empty($file_id)) {

                    $nestedData = array();

                    $nestedData[] = $count;
                    $nestedData[] = $value['lp_file_number'];
                    $nestedData[] = convertTimezone($value['created_at']);

                    $documentUrl = env('AWS_PATH') . 'lp-xml/' . $value['lp_file_number'] . '.xml';
                    if ($this->order->fileExistOrNotOnS3('lp-xml/' . $value['lp_file_number'] . '.xml')) {
                        $nestedData[] = "<div style='display:flex;'><a href='#' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"xml"' . ");'><i class='fas fa-fw fa-download'></i></a>
                        <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                    } else {
                        $nestedData[] = 'XML not exist';
                    }

                    // $nestedData[] = date("m/d/Y h:i:s A", strtotime($value['created_at']));

                    $data[] = $nestedData;
                    $count++;
                }
            }
        }
        $json_data['recordsTotal'] = intval($logs_list['recordsTotal']);
        $json_data['recordsFiltered'] = intval($logs_list['recordsFiltered']);
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

    public function taxLog()
    {
        $data = array();
        $data['title'] = 'PCT Order: Tax Log';
        $this->admintemplate->show("order/home", "tax_logs", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/tax_logs', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function taxData()
    {
        $data = array();
        $data['title'] = 'PCT Order: Tax Data';
        $this->admintemplate->show("order/home", "tax_data", $data);
    }

    public function get_tax_data()
    {
        $params = array();

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['dateRange'] = isset($_POST['dateRange']) && !empty($_POST['dateRange']) ? $_POST['dateRange'] : '';
            $params['taxLog'] = isset($_POST['taxLog']) && !empty($_POST['taxLog']) ? $_POST['taxLog'] : '';

            $pageno = ($params['start'] / $params['length']) + 1;

            $logs_list = $this->titlePoint_model->getTaxData($params);

            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $params['dateRange'] = isset($_POST['dateRange']) && !empty($_POST['dateRange']) ? $_POST['dateRange'] : '';
            $params['taxLog'] = isset($_POST['taxLog']) && !empty($_POST['taxLog']) ? $_POST['taxLog'] : '';
            $logs_list = $this->titlePoint_model->getTaxData($params);
        }
        $data = array();

        if (isset($logs_list['data']) && !empty($logs_list['data'])) {
            $count = $params['start'] + 1;
            foreach ($logs_list['data'] as $key => $value) {
                $file_id = isset($value['file_id']) && !empty($value['file_id']) ? $value['file_id'] : '';
                if (isset($file_id) && !empty($file_id)) {
                    // $order_details = $this->titlePoint_model->get_order_details($file_id);

                    $nestedData = array();
                    $nestedData[] = $count;
                    $nestedData[] = $value['file_number'];
                    $nestedData[] = $value['full_address'];
                    $nestedData[] = $value['apn'];

                    if ((!empty($value['tax_data_status']))) {
                        $nestedData[] = $value['tax_data_status'];
                    } else {
                        $nestedData[] = 'Failed';
                    }
                    $nestedData[] = convertTimezone($value['created_at']);
                    $orderId = $value['order_id'];
                    $sessionId = $value['session_id'];
                    $documentUrl = env('AWS_PATH') . 'tax-data-xml/' . $sessionId . '.xml';
                    if (!empty($value['tax_data_status']) && $this->order->fileExistOrNotOnS3('tax-data-xml/' . $sessionId . '.xml')) {
                        $nestedData[] = "<div style='display:flex;'><a href='javascript:void(0)' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"xml"' . ");'><i class='fas fa-fw fa-download'></i></a>
                        <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                    } else {
                        $nestedData[] = 'XML not exist';
                    }

                    $data[] = $nestedData;
                    $count++;
                }
            }
        }
        $json_data['recordsTotal'] = intval($logs_list['recordsTotal']);
        $json_data['recordsFiltered'] = intval($logs_list['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function get_tax_logs()
    {
        $params = array();

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['dateRange'] = isset($_POST['dateRange']) && !empty($_POST['dateRange']) ? $_POST['dateRange'] : '';
            $params['taxLog'] = isset($_POST['taxLog']) && !empty($_POST['taxLog']) ? $_POST['taxLog'] : '';

            $pageno = ($params['start'] / $params['length']) + 1;

            $logs_list = $this->titlePoint_model->getTaxLogs($params);

            // $cnt = ($pageno == 1) ? ($params['start']+1) : (($pageno - 1) * $params['length']) + 1;

            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $params['dateRange'] = isset($_POST['dateRange']) && !empty($_POST['dateRange']) ? $_POST['dateRange'] : '';
            $params['taxLog'] = isset($_POST['taxLog']) && !empty($_POST['taxLog']) ? $_POST['taxLog'] : '';
            $logs_list = $this->titlePoint_model->getTaxLogs($params);
        }
        $data = array();

        if (isset($logs_list['data']) && !empty($logs_list['data'])) {
            $count = $params['start'] + 1;
            foreach ($logs_list['data'] as $key => $value) {
                $file_id = isset($value['file_id']) && !empty($value['file_id']) ? $value['file_id'] : '';
                if (isset($file_id) && !empty($file_id)) {
                    // $order_details = $this->titlePoint_model->get_order_details($file_id);

                    $nestedData = array();
                    /*$nestedData[] = $value['customer_number'];*/
                    $nestedData[] = $count;
                    $nestedData[] = $value['file_number'];
                    $nestedData[] = $value['full_address'];
                    $nestedData[] = $value['apn'];

                    if ($this->order->fileExistOrNotOnS3('tax/' . $value['file_number'] . '.pdf')) {
                        $nestedData[] = 'Success';
                    } else if ((strtolower($value['tax_file_status']) != 'success') && !empty($value['tax_file_status'])) {
                        $nestedData[] = $value['tax_file_status'];
                    } else if (empty($value['tax_file_status']) && (strtolower($value['cs3_message']) == 'success')) {
                        $nestedData[] = 'Failed';
                    } else {
                        $nestedData[] = $value['cs3_message'];
                    }
                    // $nestedData[] = date("m/d/Y h:i:s A", strtotime($value['created_at']));
                    $nestedData[] = convertTimezone($value['created_at']);
                    $orderId = $value['order_id'];
                    $fileNumber = $value['file_number'];
                    if ($value['tax_file_status'] == 'processing' && !empty($value['tax_request_id'])) {
                        $taxRequestId = $value['tax_request_id'];
                        $nestedData[] = "<a style='margin-left:5px;' href='#' onclick='regenerateTaxDocument(" . '"' . $taxRequestId . '"' . ", " . '"' . $orderId . '"' . ", " . '"' . $fileNumber . '"' . ");' title='Regenerate Tax Document'><i class='fas fa-sync' aria-hidden='true'></i></a>";
                    } else if ($value['tax_file_status'] == 'processing' && empty($value['tax_request_id'])) {
                        $cs3ServiceId = $value['cs3_service_id'];
                        $nestedData[] = "<a style='margin-left:5px;' href='#' onclick='generateTaxDocument(" . '"' . $cs3ServiceId . '"' . ", " . '"' . $orderId . '"' . ", " . '"' . $fileNumber . '"' . ");' title='Regenerate Tax Document'><i class='fas fa-sync' aria-hidden='true'></i></a>";
                    } else {
                        $nestedData[] = '';
                    }
                    $data[] = $nestedData;
                    $count++;
                }
            }
        }
        $json_data['recordsTotal'] = intval($logs_list['recordsTotal']);
        $json_data['recordsFiltered'] = intval($logs_list['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function grantDeedLog()
    {
        $data = array();
        $data['title'] = 'PCT Order: Grant Deed Log';
        $this->admintemplate->show("order/home", "grant_deed_logs", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/grant_deed_logs', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_grant_deed_logs()
    {
        $params = array();

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['dateRange'] = isset($_POST['dateRange']) && !empty($_POST['dateRange']) ? $_POST['dateRange'] : '';
            $params['grantLog'] = isset($_POST['grantLog']) && !empty($_POST['grantLog']) ? $_POST['grantLog'] : '';
            $pageno = ($params['start'] / $params['length']) + 1;

            $logs_list = $this->titlePoint_model->getGrantDeedLogs($params);

            // $cnt = ($pageno == 1) ? ($params['start']+1) : (($pageno - 1) * $params['length']) + 1;

            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $params['dateRange'] = isset($_POST['dateRange']) && !empty($_POST['dateRange']) ? $_POST['dateRange'] : '';
            $params['grantLog'] = isset($_POST['grantLog']) && !empty($_POST['grantLog']) ? $_POST['grantLog'] : '';
            $logs_list = $this->titlePoint_model->getGrantDeedLogs($params);
        }
        $data = array();

        if (isset($logs_list['data']) && !empty($logs_list['data'])) {
            $count = $params['start'] + 1;
            foreach ($logs_list['data'] as $key => $value) {
                $file_id = isset($value['file_id']) && !empty($value['file_id']) ? $value['file_id'] : '';
                if (isset($file_id) && !empty($file_id)) {
                    // $order_details = $this->titlePoint_model->get_order_details($file_id);

                    $nestedData = array();

                    $nestedData[] = $count;
                    $nestedData[] = $value['file_number'];
                    $nestedData[] = $value['full_address'];
                    $nestedData[] = $value['grant_deed_type'];

                    if ($this->order->fileExistOrNotOnS3('grant-deed/' . $value['file_number'] . '.pdf')) {
                        $nestedData[] = 'Success';
                    } elseif (strtolower($value['grant_deed_message']) != 'success') {
                        $nestedData[] = $value['grant_deed_message'];
                    } else {
                        $nestedData[] = 'Failed';
                    }

                    // $nestedData[] = date("m/d/Y h:i:s A", strtotime($value['created_at']));
                    $nestedData[] = convertTimezone($value['created_at']);
                    $data[] = $nestedData;
                    $count++;
                }
            }
        }
        $json_data['recordsTotal'] = intval($logs_list['recordsTotal']);
        $json_data['recordsFiltered'] = intval($logs_list['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }
}
