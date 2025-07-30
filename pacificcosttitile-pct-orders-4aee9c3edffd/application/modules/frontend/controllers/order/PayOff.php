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
        $this->load->library('order/template');
        $this->load->library('order/transacteetemplate');
        $this->load->library('order/order');
        $this->load->model('order/apiLogs');
        $this->load->model('order/home_model');
        $this->load->library('order/resware');
        $this->load->library('order/common');
        $this->common->is_pay_off_user();
    }

    public function index()
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

        $userdata = $this->session->userdata('user');
        $name = isset($userdata['name']) && !empty($userdata['name']) ? $userdata['name'] : '';
        $data['name'] = $name;
        $data['user_email'] = $userdata['email'];
        $data['title'] = 'Payoff Dashboard | Pacific Coast Title Company';
        $data['pageTitle'] = 'Pacific Coast Title - Approved Wire List';
        // $this->template->addJS( base_url('assets/frontend/js/order/payoff.js?v='.$this->version));
        // $this->template->show("order/pay_off", "pay_off_dashboard", $data);
        $this->transacteetemplate->addJS(base_url('assets/frontend/js/order/payoff.js?v=' . $this->version));

        $this->transacteetemplate->addCSS(base_url('assets/backend/css/transactee.css?v=' . $this->version));
        $this->transacteetemplate->show("order/transactee", "transactee_dashboard", $data);
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

    public function get_transactees()
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
            $order_lists = $this->order->get_transactees($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $order_lists = $this->order->get_transactees($params);
        }

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
                // $nestedData[] = $order['first_name'] . ' ' . $order['last_name'] . ' ' . date("m/d/Y @ g:i a", strtotime($order['approved_date']));
                $nestedData[] = ($order['is_approved']) ? $order['first_name'] . ' ' . $order['last_name'] . ' ' . date("m/d/Y @ g:i a", strtotime($order['approved_date'])) : '';

                $nestedData[] = "<div style='display: flex; justify-content: space-around;' >
                                    <a href='javascript:void(0);' onclick='openNotes(" . '"' . $id . '"' . ", " . '"' . $notes . '"' . ", " . '"' . $admin_notes . '"' . ");' class='btn btn-secondary btn-icon-split'>
                                        <span class='icon text-white-50'>
                                            <i class='fa fa-credit-card'></i>
                                        </span>
                                        <span class='text'>Notes</span>
                                    </a>
                                </div>";
                $nestedData[] = "<div style='display: flex; justify-content: space-around;' >
                                    <a href='javascript:void(0);' onclick='getDocuments($id);' class='btn btn-secondary btn-icon-split'>
                                        <span class='icon text-white-50'>
                                            <i class='fa fa-credit-card'></i>
                                        </span>
                                        <span class='text'>Upload/View</span>
                                    </a>
                                </div>";
                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($order_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($order_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function uploadTransacteeDocuments()
    {
        $transacteeId = $_POST['transactee_id'];

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
                    $nestedData[] = "<div style='display:flex;'><a href='javascript:void(0);' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"transactee"' . ");'><i class='fas fa-fw fa-download'></i></a>
                        <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a>
                        </div>";
                } else {
                    $documentUrl = env('AWS_PATH') . "transactee-upload-doc/" . $documentName;
                    $nestedData[] = "<div style='display:flex;'><a href='javascript:void(0);' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"transactee"' . ");'><i class='fas fa-fw fa-download'></i></a>
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

    public function downloadPayOffDocument()
    {
        $userdata = $this->session->userdata('user');
        $file_id = $this->input->post('file_id');
        $user_data = array();
        $user_data = array(
            'admin_api' => 1,
        );
        $user_data['from_mail'] = 1;

        $endPoint = 'files/' . $file_id . '/documents';
        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_documents', env('RESWARE_ORDER_API') . $endPoint, array(), array(), $file_id, 0);
        $resultDocuments = $this->resware->make_request('GET', $endPoint, '', $user_data);
        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_documents', env('RESWARE_ORDER_API') . $endPoint, array(), $resultDocuments, $file_id, $logid);
        $resDocuments = json_decode($resultDocuments, true);

        foreach ($resDocuments['Documents'] as $orderDocuments) {
            if ($orderDocuments['DocumentType']['DocumentTypeID'] == 1035) {
                $api_document_id = $orderDocuments['DocumentID'];
                $this->load->model('order/document');
                $endPoint = 'documents/' . $api_document_id . '?format=json';
                $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_document', env('RESWARE_ORDER_API') . $endPoint, array(), array(), 0, 0);
                $resultDocument = $this->resware->make_request('GET', $endPoint, '', $user_data);
                $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_document', env('RESWARE_ORDER_API') . $endPoint, array(), $resultDocument, 0, $logid);
                $resDocument = json_decode($resultDocument, true);
                if (isset($resDocument['Document']) && !empty($resDocument['Document'])) {
                    $binaryData = $resDocument['Document']['DocumentBody'];
                }
            }
        }
        echo $binaryData;exit;
    }

    public function updatePayOffAction()
    {
        $fileId = $this->input->post('file_id');
        $endPoint = 'files/' . $fileId . '/actions';
        $user_data['admin_api'] = 1;
        $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, array(), array(), 0, 0);
        $res = $this->resware->make_request('GET', $endPoint, array(), $user_data);
        $this->apiLogs->syncLogs(0, 'resware', 'get_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, array(), $res, 0, $logid);
        $result = json_decode($res, true);

        if (isset($result['Actions']) && !empty($result['Actions'])) {
            $array_keymap = $this->order->array_recursive_search_key_map(154, $result['Actions']);
            if (!empty($array_keymap)) {
                $actionData = array(
                    'StartTask' => array(
                        'CoordinatorTypeID' => 8,
                        'DueDate' => '/Date(' . (strtotime(date('Y-m-d H:i:s')) * 1000) . '-0000)/',
                    ),
                );
                $endPoint = 'files/' . $fileId . '/actions/' . $result['Actions'][$array_keymap[0]]['FileActionID'];
                $user_data['admin_api'] = 1;
                $actionData = json_encode($actionData);
                $logid = $this->apiLogs->syncLogs(0, 'resware', 'update_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, $actionData, array(), $fileId, 0);
                $res = $this->resware->make_request('PUT', $endPoint, $actionData, $user_data);
                $this->apiLogs->syncLogs(0, 'resware', 'update_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, $actionData, $res, $fileId, $logid);
                $result = json_decode($res, true);

                if (!empty($result['FileActionID'])) {
                    $resultPayOffaction = array('status' => 'success', 'msg' => 'Payoff action updated successfully.');
                } else {
                    $resultPayOffaction = array('status' => 'error', 'msg' => 'Something went wrong during update action prelim.');
                }
            } else {
                $actionData = array(
                    'ActionType' => array(
                        'ActionTypeID' => 154,
                    ),
                    'Group' => array(
                        'ActionGroupID' => 3,
                    ),
                    'StartTask' => array(
                        'CoordinatorTypeID' => 8,
                        'DueDate' => '/Date(' . (strtotime(date('Y-m-d H:i:s')) * 1000) . '-0000)/',
                    ),
                );
                $endPoint = 'files/' . $fileId . '/actions/';
                $user_data['admin_api'] = 1;
                $actionData = json_encode($actionData);
                $logid = $this->apiLogs->syncLogs(0, 'resware', 'add_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, $actionData, array(), $fileId, 0);
                $res = $this->resware->make_request('POST', $endPoint, $actionData, $user_data);
                $this->apiLogs->syncLogs(0, 'resware', 'add_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, $actionData, $res, $fileId, $logid);
                $result = json_decode($res, true);

                if (!empty($result['FileActionID'])) {
                    $resultPayOffaction = array('status' => 'success', 'msg' => 'Payoff action added successfully.');
                } else {
                    $resultPayOffaction = array('status' => 'error', 'msg' => 'Something went wrong during update action prelim.');
                }
            }
        }
        echo json_encode($resultPayOffaction);
    }

    public function createPayoff()
    {
        $fileId = $this->uri->segment(2);
        $this->load->model('order/home_model');
        $data['orderDetails'] = $this->order->get_order_details($fileId);
        $data['orderUser'] = $this->home_model->get_user(array('id' => $data['orderDetails']['customer_id']));
        $data['titleOfficer'] = $this->home_model->get_user(array('id' => $data['orderDetails']['title_officer']));
        $userdata = $this->session->userdata('user');
        $name = isset($userdata['name']) && !empty($userdata['name']) ? $userdata['name'] : '';
        $data['title'] = 'Smart Dashboard | Pacific Coast Title Company';
        $this->load->view('order/pay_off/create_payoff', $data);
    }

    public function addTransactee()
    {
        $data['title'] = 'Add Transactee | Pacific Coast Title Company';
        $data['pageTitle'] = 'Add Transactee';
        $userdata = $this->session->userdata('user');

        if ($this->input->post()) {
            $this->form_validation->set_rules('transctee_name', 'Transctee Name', 'required', array('required' => 'Please Enter Transctee Name'));
            $this->form_validation->set_rules('file_number', 'File Number', 'required', array('required' => 'Please Enter File Number'));
            $this->form_validation->set_rules('account_number', 'Account Number', 'required|is_unique[pct_vendors.account_number]', array('required' => 'Please Enter Account Number', 'is_unique' => 'The Account Number is already exist'));
            $this->form_validation->set_rules('aba', 'ABA/Routing', 'required', array('required' => 'Please Enter ABA/Routing'));
            $this->form_validation->set_rules('bank_name', 'Bank Name', 'required', array('required' => 'Please Enter Bank Name'));
            $this->form_validation->set_rules('notes', 'Notes', 'required', array('required' => 'Please Enter Notes'));
            // $this->form_validation->set_message('is_unique', 'The %s is already exist');

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
                            'notes' => $_POST['notes'],
                            'document_original_names' => json_encode($originalDocumentNameList),
                            'document_names' => json_encode($documentNameList),
                            'submitted' => date('Y-m-d'),
                            'created_by' => 'user',
                            'created_by_id' => $userdata['id'],
                        );
                        $this->load->model('order/transactee_model');
                        $insert = $this->transactee_model->insert($transacteeData);
                        $transacteeData['submitted_by'] = $userdata['name'];
                        $message = $this->load->view('frontend/emails/create_transactee.php', $transacteeData, true);

                        $subject = 'New Payoff: Approval Needed';
                        $to = [
                            'bheethuis@pct.com',
                            'htrinh@pct.com',
                        ];
                        // $cc = array('piyush-crest@yopmail.com');
                        $this->order->sendEmail($to, $cc, $subject, $transacteeData, $message, 'create_transactee');
                        if ($insert) {
                            $data['success_msg'] = 'Transactee added successfully.';
                        } else {
                            $data['error_msg'] = 'Transactee not created.';
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
                $data['notes_error_msg'] = form_error('notes');
            }
        }
        $this->transacteetemplate->addCSS(base_url('assets/backend/css/transactee.css?v=' . $this->version));
        $this->transacteetemplate->show("order/transactee", "add_transactee", $data);
        // $this->load->view('order/transactee/add_transactee', $data);
    }

    public function generatePayoff()
    {
        $success = array();
        $errors = array();
        $config['upload_path'] = './uploads/payoff/';
        $config['allowed_types'] = 'doc|docx|gif|msg|pdf|tif|tiff|xls|xlsx|xml';
        $config['max_size'] = 18000;
        $userdata = $this->session->userdata('user');
        $this->load->library('upload', $config);
        $fileId = $this->input->post('file_id');
        $file_number = $this->input->post('file_number');

        if ($userdata['is_payoff_user'] == 1 || $userdata['is_master'] == 1) {
            $user_data['admin_api'] = 1;
        }

        if (!empty($_FILES['payoff_files']['name'])) {
            foreach ($_FILES['payoff_files']['name'] as $key => $file) {
                $_FILES['file']['name'] = $_FILES['payoff_files']['name'][$key];
                $_FILES['file']['type'] = $_FILES['payoff_files']['type'][$key];
                $_FILES['file']['tmp_name'] = $_FILES['payoff_files']['tmp_name'][$key];
                $_FILES['file']['error'] = $_FILES['payoff_files']['error'][$key];
                $_FILES['file']['size'] = $_FILES['payoff_files']['size'][$key];

                if (!$this->upload->do_upload('file')) {
                    $errors[] = $this->upload->display_errors();
                } else {
                    $data = $this->upload->data();

                    $contents = file_get_contents($data['full_path']);
                    $binaryData = base64_encode($contents);
                    $document_name = date('YmdHis') . "_" . $data['file_name'];
                    rename(FCPATH . "/uploads/payoff/" . $data['file_name'], FCPATH . "/uploads/payoff/" . $document_name);
                    $this->order->uploadDocumentOnAwsS3($document_name, 'payoff');

                    $endPoint = 'files/' . $fileId . '/documents';
                    $documentApiData = array(
                        'DocumentName' => $data['file_name'],
                        'DocumentType' => array(
                            'DocumentTypeID' => 1035,
                        ),
                        'Description' => 'Create PayOff Document',
                        'InternalOnly' => false,
                        'DocumentBody' => $binaryData,
                    );
                    $document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);

                    $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $fileId, 0);
                    $result = $this->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
                    $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $fileId, $logid);
                    $res = json_decode($result);
                    if (!empty($res->Document->DocumentID)) {
                        $success[] = $data['file_name'] . " uploaded successfully on Resware side for file number " . $file_number;
                    } else {
                        $errors[] = $data['file_name'] . ": Something went wrong.Please try again.";
                    }
                }
            }
        }
        $data = array();

        $data['data']['to'] = $this->input->post('to');
        $data['data']['recording_date'] = $this->input->post('recording_date');
        $data['data']['8am'] = $this->input->post('8am');
        $data['data']['am_special'] = $this->input->post('am_special');
        $data['data']['need_prefigures_by'] = $this->input->post('need_prefigures_by');
        $data['data']['customer'] = $this->input->post('customer');
        $data['data']['reference_number'] = $this->input->post('reference_number');
        $data['data']['phone'] = $this->input->post('phone');
        $data['data']['fax'] = $this->input->post('fax');
        $data['data']['email'] = $this->input->post('email');
        $data['data']['property_address'] = $this->input->post('property_address');
        $data['data']['order_type'] = $this->input->post('order_type');
        $data['data']['apn'] = $this->input->post('apn');
        $data['data']['country'] = $this->input->post('country');
        $data['data']['seller'] = $this->input->post('seller');
        $data['data']['ssn'] = $this->input->post('ssn');
        $data['data']['buyer'] = $this->input->post('buyer');
        $data['data']['ssn_buyer'] = $this->input->post('ssn_buyer');
        $data['data']['fund_expected'] = $this->input->post('fund_expected');
        $data['data']['funding_from'] = $this->input->post('funding_from');
        $data['data']['payment_method'] = $this->input->post('payment_method');
        $data['data']['fund_expected1'] = $this->input->post('fund_expected1');
        $data['data']['funding_from1'] = $this->input->post('funding_from1');
        $data['data']['payment_method1'] = $this->input->post('payment_method1');
        $data['data']['pay'] = $this->input->post('pay');
        $data['data']['FHA0'] = $this->input->post('FHA0');
        $data['data']['payment_method2'] = $this->input->post('payment_method2');
        $data['data']['pay1'] = $this->input->post('pay1');
        $data['data']['FHA1'] = $this->input->post('FHA1');
        $data['data']['payment_method3'] = $this->input->post('payment_method3');
        $data['data']['pay2'] = $this->input->post('pay2');
        $data['data']['FHA2'] = $this->input->post('FHA2');
        $data['data']['payment_method4'] = $this->input->post('payment_method4');
        $data['data']['pay3'] = $this->input->post('pay3');
        $data['data']['FHA3'] = $this->input->post('FHA3');
        $data['data']['payment_method5'] = $this->input->post('payment_method5');
        $data['data']['pay_or_not'] = $this->input->post('pay_or_not');
        $data['data']['hold_until'] = $this->input->post('hold_until');
        $data['data']['taxes'] = $this->input->post('taxes');
        $data['data']['other'] = $this->input->post('other');
        $data['data']['current_taxes'] = $this->input->post('current_taxes');
        $data['data']['first'] = $this->input->post('first');
        $data['data']['first_value'] = $this->input->post('first_value');
        $data['data']['penalty1'] = $this->input->post('penalty1');
        $data['data']['penalty1_value'] = $this->input->post('penalty1_value');
        $data['data']['total1'] = $this->input->post('total1');
        $data['data']['second'] = $this->input->post('second');
        $data['data']['second_value'] = $this->input->post('second_value');
        $data['data']['penalty2'] = $this->input->post('penalty2');
        $data['data']['penalty2_value'] = $this->input->post('penalty2_value');
        $data['data']['total2'] = $this->input->post('total2');
        $data['data']['first1'] = $this->input->post('first1');
        $data['data']['first1_value'] = $this->input->post('first1_value');
        $data['data']['penalty3'] = $this->input->post('penalty3');
        $data['data']['penalty3_value'] = $this->input->post('penalty3_value');
        $data['data']['total3'] = $this->input->post('total3');
        $data['data']['second1'] = $this->input->post('second1');
        $data['data']['second1_value'] = $this->input->post('second1_value');
        $data['data']['penalty4'] = $this->input->post('penalty4');
        $data['data']['penalty4_value'] = $this->input->post('penalty4_value');
        $data['data']['total4'] = $this->input->post('total4');
        $data['data']['delinquent_taxes'] = $this->input->post('delinquent_taxes');
        $data['data']['amount'] = $this->input->post('amount');
        $data['data']['apn_2'] = $this->input->post('apn_2');
        $data['data']['payment_method6'] = $this->input->post('payment_method6');

        $this->load->library('m_pdf');
        $html = $this->load->view('order/pay_off/create_payoff', $data, true);
        // $stylesheet = file_get_contents('assets/frontend/css/payoff/style.css');

        // $customCss = '';
        // $combinedCss = $stylesheet . $customCss;
        // $this->m_pdf->pdf->WriteHTML($combinedCss, 1);
        $this->m_pdf->pdf->WriteHTML($html, 2);
        $this->load->model('order/document');

        if (!is_dir('uploads/payoff')) {
            mkdir('./uploads/payoff', 0777, true);
        }
        chmod(FCPATH . 'uploads/payoff', 0777);
        $document_name = date('YmdHis') . "_" . "payoff_" . $fileId . ".pdf";
        $pdfFilePath = './uploads/payoff/' . $document_name;
        $this->m_pdf->pdf->Output($pdfFilePath, 'F');
        $contents = file_get_contents($pdfFilePath);
        $binaryData = base64_encode($contents);
        $this->order->uploadDocumentOnAwsS3($document_name, 'payoff');

        $endPoint = 'files/' . $fileId . '/documents';
        $documentApiData = array(
            'DocumentName' => 'Payoff Package',
            'DocumentType' => array(
                'DocumentTypeID' => 1035,
            ),
            'Description' => 'Create PayOff Document',
            'InternalOnly' => false,
            'DocumentBody' => $binaryData,
        );
        $document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);
        if ($userdata['is_payoff_user'] == 1 || $userdata['is_master'] == 1) {
            $user_data['admin_api'] = 1;
        }

        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $fileId, 0);
        $result = $this->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $fileId, $logid);
        $res = json_decode($result);
        if (!empty($res->Document->DocumentID)) {
            $success[] = $document_name . " uploaded successfully on Resware side for file number " . $file_number;
        } else {
            $errors[] = $document_name . ": Something went wrong.Please try again.";
        }

        $this->home_model->update(array('is_payoff_generated' => 1), array('file_id' => $fileId), 'order_details');

        $endPoint = 'files/' . $fileId . '/actions';
        $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, array(), array(), 0, 0);
        $res = $this->resware->make_request('GET', $endPoint, array(), $user_data);
        $this->apiLogs->syncLogs(0, 'resware', 'get_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, array(), $res, 0, $logid);
        $result = json_decode($res, true);
        if (isset($result['Actions']) && !empty($result['Actions'])) {
            $array_keymap = $this->order->array_recursive_search_key_map(151, $result['Actions']);
            if (!empty($array_keymap)) {
                $actionData = array(
                    'StartTask' => array(
                        'CoordinatorTypeID' => 19,
                        'DueDate' => '/Date(' . (strtotime(date('Y-m-d H:i:s')) * 1000) . '-0000)/',
                    ),
                );
                $endPoint = 'files/' . $fileId . '/actions/' . $result['Actions'][$array_keymap[0]]['FileActionID'];
                $user_data['admin_api'] = 1;
                $actionData = json_encode($actionData);
                $logid = $this->apiLogs->syncLogs(0, 'resware', 'update_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, $actionData, array(), $fileId, 0);
                $res = $this->resware->make_request('PUT', $endPoint, $actionData, $user_data);
                $this->apiLogs->syncLogs(0, 'resware', 'update_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, $actionData, $res, $fileId, $logid);
                $result = json_decode($res, true);

                if (!empty($result['FileActionID'])) {
                    $success[] = array('status' => 'success', 'msg' => 'Payoff action updated successfully.');
                } else {
                    $errors[] = array('status' => 'error', 'msg' => 'Something went wrong during start action 151.');
                }
            } else {
                $actionData = array(
                    'ActionType' => array(
                        'ActionTypeID' => 151,
                    ),
                    'Group' => array(
                        'ActionGroupID' => 6,
                    ),
                    'StartTask' => array(
                        'CoordinatorTypeID' => 19,
                        'DueDate' => '/Date(' . (strtotime(date('Y-m-d H:i:s')) * 1000) . '-0000)/',
                    ),
                );
                $endPoint = 'files/' . $fileId . '/actions/';
                $user_data['admin_api'] = 1;
                $actionData = json_encode($actionData);
                $logid = $this->apiLogs->syncLogs(0, 'resware', 'add_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, $actionData, array(), $fileId, 0);
                $res = $this->resware->make_request('POST', $endPoint, $actionData, $user_data);
                $this->apiLogs->syncLogs(0, 'resware', 'add_actions_for_order', env('RESWARE_ORDER_API') . $endPoint, $actionData, $res, $fileId, $logid);
                $result = json_decode($res, true);

                if (!empty($result['FileActionID'])) {
                    $success[] = array('status' => 'success', 'msg' => 'Payoff action 151 started successfully.');
                } else {
                    $errors[] = array('status' => 'error', 'msg' => 'Something went wrong during start action 151.');
                }
            }
        }

        $data['errors'] = $errors;
        $data['success'] = $success;
        $data = array(
            "errors" => $errors,
            "success" => $success,
        );
        $this->session->set_userdata($data);
        redirect(base_url() . 'pay-off-dashboard');
    }
}
