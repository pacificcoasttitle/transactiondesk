<?php
(defined('BASEPATH')) or exit('No direct script access allowed');

class Label extends MX_Controller
{
    private $user;
    // private $label_js_version = '01';
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
        $this->load->model('label_model');
        $this->load->library('order/order');
    }

    public function index()
    {
        $data['title'] = 'Labels | Pacific Coast Title Company';
        $condition = array(
            'is_sales_rep' => 1,
            'status' => 1,
        );
        $data['salesReps'] = $this->label_model->getSalesRepData($condition, $this->user['id']);
        $data['report_total'] = array_sum(array_column($data['salesReps'], 'report_count'));
        $lableCondition = array(
            'added_by' => $this->user['id'],
        );
        $data['labels_data'] = $this->label_model->getData($lableCondition);
        // $this->template->addJS( base_url('assets/frontend/js/label.js?v=lable_'.$this->version));
        // $this->template->show("label", "list", $data);
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/label.js?v=' . $this->version));
        $this->salesdashboardtemplate->show("label", "list", $data);
    }

    public function importData()
    {

        $valid = true;

        if (empty($this->input->post('sales_rep'))) {
            $valid = false;
            $this->session->set_flashdata('error', 'Please select Sales Representative');
        } else if (empty($this->input->post('file_name'))) {
            $valid = false;
            $this->session->set_flashdata('error', 'Please Enter File Name');
        } else if (empty($_FILES['csvFile']['tmp_name'])) {
            $valid = false;
            $this->session->set_flashdata('error', 'Please select csv file');
        }

        $document_name = '';

        if (!empty($_FILES['csvFile']['name'])) {
            if (!is_dir(FCPATH . 'uploads/label')) {
                mkdir(FCPATH . 'uploads/label', 0777, true);
            }
            chmod(FCPATH . 'uploads/label', 0777);
            $config['upload_path'] = './uploads/label/';
            $config['allowed_types'] = 'csv';
            $config['max_size'] = 18000;
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('csvFile')) {
                $error = $this->upload->display_errors();
                $valid = false;
                $this->session->set_flashdata('error', $error);
            } else {
                $data = $this->upload->data();
                $fileName = str_replace(' ', '-', $_FILES['csvFile']['name']);
                $fileName = str_replace('CSV', 'csv', $_FILES['csvFile']['name']);
                $fileName = preg_replace('/[^A-Za-z0-9.\-]/', '', $fileName);
                $fileName = preg_replace('/-+/', '-', $fileName);
                $document_name = date('YmdHis') . "_" . $fileName;
                rename(FCPATH . "/uploads/label/" . $data['file_name'], FCPATH . "/uploads/label/" . $document_name);
                $this->load->library('order/order');
                $this->order->uploadDocumentOnAwsS3($document_name, 'label');
            }
        }

        if (!$valid) {
            $this->session->set_flashdata('_previous_data', $this->input->post());
            redirect('labels');
        }
        $this->load->library('CSVReader');
        $csv_records = $this->csvreader->parse_csv($_FILES['csvFile']['tmp_name']);
        if (is_array($csv_records) && isset($csv_records[1])) {
            $first_reocrd = $csv_records[1];
            $columns = array();
            foreach ($first_reocrd as $key => $value) {
                $columns[] = $key;
            }
            $main_record = array();
            $main_record['sales_rep_id'] = $this->input->post('sales_rep');
            $main_record['file_name'] = $this->input->post('file_name');
            $main_record['original_file_name'] = $document_name;
            $main_record['added_by'] = $this->user['id'];
            $main_record['file_columns'] = implode(",", $columns);

            $last_id = $this->label_model->insert($main_record);

            if ($last_id) {
                $this->session->set_flashdata('success', 'Csv data imported successfully.');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong. Please try again later!');
                $this->session->set_flashdata('_previous_data', $this->input->post());
            }
        }
        redirect('labels');
    }

    public function downloadPdf()
    {
        $data = array();
        $line_1_columns = $this->input->post('line_1_columns');
        $line_2_columns = $this->input->post('line_2_columns');
        $line_3_columns = $this->input->post('line_3_columns');
        $or_current_resident = $this->input->post('or_current_resident');
        $file_name = $this->input->post('file_name');
        $url = env('AWS_PATH') . 'label/' . $file_name;
        $fileContents = file_get_contents($url);
        file_put_contents('./uploads/label/' . $file_name, $fileContents);
        $this->load->library('CSVReader');
        $csv_records = $this->csvreader->parse_csv('./uploads/label/' . $file_name);
        $i = 0;
        $data = array();

        if (is_array($csv_records)) {
            foreach ($csv_records as $csv_record) {
                if ($line_1_columns == '1') {
                    $data['pdfInfos'][$i]['line_1'] = $csv_record[$this->input->post('line_1_1')];
                } else if ($line_1_columns == '2') {
                    $data['pdfInfos'][$i]['line_1'] = $csv_record[$this->input->post('line_1_1')] . ", " . $csv_record[$this->input->post('line_1_2')];
                } else if ($line_1_columns == '3') {
                    $data['pdfInfos'][$i]['line_1'] = $csv_record[$this->input->post('line_1_1')] . ", " . $csv_record[$this->input->post('line_1_2')] . ", " . $csv_record[$this->input->post('line_1_3')];
                }
                if ($line_2_columns == '1') {
                    $data['pdfInfos'][$i]['line_2'] = $csv_record[$this->input->post('line_2_1')];
                } else if ($line_2_columns == '2') {
                    $data['pdfInfos'][$i]['line_2'] = $csv_record[$this->input->post('line_2_1')] . ", " . $csv_record[$this->input->post('line_2_2')];
                } else if ($line_2_columns == '3') {
                    $data['pdfInfos'][$i]['line_2'] = $csv_record[$this->input->post('line_2_1')] . ", " . $csv_record[$this->input->post('line_2_2')] . ", " . $csv_record[$this->input->post('line_2_3')];
                }
                if ($line_3_columns == '1') {
                    $data['pdfInfos'][$i]['line_3'] = $csv_record[$this->input->post('line_3_1')];
                } else if ($line_3_columns == '2') {
                    $data['pdfInfos'][$i]['line_3'] = $csv_record[$this->input->post('line_3_1')] . ", " . $csv_record[$this->input->post('line_3_2')];
                } else if ($line_3_columns == '3') {
                    $data['pdfInfos'][$i]['line_3'] = $csv_record[$this->input->post('line_3_1')] . ", " . $csv_record[$this->input->post('line_3_2')] . ", " . $csv_record[$this->input->post('line_3_3')];
                }
                $i++;
            }
            $data['or_current_resident'] = $or_current_resident;
        }

        $this->load->view('label/pdf');
        $this->load->library('m_pdf');
        $html = $this->load->view('label/pdf', $data, true);
        $stylesheet = file_get_contents('assets/frontend/css/label/style.css');
        $customCss = '@media print { @page { size: auto; } }';
        $combinedCss = $stylesheet . $customCss;
        $this->m_pdf->pdf->WriteHTML($combinedCss, 1);
        $this->m_pdf->pdf->WriteHTML($html, 2);
        $this->load->model('order/document');

        if (!is_dir(FCPATH . 'uploads/label')) {
            mkdir(FCPATH . 'uploads/label', 0777, true);
        }
        chmod(FCPATH . 'uploads/label', 0777);
        $document_name = str_replace('csv', 'pdf', $file_name);
        $pdfFilePath = './uploads/label/' . $document_name;
        $this->m_pdf->pdf->Output($pdfFilePath, 'F');
        $contents = file_get_contents($pdfFilePath);
        $binaryData = base64_encode($contents);
        $data = array('status' => 'success', 'data' => $binaryData, 'file_name' => $document_name);
        $filepath = "uploads/label/" . $document_name;
        chmod($filepath, 0644);
        gc_collect_cycles();
        unlink($filepath);
        $orgFilepath = "uploads/label/" . $file_name;
        chmod($orgFilepath, 0644);
        gc_collect_cycles();
        unlink($orgFilepath);
        echo json_encode($data);exit;
    }
}
