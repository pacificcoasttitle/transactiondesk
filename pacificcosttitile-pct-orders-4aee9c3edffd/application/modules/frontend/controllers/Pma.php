<?php
(defined('BASEPATH')) or exit('No direct script access allowed');
class Pma extends MX_Controller
{
    private $user;
    // private $pma_js_version = '01';
    private $version;
    public function __construct()
    {
        parent::__construct();
        $userdata = $this->session->userdata('user');
        if (empty($userdata) || !isset($userdata['is_master']) || $userdata['is_master'] != 1) {
            redirect('dashboard');
        }
        $this->version = strtotime(date('Y-m-d'));
        $this->user = $userdata;
        // var_dump($this->user);die;
        $this->load->library('order/template');
        $this->load->library('order/salesDashboardTemplate');
        $this->load->model('order/home_model');
        $this->load->library('order/order');
    }

    public function index()
    {
        $data['title'] = 'PMA | Pacific Coast Title Company';
        // $this->template->addCSS( base_url('assets/frontend/css/tablesorter-blue.css') );
        // // $this->template->addCSS( base_url('assets/frontend/css/tablesorter-blue.css') );
        // $this->template->addJS('https://maps.googleapis.com/maps/api/js?key='.env('GOOGLE_MAP_KEY').'&libraries=places&sensor=false');
        // // $this->template->addJS('http://code.jquery.com/ui/1.10.3/jquery-ui.js');
        // $this->template->addJS('assets/frontend/js/jquery.tablesorter.min.js');
        // $this->template->addJS( base_url('assets/frontend/js/pma.js?v=pma_'.$this->pma_js_version));
        // $this->template->show("pma", "list", $data);

        $this->salesdashboardtemplate->addCSS(base_url('assets/frontend/css/tablesorter-blue.css'));
        $this->salesdashboardtemplate->addJS('https://maps.googleapis.com/maps/api/js?key=' . env('GOOGLE_MAP_KEY') . '&libraries=places&sensor=false');
        $this->salesdashboardtemplate->addJS('assets/frontend/js/jquery.tablesorter.min.js');
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/pma.js?v=' . $this->version));
        $this->salesdashboardtemplate->show("pma", "list", $data);
    }

    public function task($action = 'fetchItems')
    {

        $return_data = array();

        $this->load->model('pma/pct_realtor_data_model', 'realtors');
        if ($action == 'fetchItems') {

            $realtors = $this->realtors->get_many_by('agent !=', '');
            $realtor_name = $realtor_company = array();
            foreach ($realtors as $realtor) {
                $realtor_name[] = $realtor->agent;
                if (!empty($realtor->company)) {
                    $realtor_company[] = $realtor->company;
                }
            }

            $realtor_name = array_values(array_unique($realtor_name));
            $realtor_company = array_values(array_unique($realtor_company));

            $return_data['realtor_name'] = $realtor_name;
            $return_data['realtor_company'] = $realtor_company;
        }

        if ($action == 'populate') {
            $type = isset($_GET['type']) ? $_GET['type'] : '';
            $popData = array();
            if ($type == 'agent') {
                $agent = stripslashes($_GET['agent']);
                $realtors = $this->realtors->get_by('agent', $agent);
                if ($realtors) {
                    $popData['company'] = stripslashes($realtors->company);
                    $popData['address'] = stripslashes($realtors->address);
                }
            } else {
                $company = $_GET['company'];
                $company = stripslashes($_GET['company']);
                $realtors = $this->realtors->get_by('company', $company);
                if ($realtors) {
                    $popData['company'] = $company;
                    $popData['address'] = stripslashes($realtors->address);
                }
                // $entry = mysqli_query($con, "SELECT * FROM pmaformdata WHERE company='$company' ORDER BY id ASC");
            }
            $return_data = $popData;
        }

        echo json_encode($return_data);exit;

    }

    public function proxy()
    {
        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );

        $request = $_GET['requrl'];

        // $request = 'http://pct.com/pma/proxy.php?requrl='.urlencode($request);
        // $file = file_get_contents($request, false, stream_context_create($arrContextOptions));
        // echo $file;die;

        $request = str_replace('^', '<', $request);
        $api_key = env('BLACK_KNIGHT_KEY');

        $request .= '&key=' . $api_key;
        $file = file_get_contents($request, false, stream_context_create($arrContextOptions));
        echo $file;
    }

    public function rep_list()
    {
        $condition = array(
            'where' => array(
                'is_sales_rep' => 1,
                'status' => 1,
            ),
        );
        $salesReps = $this->home_model->getSalesRepDetails($condition);
        $options = '';
        $options .= '<option value="">Select Rep</option>';
        foreach ($salesReps as $salesRep) {
            $options .= '<option value="' . $salesRep['id'] . '">' . $salesRep['first_name'] . ' ' . $salesRep['last_name'] . '</option>';
        }
        echo $options;
    }

    public function pma_data()
    {

        $dataUpdate = $this->input->post('dataUpdate');
        $this->load->model('pma/pma_data_model', 'pma');
        $this->load->model('pma/customer_basic_detail_model', 'reps');

        if ($dataUpdate == 'yes') {
            $insert_data = array();
            $insert_data['address'] = $this->input->post('address');
            $insert_data['apn'] = $this->input->post('apn');
            $insert_data['city'] = $this->input->post('city');
            $insert_data['sales_rep'] = $this->input->post('repId');
            $insert_data['link'] = $this->input->post('link');
            $insert_data['added_by'] = $this->user['id'];
            $insert_data['runDate'] = date('Y-m-d H:i:s');
            $cost110 = $this->input->post('cost110');
            $cost111 = $this->input->post('cost111');
            $cost187 = $this->input->post('cost187');

            $insert_data['cost'] = $cost110 + $cost111 + $cost187;

            $this->pma->insert($insert_data);
        }

        $reports = array();
        $cost = 0;

        $report_data = $this->pma->order_by('id', 'DESC')->with('sales_rep')->get_many_by('added_by', $this->user['id']);
        foreach ($report_data as $report_record) {
            $temp_data = array();
            $temp_data['id'] = $report_record->id;
            $temp_data['address'] = $report_record->address;
            $temp_data['city'] = $report_record->city;
            $temp_data['link'] = env('AWS_PATH') . $report_record->link;
            // $temp_data['runDate']=strtotime($report_record->runDate) ? date('m/d/y',strtotime($report_record->runDate)) : '';
            $temp_data['runDate'] = strtotime($report_record->runDate) ? convertTimezone($report_record->runDate, 'm/d/y') : '';
            $temp_data['sales_rep'] = '';
            if ($report_record->sales_rep) {
                $temp_data['sales_rep'] = $report_record->sales_rep->first_name . ' ' . $report_record->sales_rep->last_name;
            }
            $cost += $report_record->cost;
            $reports[] = $temp_data;

        }
        $returnData['reports'] = $reports;

        $sales_reps = array();
        $sales_rep_data = $this->reps->with('pma')->order_by('first_name', 'ASC')->limit(10)->get_many_by('is_sales_rep', '1');
        foreach ($sales_rep_data as $sales_rep_record) {

            $sales_rep_temp = array();
            $sales_rep_temp['rep_id'] = $sales_rep_record->id;
            $sales_rep_temp['name'] = $sales_rep_record->first_name . ' ' . $sales_rep_record->last_name;
            $sales_rep_temp['image'] = !empty($sales_rep_record->sales_rep_report_image) ? env('AWS_PATH') . $sales_rep_record->sales_rep_report_image : '';
            $sales_rep_temp['image_alt'] = strtoupper(substr(trim($sales_rep_record->first_name), 0, 1) . substr(trim($sales_rep_record->last_name), 0, 1));
            $sales_rep_temp['email'] = $sales_rep_record->email_address;
            $sales_rep_temp['phone'] = $sales_rep_record->telephone_no;
            $sales_rep_temp['report_total'] = count($sales_rep_record->pma);
            $cost_sum = 0;
            $report_total_temp = 0;
            $pma_report = $sales_rep_record->pma;
            if (is_array($pma_report) && count($pma_report)) {
                foreach ($pma_report as $pma_report_record) {
                    if ($pma_report_record->added_by == $this->user['id']) {
                        $cost_sum += $pma_report_record->cost;
                        $report_total_temp++;
                    }
                }
            }
            $sales_rep_temp['report_cost'] = $cost_sum;
            $sales_rep_temp['report_total'] = $report_total_temp;

            $sales_reps[] = $sales_rep_temp;

        }
        $returnData['sales_reps'] = $sales_reps;
        $returnData['sales_rep_data'] = $sales_rep_data;

        $total = $this->pma->count_by('added_by', $this->user['id']);

        // $costTotal = mysqli_query($con, "SELECT SUM(cost) AS value_sum FROM pma");
        // $row = mysqli_fetch_assoc($costTotal);
        // $sum = $row['value_sum'];
        // $sum = 0;
        $sum = '$' . $cost;
        $returnData['cost'] = $sum;
        $returnData['total'] = $total;
        echo json_encode($returnData);

    }

    public function pma()
    {
        $post_data = $this->input->post();
        $this->load->helper('pma');
        // var_dump($post_data);

        $report_data = array();
        /* Start generating pdf */
        $address = $this->input->post('address');
        $linkAddress = str_replace(" ", "_", $this->input->post('address'));
        $propertyCity = $this->input->post('city');
        $propertyZip = $this->input->post('zip');
        $propertyState = $this->input->post('state');
        $report_data['rep_name'] = $this->input->post('rep');
        $report_data['realtorName'] = $this->input->post('realtor-name');
        $report_data['realtorCompany'] = $this->input->post('realtor-company');
        $report_data['realtorAddress'] = $this->input->post('realtor-address');
        $rep111 = $this->input->post('report111');
        $rep187 = $this->input->post('report187');

        $arrContextOptions = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );

        /*Report 187*/
        $rep187 = urldecode($rep187);
        // $report187 = file_get_contents($rep187,false, stream_context_create($arrContextOptions));
        $report187 = simplexml_load_file($rep187);

        if (!($report187)) {
            $report187 = simplexml_load_file(base_url('assets/pma/test187.xml'));
        }
        $report_data['main_report'] = $report187;
        /*Report 187*/

        /*Report 111*/
        $rep111 = urldecode($rep111);
        // $report111 = file_get_contents($rep111,false, stream_context_create($arrContextOptions));
        $report111 = simplexml_load_file($rep111);

        $report_data['report_111'] = $report111;
        /*Report 111*/

        /* Comparable Data */
        $comparable_apn = array();
        for ($apn_i = 1; $apn_i <= 8; $apn_i++) {
            $apn_append = 'apn' . $apn_i;
            if (!empty($this->input->post($apn_append))) {
                $comparable_apn[] = $this->input->post($apn_append);
            }
        }
        $report_data['comparable_apn'] = $comparable_apn;

        $compare_i = 0;
        if (count($comparable_apn)) {
            foreach ($report187->ComparableSalesReport->ComparableSales->ComparableSale as $key => $comparableSale) {
                if (in_array($comparableSale->APN, $comparable_apn)) {
                    $comparableSales[] = $comparableSale;
                    $compare_i++;
                    if ($compare_i >= 8) {
                        break;
                    }
                }
            }
        } else {
            foreach ($report187->ComparableSalesReport->ComparableSales->ComparableSale as $key => $comparableSale) {
                $comparableSales[] = $comparableSale;
                $compare_i++;
                if ($compare_i >= 8) {
                    break;
                }
            }
        }
        $report_data['comparableSales'] = $comparableSales;
        /* Comparable Data */

        // echo json_encode($returnData);
        if ($this->input->post('report_lang') == 'english') {
            $html = $this->load->view('pma/report/index', $report_data, true);
        } else {
            $html = $this->load->view('pma/report/index_spanish', $report_data, true);
        }

        // echo $html;die;

        $this->load->library('snappy_pdf');

        // header('Content-Type: application/pdf');
        $document_name = $linkAddress . '_' . time() . '_' . $this->user['id'] . '.pdf';
        $dir_to_upload = 'uploads/sales-rep/pma';
        if (!is_dir(FCPATH . $dir_to_upload)) {
            mkdir(FCPATH . $dir_to_upload, 0777, true);
        }
        // if (!(is_writable(FCPATH.$dir_to_upload))) {
        chmod(FCPATH . $dir_to_upload, 0777);
        // }
        $dir_name = FCPATH . $dir_to_upload . '/';
        $dir_name = str_replace('\\', '/', $dir_name);
        // echo $dir_name.$document_name;die;
        $this->snappy_pdf->pdf->generateFromHtml($html, $dir_name . $document_name);

        $returnData = array();
        $returnData['pdfLink'] = $dir_to_upload . '/' . $document_name;
        $response = $this->order->uploadDocumentOnAwsS3($document_name, 'sales-rep/pma');
        if ($response) {
            //report_url
            $returnData['pdfLink'] = 'sales-rep/pma/' . $document_name;
            if (is_file($dir_name . $document_name)) {
                unlink($dir_name . $document_name);
            }
        }
        echo json_encode($returnData);exit;

    }

}
