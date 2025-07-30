<?php
(defined('BASEPATH')) or exit('No direct script access allowed');
class SalesSnapShot extends MX_Controller
{

    private $user;
    // private $sales_snap_shot_js_version = '01';
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
        $this->load->library('order/template');
        $this->load->library('order/salesDashboardTemplate');
        $this->load->model('order/home_model');
        $this->load->model('salesSnapShot_model');
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
        $data['salesReps'] = $this->salesSnapShot_model->getSalesRepData($condition, $this->user['id']);
        $data['report_total'] = array_sum(array_column($data['salesReps'], 'report_count'));
        $report_condition = array(
            'added_by' => $this->user['id'],
        );
        $data['reports_data'] = $this->salesSnapShot_model->getData($report_condition);
        // echo "<pre>";
        // print_r($data);die;
        // $this->template->addJS( base_url('assets/frontend/js/report.js?v='.$this->version));
        // $this->template->show("report", "list", $data);
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/report.js?v=' . $this->version));
        $this->salesdashboardtemplate->show("salesSnapShot", "list", $data);
    }

    public function importData()
    {
        $this->load->library('CSVReader');
        $valid = true;
        if (empty($this->input->post('sales_rep'))) {
            $valid = false;
            $this->session->set_flashdata('error', 'Please select Sales Representative');
        } else if (empty($this->input->post('area_name'))) {
            $valid = false;
            $this->session->set_flashdata('error', 'Please Enter Area Name');
        } else if (empty($this->input->post('month_option'))) {
            $valid = false;
            $this->session->set_flashdata('error', 'Please select month option');
        } else if (empty($_FILES['csvFile']['tmp_name'])) {
            $valid = false;
            $this->session->set_flashdata('error', 'Please select csv file');
        }

        if (!$valid) {
            $this->session->set_flashdata('_previous_data', $this->input->post());
            redirect('sales-snap-shot');
        }

        $csv_records = $this->csvreader->parse_csv($_FILES['csvFile']['tmp_name']); //path to csv file
        $valid_keys = ['APN / Parcel Number', 'Bedrooms', 'Baths', 'Building Size', 'Owner Occupied', 'Purchase Price', 'Purchase Date'];

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
                redirect('sales-snap-shot');
            }

            $main_record = array();
            $main_record['sales_rep'] = $this->input->post('sales_rep');
            $main_record['month_option'] = $this->input->post('month_option');
            $main_record['area_name'] = $this->input->post('area_name');
            $main_record['property_type'] = $this->input->post('property_type');
            $main_record['added_by'] = $this->user['id'];
            $last_id = $this->salesSnapShot_model->insert($main_record);

            $monthArr = array();
            for ($i = -(int) $main_record['month_option']; $i < 0; $i++) {
                $monthArr[] = date('m', strtotime("$i month"));
            }
            $records = array();
            $i = 0;
            $report_data = array();
            if ($last_id) {
                foreach ($csv_records as $csv_record) {
                    $time = strtotime($csv_record['Purchase Date']);
                    $month = date('m', $time);
                    if (in_array($month, $monthArr)) {
                        $records[$i]['building_size'] = $csv_record['Building Size'];
                        $records[$i]['bedrooms'] = $csv_record['Bedrooms'];
                        $records[$i]['baths'] = $csv_record['Baths'];
                        $records[$i]['purchase_price'] = $csv_record['Purchase Price'];
                        $records[$i]['owner_occupied'] = strtolower($csv_record['Owner Occupied']);
                        $records[$i]['purchase_date'] = $csv_record['Purchase Date'];
                        $i++;
                    }
                }

                $report_data['area_name'] = $this->input->post('area_name');
                $report_data['property_type'] = $this->input->post('property_type');
                $condition = array(
                    'is_sales_rep' => 1,
                    'status' => 1,
                    'id' => $this->input->post('sales_rep'),
                );
                $report_data['salesRep'] = $this->home_model->getSalesRepDetails($condition);
                $report_data['total_records'] = count($records);
                $report_data['avg_sales_price'] = (array_sum(array_column($records, 'purchase_price'))) / count($records);
                $report_data['avg_price_per_sq_ft'] = (array_sum(array_column($records, 'purchase_price'))) / (array_sum(array_column($records, 'building_size')));
                $report_data['avg_beds'] = (array_sum(array_column($records, 'bedrooms'))) / count($records);
                $report_data['avg_baths'] = (array_sum(array_column($records, 'baths'))) / count($records);

                $rentalArr = array_filter($records, function ($var) {
                    return ($var['owner_occupied'] == 'n');
                });

                $report_data['absentee'] = (100 * count($rentalArr)) / count($records);

                $monthly_data = array();
                $k = 0;
                for ($i = -(int) $main_record['month_option']; $i < 0; $i++) {
                    $month = date('m', strtotime("$i month"));
                    $month_records = array_filter($records, function ($var) use ($month) {
                        $date = new DateTime($var['purchase_date']);
                        return $date->format("m") == $month;
                    });
                    $monthly_data[$k]['month'] = date('F', strtotime("$i month")) . " - " . date('Y', strtotime("$i month"));
                    if (!empty($month_records)) {
                        $monthly_data[$k]['avg_sales_price'] = (array_sum(array_column($month_records, 'purchase_price'))) / count($month_records);
                        $monthly_data[$k]['avg_price_per_sq_ft'] = (array_sum(array_column($month_records, 'purchase_price'))) / (array_sum(array_column($month_records, 'building_size')));
                        if ($k == 0) {
                            $monthly_data[$k]['price_change'] = 0.00;
                        } else {
                            $monthly_data[$k]['price_change'] = (100 * ($monthly_data[$k]['avg_price_per_sq_ft'] - $monthly_data[$k - 1]['avg_price_per_sq_ft'])) / $monthly_data[$k - 1]['avg_price_per_sq_ft'];
                        }
                    } else {
                        $monthly_data[$k]['avg_sales_price'] = 0.00;
                        $monthly_data[$k]['avg_price_per_sq_ft'] = 0.00;
                        $monthly_data[$k]['price_change'] = 0.00;
                    }
                    $k++;
                }

                $report_data['monthly_data'] = array_reverse($monthly_data);

                if ($this->input->post('month_option') == '03') {
                    $html = $this->load->view('salesSnapShot/three_month_pdf', $report_data, true);
                } else if ($this->input->post('month_option') == '06') {
                    $html = $this->load->view('salesSnapShot/six_month_pdf', $report_data, true);
                } else if ($this->input->post('month_option') == '12') {
                    $html = $this->load->view('salesSnapShot/twelve_month_pdf', $report_data, true);
                }
                $this->load->library('snappy_pdf');

                $document_name = $report_data['area_name'] . '_' . time() . '_' . $last_id . '.pdf';
                if (!is_dir(FCPATH . 'uploads/sales-snap-shot')) {
                    mkdir(FCPATH . 'uploads/sales-snap-shot', 0777, true);
                }

                chmod(FCPATH . 'uploads/sales-snap-shot', 0777);

                $dir_name = FCPATH . 'uploads/sales-snap-shot/';
                $dir_name = str_replace('\\', '/', $dir_name);

                $this->snappy_pdf->pdf->setOption('page-size', 'Letter');
                //  $this->snappy_pdf->pdf->setOption('zoom', '1.05');
                $this->snappy_pdf->pdf->generateFromHtml($html, $dir_name . $document_name);
                $response = $this->order->uploadDocumentOnAwsS3($document_name, 'sales-snap-shot');
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
                    $this->salesSnapShot_model->update($update_data, $condition);
                }
                $this->session->set_flashdata('success', 'Sales Snap shot Created.');
            } else {
                $this->session->set_flashdata('error', 'Please try again!');
                $this->session->set_flashdata('_previous_data', $this->input->post());
            }
        }
        redirect('sales-snap-shot');
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
                            $msg = "Borr
                            ower File successfully uploaded";
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

    public function sendEmailToSalesRep()
    {
        $to = $this->input->post('email');
        $url = $this->input->post('url');
        $key = $this->input->post('key');
        if (empty($to) || empty($url) || empty($key)) {
            echo json_encode(['status' => 'error', 'message' => 'Details missing']);exit;
        }
        $file[] = $url;
        $from_name = 'Pacific Coast Title Company';
        $from_mail = env('FROM_EMAIL');
        $message = 'Please check attachment for Snap Shot document.';
        $subject = ($key === 'snapshot-email') ? 'Sales Snapshot Ready!' : 'Sales Activity Report Ready!';

        $this->load->helper('sendemail');
        $data = array(
            'link' => $url,
        );
        $cc[] = 'piyush-crest@yopmail.com';
        // $to = 'piyush-crest@yopmail.com';
        $template = ($key === 'snapshot-email') ? 'salesSnapShot/snapshot_email_template.php' : 'salesReport/activity_email_template.php';
        $message = $this->load->view($template, $data, true);
        $mail_result = send_email($from_mail, $from_name, $to, $subject, $message, $file, $cc, []);
        if ($mail_result) {
            echo json_encode(['status' => 'success', 'message' => 'Email sent!']);exit;
        }
        echo json_encode(['status' => 'error', 'message' => 'Email not sent, Try again later']);exit;
    }
}
