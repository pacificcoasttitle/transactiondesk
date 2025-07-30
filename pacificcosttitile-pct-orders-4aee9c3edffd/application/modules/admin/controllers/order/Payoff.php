<?php

(defined('BASEPATH')) or exit('No direct script access allowed');

class PayOff extends MX_Controller
{
    // private $payoff_js_version = '01';
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
        $this->load->library('order/adminTemplate');
        $this->load->model('order/home_model');
        $this->load->model('order/order_model');
        $this->load->model('order/transactee_model');
        $this->load->library('order/common');
        $this->load->library('order/order');
        $this->common->is_admin();
    }

    public function transactees_list()
    {
        $data['errors'] = array();
        $data['success'] = array();
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }

        $userdata = $this->session->userdata('admin');
        $name = isset($userdata['name']) && !empty($userdata['name']) ? $userdata['name'] : '';
        $data['name'] = $name;
        $data['user_email'] = $userdata['email'];
        $data['title'] = 'Payoff Admin Dashboard | Pacific Coast Title Company';
        $data['pageTitle'] = 'Pacific Coast Title - Approved Wire List';

        $con = array(
            'where' => array(
                'is_payoff_user' => 1,
                'status' => 1,
            ),
        );
        $data['payoff_user'] = $this->home_model->get_rows($con);
        // echo "<pre>";
        // print_r($data);die;
        // $this->template->addJS( base_url('assets/frontend/js/order/payoff.js?v='.$this->version));
        // $this->template->show("order/pay_off", "pay_off_dashboard", $data);
        // $this->admintemplate->addJS(base_url('assets/frontend/js/order/payoff.js?v=' . $this->version));
        // $this->admintemplate->addCSS(base_url('assets/backend/css/transactee.css?v=' . $this->version));
        $this->admintemplate->addJS(base_url('assets/backend/js/transactee.js'));
        $this->admintemplate->show("order/transactee", "transactee_list", $data);
    }

    public function get_pay_off_orders()
    {
        $params = array();
        $data = array();
        $params['is_pay_off'] = 1;

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
                $file_id = $order['file_id'];
                $nestedData[] = date("m/d/Y", strtotime($order['created_at']));
                $nestedData[] = $order['file_number'];
                $nestedData[] = $order['first_name'] . " " . $order['last_name'];
                $nestedData[] = ucfirst($order['resware_status']);
                $class = $order['is_payoff_generated'] == 1 ? 'button-color-green' : 'button-color';
                $text = $order['is_payoff_generated'] == 1 ? 'Recreate Payoff' : 'Create Payoff';
                $nestedData[] = "<div style='display: flex; justify-content: space-around;' ><a href='javascript:void(0);' onclick='downloadPayOffDocument($file_id);' class='btn btn-secondary btn-icon-split'>
                                    <span class='icon text-white-50'>
                                        <i class='fas fa-eye'></i>
                                    </span>
                                    <span class='text'>View Package</span>
                                </a>
                                <a href='javascript:void(0);' onclick='updatePayOffAction($file_id);' class='btn btn-success btn-icon-split'>
                                    <span class='icon text-white-50'>
                                        <i class='fas fa-dollar-sign'></i>
                                    </span>
                                    <span class='text'>Disburse funds</span>
                                </a>
                                <a href='" . base_url() . "create-payoff/$file_id' class='btn btn-primary btn-icon-split'>
                                    <span class='icon text-white-50'>
                                        <i class='fas fa-money-check'></i>
                                    </span>
                                    <span class='text'>$text</span>
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

    public function get_transactees_list()
    {
        $params = array();
        $data = array();
        $params['is_pay_off'] = 1;

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 2;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['user_id'] = isset($_POST['user_id']) && !empty($_POST['user_id']) ? $_POST['user_id'] : '';
            $pageno = ($params['start'] / $params['length']) + 1;
            $order_lists = $this->transactee_model->get_transactees($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $order_lists = $this->transactee_model->get_transactees($params);
        }
        // echo "<pre>";
        // print_r($order_lists);die;
        // echo "test";die;
        if (isset($order_lists['data']) && !empty($order_lists['data'])) {
            $i = $params['start'] + 1;
            foreach ($order_lists['data'] as $order) {
                $createdBy = $order['a_first_name'] . ' ' . $order['a_last_name'];
                if ($order['created_by'] == 'user') {
                    $createdBy = $order['c_first_name'] . ' ' . $order['c_last_name'];
                }

                $nestedData = array();
                $nestedData[] = $i;
                $id = $order['id'];
                $notes = $order['notes'] ? $order['notes'] : null;
                $admin_notes = $order['admin_notes'] ? $order['admin_notes'] : '';
                // $admin_notes = $order['admin_notes'] ?? null;
                $nestedData[] = $order['transctee_name'];
                $nestedData[] = $order['file_number'];
                $nestedData[] = $order['account_number'];
                $nestedData[] = $order['aba'];
                $nestedData[] = $order['bank_name'];
                $nestedData[] = $createdBy . ' ' . date("m/d/Y", strtotime($order['submitted']));
                $nestedData[] = ($order['is_approved']) ? $order['first_name'] . ' ' . $order['last_name'] . ' ' . $this->common->convertTimezone($order['approved_date'], 'm/d/Y @ g:i a', 'America/Los_Angeles') : '';
                // $nestedData[] = $order['first_name'] . ' ' . $order['last_name'];
                $isApproved = $order['is_approved'];
                if ($isApproved == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                $nestedData[] = "<input $checked  onclick='activateTransactee();' style='height:30px;width:20px;' type='checkbox' id='$id' name='$id'>";

                // $editOrderUrl = 'test';
                $editOrderUrl = base_url() . 'order/admin/edit-transactee-details/' . $id;
                $deleteOrderUrl = base_url() . 'order/admin/delete-transactee-details/' . $id;

                $action = "<div class='dropdown'>
                <a class='btn dropdown-toggle click-action-type' type='button' data-toggle='dropdown' href='#'>Click Action Type
                    <span class='caret'></span>
                </a>
                <ul class='dropdown-menu' style='width:210px !important;max-width:none !important;'>
                    <li>
                        <a href='javascript:void(0)' onclick='editTransactee($id, false);' title ='View Transactee Detail'>
                            <button class='btn btn-grad-2a button-color' type='button'>
                                <i class='fas fa-eye' aria-hidden='true' style='margin-right:5px;'></i>
                                View
                            </button>
                        </a>
                    </li>
                    <li>
                        <a href='$editOrderUrl' title ='Edit Transactee Detail'>
                            <button class='btn btn-grad-2a button-color' type='button'>
                                <i class='fas fa-edit' aria-hidden='true' style='margin-right:5px;'></i>
                                Edit
                            </button>
                        </a>
                    </li>
                    <li>
                        <a href='#' onclick='deleteTransactee($id);' title ='Delete Transactee'>
                            <button class='btn btn-grad-2a button-color' type='button'>
                                <i class='fas fa-fw fa-trash' aria-hidden='true' style='margin-right:5px;'></i>
                                Delete
                            </button>
                        </a>
                    </li>
                    <li>
                        <a href='#' onclick='getDocuments($id);' title ='Manage Document'>
                            <button class='btn btn-grad-2a button-color' type='button'>
                                <i class='fas fa-file' aria-hidden='true' style='margin-right:5px;'></i>
                                Manage Document
                            </button>
                        </a>
                    </li></ul></div>";

                // <i class="fa-solid fa-up-right-from-square"></i>
                $nestedData[] = $action;
                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($order_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($order_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function update_transactee_status()
    {
        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $userdata = $this->session->userdata('admin');
        // echo "<pre>";
        // print_r($userdata);die;
        $name = isset($userdata['name']) && !empty($userdata['name']) ? $userdata['name'] : '';

        $data['is_approved'] = $status;
        $data['updated_at'] = date("Y-m-d H:i:s");
        $data['approved_by'] = $userdata['id'];
        $data['approved_date'] = date("Y-m-d H:i:s");

        // $user = $this->home_model->get_user($condition);
        $condition = array(
            'id' => $id,
        );
        /** Save user Activity */
        $activity = 'User ' . $userdata['email'] . 'status updated to :- ' . $status;
        $this->order->logAdminActivity($activity);
        /** End Save user activity */
        $res = $this->db->update('pct_vendors', $data, $condition);
        // print_r($res);die;
        $data = array('status' => 'success', 'msg' => 'Transactee status updated successfully.');
        echo json_encode($data);
    }

    public function uploadTransacteeDocuments()
    {
        $transacteeId = $_POST['transactee_id'];

        $this->load->model('order/transactee_model');
        $config['upload_path'] = './uploads/transactee-upload-doc/';
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 12000;
        $this->load->library('upload', $config);

        if (!is_dir('/uploads/transactee-upload-doc')) {
            mkdir('./uploads/transactee-upload-doc', 0777, true);
        }

        if (!empty($_FILES['transactee_documents']['name'])) {
            if (!$this->upload->do_upload('transactee_documents')) {
                $errorMsg = $this->upload->display_errors();
                $data = array(
                    "error" => $errorMsg,
                    "success" => '',
                );
                echo json_encode($data);exit;
            } else {

                $data = $this->upload->data();
                $contents = file_get_contents($data['full_path']);
                $document_name = date('YmdHis') . "_" . $data['file_name'];
                rename(FCPATH . "/uploads/transactee-upload-doc/" . $data['file_name'], FCPATH . "/uploads/transactee-upload-doc/" . $document_name);

                $this->order->uploadDocumentOnAwsS3($document_name, 'transactee-upload-doc');

                $this->load->model('order/transactee_model');

                $getTransacteeDetails = $this->transactee_model->getDetails($transacteeId);
                $originalDocumentNameList = !empty($getTransacteeDetails['document_original_names']) ? json_decode($getTransacteeDetails['document_original_names'], true) : [];
                $documentNameList = !empty($getTransacteeDetails['document_names']) ? json_decode($getTransacteeDetails['document_names'], true) : [];
                array_push($originalDocumentNameList, $data['file_name']);
                array_push($documentNameList, $document_name);
                $condition['id'] = $transacteeId;
                $transacteeData['document_original_names'] = json_encode($originalDocumentNameList);
                $transacteeData['document_names'] = json_encode($documentNameList);
                // print_r($transacteeData);die;
                $res = $this->transactee_model->update($transacteeData, $condition);
                // $documentId = $this->document->insert($documentData);

                if ($res) {
                    $success = "Document uploaded successfully";
                } else {
                    $errors = " Something went wrong.Please try again";
                }

            }
        } else {
            $errors = "Something went wrong.Please try again";
        }

        $data = array(
            "error" => $errors,
            "success" => $success,
        );
        echo json_encode($data);exit;
    }

    public function edit_transactee_details($id)
    {
        $data = array();
        $data['title'] = 'PCT Order: Edit Admin Transactee';
        $data['pageTitle'] = 'Transactee';
        $id = $this->uri->segment('4');

        if (isset($id) && !empty($id)) {
            if (isset($_POST) && !empty($_POST)) {
                // print_r($_POST);die;
                $this->form_validation->set_rules('transctee_name', 'Transctee Name', 'required', array('required' => 'Please Enter Transctee Name'));
                $this->form_validation->set_rules('file_number', 'File Number', 'required', array('required' => 'Please Enter File Number'));
                $this->form_validation->set_rules('account_number', 'Account Number', 'required', array('required' => 'Please Enter Account Number'));
                // $this->form_validation->set_rules('account_number', 'Account Number', 'required|callback_check_unique_account_number[' . $id . ']', array('required' => 'Please Enter Account Number'));
                // $this->form_validation->set_rules(
                //     'account_number',
                //     'Account Number',
                //     'required|callback_check_unique_account_number[' . $id . ']',
                //     array('required' => 'Please Enter Account Number', 'check_unique_account_number' => 'The Account Number is already in use.')
                // );
                $this->form_validation->set_rules('aba', 'ABA/Routing', 'required', array('required' => 'Please Enter ABA/Routing'));
                $this->form_validation->set_rules('bank_name', 'Bank Name', 'required', array('required' => 'Please Enter Bank Name'));
                // $this->form_validation->set_rules('notes', 'Notes', 'required', array('required' => 'Please Enter Notes'));

                if ($this->form_validation->run() == true) {
                    $accountNumber = $_POST['account_number'];
                    $bool = $this->check_unique_account_number($accountNumber, $id);
                    if (!$bool) {
                        $data['account_number_error_msg'] = 'The Account Number is already in use';
                    } else {
                        $transacteeData = array(
                            'transctee_name' => $_POST['transctee_name'],
                            'file_number' => $_POST['file_number'],
                            'account_number' => $_POST['account_number'],
                            'aba' => $_POST['aba'],
                            'bank_name' => $_POST['bank_name'],
                            'admin_notes' => $_POST['admin_notes'],
                            'updated_at' => date("Y-m-d H:i:s"),
                            // 'approved_date' => date('Y-m-d H:i:s'),
                            // 'approved_by' => $userdata['id'],
                            // 'is_approved' => 1,
                        );
                        $condition = array('id' => $id);
                        $update = $this->transactee_model->update($transacteeData, $condition);
                        /** Save user Activity */
                        $activity = 'transactee updated :- ' . $_POST['transctee_name'];
                        $this->common->logAdminActivity($activity);
                        /** End save user activity */
                        if ($update) {
                            $data['success_msg'] = 'Transactee updated successfully.';
                        } else {
                            $data['error_msg'] = 'Error occurred while updating Title Officer';
                        }
                    }
                } else {
                    $data['transctee_name_error_msg'] = form_error('transctee_name');
                    $data['file_number_error_msg'] = form_error('file_number');
                    $data['account_number_error_msg'] = form_error('account_number');
                    $data['aba_error_msg'] = form_error('aba');
                    $data['bank_name_error_msg'] = form_error('bank_name');
                    // $data['notes_error_msg'] = form_error('notes');
                    // print_r($data);die;
                }
            }

            $transactee_info = $this->transactee_model->getDetails($id);
        } else {
            redirect('order/admin/transactees-list');
        }
        $data['transactee_info'] = $transactee_info;
        // echo "<pre>";
        // print_r($data);die;
        $this->admintemplate->show("order/transactee", "edit_transactee", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/title/edit_title_officer', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function delete_transactee_details()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        if ($id) {
            // $this->load->model('order/payoff_model');

            $payoffUserData = array('status' => 0);
            $condition = array('id' => $id);
            // $payoffUser = $this->payoff_model->getPayoffUsers($condition);
            $deleted = $this->transactee_model->delete($condition);
            if ($deleted) {
                /** Save user Activity */
                $activity = 'Transactee user deleted :- ' . $payoffUser['email_address'];
                $this->common->logAdminActivity($activity);
                /** End save user activity */
                $successMsg = 'Transactee user deleted successfully.';
                $response = array('status' => 'success', 'message' => $successMsg);
            }
        } else {
            $msg = 'Transactee ID is required.';
            $response = array('status' => 'error', 'message' => $msg);
        }
        echo json_encode($response);
    }

    public function get_transactee_details()
    {
        $transacteeId = $this->input->post('transactee_id');
        if ($transacteeId) {
            $getTransacteeDetails = $this->transactee_model->getDetails($transacteeId);

            // $return_data = array('status' => false, 'data' => []);
            if (!empty($getTransacteeDetails)) {
                $return_data['status'] = true;
                $return_data['message'] = 'Data Fetched successfully.';
                $return_data['data'] = $getTransacteeDetails;
            }
        } else {
            $return_data['status'] = false;
            $return_data['message'] = 'Invalid Request !';
            $return_data['data'] = [];
        }
        echo json_encode($return_data);
    }

    public function getTransacteeDocumentList()
    {
        $params = array();
        $data = array();
        if (isset($_POST['id']) && !empty($_POST['id'])) {
            $transacteeId = $_POST['id'];
            $this->load->model('order/transactee_model');
            $getTransacteeDetails = $this->transactee_model->getDetails($transacteeId);
        } else {
            $json_data['status'] = 'error';
            $json_data['message'] = 'Invalid request!';
            echo json_encode($json_data);exit;
        }
        $originalDocumentNameList = !empty($getTransacteeDetails['document_original_names']) ? json_decode($getTransacteeDetails['document_original_names'], true) : [];
        $documentNameList = !empty($getTransacteeDetails['document_names']) ? json_decode($getTransacteeDetails['document_names'], true) : [];
        if (isset($originalDocumentNameList) && !empty($originalDocumentNameList)) {
            // $i = $params['start'] + 1;
            foreach ($originalDocumentNameList as $key => $value) {
                $nestedData = array();
                $nestedData[] = $key + 1;
                $nestedData[] = $value;

                $documentName = $documentNameList[$key];
                if (env('AWS_ENABLE_FLAG') == 1) {
                    $documentUrl = env('AWS_PATH') . "transactee-upload-doc/" . $documentName;
                    $nestedData[] = "<div style='display:flex;justify-content: center;'><a href='javascript:void(0);' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"transactee"' . ");'><i class='fas fa-fw fa-download'></i></a>
                        <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a>
                        </div>";
                } else {
                    $documentUrl = env('AWS_PATH') . "vendo-doc/" . $documentName;
                    $nestedData[] = "<div style='display:flex;justify-content: center;'><a href='javascript:void(0);' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"transactee"' . ");'><i class='fas fa-fw fa-download'></i></a>
                        <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a>
                        </div>";
                }

                $data[] = $nestedData;
            }
        }

        $json_data['recordsTotal'] = intval($order_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($order_lists['recordsFiltered']);
        $json_data['data'] = $data;
        $json_data['status'] = 'success';
        $json_data['message'] = 'Record fetched!';
        echo json_encode($json_data);
    }

    public function add_transactee()
    {
        $data['title'] = 'Add Transactee | Pacific Coast Title Company';
        $data['pageTitle'] = 'Add Transactee';
        $userdata = $this->session->userdata('admin');

        if ($this->input->post()) {
            $this->form_validation->set_rules('transctee_name', 'Transctee Name', 'required', array('required' => 'Please Enter Transctee Name'));
            $this->form_validation->set_rules('file_number', 'File Number', 'required', array('required' => 'Please Enter File Number'));
            $this->form_validation->set_rules('account_number', 'Account Number', 'required|is_unique[pct_vendors.account_number]', array('required' => 'Please Enter Account Number', 'is_unique' => 'The Account Number is already exist'));
            $this->form_validation->set_rules('aba', 'ABA/Routing', 'required', array('required' => 'Please Enter ABA/Routing'));
            $this->form_validation->set_rules('bank_name', 'Bank Name', 'required', array('required' => 'Please Enter Bank Name'));
            $this->form_validation->set_rules('admin_notes', 'Admin Notes', 'required', array('required' => 'Please Enter Admin Notes'));

            if ($this->form_validation->run() == true) {
                $config['upload_path'] = './uploads/transactee-upload-doc/';
                $config['allowed_types'] = 'pdf';
                $config['max_size'] = 12000;
                $this->load->library('upload', $config);

                if (!is_dir('/uploads/transactee-upload-doc')) {
                    mkdir('./uploads/transactee-upload-doc', 0777, true);
                }

                // echo "<pre>";
                // print_r($_FILES);die;
                if (!empty($_FILES['transactee_documents']['name'])) {
                    if (!$this->upload->do_upload('transactee_documents')) {
                        $errorMsg = $this->upload->display_errors();
                        $data = array(
                            "error" => $errorMsg,
                            "success" => '',
                        );
                        echo json_encode($data);exit;
                    } else {
                        $data = $this->upload->data();
                        $contents = file_get_contents($data['full_path']);
                        $document_name = date('YmdHis') . "_" . $data['file_name'];
                        rename(FCPATH . "/uploads/transactee-upload-doc/" . $data['file_name'], FCPATH . "/uploads/transactee-upload-doc/" . $document_name);

                        $this->order->uploadDocumentOnAwsS3($document_name, 'transactee-upload-doc');

                        $this->load->model('order/transactee_model');

                        $getTransacteeDetails = $this->transactee_model->getDetails($transacteeId);
                        $originalDocumentNameList = !empty($getTransacteeDetails['document_original_names']) ? json_decode($getTransacteeDetails['document_original_names'], true) : [];
                        $documentNameList = !empty($getTransacteeDetails['document_names']) ? json_decode($getTransacteeDetails['document_names'], true) : [];
                        array_push($originalDocumentNameList, $data['file_name']);
                        array_push($documentNameList, $document_name);
                        $transacteeData = array(
                            'transctee_name' => $_POST['transctee_name'],
                            'file_number' => $_POST['file_number'],
                            'account_number' => $_POST['account_number'],
                            'aba' => $_POST['aba'],
                            'bank_name' => $_POST['bank_name'],
                            'admin_notes' => $_POST['admin_notes'],
                            'document_original_names' => json_encode($originalDocumentNameList),
                            'document_names' => json_encode($documentNameList),
                            'submitted' => date('Y-m-d H:i:s'),
                            'created_by' => 'admin',
                            'created_by_id' => $userdata['id'],
                            'approved_date' => date('Y-m-d H:i:s'),
                            'approved_by' => $userdata['id'],
                            'is_approved' => 1,
                        );

                        $insert = $this->transactee_model->insert($transacteeData);

                        /** Save user Activity */
                        $activity = 'Transctee created :- ' . $_POST['transctee_name'] . 'By ' . $userdata['name'];
                        $this->common->logAdminActivity($activity);
                        /** End save user activity */
                        if ($insert) {
                            $data['success_msg'] = 'Transctee created successfully.';
                        } else {
                            $data['error_msg'] = 'Transctee not created.';
                        }
                    }

                } else {
                    $data['transactee_documents_error_msg'] = 'Please upload document';
                }

            } else {
                $data['transctee_name_error_msg'] = form_error('transctee_name');
                $data['file_number_error_msg'] = form_error('file_number');
                $data['account_number_error_msg'] = form_error('account_number');
                $data['aba_error_msg'] = form_error('aba');
                $data['bank_name_error_msg'] = form_error('bank_name');
                $data['admin_notes_error_msg'] = form_error('admin_notes');
            }
        }
        // $this->admintemplate->addCSS(base_url('assets/backend/css/transactee.css?v=' . $this->version));
        $this->admintemplate->show("order/transactee", "add_transactee", $data);
        // $this->load->view('order/transactee/add_transactee', $data);
    }

    public function check_unique_account_number($account_number, $id)
    {
        log_message('debug', "check_unique_account_number called with account_number: $account_number and id: $id");

        $data = $this->transactee_model->getDetails($account_number, 'account_number');
        log_message('debug', "Data retrieved: " . print_r($data, true));
        if ($data && $data['id'] != $id) {
            $this->form_validation->set_message('check_unique_account_number', 'The Account Number is already in use.');
            return false;
        } else {
            return true;
        }
    }

}
