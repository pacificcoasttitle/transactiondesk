<?php

(defined('BASEPATH')) or exit('No direct script access allowed');

class Dashboard extends MX_Controller
{

    // private $dashboard_js_version = '01';
    // private $fees_js_version = '01';
    // private $order_fee_js_version = '03';
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
        $this->load->model('order/orderRecording');
        $this->load->library('order/order');
        $this->load->model('order/apiLogs');
        $this->load->model('order/reviewPrelimData');
        $this->load->model('order/titleOfficer');
        $this->load->model('order/home_model');
        $this->load->model('order/fees_model');
        $this->load->library('order/resware');
        $this->order->is_user();
    }

    public function index()
    {
        $userdata = $this->session->userdata('user');
        $name = isset($userdata['name']) && !empty($userdata['name']) ? $userdata['name'] : '';
        $is_master = isset($userdata['is_master']) && !empty($userdata['is_master']) ? $userdata['is_master'] : '';
        $data['name'] = $name;
        $data['is_master'] = $is_master;
        $data['user_email'] = $userdata['email'];
        $data['is_title_officer'] = $userdata['is_title_officer'] == 1 ? 1 : 0;
        $data['is_special_lender'] = $userdata['is_special_lender'] == 1 ? 1 : 0;
        $data['is_sales_rep'] = isset($userdata['is_sales_rep']) && !empty($userdata['is_sales_rep']) ? 1 : 0;
        $data['order_lists'] = $this->order->get_recent_orders();
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        // $this->template->addJS( base_url('assets/frontend/js/order/dashboard.js?v='.$this->version) );
        // $this->template->show("order", "dashboard", $data);
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/dashboard.js?v=' . $this->version));
        $this->salesdashboardtemplate->show("order", "dashboard", $data);
    }

    public function recordings()
    {
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $this->template->show("order", "recordings", $data);
    }

    public function get_recordings()
    {
        $this->db->select('*');
        $this->db->from('pct_order_recordings_monthly_sync');
        $this->db->where('month', date('Ym'));
        $this->db->order_by("day", "desc");
        $this->db->where('is_sync', 1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $syncData = $query->result_array();
            if ($syncData[0]['day'] == date('d')) {
                $this->get_recordings_from_api(date("Y-m-d"));
            } else {
                $day = $syncData[0]['day'];
                $begin = new DateTime(date("Y-m-$day"));
                $end = new DateTime(date('Y-m-d', strtotime(date('Y-m-d') . ' +1 day')));

                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($begin, $interval, $end);
                $i = 0;
                foreach ($period as $dt) {
                    $this->get_recordings_from_api($dt->format("Y-m-d"));
                    if ($i != 0) {
                        $this->db->insert('pct_order_recordings_monthly_sync', array('is_sync' => 1, 'month' => $dt->format("Ym"), 'day' => $dt->format("d"), 'created' => date('Y-m-d H:i:s')));
                    }
                    $i++;
                }
            }
        } else {
            $begin = new DateTime(date('Y-m-01'));
            $end = new DateTime(date('Y-m-d', strtotime(date('y-m-d') . ' +1 day')));

            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);

            foreach ($period as $dt) {
                $this->get_recordings_from_api($dt->format("Y-m-d"));
                $this->db->insert('pct_order_recordings_monthly_sync', array('is_sync' => 1, 'month' => $dt->format("Ym"), 'day' => $dt->format("d"), 'created' => date('Y-m-d H:i:s')));
            }
        }

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
            $recording_lists = $this->orderRecording->get_recordings($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $recording_lists = $this->orderRecording->get_recordings($params);
        }

        if (isset($recording_lists['data']) && !empty($recording_lists['data'])) {
            foreach ($recording_lists['data'] as $key => $value) {
                $nestedData = array();
                $date = strtotime($value['recording_date']);
                $recording_date = date('m/d/Y H:i:s', $date);
                $nestedData[] = $recording_date;
                $nestedData[] = $value['instrument_number'];
                $nestedData[] = 'file_number';
                $data[] = $nestedData;
            }
        }

        $json_data['recordsTotal'] = intval($recording_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($recording_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function get_recordings_from_api($date)
    {
        $userdata = $this->session->userdata('user');
        $this->load->model('order/apiLogs');
        $url = getenv('GET_RECORDING_URL') . 'date=' . $date . '&api_token=' . getenv('RECORDING_API_TOKEN');
        $logId = $this->apiLogs->syncLogs($userdata['id'], 'recording', 'get_recordings', $url, array(), array());
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POSTFIELDS, array());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "cache-control: no-cache",
            "Content-Type: application/json",
        ));
        $error_msg = curl_error($ch);
        $result = curl_exec($ch);
        $this->apiLogs->syncLogs($userdata['id'], 'recording', 'get_recordings', $url, array(), $result, 0, $logId);

        if (isset($result) && !empty($result)) {
            $response = json_decode($result, true);
            $records = array();
            if (isset($response) && !empty($response)) {
                foreach ($response as $key => $value) {
                    if (isset($value['documents']) && !empty($value['documents'])) {
                        foreach ($value['documents'] as $k => $v) {
                            $date = strtotime($v['recordingTime']);
                            $recording_date = date('m/d/Y H:i:s', $date);
                            $records = array(
                                'instrument_number' => $v['instrumentNumber'],
                                'state' => $v['state'],
                                'county' => $v['county'],
                                'recording_date' => $v['recordingTime'],
                                'created' => date('Y-m-d H:i:s'),
                                'updated' => date('Y-m-d H:i:s'),
                            );
                            $this->db->replace('pct_order_recordings', $records);
                        }
                    }
                }
            }
        }
    }

    public function fees()
    {
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/fees.js?v=' . $this->version));
        $this->salesdashboardtemplate->show("order", "fees", $data);
    }

    public function get_transaction_orders()
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
                $nestedData[] = "<a href='" . base_url() . "get-fees/" . $order['file_id'] . "'>
									<button type='submit' class='btn btn-info btn-icon-split'>
										<span class='icon text-white-50'>
											<i class='fas fa-file'></i>
										</span>
										<span class='text'>Get Fees</span>
									</button>
								</a>";

                $data[] = $nestedData;
                $i++;
            }
        }

        $json_data['recordsTotal'] = intval($order_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($order_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function get_fees()
    {
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $fileId = $this->uri->segment(2);
        $orderDetails = $this->order->get_order_details($fileId);
        // echo "<pre>";
        // print_r($orderDetails);die;
        $post_data = $result_decoded = array();
        $apiData = json_encode(array('FileNumber' => $orderDetails['file_number']));
        $userData = array(
            'admin_api' => 1,
        );

        $result = $this->resware->make_request('POST', 'files/search', $apiData, $userData);
        if (json_decode($result) && count(json_decode($result)->Files)) {
            $result_decoded = json_decode($result);
            $loanAmount = $result_decoded->Files[0]->Loans[0]->LoanAmount;
            $salesAmount = $result_decoded->Files[0]->SalesPrice;
        }

        $result_decoded = json_decode($result);
        $titleOfficerId = null;
        if (!empty($orderDetails)) {
            $post_data['seller'] = $orderDetails['primary_owner'];
            $post_data['title_officer_email'] = $orderDetails['title_officer_email'];
            $titleOfficerId = $orderDetails['title_officer_id'];
        } else {
            $post_data['seller'] = '';
        }
        $property_data = $result_decoded->Files[0]->Properties[0];
        $buyer_data = $result_decoded->Files[0]->Buyers[0];
        $buyer_name = $buyer_data->Primary;

        $post_data['file_id'] = $result_decoded->Files[0]->FileID;
        $post_data['file_number'] = $result_decoded->Files[0]->FileNumber;
        $post_data['loanAmount'] = $result_decoded->Files[0]->Loans[0]->LoanAmount ? $result_decoded->Files[0]->Loans[0]->LoanAmount : 0;
        $post_data['salesPrice'] = $result_decoded->Files[0]->SalesPrice;
        $post_data['city'] = $property_data->City;
        $post_data['county'] = $property_data->County;
        $post_data['borrower'] = $result_decoded->Files[0]->Buyers[0]->Primary->First . " " . $result_decoded->Files[0]->Buyers[0]->Primary->Last;
        $post_data['full_address'] = $property_data->StreetNumber;
        $post_data['full_address'] .= !empty($property_data->StreetDirection) ? ' ' . substr($property_data->StreetDirection, 0, 1) : '';
        $post_data['full_address'] .= ' ' . $property_data->StreetName;
        $post_data['full_address'] .= ' ' . $property_data->StreetSuffix;
        $post_data['full_address'] .= ', ' . $property_data->City;
        $post_data['full_address'] .= ', ' . $property_data->State;
        $post_data['full_address'] .= ' ' . $property_data->Zip;
        $post_data['borrower'] = !empty($buyer_name->First) ? $buyer_name->First : '';
        $post_data['borrower'] .= !empty($buyer_name->Middle) ? ' ' . $buyer_name->Middle : '';
        $post_data['borrower'] .= !empty($buyer_name->Last) ? ' ' . $buyer_name->Last : '';
        $post_data['borrower'] = trim($post_data['borrower']);

        if (empty($post_data['borrower'])) {
            $post_data['borrower'] = !empty($buyer_name->BusinessName) ? $buyer_name->BusinessName : '';
        }

        $post_data['ECD'] = '';
        if (!empty($result_decoded->Files[0]->Dates->FileCompletedDate)) {
            $ecd_timestamp = str_replace("-0000)/", "", str_replace("/Date(", "", $result_decoded->Files[0]->Dates->FileCompletedDate));
            $ecd_date = date('m/d/Y', $ecd_timestamp / 1000);
            $post_data['ECD'] = $ecd_date;
        }

        if (strpos($result_decoded->Files[0]->TransactionProductType->ProductType, 'Sale') !== false) {
            $post_data['lenderInsurance'] = 1;
            $post_data['transactionType'] = 'Resale';
            $post_data['transferTaxesCheck'] = 1;
            $transType = 'sale';
        } else {
            $post_data['netsheet_for'] = '';
            $post_data['lenderInsurance'] = 0;
            $post_data['transactionType'] = 'Re-Finance';
            $post_data['transferTaxesCheck'] = 0;
            $transType = 'loan';
            $this->load->library('order/resware');
            $endPoint = 'files/' . $fileId . '/partners';
            $user_data['admin_api'] = 1;
            $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_partners_from_admin', env('RESWARE_ORDER_API') . $endPoint, array(), array(), $fileId, 0);
            $resultPartners = $this->resware->make_request('GET', $endPoint, '', $user_data);
            $this->apiLogs->syncLogs(0, 'resware', 'get_partners_from_admin', env('RESWARE_ORDER_API') . $endPoint, array(), $resultPartners, $fileId, $logid);
            $resPartners = json_decode($resultPartners, true);

            if (!empty($resPartners)) {
                $key = array_search(7, array_column($resPartners['Partners'], 'PartnerTypeID'));
                if (str_contains($resPartners['Partners'][$key]['PartnerName'], 'Doma Title Insurance') || ($resPartners['Partners'][$key]['PartnerName'] == 'North American Title Insurance Company')) {
                    $post_data['underwriter'] = 3;
                } elseif ($resPartners['Partners'][$key]['PartnerName'] == 'Westcor Land Title Insurance Company') {
                    $post_data['underwriter'] = 4;
                } else {
                    $post_data['underwriter'] = 4;
                }
            } else {
                $post_data['underwriter'] = 4;
            }
        }
        $post_data['escrowPriceCheck'] = 1;
        $post_data['recordingPriceCheck'] = 1;
        if ($orderDetails['purchase_type'] == '40' || $orderDetails['purchase_type'] == '27' || $orderDetails['purchase_type'] == '24') {
            $post_data['underwriter'] = 5;
        }
        $excludeType = ['Title Related Fees', 'Escrow'];
        $otherFees = $this->fees_model->getFeesEstimation($transType, $excludeType);
        $recordingRatesData = $this->fees_model->getRecordingFees($transType, $titleOfficerId);
        $otherFeesData = $this->fees_model->getOtherAdditionalFees($transType, $titleOfficerId);
        // $data['recordingRates'] = $this->fees_model->getRecordingFees($transType);
        // $data['additional_fees'] = $this->fees_model->getOtherAdditionalFees($transType);
        // echo "<pre>";
        // print_r($data);die;
        $ch = curl_init(env('CALC_API_URL') . 'index.php?welcome/createNetsheetDoc');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . env('PCT_CALC_TOKEN'),
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($post_data)))
        );
        $error_msg = curl_error($ch);
        // print_r(curl_exec($ch));die;
        $calcResult = json_decode(curl_exec($ch), true);
        // print_r($calcResult);die;
        $calcResult['transactionType'] = $post_data['transactionType'];
        $data['is_escrow_flag'] = 0;
        if (str_contains(strtolower($orderDetails['product_type']), 'title and escrow')) {
            $data['is_escrow_flag'] = 1;
        }
        // if ($orderDetails['is_client_escrow'] == 1 || $orderDetails['is_escrow'] == 1) {
        //     $data['is_escrow_flag'] = 1;
        // } else {
        //     $data['is_escrow_flag'] = 0;
        // }
        $calcResult['other_additional_fees_total'] = $otherFeesData['otherFeesTotal'];
        $calcResult['other_additional_fees'] = $otherFeesData['otherFees'];
        $calcResult['recordingTotal'] = $recordingRatesData['recordingFeesTotal'];
        $calcResult['recordingAdditionalFees'] = $recordingRatesData['recordingFees'];
        $data['calcResult'] = $calcResult;
        // echo "<pre>";
        // print_r($data);die;
        $data['order_number'] = isset($orderDetails['file_number']) && !empty($orderDetails['file_number']) ? $orderDetails['file_number'] : '';
        $data['full_address'] = isset($orderDetails['full_address']) && !empty($orderDetails['full_address']) ? $orderDetails['full_address'] : '';
        $data['sales_amount'] = $salesAmount;
        $data['loan_amount'] = $loanAmount;

        $this->salesdashboardtemplate->addCSS(base_url('assets/front/css/style.css'));
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/jspdf.debug.js'));
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/html2canvas.min.js'));
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/html2pdf.bundle.js'));

        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/order_fee.js?v=' . $this->version));
        $this->salesdashboardtemplate->show("order", "get_fees", $data);
    }

    public function get_fee_estimate_pdf()
    {
        $userdata = $this->session->userdata('user');
        $closing_fee_id = isset($_POST['closing_fee_id']) && !empty($_POST['closing_fee_id']) ? $_POST['closing_fee_id'] : '';

        if ($closing_fee_id) {
            $this->load->library('order/resware');

            $endPoint = '/estimates/closingfees/' . $closing_fee_id . '/receipt/pdf';

            $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_fee_estimate_pdf', env('RESWARE_ORDER_API') . $endPoint, $closing_fee_id, array(), 0, 0);

            $result = $this->resware->make_request('GET', $endPoint, array());

            $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_fee_estimate_pdf', env('RESWARE_ORDER_API') . $endPoint, $closing_fee_id, $result, 0, $logid);

            if (isset($result) && !empty($result)) {
                echo base64_encode($result);
            }
        }
    }

    public function multiexplode($delimiters, $string)
    {

        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return $launch;
    }

    public function prelim()
    {
        $fileId = $this->input->post('fileId');
        $data['orderDetails'] = $this->order->get_order_details($fileId);
        $results = $this->load->view('order/review_file_prelim', $data, true);
        echo json_encode($results, true);
    }

    public function get_order_details()
    {
        $fileId = $this->input->post('fileId');
        $data = array();
        if ($fileId) {
            $orderDetails = $this->order->get_order_details($fileId);

            $data['loan_amount'] = isset($orderDetails['loan_amount']) && !empty($orderDetails['loan_amount']) ? $orderDetails['loan_amount'] : '';

            $sales_amount = isset($orderDetails['sales_amount']) && !empty($orderDetails['sales_amount']) ? $orderDetails['sales_amount'] : '';

            if (isset($sales_amount) && !empty($sales_amount)) {
                $data['borrower'] = isset($orderDetails['borrower']) && !empty($orderDetails['borrower']) ? $orderDetails['borrower'] : '';

                $data['secondary_borrower'] = isset($orderDetails['secondary_borrower']) && !empty($orderDetails['secondary_borrower']) ? $orderDetails['secondary_borrower'] : '';
            } else if (isset($orderDetails['loan_amount']) && !empty($orderDetails['loan_amount'])) {
                $primary_owner = isset($orderDetails['primary_owner']) && !empty($orderDetails['primary_owner']) ? $orderDetails['primary_owner'] : '';
                $secondary_owner = isset($orderDetails['secondary_owner']) && !empty($orderDetails['secondary_owner']) ? $orderDetails['secondary_owner'] : '';

                $data['borrower'] = $primary_owner;
                $data['secondary_borrower'] = $secondary_owner;
            }

            $data['escrow_lender_id'] = isset($orderDetails['escrow_lender_id']) && !empty($orderDetails['escrow_lender_id']) ? $orderDetails['escrow_lender_id'] : '';
            $data['property_id'] = isset($orderDetails['property_id']) && !empty($orderDetails['property_id']) ? $orderDetails['property_id'] : '';
            $data['transaction_id'] = isset($orderDetails['transaction_id']) && !empty($orderDetails['transaction_id']) ? $orderDetails['transaction_id'] : '';
            $data['orderId'] = isset($orderDetails['order_id']) && !empty($orderDetails['order_id']) ? $orderDetails['order_id'] : '';

            $data['fileId'] = $fileId;
            $lender = '';

            if (isset($orderDetails['lender_company_name']) && !empty($orderDetails['lender_company_name'])) {
                $lender = $orderDetails['lender_company_name'];
            }
            if (isset($orderDetails['lender_email']) && !empty($orderDetails['lender_email'])) {
                $lender .= " - " . $orderDetails['lender_email'];
            }
            $data['lender'] = $lender;
            $data['status'] = 'success';
        } else {
            $data['status'] = 'error';
        }

        echo json_encode($data);exit;
    }

    public function update_order_details()
    {
        $this->load->library('order/resware');
        /*$orderId = isset($_POST['orderId']) && !empty($_POST['orderId']) ? $_POST['orderId'] : '';

        if($orderId)
        {
        $this->load->model('order/home_model');

        $loan_amount = isset($_POST['loan_amount']) && !empty($_POST['loan_amount']) ? $_POST['loan_amount'] : '';
        $borrower = isset($_POST['borrower']) && !empty($_POST['borrower']) ? $_POST['borrower'] : '';
        $secondary_borrower = isset($_POST['secondary_borrower']) && !empty($_POST['secondary_borrower']) ? $_POST['secondary_borrower'] : '';
        $LenderId = isset($_POST['LenderId']) && !empty($_POST['LenderId']) ? $_POST['LenderId'] : '';
        $fileId = isset($_POST['fileId']) && !empty($_POST['fileId']) ? $_POST['fileId'] : '';
        $transaction_id = isset($_POST['transaction_id']) && !empty($_POST['transaction_id']) ? $_POST['transaction_id'] : '';
        $property_id = isset($_POST['property_id']) && !empty($_POST['property_id']) ? $_POST['property_id'] : '';

        $orderDetails = $this->order->get_order_details($fileId);
        $sales_amount = isset($orderDetails['sales_amount']) && !empty($orderDetails['sales_amount']) ? $orderDetails['sales_amount'] : '';

        if(isset($sales_amount) && !empty($sales_amount))
        {
        $update_data = array();
        if($borrower)
        {
        $update_data['borrower'] = $borrower;
        }
        if($secondary_borrower)
        {
        $update_data['secondary_borrower'] = $secondary_borrower;
        }
        $condition = array(
        'id' => $transaction_id
        );
        $borrower_update_flag = $this->home_model->update($update_data, $condition, 'transaction_details');
        }
        else if(isset($orderDetails['loan_amount']) && !empty($orderDetails['loan_amount']))
        {
        $update_data = array();
        if($borrower)
        {
        $update_data['primary_owner'] = $borrower;
        }
        if($secondary_borrower)
        {
        $update_data['secondary_owner'] = $secondary_borrower;
        }

        $condition = array(
        'id' => $property_id
        );

        $owner_update_flag = $this->home_model->update($update_data, $condition, 'property_details');
        }

        if(isset($loan_amount) && !empty($loan_amount))
        {
        $update_data = array();

        if($loan_amount)
        {
        $update_data['loan_amount'] = $loan_amount;
        }
        $condition = array(
        'id' => $transaction_id
        );
        $transaction_update_flag = $this->home_model->update($update_data, $condition, 'transaction_details');
        }

        if(isset($LenderId) && !empty($LenderId))
        {
        $update_data = array();

        $update_data['escrow_lender_id'] = $LenderId;

        $condition = array(
        'id' => $property_id
        );

        $property_update_flag = $this->home_model->update($update_data, $condition, 'property_details');
        }
        if($property_update_flag || $transaction_update_flag)
        {
        $data = array('status'=>'success', 'fileId'=>$fileId);
        }
        else
        {
        $data = array('status'=>'error');
        }
        echo json_encode($data); exit;
        }*/

        $userdata = $this->session->userdata('user');
        $orderId = $this->input->post('orderId');
        if ($orderId) {
            $this->load->model('order/home_model');

            $TitleOfficer = $this->input->post('TitleOfficer');
            $loan_amount = $this->input->post('loan_amount');
            $loan_number = $this->input->post('loan_number');
            $primary_first_name = $this->input->post('primary_first_name');
            $primary_last_name = $this->input->post('primary_last_name');
            $primary_owner = $primary_first_name . " " . $primary_last_name;
            $secondary_first_name = $this->input->post('secondary_first_name');
            $secondary_last_name = $this->input->post('secondary_last_name');
            $secondaryOwner = $secondary_first_name . " " . $secondary_last_name;
            $name = explode(" ", $this->input->post('LenderName'));

            $LenderId = $this->input->post('LenderId');
            $orderId = $this->input->post('orderId');
            $transaction_id = $this->input->post('transaction_id');
            $property_id = $this->input->post('property_id');
            $fileId = $this->input->post('fileId');
            $s_report_date = $this->input->post('s_report_date');
            $p_report_date = $this->input->post('p_report_date');
            $s_report_date = date("Y-m-d", strtotime($s_report_date));
            $p_report_date = date("Y-m-d", strtotime($p_report_date));

            $orderDetails = $this->order->get_order_details($fileId);

            if (isset($LenderId) && !empty($LenderId)) {
                $lender_details = array(
                    'first_name' => $name[0],
                    'last_name' => !empty($name[1]) ? $name[1] : '',
                    'telephone_no' => !empty($this->input->post('LenderTelephone')) ? $this->input->post('LenderTelephone') : "",
                    'email_address' => !empty($this->input->post('LenderEmailAddress')) ? $this->input->post('LenderEmailAddress') : "",
                    'company_name' => !empty($this->input->post('LenderCompany')) ? $this->input->post('LenderCompany') : "",
                    'street_address' => !empty($this->input->post('LenderAddress')) ? $this->input->post('LenderAddress') : "",
                    'city' => !empty($this->input->post('LenderCity')) ? $this->input->post('LenderCity') : "",
                    'zip_code' => !empty($this->input->post('LenderZipcode')) ? $this->input->post('LenderZipcode') : "",
                );
                $condition = array(
                    'id' => $LenderId,
                );
                $this->home_model->update($lender_details, $condition, 'customer_basic_details');
            }

            $orderUser = $this->home_model->get_user(array('id' => $orderDetails['customer_id']));

            if ($orderDetails['sales_amount'] > 0) {
                $propertyDetails = array('escrow_lender_id' => $LenderId);

                $property_update_flag = $this->home_model->update($propertyDetails, array('id' => $orderDetails['property_id']), 'property_details');

                $transaction_update_flag = $this->home_model->update(array('loan_amount' => $loan_amount, 'loan_number' => $loan_number, 'borrower' => $primary_owner, 'secondary_borrower' => $secondaryOwner, 'title_officer' => $TitleOfficer, 'preliminary_report_date' => $p_report_date, 'supplemental_report_date' => $s_report_date), array('id' => $orderDetails['transaction_id']), 'transaction_details');
            } else {

                $propertyDetails = array('escrow_lender_id' => $LenderId, 'primary_owner' => $primary_owner, 'secondary_owner' => $secondaryOwner);

                $property_update_flag = $this->home_model->update($propertyDetails, array('id' => $orderDetails['property_id']), 'property_details');

                $transaction_update_flag = $this->home_model->update(array('loan_amount' => $loan_amount, 'loan_number' => $loan_number, 'title_officer' => $TitleOfficer, 'preliminary_report_date' => $p_report_date, 'supplemental_report_date' => $s_report_date), array('id' => $orderDetails['transaction_id']), 'transaction_details');
            }

            if ($property_update_flag || $transaction_update_flag) {
                $orderDetails = $this->order->get_order_details($fileId);
                $customer_id = isset($orderDetails['customer_id']) && !empty($orderDetails['customer_id']) ? $orderDetails['customer_id'] : '';

                $this->load->model('order/home_model');
                $customer_data = $this->home_model->get_user(array('id' => $customer_id));
                $pdfData['company'] = isset($customer_data['company_name']) && !empty($customer_data['company_name']) ? $customer_data['company_name'] : '';

                $address = array();
                $street_address = isset($customer_data['street_address']) && !empty($customer_data['street_address']) ? $customer_data['street_address'] : '';
                if ($street_address) {
                    $address[] = $street_address;
                }
                $city = isset($customer_data['city']) && !empty($customer_data['city']) ? $customer_data['city'] : '';
                if ($city) {
                    $address[] = $city;
                }

                $zip_code = isset($customer_data['zip_code']) && !empty($customer_data['zip_code']) ? $customer_data['zip_code'] : '';
                if ($zip_code) {
                    $address[] = $zip_code;
                }
                $pdfData['address'] = implode(', ', $address);
                $pdfData['order_number'] = isset($orderDetails['file_number']) && !empty($orderDetails['file_number']) ? $orderDetails['file_number'] : '';
                $pdfData['property_address'] = isset($orderDetails['full_address']) && !empty($orderDetails['full_address']) ? $orderDetails['full_address'] : '';
                $pdfData['sales_amount'] = isset($orderDetails['sales_amount']) && !empty($orderDetails['sales_amount']) ? $orderDetails['sales_amount'] : '';
                $pdfData['loan_amount'] = isset($orderDetails['loan_amount']) && !empty($orderDetails['loan_amount']) ? $orderDetails['loan_amount'] : '';
                $pdfData['loan_number'] = isset($orderDetails['loan_number']) && !empty($orderDetails['loan_number']) ? $orderDetails['loan_number'] : '';

                if (isset($orderDetails['title_officer']) && !empty($orderDetails['title_officer'])) {
                    if (preg_match('/\\d/', $orderDetails['title_officer']) > 0) {
                        $condition = array(
                            'id' => $orderDetails['title_officer'],
                            'status' => 1,
                        );
                        $titleOfficerDetails = $this->titleOfficer->getTitleOfficerDetails($condition);
                    }
                    $pdfData['title_officer'] = isset($titleOfficerDetails['name']) && !empty($titleOfficerDetails['name']) ? $titleOfficerDetails['name'] : '';
                    $pdfData['title_officer_email'] = isset($titleOfficerDetails['email_address']) && !empty($titleOfficerDetails['email_address']) ? $titleOfficerDetails['email_address'] : '';
                    $pdfData['title_officer_phone'] = isset($titleOfficerDetails['telephone_no']) && !empty($titleOfficerDetails['telephone_no']) ? $titleOfficerDetails['telephone_no'] : '';
                }

                if ($orderDetails['sales_amount'] > 0) {
                    if (!empty($orderDetails['borrower'])) {
                        $pdfData['primary_owner'] = $orderDetails['borrower'];

                    } else {
                        $pdfData['primary_owner'] = '';
                    }

                    if (!empty($orderDetails['secondary_borrower'])) {
                        $pdfData['secondary_owner'] = $orderDetails['secondary_borrower'];
                    } else {
                        $pdfData['secondary_owner'] = '';
                    }
                } else {
                    if (!empty($orderDetails['primary_owner'])) {
                        $pdfData['primary_owner'] = $orderDetails['primary_owner'];

                    } else {
                        $pdfData['primary_owner'] = '';
                    }

                    if (!empty($orderDetails['secondary_owner'])) {
                        $pdfData['secondary_owner'] = $orderDetails['secondary_owner'];
                    } else {
                        $pdfData['secondary_owner'] = '';
                    }
                }

                $pdfData['supplemental_report_date'] = isset($orderDetails['supplemental_report_date']) && !empty($orderDetails['supplemental_report_date']) ? date("m/d/Y h:i:s A", strtotime($orderDetails['supplemental_report_date'])) : '';

                $pdfData['preliminary_report_date'] = isset($orderDetails['preliminary_report_date']) && !empty($orderDetails['preliminary_report_date']) ? date("m/d/Y h:i:s A", strtotime($orderDetails['preliminary_report_date'])) : '';

                $pdfData['underwriter'] = '';
                $endPoint = 'files/' . $fileId . '/partners';
                $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_partners', env('RESWARE_ORDER_API') . $endPoint, array(), array(), $orderDetails['order_id'], 0);

                $user_data['admin_api'] = 1;
                $user_data['from_mail'] = 1;

                $resultPartners = $this->resware->make_request('GET', $endPoint, '', $user_data);
                $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_partners', env('RESWARE_ORDER_API') . $endPoint, array(), $resultPartners, $orderDetails['order_id'], $logid);
                $resPartners = json_decode($resultPartners, true);
                if (!empty($resPartners)) {
                    $key = array_search(7, array_column($resPartners['Partners'], 'PartnerTypeID'));
                    if ($resPartners['Partners'][$key]['PartnerName'] == 'Outside Title Order') {
                        $pdfData['underwriter'] = 'Westcor Land Title Insurance Company';
                    } else {
                        $pdfData['underwriter'] = $resPartners['Partners'][$key]['PartnerName'];
                    }
                }

                $pdfData['proposed_branch_id'] = $orderDetails['proposed_branch_id'];
                if (!empty($orderDetails['proposed_branch_id'])) {
                    $branchDetails = $this->order->getProposedBranchDetail($orderDetails['proposed_branch_id']);
                    $pdfData['branch_address'] = $branchDetails['address'];
                    $pdfData['branch_city'] = $branchDetails['city'];
                    $pdfData['branch_state'] = $branchDetails['state'];
                    $pdfData['branch_zip'] = $branchDetails['zip'];
                }

                $html = $this->load->view('order/proposed_insured_pdf', $pdfData, true);
                $this->load->library('m_pdf');
                $this->m_pdf->pdf->WriteHTML($html);
                $this->load->model('order/document');
                $proposedDocumentCount = $this->document->countProposedInsuredDocument($orderDetails['order_id']);
                $document_name = "proposed_" . $proposedDocumentCount . "_" . $fileId . ".pdf";

                if (!is_dir('uploads/proposed-insured')) {
                    mkdir('./uploads/proposed-insured', 0777, true);
                }

                $pdfFilePath = './uploads/proposed-insured/' . $document_name;
                $this->m_pdf->pdf->Output($pdfFilePath, 'F');
                $this->order->uploadDocumentOnAwsS3($document_name, 'proposed-insured');
                $contents = file_get_contents($pdfFilePath);
                $binaryData = base64_encode($contents);
                // unlink($pdfFilePath);
                $this->home_model->update(array('proposed_insured_document_name' => $document_name), array('file_id' => $fileId), 'order_details');

                $fileSize = filesize('./uploads/proposed-insured/' . $document_name);
                $documentData = array(
                    'document_name' => $document_name,
                    'original_document_name' => $document_name,
                    'document_type_id' => 1031,
                    'document_size' => $fileSize,
                    'user_id' => $userdata['id'],
                    'order_id' => $orderDetails['order_id'],
                    'description' => 'Proposed Insured Document',
                    'is_sync' => 0,
                    'is_prelim_document' => 0,
                    'is_proposed_insured_doc' => 1,
                );
                $documentId = $this->document->insert($documentData);

                $data = array('status' => 'success', 'data' => $binaryData);
            } else {
                $data = array('status' => 'error');
            }
        } else {
            $data = array('status' => 'error');
        }
        echo json_encode($data);exit;
    }

    public function testmail()
    {
        $from_name = 'Pacific Coast Title Company';
        $from_mail = env('FROM_EMAIL');
        $order_message_body = 'Please check attachment for CPL document.';
        $message = $order_message_body;
        $subject = 'CPL Document';
        $to = 'piyush.j@crestinfosystems.net';
        $cc = array();
        $cc = array('hit9391@gmail.com');
        $bcc = array('hitesh_9391@yahoo.com');
        $file = array(base_url() . 'uploads/documents/fnf_155300.pdf');
        $this->load->helper('sendemail');
        $mail_result = send_email($from_mail, $from_name, $to, $subject, $message, $file, $cc, $bcc);
        echo $mail_result;exit;
    }

    public function getOrdersDashboard()
    {
        $params = array();
        $data = array();
        $params['dashboard_order_by'] = 1;
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
                $nestedData[] = $order['resware_status'];
                $nestedData[] = !empty($order['opened_date']) ? convertTimezone($order['opened_date'], 'm/d/Y') : '';
                $nestedData[] = $order['full_address'];

                if ($order['prelim_summary_id'] != 0) {
                    $class = isset($order['is_visited']) && !empty($order['is_visited']) ? 'secondary' : 'success';
                    $actions = "<a href='" . base_url() . "review-file/" . $order['file_id'] . "'>
							<button type='submit' class='btn btn-$class btn-icon-split'>
								<span class='icon text-white-50'>
									<i class='fas fa-file'></i>
								</span>
								<span class='text'>Review File</span>
							</button>
						</a>";
                } else {
                    $actions = "<a href='javascript:void(0)'>
						<button type='submit' class='btn btn-info btn-icon-split'>
							<span class='icon text-white-50'>
								<i class='fas fa-tasks'></i>
							</span>
							<span class='text'>Not Ready</span>
						</button></a>";
                }

                $nestedData[] = $actions;
                $data[] = $nestedData;
                $i++;
            }
        }

        $json_data['recordsTotal'] = intval($order_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($order_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function countWokingsDaysLeftOfMonth()
    {
        $count = 0;
        $counter = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        while (date("n", $counter) == date('m')) {
            if (in_array(date("w", $counter), array(0, 6)) == false) {
                $count++;
            }
            $counter = strtotime("+1 day", $counter);
        }
        $this->db->select('*');
        $this->db->from('pct_holidays');
        $this->db->where('holiday_date >', date('Y-m-d'));
        $this->db->where('holiday_date <=', date("Y-m-t", strtotime(date('Y-m-d'))));
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $res) {
            $weekendFlag = (date('N', strtotime($res['holiday_date'])) >= 6);
            if ($weekendFlag != 1) {
                $count--;
            }
        }
        return $count;
    }

    public function countWorkedDaysOfMonth()
    {
        $count = 0;

        $counter = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
        while (date("n", $counter) == date('m')) {
            if (in_array(date("w", $counter), array(0, 6)) == false) {
                $count++;
            }
            $counter = strtotime("-1 day", $counter);
        }
        $this->db->select('*');
        $this->db->from('pct_holidays');
        $this->db->where('holiday_date >=', date('Y-m-01'));
        $this->db->where('holiday_date <', date('Y-m-d'));
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $res) {
            $weekendFlag = (date('N', strtotime($res['holiday_date'])) >= 6);
            if ($weekendFlag != 1) {
                $count--;
            }
        }
        return $count;
    }

}
