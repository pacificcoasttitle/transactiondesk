<?php
(defined('BASEPATH')) or exit('No direct script access allowed');
class SalesActivityReport extends MX_Controller
{

    private $user;
    // private $js_version = '01';
    private $version;
    public $monthArr = [
        1 => "January",
        2 => "February",
        3 => "March",
        4 => "April",
        5 => "May",
        6 => "June",
        7 => "July",
        8 => "August",
        9 => "September",
        10 => "October",
        11 => "November",
        12 => "December",
    ];
    public function __construct()
    {
        parent::__construct();
        $userdata = $this->session->userdata('user');
        if (empty($userdata) || !isset($userdata['is_master']) || $userdata['is_master'] != 1) {
            redirect('dashboard');
        }
        $this->version = strtotime(date('Y-m-d'));
        $this->user = $userdata;
        $this->load->library('order/template');
        $this->load->library('order/salesDashboardTemplate');
        $this->load->model('order/home_model');
        $this->load->model('salesReport_model');
        $this->load->library('order/order');
        $this->load->helper('common');
    }

    public function index()
    {
        $data['title'] = 'Sales Reports | Pacific Coast Title Company';
        $condition = array(
            'is_sales_rep' => 1,
            'status' => 1,
        );
        $data['salesReps'] = $this->salesReport_model->getSalesRepData($condition, $this->user['id']);
        $data['report_total'] = array_sum(array_column($data['salesReps'], 'report_count'));
        $report_condition = array(
            'added_by' => $this->user['id'],
        );
        $data['monthNameList'] = $this->monthArr;
        $data['reports_data'] = $this->salesReport_model->getData($report_condition);

        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/report.js?v=' . $this->version));
        $this->salesdashboardtemplate->show("salesReport", "list", $data);
    }

    public function importData()
    {
        $this->load->library('CSVReader');
        $valid = true;
        if (empty($this->input->post('sales_rep'))) {
            $valid = false;
            $this->session->set_flashdata('error', 'Please select Sales Representative');
        } else if (empty($this->input->post('month'))) {
            $valid = false;
            $this->session->set_flashdata('error', 'Please Select Month');
        } else if (empty($this->input->post('county'))) {
            $valid = false;
            $this->session->set_flashdata('error', 'Please Select County');
        } else if (empty($_FILES['csvFile']['tmp_name'])) {
            $valid = false;
            $this->session->set_flashdata('error', 'Please select csv file');
        }

        if (!$valid) {
            $this->session->set_flashdata('_previous_data', $this->input->post());
            redirect('sales-activity-report');
        }

        $csv_records = $this->csvreader->parse_csv($_FILES['csvFile']['tmp_name']); //path to csv file
        $valid_keys = ['Site City', 'Purchase Price', 'Property Type'];

        if (is_array($csv_records) && isset($csv_records[1])) {
            $first_reocrd = $csv_records[1];
            $keys_not_found = array();

            foreach ($valid_keys as $valid_key) {
                if (!isset($first_reocrd[$valid_key])) {
                    $keys_not_found[] = $valid_key;
                }
            }

            if (count($keys_not_found)) {
                $this->session->set_flashdata('error', 'Column not found : ' . implode(', ', $keys_not_found));
                $this->session->set_flashdata('_previous_data', $this->input->post());
                redirect('sales-activity-report');
            }

            $main_record = array();
            $main_record['sales_rep'] = $this->input->post('sales_rep');
            $main_record['month'] = $this->input->post('month');
            $main_record['added_by'] = $this->user['id'];
            $main_record['county'] = $this->input->post('county');
            $last_id = $this->salesReport_model->insert($main_record);
            // $last_id = 4;

            $records = array();
            $i = 0;
            $report_data = array();
            if ($last_id) {
                foreach ($csv_records as $csv_record) {
                    $records[$csv_record['Site City']][strtolower(trim($csv_record['Property Type']))][] = [
                        'property_type' => $csv_record['Property Type'],
                        'purchase_price' => $csv_record['Purchase Price'],
                    ];
                }

                foreach ($records as $key => $val) {
                    $avgRconValue = $rconCount = $avgSfrValue = $sfrCount = 0;
                    if (array_key_exists('rsfr', $val)) {
                        $allValues = array_column($val['rsfr'], 'purchase_price');
                        $avgSfrValue = array_sum($allValues) / count($allValues);
                        $sfrCount = count($allValues);
                    }

                    if (array_key_exists('rcon', $val)) {
                        $allValues = array_column($val['rcon'], 'purchase_price');
                        $rconCount = count($allValues);
                        $avgRconValue = array_sum($allValues) / count($allValues);
                    }

                    $records[$key]['SFR'] = ['avgSalePrice' => round($avgSfrValue, 2), 'key' => 'SFR', 'count' => $sfrCount];
                    $records[$key]['Condos'] = ['avgSalePrice' => round($avgRconValue, 2), 'key' => 'Condor', 'count' => $rconCount];
                    unset($records[$key]['rcon']);
                    unset($records[$key]['rsfr']);
                }
                ksort($records);
                $report_data['records'] = array_chunk($records, 30, true);
                $monthNumber = $this->input->post('month');
                $report_data['monthNumber'] = $monthNumber;
                $report_data['monthName'] = $this->monthArr[$monthNumber];
                $report_data['country'] = $countryName = $this->input->post('county');
                $condition = array(
                    'is_sales_rep' => 1,
                    'status' => 1,
                    'id' => $this->input->post('sales_rep'),
                );
                $report_data['salesRep'] = $this->home_model->getSalesRepDetails($condition);
                $html = $this->load->view('salesReport/sales_activity_report', $report_data, true);
                // print_r($html);die;
                $this->load->library('snappy_pdf');
                $countyCode = COUNTRY_CODE;
                $document_name = $countyCode[strtolower($countryName)] . '_' . $report_data['salesRep']['first_name'] . '_' . $this->monthArr[$monthNumber] . '_' . date('Y') . '_' . $last_id . '.pdf';
                if (!is_dir(FCPATH . 'uploads/sales-activity')) {
                    mkdir(FCPATH . 'uploads/sales-activity', 0777, true);
                }

                chmod(FCPATH . 'uploads/sales-activity', 0777);

                $dir_name = FCPATH . 'uploads/sales-activity/';
                $dir_name = str_replace('\\', '/', $dir_name);

                $this->snappy_pdf->pdf->setOption('page-size', 'Letter');
                $this->snappy_pdf->pdf->setOption('zoom', '1.24');
                $this->snappy_pdf->pdf->generateFromHtml($html, $dir_name . $document_name);
                $response = $this->order->uploadDocumentOnAwsS3($document_name, 'sales-activity');
                if ($response) {
                    if (is_file($dir_name . $document_name)) {
                        unlink($dir_name . $document_name);
                    }
                    $update_data = array(
                        'report_url' => $document_name,
                    );
                    $condition = array(
                        'id' => $last_id,
                    );
                    $this->salesReport_model->update($update_data, $condition);
                }
                $this->session->set_flashdata('success', 'Sales Activity Recorded.');
            } else {
                $this->session->set_flashdata('error', 'Please try again!');
                $this->session->set_flashdata('_previous_data', $this->input->post());
            }
        }
        redirect('sales-activity-report');
    }
}
