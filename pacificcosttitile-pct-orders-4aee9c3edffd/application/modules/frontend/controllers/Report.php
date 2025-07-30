<?php
(defined('BASEPATH')) or exit('No direct script access allowed');
class Report extends MX_Controller
{

    private $user;
    private $sorting_fields;
    private $report_js_version = '01';
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
        $this->sorting_fields = [
            'carrier_route' => 'Route',
            'avg_price' => 'Avg. $',
            'total_sales' => '#of Sales',
            'NOO_ratio' => 'NOO %',
            'avg_yr_owned' => 'Avg. Y.O.',
            'total_units' => '# of Units',
            'turnover_rate' => 'T.O.%',
        ];
        $this->load->library('order/template');
        $this->load->library('order/salesDashboardTemplate');
        $this->load->model('order/home_model');
        $this->load->model('report_model');
        $this->load->library('order/order');
        $this->load->helper('common');
    }

    public function index()
    {
        $data['title'] = 'Reports | Pacific Coast Title Company';
        $condition = array(
            'is_sales_rep' => 1,
            'status' => 1,
        );
        $data['salesReps'] = $this->report_model->getSalesRepData($condition, $this->user['id']);
        $data['report_total'] = array_sum(array_column($data['salesReps'], 'report_count'));
        $report_condition = array(
            'added_by' => $this->user['id'],
        );
        $data['reports_data'] = $this->report_model->getData($report_condition);
        $data['sorting_fields'] = $this->sorting_fields;
        // $this->template->addJS( base_url('assets/frontend/js/report.js?v='.$this->version));
        // $this->template->show("report", "list", $data);
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/report.js?v=' . $this->version));
        $this->salesdashboardtemplate->show("report", "list", $data);
    }

    public function importData()
    {

        $this->load->library('CSVReader');

        //check Errors
        $valid = true;
        if (empty($this->input->post('sales_rep'))) {
            $valid = false;
            $this->session->set_flashdata('error', 'Please select Sales Representative');
        } elseif (empty($this->input->post('area_name'))) {
            $valid = false;
            $this->session->set_flashdata('error', 'Please Enter Area Name');
        } elseif (empty($this->input->post('sort_by'))) {
            $valid = false;
            $this->session->set_flashdata('error', 'Please select Sorting Order');
        } elseif (empty($_FILES['csvFile']['tmp_name'])) {
            $valid = false;
            $this->session->set_flashdata('error', 'Please select csv file');
        }
        if (!$valid) {
            $this->session->set_flashdata('_previous_data', $this->input->post());
            redirect('reports');

        }
        // var_dump($_FILES);

        $csv_records = $this->csvreader->parse_csv($_FILES['csvFile']['tmp_name']); //path to csv file
        $valid_keys = ['carrier_route', 'avg_price', 'turnover_rate', 'total_sales', 'NOO_ratio', 'avg_yr_owned', 'total_units', 'sa_site_zip', 'sa_site_city'];

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
                redirect('reports');

            }

            $zip_code = $csv_records[1]['sa_site_zip'];

            $main_record = array();
            $main_record['sales_rep'] = $this->input->post('sales_rep');
            $main_record['sort_by'] = $this->input->post('sort_by');
            $main_record['area_name'] = $this->input->post('area_name');
            $main_record['added_by'] = $this->user['id'];
            $main_record['zip_code'] = $zip_code;

            $last_id = $this->report_model->insert($main_record);

            if ($last_id) {

                foreach ($csv_records as $csv_record) {
                    // var_dump($csv_record);
                    $child_record = array();
                    $child_record['report_id'] = $last_id;
                    $child_record['carrier_route'] = $csv_record['carrier_route'];
                    $child_record['avg_price'] = $csv_record['avg_price'];
                    $child_record['turnover_rate'] = $csv_record['turnover_rate'];
                    $child_record['total_sales'] = $csv_record['total_sales'];
                    $child_record['NOO_ratio'] = $csv_record['NOO_ratio'];
                    $child_record['avg_yr_owned'] = $csv_record['avg_yr_owned'];
                    $child_record['total_units'] = $csv_record['total_units'];
                    $child_record['sa_site_zip'] = $csv_record['sa_site_zip'];
                    $child_record['sa_site_city'] = $csv_record['sa_site_city'];
                    $this->report_model->insert($child_record, 'pct_sales_rep_report_records');
                }
                //Create pdf
                $report_data = array();
                $condition = array(
                    'report_id' => $last_id,
                );
                $order_by = $this->input->post('sort_by');
                $limit = 10;
                $records = $this->report_model->getReportData($condition, $order_by, $limit);

                $this->report_model->delete_records($condition, 'pct_sales_rep_report_records');

                $report_data['records'] = $records;

                $condition = array(
                    'is_sales_rep' => 1,
                    'status' => 1,
                    'id' => $this->input->post('sales_rep'),
                );
                $report_data['salesRep'] = $this->home_model->getSalesRepDetails($condition);
                $report_data['area_name'] = $this->input->post('area_name');
                $report_data['sort_by'] = $this->input->post('sort_by');
                $report_data['sorting_fields'] = $this->sorting_fields;

                $box_columns = ['turnover_rate', 'NOO_ratio', 'avg_yr_owned', 'total_units', 'total_sales', 'avg_price'];
                foreach ($records as $key => $record) {
                    foreach ($box_columns as $box_column) {
                        if (!isset($box_data[$box_column])) {
                            $box_data[$box_column]['value'] = $record["$box_column"];
                            $box_data[$box_column]['route'] = separateZipRoute($record["carrier_route"], $record["sa_site_zip"]);
                        }

                        if ($record["$box_column"] > $box_data[$box_column]['value']) {
                            $box_data[$box_column]['value'] = $record["$box_column"];
                            $box_data[$box_column]['route'] = separateZipRoute($record["carrier_route"], $record["sa_site_zip"]);
                        }
                    }
                }
                $report_data['box_data'] = $box_data;

                $html = $this->load->view('report/report_pdf', $report_data, true);

                // echo $html;die;

                $this->load->library('snappy_pdf');

                // header('Content-Type: application/pdf');
                $document_name = time() . '_' . $last_id . '.pdf';
                if (!is_dir(FCPATH . 'uploads/sales-rep/pdf')) {
                    mkdir(FCPATH . 'uploads/sales-rep/pdf', 0777, true);
                }
                // if (!(is_writable(FCPATH.'uploads/sales-rep/pdf'))) {
                chmod(FCPATH . 'uploads/sales-rep/pdf', 0777);
                // }
                $dir_name = FCPATH . 'uploads/sales-rep/pdf/';
                $dir_name = str_replace('\\', '/', $dir_name);
                // echo $dir_name.$document_name;die;
                $this->snappy_pdf->pdf->generateFromHtml($html, $dir_name . $document_name);
                // die;
                $response = $this->order->uploadDocumentOnAwsS3($document_name, 'sales-rep/pdf');
                if ($response) {
                    //report_url
                    if (is_file($dir_name . $document_name)) {
                        unlink($dir_name . $document_name);
                    }
                    $update_data = array(
                        'report_url' => $document_name,
                    );
                    $condition = array(
                        'id' => $last_id,
                    );
                    $this->report_model->update($update_data, $condition);
                }
                // die;

                $this->session->set_flashdata('success', 'Report Created.');
            } else {
                $this->session->set_flashdata('error', 'Please try again!');
                $this->session->set_flashdata('_previous_data', $this->input->post());
            }
        }
        redirect('reports');

    }

    public function sales_rep($id = 0)
    {
        $data['title'] = 'Sales Rep | Pacific Coast Title Company';
        if ($id) {
            if ($this->input->server('REQUEST_METHOD') === 'POST') {
                $this->load->library('form_validation');
                $this->form_validation->set_rules('first_name', 'Sales Rep. First Name', 'required', array('required' => 'Please Enter Sales Rep. First Name'));
                $this->form_validation->set_rules('last_name', 'Sales Rep. Last Name', 'required', array('required' => 'Please Enter Sales Rep. Last Name'));
                $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email', array('required' => 'Please Enter Email', 'valid_email' => 'Please enter valid Email'));
                $this->form_validation->set_rules('telephone_no', 'Phone Number', 'required', array('required' => 'Please Enter Phone Number'));

                $config['upload_path'] = 'uploads/sales-rep/';
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['max_size'] = 12000;

                if ($this->form_validation->run() == true) {
                    $fileuri = '';
                    $status = "success";

                    $update_data = array();
                    $update_data['first_name'] = $this->input->post('first_name');
                    $update_data['last_name'] = $this->input->post('last_name');
                    $update_data['title'] = $this->input->post('title');
                    $update_data['email_address'] = $this->input->post('email_address');
                    $update_data['telephone_no'] = $this->input->post('telephone_no');
                    if (is_uploaded_file($_FILES['sales_rep_report_image']['tmp_name'])) {
                        if (!is_dir('uploads/sales-rep')) {
                            mkdir('./uploads/sales-rep', 0777, true);
                        }

                        $new_name = 'sales_rep_report_' . time() . rand(10, 1000);
                        $config['file_name'] = $new_name;
                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('sales_rep_report_image')) {
                            $status = 'error';
                            $msg = $this->upload->display_errors();
                            // echo $msg;die;
                        } else {
                            $data = $this->upload->data();
                            // var_dump($data);die;
                            $status = "success";
                            $msg = "Borrower File successfully uploaded";
                            $document_name = 'sales_rep_report_' . time() . rand(10, 1000) . '.' . $data['image_type'];
                            rename('./uploads/sales-rep/' . $data['file_name'], './uploads/sales-rep/' . $document_name);
                            // die;
                            $this->order->uploadDocumentOnAwsS3($document_name, 'sales-rep');
                            // die("Done");
                            $fileuri = 'sales-rep/' . $document_name;
                            $update_data['sales_rep_report_image'] = $fileuri;

                        }
                    }

                    $condition = array('id' => $id);

                    $this->home_model->update($update_data, $condition);

                    $this->session->set_flashdata('success', 'Record updated');

                    redirect('reports/sales_rep/' . $id);
                }

            }
            $condition = array(
                'is_sales_rep' => 1,
                'status' => 1,
                'id' => $id,
            );
            $data['salesRep'] = $this->home_model->getSalesRepDetails($condition);

            $this->salesdashboardtemplate->show("report", "sales_rep_edit", $data);
            // $this->template->show("report", "sales_rep_edit", $data);
        } else {

            $condition = array(
                'is_sales_rep' => 1,
                'status' => 1,
            );
            $data['salesReps'] = $this->report_model->getSalesRepData($condition);
            $this->salesdashboardtemplate->show("report", "sales_rep", $data);
            // $this->template->show("report", "sales_rep", $data);
        }
    }

    public function check_pdf($value = '')
    {
        $data = array();
        $condition = array(
            'report_id' => 3,
        );
        $order_by = 'avg_price';
        $limit = 10;
        $records = $this->report_model->getReportData($condition, $order_by, $limit);

        $data['records'] = $records;

        $condition = array(
            'is_sales_rep' => 1,
            'status' => 1,
            'id' => 11971,
        );
        $data['salesRep'] = $this->home_model->getSalesRepDetails($condition);

        $html = $this->load->view('report/report_pdf', $data, true);
        // $html = '<h1>Test</h1>';
        echo $html;
        $this->load->library('snappy_pdf');

        // header('Content-Type: application/pdf');

        // echo $this->snappy_pdf->pdf->getOutputFromHtml($html);

    }
}
