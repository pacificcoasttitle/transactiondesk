<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MX_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/welcome
     *    - or -
     *         http://example.com/index.php/welcome/index
     *    - or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    private $version = '09';
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(
            array('file', 'url', 'form')
        );
        $this->version = strtotime(date('Y-m-d'));
        $this->load->library('order/adminTemplate');
        $this->load->library('form_validation');
        $this->load->model('order/home_model');
        $this->load->model('order/order_model');
        $this->load->library('order/common');
        $this->load->library('order/order');
        $this->common->is_admin();

    }

    public function index()
    {
        $this->common->checkRoleAccess();
        $data = array();
        // $data['title'] = 'PCT Order: Dashboard';
        $order_filter = ['for_month' => date('m'), 'for_year' => date('Y')];
        $openOrderData = $this->order_model->get_order_count($order_filter);
        $order_filter['type'] = 'closed';
        $closedOrderData = $this->order_model->get_order_count($order_filter);
        // $titlePointData = $this->order_model->get_title_point_count();

        $openLoanCount = $openSalesCount = 0;
        $closedLoanCount = $closedSalesCount = 0;

        if (isset($openOrderData) && !empty($openOrderData)) {
            foreach ($openOrderData as $key => $value) {
                if ($value['type'] == 'loan') {
                    $openLoanCount = $value['total'];
                }
                if ($value['type'] == 'sale') {
                    $openSalesCount = $value['total'];
                }
            }
        }

        if (isset($closedOrderData) && !empty($closedOrderData)) {
            foreach ($closedOrderData as $key => $value) {
                if ($value['type'] == 'loan') {
                    $closedLoanCount = $value['total'];
                }
                if ($value['type'] == 'sale') {
                    $closedSalesCount = $value['total'];
                }
            }
        }
        // $totalFailCount = 0;

        // $lvCount = isset($titlePointData['lv_total_records']) && !empty($titlePointData['lv_total_records']) ? $titlePointData['lv_total_records'] : 0;
        // $totalFailCount += $lvCount;

        // $grantDeedCount = isset($titlePointData['grant_deed_total_records']) && !empty($titlePointData['grant_deed_total_records']) ? $titlePointData['grant_deed_total_records'] : 0;
        // $totalFailCount += $grantDeedCount;

        // $taxCount = isset($titlePointData['tax_total_records']) && !empty($titlePointData['tax_total_records']) ? $titlePointData['tax_total_records'] : 0;

        // $totalFailCount += $taxCount;

        $this->load->model('order/customer_basic_details_model');
        $customer_filter = ['is_escrow' => 1, 'status' => 1];
        $escrowUsersCount = $this->customer_basic_details_model->count_by($customer_filter);
        $customer_filter = ['is_escrow' => 0, 'status' => 1];
        $lenderUsersCount = $this->customer_basic_details_model->count_by($customer_filter);
        $customer_filter = ['is_sales_rep' => 1];
        $salesRepUsersCount = $this->customer_basic_details_model->count_by($customer_filter);
        $customer_filter = [];
        $expiredPasswords = $this->home_model->get_incorrect_customers($customer_filter);
        $expiredPasswordCount = $expiredPasswords['recordsTotal'];
        $failedJsonCount = $this->home_model->get_failed_json_files();

        $data = array(
            'title' => 'PCT Order: Dashboard',
            'openLoanCount' => $openLoanCount,
            'openSalesCount' => $openSalesCount,
            'closedLoanCount' => $closedLoanCount,
            'closedSalesCount' => $closedSalesCount,
            'escrowUsersCount' => $escrowUsersCount,
            'lenderUsersCount' => $lenderUsersCount,
            'salesRepUsersCount' => $salesRepUsersCount,
            'expiredPasswordCount' => $expiredPasswordCount,
            'failedJsonCount' => $failedJsonCount,

            // 'lvCount' => $lvCount,
            // 'grantDeedCount' => $grantDeedCount,
            // 'taxCount' => $taxCount,
            // 'totalFailCount' => $totalFailCount
        );

        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/index', $data);
        // $this->load->view('order/layout/footer', $data);

        $this->admintemplate->addCSS(base_url('assets/libs/calendar/main.css'));
        $this->admintemplate->addJS(base_url('assets/libs/calendar/main.js'));
        $this->admintemplate->addJS(base_url('assets/backend/js/dashboard.js?v=' . $this->version));
        $this->admintemplate->show("order/home", "index", $data);
    }

    public function dashboard()
    {
        $data = array();
        $data['title'] = 'PCT Order: Escrow';
        // $this->admintemplate->addCSS( base_url('assets/backend/hr/vendor/datatables/dataTables.bootstrap4.min.css'));
        // $this->admintemplate->addJS( base_url('assets/backend/hr/vendor/datatables/jquery.dataTables.min.js'));
        // $this->admintemplate->addJS( base_url('assets/backend/hr/vendor/datatables/dataTables.bootstrap4.min.js'));
        $this->admintemplate->show("order/home", "dashboard", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/dashboard', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_customer_list()
    {
        $params = array();
        // echo "<pre>"; print_r($_POST); exit;
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['is_escrow'] = 1;

            $pageno = ($params['start'] / $params['length']) + 1;

            $customer_lists = $this->home_model->get_customers($params);
            // $cnt = ($pageno == 1) ? ($params['start']+1) : (($pageno - 1) * $params['length']) + 1;

            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $customer_lists = $this->home_model->get_customers($params);
        }
        $data = array();

        if (isset($customer_lists['data']) && !empty($customer_lists['data'])) {
            foreach ($customer_lists['data'] as $key => $value) {
                $nestedData = array();
                $user_id = $value['id'];
                /*$nestedData[] = $value['customer_number'];*/
                $nestedData[] = $value['first_name'];
                $nestedData[] = $value['last_name'];
                $nestedData[] = $value['email_address'];

                // $nestedData[] = $value['telephone_no'];
                $nestedData[] = $value['company_name'];
                $nestedData[] = $value['street_address'];
                $nestedData[] = $value['city'];
                $nestedData[] = $value['zip_code'];
                if ($value['is_dual_cpl'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                $nestedData[] = "<input $checked onclick='isDualCplUser();' style='height:30px;width:20px;' type='checkbox' id='$user_id' name='$user_id'>";
                if ($value['is_allow_only_resware_orders'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                $nestedData[] = "<input $checked onclick='isAllowOnlyReswareOrders();' style='height:30px;width:20px;' type='checkbox' id='$user_id' name='$user_id'>";

                if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                    /*$action = "<a href='javascript:void(0);' class='btn btn-action edit-group' data-id=".$value->id." data-name='".$value->name."' title ='Edit Group Detail'><span class='fas fa-edit' aria-hidden='true'></span></a>";*/

                    $action = "<a href='javascript:void(0);' onclick='deleteCustomer(" . $value['id'] . ")'  title='Delete Customer'><span class='fas fa-trash' aria-hidden='true'></span></a>";
                    $nestedData[] = $action;
                }

                $data[] = $nestedData;
                // $cnt++;
            }
        }
        $json_data['recordsTotal'] = intval($customer_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($customer_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function import()
    {
        $data = array();
        $data['title'] = 'PCT Order: Import';
        if ($this->input->post()) {
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '2048M');
            // Form field validation rules
            $this->form_validation->set_rules('file', 'CSV file', 'callback_file_check');

            // Validate submitted form data
            if ($this->form_validation->run($this) == true) {
                $insertCount = $updateCount = $rowCount = $notAddCount = 0;

                // If file uploaded
                if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                    // Load CSV reader library
                    $this->load->library('CSVReader');

                    // Parse data from CSV file
                    $csvData = $this->csvreader->parse_csv($_FILES['file']['tmp_name']);

                    $password = md5('Pacific1');

                    // Insert/update CSV data into database
                    if (!empty($csvData)) {
                        foreach ($csvData as $row) {
                            $rowCount++;
                            if (isset($row['Email']) && !empty($row['Email'])) {
                                $email_address = str_replace(' ', '', $row['Email']);
                                $email_address = strtolower($email_address);
                                // Prepare data for DB insertion
                                $customerData = array(
                                    'resware_user_id' => $row['Partner Employee ID'],
                                    'partner_id' => $row['Partner Company ID'],
                                    'first_name' => $row['First Name'],
                                    'last_name' => $row['Last Name'],
                                    'title' => $row['Title'],
                                    'telephone_no' => $row['Phone'],
                                    'email_address' => $email_address,
                                    'password' => $password,
                                    'company_name' => $row['Company Name'],
                                    'street_address' => $row['Street1'],
                                    'street_address_2' => $row['Street2'],
                                    'city' => $row['City'],
                                    'state' => $row['State'],
                                    'zip_code' => $row['Zip'],
                                    'is_escrow' => 1,
                                    'status' => 1,
                                );

                                $con = array(
                                    'where' => array(
                                        'email_address' => $email_address,
                                        'resware_user_id' => $row['Partner Employee ID'],
                                        'is_escrow' => 1,
                                    ),
                                    'returnType' => 'count',
                                );
                                $prevCount = $this->home_model->get_rows($con);

                                if ($prevCount > 0) {
                                    // Update member data
                                    // unset($customerData['customer_number']);
                                    $condition = array('email_address' => $email_address, 'resware_user_id' => $row['Partner Employee ID'], 'is_escrow' => 1);
                                    $update = $this->home_model->update($customerData, $condition);

                                    if ($update) {
                                        $updateCount++;
                                    }
                                } else {
                                    // Insert member data
                                    $insert = $this->home_model->insert($customerData);

                                    if ($insert) {
                                        $insertCount++;
                                    }
                                }
                            }

                        }

                        // Status message with imported data count
                        $notAddCount = ($rowCount - ($insertCount + $updateCount));
                        $successMsg = 'Customers imported successfully. Total Rows (' . $rowCount . ') | Inserted (' . $insertCount . ') | Updated (' . $updateCount . ') | Not Inserted (' . $notAddCount . ')';
                        // $this->session->set_userdata('success_msg', $successMsg);
                        $data['success_msg'] = $successMsg;
                    }
                } else {
                    // $this->session->set_userdata('error_msg', 'Error on file upload, please try again.');
                    $data['error_msg'] = 'Error on file upload, please try again.';
                }
            } else {
                // $this->session->set_userdata('error_msg', 'Invalid file, please select only CSV file.');
                $data['error_msg'] = 'Invalid file, please select only CSV file.';
            }
        }
        $this->admintemplate->show("order/home", "import", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/import', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function file_check($str)
    {
        $allowed_mime_types = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
        if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != "") {
            $mime = get_mime_by_extension($_FILES['file']['name']);
            $fileAr = explode('.', $_FILES['file']['name']);
            $ext = end($fileAr);
            if (($ext == 'csv') && in_array($mime, $allowed_mime_types)) {
                return true;
            } else {
                $this->form_validation->set_message('file_check', 'Please select only CSV file to upload.');
                return false;
            }
        } else {
            $this->form_validation->set_message('file_check', 'Please select a CSV file to upload.');
            return false;
        }
    }

    public function delete_customer()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';

        if ($id) {
            $customerData = array('status' => 0);
            $condition = array('id' => $id);
            $update = $this->home_model->update($customerData, $condition);

            /** Save user Activity */
            $user = $this->home_model->get_user($condition);
            $activity = 'User deleted successfully : ' . $user['email_address'];
            $this->order->logAdminActivity($activity);
            /** End Save user activity */

            if ($update) {
                $successMsg = 'Customer deleted successfully.';
                $response = array('status' => 'success', 'message' => $successMsg);
            }
        } else {
            $msg = 'Customer ID is required.';
            $response = array('status' => 'error', 'message' => $msg);
        }

        echo json_encode($response);
    }

    public function logout()
    {
        $this->session->unset_userdata('admin');
        redirect(base_url() . 'order/admin');
    }

    public function import_lenders()
    {
        $data = array();
        $successMsg = '';
        $data['title'] = 'PCT Order: Import';
        if ($this->input->post()) {
            $lenderType = $this->input->post('lenderType');

            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '2048M');
            // Form field validation rules
            $this->form_validation->set_rules('file', 'CSV file', 'callback_file_check');

            // Validate submitted form data
            if ($this->form_validation->run($this) == true) {
                $insertCount = $updateCount = $rowCount = $notAddCount = 0;

                // If file uploaded
                if (is_uploaded_file($_FILES['file']['tmp_name'])) {

                    // Load CSV reader library
                    $this->load->library('CSVReader');

                    // Parse data from CSV file
                    $csvData = $this->csvreader->parse_csv($_FILES['file']['tmp_name']);

                    $password = md5('Pacific1');

                    // Insert/update CSV data into database
                    if (!empty($csvData)) {
                        foreach ($csvData as $row) {
                            $rowCount++;

                            if (isset($row['Email']) && !empty($row['Email'])) {
                                $email_address = str_replace(' ', '', $row['Email']);
                                $email_address = strtolower($email_address);
                                // Prepare data for DB insertion
                                $lenderData = array(
                                    'resware_user_id' => $row['Partner Employee ID'],
                                    'partner_id' => $row['Partner Company ID'],
                                    'first_name' => $row['First Name'],
                                    'last_name' => $row['Last Name'],
                                    'title' => $row['Title'],
                                    'telephone_no' => $row['Phone'],
                                    'email_address' => $email_address,
                                    'password' => $password,
                                    'company_name' => $row['Company Name'],
                                    'street_address' => $row['Street1'],
                                    'street_address_2' => $row['Street2'],
                                    'city' => $row['City'],
                                    'state' => $row['State'],
                                    'zip_code' => $row['Zip'],
                                    'is_escrow' => 0,
                                    'lender_type' => $lenderType,
                                    'status' => 1,
                                );

                                $con = array(
                                    'where' => array(
                                        'email_address' => $email_address,
                                        'resware_user_id' => $row['Partner Employee ID'],
                                        'is_escrow' => 0,
                                    ),
                                    'returnType' => 'count',
                                );
                                $prevCount = $this->home_model->get_rows($con);

                                if ($prevCount > 0) {
                                    // Update member data
                                    $condition = array('email_address' => $email_address, 'resware_user_id' => $row['Partner Employee ID'], 'is_escrow' => 0);
                                    $update = $this->home_model->update($lenderData, $condition);

                                    if ($update) {
                                        $updateCount++;
                                    }
                                } else {
                                    // Insert member data
                                    $insert = $this->home_model->insert($lenderData);

                                    if ($insert) {
                                        $insertCount++;
                                    }
                                }
                            }

                        }

                        // Status message with imported data count
                        $notAddCount = ($rowCount - ($insertCount + $updateCount));
                        $successMsg = 'Lenders imported successfully. Total Rows (' . $rowCount . ') | Inserted (' . $insertCount . ') | Updated (' . $updateCount . ') | Not Inserted (' . $notAddCount . ')';
                        // $this->session->set_userdata('success_msg', $successMsg);
                        $data['success_msg'] = $successMsg;
                    }
                } else {
                    // $this->session->set_userdata('error_msg', 'Error on file upload, please try again.');
                    $data['error_msg'] = 'Error on file upload, please try again.';
                }
            } else {

                // $this->session->set_userdata('error_msg', 'Invalid file, please select only CSV file.');
                $data['error_msg'] = 'Invalid file, please select only CSV file.';
            }
        }
        $this->admintemplate->show("order/home", "import_lender", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/import_lender', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function lenders()
    {
        $data = array();
        $data['title'] = 'PCT Order: Lenders';
        $this->admintemplate->addJS(base_url('assets/backend/js/lender.js'));
        $this->admintemplate->show("order/home", "lenders", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/lenders', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_lender_list()
    {
        $params = array();

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['is_escrow'] = 0;

            $pageno = ($params['start'] / $params['length']) + 1;

            $lender_lists = $this->home_model->get_customers($params);
            // $cnt = ($pageno == 1) ? ($params['start']+1) : (($pageno - 1) * $params['length']) + 1;

            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $lender_lists = $this->home_model->get_customers($params);
        }
        $data = array();

        if (isset($lender_lists['data']) && !empty($lender_lists['data'])) {
            foreach ($lender_lists['data'] as $key => $value) {
                $nestedData = array();
                $user_id = $value['id'];
                $nestedData[] = $value['first_name'];
                $nestedData[] = $value['last_name'];
                $nestedData[] = $value['email_address'];
                // $nestedData[] = $value['telephone_no'];
                $nestedData[] = $value['company_name'];
                $nestedData[] = $value['street_address'] . ", " . $value['city'] . ", " . $value['state'] . ", " . $value['zip_code'];
                if ($value['is_mortgage_user'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                $nestedData[] = "<input $checked onclick='isMortgageUser();' style='height:30px;width:20px;' type='checkbox' id='$user_id' name='$user_id'>";
                $specialSel = $value['is_special_lender'] == 1 ? 'selected' : '';
                $normalSel = $value['is_special_lender'] == 0 ? 'selected' : '';
                $id = $value['id'];
                $nestedData[] = "<select class='custom-select custom-select-sm form-control form-control-sm' onchange='changeLenderUserType($id, this.value);' id='user_type' name='user_type'><option $normalSel value='0'>Normal</option><option $specialSel value='1'>Special</option></select>";
                // $nestedData[] = $value['lender_type'];

                if ($value['is_dual_cpl'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                $nestedData[] = "<input $checked onclick='isDualCplUser();' style='height:30px;width:20px;' type='checkbox' id='$user_id' name='$user_id'>";

                if ($value['is_allow_only_resware_orders'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                $nestedData[] = "<input $checked onclick='isAllowOnlyReswareOrders();' style='height:30px;width:20px;' type='checkbox' id='$user_id' name='$user_id'>";

                if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                    /*$action = "<a href='javascript:void(0);' class='btn btn-action edit-group' data-id=".$value->id." data-name='".$value->name."' title ='Edit Group Detail'><span class='fas fa-edit' aria-hidden='true'></span></a>";*/

                    $action = "<a href='javascript:void(0);' onclick='deleteCustomer(" . $value['id'] . ")' title='Delete Customer'><i class='fas fa-trash' aria-hidden='true'></i></a>";
                    $nestedData[] = $action;
                }

                $data[] = $nestedData;
                // $cnt++;
            }
        }
        $json_data['recordsTotal'] = intval($lender_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($lender_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function ion_fraud_document()
    {
        $data = array();
        $data['title'] = 'PCT Order: ION Fraud Documents';
        $this->admintemplate->addJS(base_url('assets/backend/js/cpl-document.js'));
        $this->admintemplate->show("order/home", "ion_fraud_document", $data);
    }

    public function get_ion_fraud_document_list()
    {
        $params = array();

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['is_escrow'] = 0;
            $pageno = ($params['start'] / $params['length']) + 1;
            $ion_fraud_document_list = $this->home_model->get_ion_fraud_document_list($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $ion_fraud_document_list = $this->home_model->get_ion_fraud_document_list($params);
        }

        $data = array();
        // echo "<pre>";
        // print_r($ion_fraud_document_list);die;
        if (isset($ion_fraud_document_list['data']) && !empty($ion_fraud_document_list['data'])) {
            $i = $params['start'] + 1;
            foreach ($ion_fraud_document_list['data'] as $key => $value) {
                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $value['lp_file_number'];
                $nestedData[] = $value['document_name'];
                $documentName = $value['document_name'];
                if ($value['api_document_id'] > 0) {
                    $nestedData[] = 'Yes';
                } else {
                    $nestedData[] = 'No';
                }

                $nestedData[] = convertTimezone($value['created']);

                if (env('AWS_ENABLE_FLAG') == 1) {

                    if (str_contains(strtolower($documentName), 'fraud')) {
                        $documentUrl = env('AWS_PATH') . "ion-fraud/report/" . $documentName;
                    } else {
                        $documentUrl = env('AWS_PATH') . "ion-fraud/letter/" . $documentName;
                    }
                    // $documentLetterUrl = env('AWS_PATH') . "ion-fraud/letter/" . $value['lp_file_number'] . '-Letter.pdf';

                    if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                        $nestedData[] = "<div style='display:flex;'><a href='#' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"ion-fraud"' . ");'><i class='fas fa-fw fa-download'></i></a>
                        <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                        // $nestedData[] = "<div style='display:flex;'><a href='#' onclick='downloadDocumentFromAws(" . '"' . $documentLetterUrl . '"' . ", " . '"ion-fraud"' . ");'><i class='fas fa-fw fa-download'></i></a>
                        // <a style='margin-left:10px;' target='_blank' href='$documentLetterUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                    }
                } else {
                    $documentUrl = base_url() . "uploads/ion-fraud/report" . $documentName;
                    if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                        $nestedData[] = "<div style='display:flex;'><a href='$documentUrl' download><i class='fas fa-fw fa-download'></i></a>
                        <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                        // $nestedData[] = "<div style='display:flex;'><a href='$documentLetterUrl' download><i class='fas fa-fw fa-download'></i></a>
                        // <a style='margin-left:10px;' target='_blank' href='$documentLetterUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                    }
                }
                if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $nestedData[] = "<div style='display:flex;'><a href='$documentUrl' download><i class='fas fa-fw fa-download'></i></a>
                    <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                    // $nestedData[] = "<div style='display:flex;'><a href='$documentLetterUrl' download><i class='fas fa-fw fa-download'></i></a>
                    // <a style='margin-left:10px;' target='_blank' href='$documentLetterUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                }
                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($ion_fraud_document_list['recordsTotal']);
        $json_data['recordsFiltered'] = intval($ion_fraud_document_list['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function cpl_document()
    {
        $data = array();
        $data['title'] = 'PCT Order: CPL Documents';
        $this->admintemplate->addJS(base_url('assets/backend/js/cpl-document.js'));
        $this->admintemplate->show("order/home", "cpl_document", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/cpl_document', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_cpl_document_list()
    {
        $params = array();

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['is_escrow'] = 0;
            $pageno = ($params['start'] / $params['length']) + 1;
            $cpl_document_list = $this->home_model->get_cpl_document_list($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $cpl_document_list = $this->home_model->get_cpl_document_list($params);
        }

        $data = array();

        if (isset($cpl_document_list['data']) && !empty($cpl_document_list['data'])) {
            $i = $params['start'] + 1;
            foreach ($cpl_document_list['data'] as $key => $value) {
                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $value['file_number'];
                $nestedData[] = $value['document_name'];
                $documentName = $value['document_name'];
                if ($value['api_document_id'] > 0) {
                    $nestedData[] = 'Yes';
                } else {
                    $nestedData[] = 'No';
                }
                // $nestedData[] = date("m/d/Y h:i:s A", strtotime($value['created']));
                $nestedData[] = convertTimezone($value['created']);

                if (env('AWS_ENABLE_FLAG') == 1) {

                    $documentUrl = env('AWS_PATH') . "documents/" . $documentName;

                    if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                        $nestedData[] = "<div style='display:flex;'><a href='#' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"cpl"' . ");'><i class='fas fa-fw fa-download'></i></a>
                        <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                    }
                } else {
                    $documentUrl = base_url() . "uploads/documents/" . $documentName;
                    if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                        $nestedData[] = "<div style='display:flex;'><a href='$documentUrl' download><i class='fas fa-fw fa-download'></i></a>
                        <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                    }
                }
                if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $nestedData[] = "<div style='display:flex;'><a href='$documentUrl' download><i class='fas fa-fw fa-download'></i></a>
                    <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                }
                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($cpl_document_list['recordsTotal']);
        $json_data['recordsFiltered'] = intval($cpl_document_list['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function newUsers()
    {
        $data = array();
        $data['title'] = 'PCT Order: New Users';
        $this->admintemplate->show("order/home", "new_users", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/new_users', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_new_users_list()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $pageno = ($params['start'] / $params['length']) + 1;
            $new_users_lists = $this->home_model->get_new_users_list($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $new_users_lists = $this->home_model->get_new_users_list($params);
        }

        $data = array();
        if (isset($new_users_lists['data']) && !empty($new_users_lists['data'])) {
            foreach ($new_users_lists['data'] as $key => $value) {
                $nestedData = array();
                if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $nestedData[] = $value['first_name'];
                    $nestedData[] = $value['last_name'];
                    $nestedData[] = $value['email_address'];
                    $nestedData[] = $value['company_name'];
                    $nestedData[] = 'Pacific1';
                    $nestedData[] = $value['random_password'];
                } else {
                    $nestedData[] = $value['email_address'];
                    $nestedData[] = 'Pacific1';
                    $nestedData[] = $value['random_password'];
                }
                $data[] = $nestedData;
            }
        }

        $json_data['recordsTotal'] = intval($new_users_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($new_users_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function addNewUser()
    {
        $this->load->model('order/apiLogs');
        $userdata = $this->session->userdata('admin');
        $data = array();
        $data['title'] = 'PCT Order: Add New User';
        $salesRepData = array();

        if ($this->input->post()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'required', array('required' => 'Please Enter First Name'));
            $this->form_validation->set_rules('last_name', 'Last Name', 'required', array('required' => 'Please Enter Last Name'));
            $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email', array('required' => 'Please Enter Email', 'valid_email' => 'Please enter valid Email'));
            $this->form_validation->set_rules('company', 'Company', 'required', array('required' => 'Please Enter Company'));
            $this->form_validation->set_rules('user_type', 'User type', 'required', array('required' => 'Please Select User Type'));
            $this->form_validation->set_rules('address', 'Address', 'required', array('required' => 'Please Enter Address'));
            $this->form_validation->set_rules('city', 'City', 'required', array('required' => 'Please Enter City'));
            $this->form_validation->set_rules('state', 'State', 'required', array('required' => 'Please Enter State'));
            $this->form_validation->set_rules('zipcode', 'Zipcode', 'required', array('required' => 'Please Enter Zipcode'));
            $this->form_validation->set_rules('partner_id', 'Company', 'required', array('required' => 'Please select company based on search'));

            if ($this->form_validation->run() == true) {
                $userType = $this->input->post('user_type');
                if ($userType == 'realtor') {
                    $this->load->model('order/agent_model');
                    $agentData = array(
                        'name' => $this->input->post('first_name') . " " . $this->input->post('last_name'),
                        'email_address' => $this->input->post('email_address'),
                        'telephone_no' => $this->input->post('telephone_no'),
                        'company' => $this->input->post('company'),
                        'address' => $this->input->post('address'),
                        'city' => $this->input->post('city'),
                        'zipcode' => $this->input->post('zipcode'),
                        'is_listing_agent' => 0,
                        'status' => 1,
                    );

                    $condition = array(
                        'where' => array(
                            'partner_id' => $this->input->post('partner_id'),
                            'partner_employee_id' => $this->input->post('resware_client_id'),
                        ),
                        'returnType' => 'count',
                    );
                    $prevCount = $this->agent_model->get_rows($condition);

                    if ($prevCount > 0) {
                        $updateCondition = array(
                            'partner_id' => $this->input->post('partner_id'),
                            'partner_employee_id' => $this->input->post('resware_client_id'),
                        );
                        $update = $this->agent_model->update($agentData, $updateCondition);
                    } else {
                        $agentData['partner_id'] = $this->input->post('partner_id');
                        $agentData['partner_employee_id'] = $this->input->post('resware_client_id');
                        $insert = $this->agent_model->insert($agentData);
                    }
                } else {
                    if ($userType == 'escrow') {
                        $userTypeFlag = 1;
                    } else if ($userType == 'lender') {
                        $userTypeFlag = 0;
                    } else if ($userType == 'mortgage_broker') {
                        $userTypeFlag = 2;
                    }

                    $customerData = array(
                        'partner_id' => $this->input->post('partner_id'),
                        'resware_user_id' => !empty($this->input->post('resware_client_id')) ? $this->input->post('resware_client_id') : 0,
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'telephone_no' => $this->input->post('telephone_no'),
                        'email_address' => $this->input->post('email_address'),
                        'password' => 'Pacific1',
                        'company_name' => $this->input->post('company'),
                        'street_address' => $this->input->post('address'),
                        'city' => $this->input->post('city'),
                        'state' => $this->input->post('state'),
                        'zip_code' => $this->input->post('zipcode'),
                        'is_escrow' => $userTypeFlag,
                        'is_master' => 0,
                        'is_password_updated' => 0,
                        'is_new_user' => 1,
                        'status' => 1,
                    );
                    $response = $this->addNewUserToResware($customerData);

                    if ($response['success']) {
                        $customerData['resware_user_id'] = $response['resware_user_id'];
                        $customerData['random_password'] = $this->order->randomPassword();
                        $reswareUpdatePwdData = array(
                            'user_name' => $this->input->post('email_address'),
                            'password' => 'Pacific1',
                            'new_password' => $customerData['random_password'],
                        );
                        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'change_password', env('RESWARE_UPDATE_PWD_API'), $reswareUpdatePwdData, array(), 0, 0);
                        $updatePwdResult = $this->updatePasswordResware($reswareUpdatePwdData);
                        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'change_password', env('RESWARE_UPDATE_PWD_API'), $reswareUpdatePwdData, $updatePwdResult, 0, $logid);
                        $responsePwd = json_decode($updatePwdResult, true);

                        if (!empty($responsePwd['message'])) {
                            $customerData['resware_error_msg'] = $responsePwd['message'];
                            $data['error_msg'] = 'Password update failed due to: ' . $responsePwd['message'];

                        } else {
                            $customerData['is_password_updated'] = 1;
                            $data['success_msg'] = 'Password updated successfully for email user: ' . $this->input->post('email_address');
                        }

                        if (!empty($this->input->post('resware_client_id'))) {
                            $condition = array(
                                'where' => array(
                                    'partner_id' => $this->input->post('partner_id'),
                                    'resware_user_id' => $this->input->post('resware_client_id'),
                                ),
                                'returnType' => 'count',
                            );
                            $prevCount = $this->home_model->get_rows($condition);
                            if ($prevCount > 0) {
                                $updateCondition = array(
                                    'partner_id' => $this->input->post('partner_id'),
                                    'resware_user_id' => $this->input->post('resware_client_id'),
                                );
                                $update = $this->home_model->update($customerData, $updateCondition);
                                /** Save user Activity */
                                $activity = 'New user updated :- ' . $this->input->post('email_address');
                                $this->order->logAdminActivity($activity);
                                /** End Save user activity */
                            } else {
                                $insert = $this->home_model->insert($customerData);
                                /** Save user Activity */
                                $activity = 'New user created :- ' . $this->input->post('email_address');
                                $this->order->logAdminActivity($activity);
                                /** End Save user activity */
                            }
                        } else {
                            $insert = $this->home_model->insert($customerData);
                            /** Save user Activity */
                            $activity = 'New user created :- ' . $this->input->post('email_address');
                            $this->order->logAdminActivity($activity);
                            /** End Save user activity */
                        }

                    } else {
                        $data['error_msg'] = $response['msg'];
                    }
                }
            } else {
                $data['first_name_error_msg'] = form_error('first_name');
                $data['last_name_error_msg'] = form_error('last_name');
                $data['email_address_error_msg'] = form_error('email_address');
                $data['company_error_msg'] = form_error('company');
                $data['user_type_error_msg'] = form_error('user_type');
                $data['address_error_msg'] = form_error('address');
                $data['city_error_msg'] = form_error('city');
                $data['state_error_msg'] = form_error('state');
                $data['zipcode_error_msg'] = form_error('zipcode');
                if (empty(form_error('company')) && !empty(form_error('partner_id'))) {
                    $data['company_error_msg'] = form_error('partner_id');
                }
            }
        }
        $this->admintemplate->addCSS(base_url('assets/frontend/css/smart-forms.css'));
        $this->admintemplate->addCSS(base_url('assets/frontend/css/jquery-ui.css'));
        $this->admintemplate->addJS(base_url('assets/libs/jquery-1.12.4.min.js'));
        $this->admintemplate->addJS(base_url('assets/frontend/js/jquery-ui.min.js'));
        $this->admintemplate->addJS(base_url('assets/backend/js/add-new-user.js'));
        $this->admintemplate->show("order/home", "add_new_user", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/add_new_user', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_company_list()
    {
        $searchTerm = isset($_POST['term']) && !empty($_POST['term']) ? $_POST['term'] : '';
        $condition = array(
            'partner_name' => $searchTerm,
        );
        $companyDetails = $this->home_model->get_company_list($condition);
        $companyInfo = array();

        if (isset($companyDetails) && !empty($companyDetails)) {
            foreach ($companyDetails as $key => $value) {
                $data['id'] = isset($value['id']) && !empty($value['id']) ? $value['id'] : '';
                $data['value'] = isset($value['value']) && !empty($value['value']) ? $value['value'] : '';
                $data['partner_id'] = isset($value['partner_id']) && !empty($value['partner_id']) ? $value['partner_id'] : '';
                $data['partner_name'] = isset($value['partner_name']) && !empty($value['partner_name']) ? $value['partner_name'] : '';
                $data['address1'] = isset($value['address1']) && !empty($value['address1']) ? $value['address1'] : '';
                $data['city'] = isset($value['city']) && !empty($value['city']) ? $value['city'] : '';
                $data['state'] = isset($value['state']) && !empty($value['state']) ? $value['state'] : '';
                $data['zip'] = isset($value['zip']) && !empty($value['zip']) ? $value['zip'] : '';
                $companyInfo[] = $data;
            }
        }
        echo json_encode($companyInfo);
    }

    public function get_title_company_list()
    {
        $searchTerm = isset($_POST['term']) && !empty($_POST['term']) ? $_POST['term'] : '';
        $condition = array(
            'partner_name' => $searchTerm,
        );
        $companyDetails = $this->home_model->get_title_company_list($condition);
        $companyInfo = array();

        if (isset($companyDetails) && !empty($companyDetails)) {
            foreach ($companyDetails as $key => $value) {
                $data['id'] = isset($value['id']) && !empty($value['id']) ? $value['id'] : '';
                $data['value'] = isset($value['value']) && !empty($value['value']) ? $value['value'] : '';
                $data['partner_id'] = isset($value['partner_id']) && !empty($value['partner_id']) ? $value['partner_id'] : '';
                $data['partner_name'] = isset($value['partner_name']) && !empty($value['partner_name']) ? $value['partner_name'] : '';
                $data['address1'] = isset($value['address1']) && !empty($value['address1']) ? $value['address1'] : '';
                $data['city'] = isset($value['city']) && !empty($value['city']) ? $value['city'] : '';
                $data['state'] = isset($value['state']) && !empty($value['state']) ? $value['state'] : '';
                $data['zip'] = isset($value['zip']) && !empty($value['zip']) ? $value['zip'] : '';
                $companyInfo[] = $data;
            }
        }
        echo json_encode($companyInfo);
    }

    public function addNewUserToResware($customerData)
    {
        $this->load->model('order/apiLogs');
        $this->load->library('order/resware');
        $this->load->library('order/order');
        $userdata = $this->session->userdata('admin');

        if (!empty($customerData['resware_user_id'])) {
            $endPoint = 'admin/partners/' . $customerData['partner_id'] . '/employees/' . $customerData['resware_user_id'];
            $method = 'PUT';
            $apiType = 'update_user';
        } else {
            $endPoint = 'admin/partners/' . $customerData['partner_id'] . '/employees';
            $method = 'POST';
            $apiType = 'create_user';
        }

        $newUserData = array(
            'Password' => 'Pacific1',
            'Enabled' => true,
            'Roles' => array(
                0 => array(
                    'RoleID' => 5033,
                    'Name' => 'Web Services: Access All Files for ResWare-to-ResWare Services',
                ),
                1 => array(
                    'RoleID' => 6013,
                    'Name' => 'Web Services: Add Actions',
                ),
                2 => array(
                    'RoleID' => 6005,
                    'Name' => 'Web Services: Add Documents',
                ),
                3 => array(
                    'RoleID' => 6002,
                    'Name' => 'Web Services: Add Notes',
                ),
                4 => array(
                    'RoleID' => 6009,
                    'Name' => 'Web Services: Add Partners',
                ),
                5 => array(
                    'RoleID' => 6015,
                    'Name' => 'Web Services: Add WebURL Documents',
                ),
                6 => array(
                    'RoleID' => 5027,
                    'Name' => 'Web Services: Bypass Address Validation',
                ),
                7 => array(
                    'RoleID' => 6003,
                    'Name' => 'Web Services: Cancel Files',
                ),
                8 => array(
                    'RoleID' => 5023,
                    'Name' => 'Web Services: Estimate Costs as 2010 HUD',
                ),
                9 => array(
                    'RoleID' => 6016,
                    'Name' => 'Web Services: Expense Reports',
                ),
                10 => array(
                    'RoleID' => 6012,
                    'Name' => 'Web Services: Get Actions',
                ),
                11 => array(
                    'RoleID' => 6007,
                    'Name' => 'Web Services: Get Custom Fields',
                ),
                12 => array(
                    'RoleID' => 6006,
                    'Name' => 'Web Services: Get Documents',
                ),
                13 => array(
                    'RoleID' => 6001,
                    'Name' => 'Web Services: Get Notes',
                ),
                14 => array(
                    'RoleID' => 6010,
                    'Name' => 'Web Services: Get Partners',
                ),
                15 => array(
                    'RoleID' => 69,
                    'Name' => 'Web Services: Order Placement',
                ),
                16 => array(
                    'RoleID' => 6004,
                    'Name' => 'Web Services: Override Property Address Validation and Reformatting',
                ),
                17 => array(
                    'RoleID' => 6011,
                    'Name' => 'Web Services: Remove Partners',
                ),
                18 => array(
                    'RoleID' => 6014,
                    'Name' => 'Web Services: Search Files',
                ),
                19 => array(
                    'RoleID' => 6019,
                    'Name' => 'Web Services: Update Partner',
                ),
                20 => array(
                    'RoleID' => 6008,
                    'Name' => 'Web Services: Write Custom Fields',
                ),
                21 => array(
                    'RoleID' => 51,
                    'Name' => 'Website',
                ),
            ),
            'WebsiteAccess' => true,
            'Name' => $customerData['email_address'],
            'PasswordExpirationDate' => '/Date(3025656585000-0000)/',
            'FirstName' => $customerData['first_name'],
            'LastName' => $customerData['last_name'],
            'ContactInformation' => array(
                'EmailAddress' => $customerData['email_address'],
            ),
        );

        $userdata['email'] = $userdata['email_address'];
        $userdata['admin_api'] = 1;
        $newUserData = json_encode($newUserData);
        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', $apiType, env('RESWARE_ORDER_API') . $endPoint, $newUserData, array(), 0, 0);
        $result = $this->resware->make_request($method, $endPoint, $newUserData, $userdata);
        $this->apiLogs->syncLogs($v['id'], 'resware', $apiType, env('RESWARE_ORDER_API') . $endPoint, $newUserData, $result, 0, $logid);

        /* Start add resware api logs */
        $reswareLogData = array(
            'request_type' => 'add_new_user_to_resware',
            'request_url' => env('RESWARE_ORDER_API') . $endPoint,
            'request' => $newUserData,
            'response' => $result,
            'status' => '',
            'created_at' => date("Y-m-d H:i:s"),
        );
        $this->db->insert('pct_resware_log', $reswareLogData);
        /* End add resware api logs */

        if (isset($result) && !empty($result)) {
            $response = json_decode($result, true);
            if (isset($response['Employee']) && !empty($response['Employee'])) {
                $res = array(
                    'resware_user_id' => $response['Employee']['UserID'],
                    'msg' => !empty($customerData['resware_user_id']) ? 'User created successfully on Resware Side' : 'User updated successfully on Resware Side',
                    'success' => true,
                );
            } else {
                $res = array(
                    'resware_user_id' => 0,
                    'msg' => $response['ResponseStatus']['Message'],
                    'success' => false,
                );
            }
        } else {
            $res = array(
                'resware_user_id' => 0,
                'msg' => 'Something wrong! Please try again',
                'success' => false,
            );
        }
        return $res;
    }

    public function grant_deed_document()
    {
        $data = array();
        $data['title'] = 'PCT Order: Grant Deed Documents';
        $this->admintemplate->addJS("https://sdk.amazonaws.com/js/aws-sdk-2.895.0.min.js");
        $this->admintemplate->addJS(base_url('assets/backend/js/cpl-document.js'));
        $this->admintemplate->show("order/home", "grant_deed_document", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/grant_deed_document', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function lv_document()
    {
        $data = array();
        $data['title'] = 'PCT Order: Legal & Vesting Documents';
        $this->admintemplate->addJS("https://sdk.amazonaws.com/js/aws-sdk-2.895.0.min.js");
        $this->admintemplate->addJS(base_url('assets/backend/js/cpl-document.js'));
        $this->admintemplate->show("order/home", "lv_document", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/lv_document', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_grant_deed_document_list()
    {
        $params = array();

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['is_escrow'] = 0;
            $pageno = ($params['start'] / $params['length']) + 1;
            $grant_document_lists = $this->home_model->get_grant_deed_document_list($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $grant_document_lists = $this->home_model->get_grant_deed_document_list($params);
        }

        $data = array();

        if (isset($grant_document_lists['data']) && !empty($grant_document_lists['data'])) {
            $i = $params['start'] + 1;
            foreach ($grant_document_lists['data'] as $key => $value) {
                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $value['file_number'] ? $value['file_number'] : $value['lp_file_number'];
                $nestedData[] = $value['document_name'];
                $documentName = $value['document_name'];
                if ($value['api_document_id'] > 0) {
                    $nestedData[] = 'Yes';
                } else {
                    $nestedData[] = 'No';
                }
                $nestedData[] = convertTimezone($value['created']);
                // $nestedData[] = date("m/d/Y h:i:s A", strtotime($value['created']));
                if (env('AWS_ENABLE_FLAG') == 1) {
                    $documentUrl = env('AWS_PATH') . "grant-deed/" . $documentName;
                    if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                        $nestedData[] = "<div style='display:flex;'><a href='#' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"grant_deed"' . ");'><i class='fas fa-fw fa-download'></i></a>
                        <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                    }
                } else {
                    $documentUrl = base_url() . "uploads/grant-deed/" . $documentName;
                    if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                        $nestedData[] = "<div style='display:flex;'><a href='$documentUrl' download><i class='fas fa-fw fa-download'></i></a>
                        <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                    }
                }

                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($grant_document_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($grant_document_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function get_lv_document_list()
    {
        $params = array();

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['is_escrow'] = 0;
            $pageno = ($params['start'] / $params['length']) + 1;
            $lv_document_lists = $this->home_model->get_lv_document_list($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $lv_document_lists = $this->home_model->get_lv_document_list($params);
        }

        $data = array();

        if (isset($lv_document_lists['data']) && !empty($lv_document_lists['data'])) {
            $i = $params['start'] + 1;
            foreach ($lv_document_lists['data'] as $key => $value) {
                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $value['file_number'] ? $value['file_number'] : $value['lp_file_number'];
                $nestedData[] = $value['document_name'];
                $documentName = $value['document_name'];
                if ($value['api_document_id'] > 0) {
                    $nestedData[] = 'Yes';
                } else {
                    $nestedData[] = 'No';
                }

                // $nestedData[] = date("m/d/Y h:i:s A", strtotime($value['created']));
                $nestedData[] = convertTimezone($value['created']);
                if (env('AWS_ENABLE_FLAG') == 1) {
                    $documentUrl = env('AWS_PATH') . "legal-vesting/" . $documentName;
                    if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                        $nestedData[] = "<div style='display:flex;'><a href='#' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"legal_vesting"' . ");'><i class='fas fa-fw fa-download'></i></a>
                        <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                    }
                } else {
                    $documentUrl = base_url() . "uploads/legal-vesting/" . $documentName;
                    if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                        $nestedData[] = "<div style='display:flex;'><a href='$documentUrl' download><i class='fas fa-fw fa-download'></i></a>
                        <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                    }
                }

                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($lv_document_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($lv_document_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function masterUsers()
    {
        $data = array();
        $data['title'] = 'PCT Order: Master Users';
        $this->admintemplate->show("order/home", "master_users", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/master_users', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_master_users_list()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $pageno = ($params['start'] / $params['length']) + 1;
            $master_users_lists = $this->home_model->get_master_users_list($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $master_users_lists = $this->home_model->get_master_users_list($params);
        }

        $data = array();
        if (isset($master_users_lists['data']) && !empty($master_users_lists['data'])) {
            foreach ($master_users_lists['data'] as $key => $value) {
                $nestedData = array();
                $nestedData[] = $value['first_name'];
                $nestedData[] = $value['last_name'];
                $nestedData[] = $value['email_address'];
                $nestedData[] = $value['street_address'] . ", " . $value['city'] . ", " . $value['state'] . ", " . $value['zip_code'];
                if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $editUrl = base_url() . 'order/admin/edit-master-user/' . $value['id'];
                    $nestedData[] = "<div style='display: flex;justify-content: space-evenly;' ><a href='" . $editUrl . "'   title='Edit Master User'><span class='fas fa-edit' aria-hidden='true'></span></a><a href='javascript:void(0);' onclick='deleteMasterUser(" . $value['id'] . ")'  title='Delete Master User'><span class='fas fa-trash' aria-hidden='true'></span></a></div>";
                }
                $data[] = $nestedData;
            }
        }

        $json_data['recordsTotal'] = intval($master_users_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($master_users_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function addNewMasterUser()
    {
        $data = array();
        $data['title'] = 'PCT Order: Add New Master User';
        $salesRepData = array();
        $this->db->select('*')
            ->from('pct_order_partner_company_info');

        $query = $this->db->get();
        $data['companys'] = $query->result_array();

        if ($this->input->post()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'required', array('required' => 'Please Enter First Name'));
            $this->form_validation->set_rules('last_name', 'Last Name', 'required', array('required' => 'Please Enter Last Name'));
            $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email|is_unique[customer_basic_details.email_address]', array('required' => 'Please Enter Email', 'valid_email' => 'Please enter valid Email', 'is_unique' => 'The %s is already taken'));
            $this->form_validation->set_rules('company', 'Company', 'required', array('required' => 'Please Enter Company'));
            $this->form_validation->set_rules('address', 'Address', 'required', array('required' => 'Please Enter Address'));
            $this->form_validation->set_rules('city', 'City', 'required', array('required' => 'Please Enter City'));
            $this->form_validation->set_rules('state', 'State', 'required', array('required' => 'Please Enter State'));
            $this->form_validation->set_rules('zipcode', 'Zipcode', 'required', array('required' => 'Please Enter Zipcode'));

            if ($this->form_validation->run() == true) {
                $customerData = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'telephone_no' => $this->input->post('telephone_no'),
                    'email_address' => $this->input->post('email_address'),
                    'password' => 'Pacific1',
                    'company_name' => $this->input->post('company'),
                    'street_address' => $this->input->post('address'),
                    'city' => $this->input->post('city'),
                    'state' => $this->input->post('state'),
                    'zip_code' => $this->input->post('zipcode'),
                    'partner_companies' => implode(",", $this->input->post('partner_companies')),
                    'is_escrow' => 0,
                    'is_master' => 1,
                    'is_password_updated' => 1,
                    'is_new_user' => 0,
                    'status' => 1,
                );
                $insert = $this->home_model->insert($customerData);
                if ($insert) {
                    /** Save user Activity */
                    $activity = 'Master user created :- ' . $this->input->post('email_address');
                    $this->order->logAdminActivity($activity);
                    /** End Save user activity */
                    $data['success_msg'] = 'Master User added successfully.';
                    $this->form_validation->reset_validation();
                } else {
                    $data['error_msg'] = 'User not added.';
                }
            } else {
                $data['first_name_error_msg'] = form_error('first_name');
                $data['last_name_error_msg'] = form_error('last_name');
                $data['email_address_error_msg'] = form_error('email_address');
                $data['company_error_msg'] = form_error('company');
                $data['address_error_msg'] = form_error('address');
                $data['city_error_msg'] = form_error('city');
                $data['state_error_msg'] = form_error('state');
                $data['zipcode_error_msg'] = form_error('zipcode');
            }
        }
        $this->admintemplate->show("order/home", "add_new_master_user", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/add_new_master_user', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function editMasterUser()
    {
        $data = array();
        $data['title'] = 'PCT Order: Edit Master User';
        $id = $this->uri->segment(4);
        $this->db->select('*')
            ->from('pct_order_partner_company_info');

        $query = $this->db->get();
        $data['companys'] = $query->result_array();

        if (isset($id) && !empty($id)) {
            if ($this->input->post()) {
                $this->form_validation->set_rules('first_name', 'First Name', 'required', array('required' => 'Please Enter First Name'));
                $this->form_validation->set_rules('last_name', 'Last Name', 'required', array('required' => 'Please Enter Last Name'));
                $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email', array('required' => 'Please Enter Email', 'valid_email' => 'Please enter valid Email'));
                $this->form_validation->set_rules('company', 'Company', 'required', array('required' => 'Please Enter Company'));
                $this->form_validation->set_rules('address', 'Address', 'required', array('required' => 'Please Enter Address'));
                $this->form_validation->set_rules('city', 'City', 'required', array('required' => 'Please Enter City'));
                $this->form_validation->set_rules('state', 'State', 'required', array('required' => 'Please Enter State'));
                $this->form_validation->set_rules('zipcode', 'Zipcode', 'required', array('required' => 'Please Enter Zipcode'));

                if ($this->form_validation->run() == true) {
                    $customerData = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'telephone_no' => $this->input->post('telephone_no'),
                        'email_address' => $this->input->post('email_address'),
                        'password' => 'Pacific1',
                        'company_name' => $this->input->post('company'),
                        'street_address' => $this->input->post('address'),
                        'city' => $this->input->post('city'),
                        'state' => $this->input->post('state'),
                        'zip_code' => $this->input->post('zipcode'),
                        'partner_companies' => implode(",", $this->input->post('partner_companies')),
                        'is_escrow' => 0,
                        'is_master' => 1,
                        'is_password_updated' => 1,
                        'is_new_user' => 0,
                        'status' => 1,
                    );

                    $updateCondition = array(
                        'id' => $id,
                    );
                    $update = $this->home_model->update($customerData, $updateCondition);
                    if ($update) {
                        /** Save user Activity */
                        // $user =  $this->home_model->get_user($condition);
                        $activity = 'Master user :- ' . $this->input->post('email_address') . ' details updated';
                        $this->order->logAdminActivity($activity);
                        /** End Save user activity */
                        $data['success_msg'] = 'Master User updated successfully.';
                        $this->form_validation->reset_validation();
                    } else {
                        $data['error_msg'] = 'Master User not updated.';
                    }
                } else {
                    $data['first_name_error_msg'] = form_error('first_name');
                    $data['last_name_error_msg'] = form_error('last_name');
                    $data['email_address_error_msg'] = form_error('email_address');
                    $data['company_error_msg'] = form_error('company');
                    $data['address_error_msg'] = form_error('address');
                    $data['city_error_msg'] = form_error('city');
                    $data['state_error_msg'] = form_error('state');
                    $data['zipcode_error_msg'] = form_error('zipcode');
                }
            }
            $con = array('id' => $id);
            $data['master_user_info'] = $this->home_model->get_rows($con);
        }
        $this->admintemplate->show("order/home", "edit_master_user", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/edit_master_user', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function tax_document()
    {
        $data = array();
        $data['title'] = 'PCT Order: Tax Documents';
        $this->admintemplate->addJS(base_url('assets/backend/js/cpl-document.js'));
        $this->admintemplate->show("order/home", "tax_document", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/tax_document', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_tax_document_list()
    {
        $params = array();

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['is_escrow'] = 0;
            $pageno = ($params['start'] / $params['length']) + 1;
            $tax_document_lists = $this->home_model->get_tax_document_list($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $tax_document_lists = $this->home_model->get_tax_document_list($params);
        }

        $data = array();

        if (isset($tax_document_lists['data']) && !empty($tax_document_lists['data'])) {
            $i = $params['start'] + 1;
            foreach ($tax_document_lists['data'] as $key => $value) {
                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $value['file_number'] ? $value['file_number'] : $value['lp_file_number'];
                $nestedData[] = $value['document_name'];
                $documentName = $value['document_name'];
                if ($value['api_document_id'] > 0) {
                    $nestedData[] = 'Yes';
                } else {
                    $nestedData[] = 'No';
                }

                // $nestedData[] = date("m/d/Y h:i:s A", strtotime($value['created']));
                $nestedData[] = convertTimezone($value['created']);
                if (env('AWS_ENABLE_FLAG') == 1) {
                    $documentUrl = env('AWS_PATH') . "tax/" . $documentName;
                    if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                        $nestedData[] = "<div style='display:flex;'><a href='#' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"tax"' . ");'><i class='fas fa-fw fa-download'></i></a>
                        <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                    }
                } else {
                    $documentUrl = base_url() . "uploads/tax/" . $documentName;
                    if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                        $nestedData[] = "<div style='display:flex;'><a href='$documentUrl' download><i class='fas fa-fw fa-download'></i></a>
                        <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                    }
                }

                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($tax_document_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($tax_document_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function curative_document()
    {
        $data = array();
        $data['title'] = 'PCT Order: Curative Documents';
        $this->admintemplate->addJS(base_url('assets/backend/js/cpl-document.js'));
        $this->admintemplate->show("order/home", "curative_document", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/curative_document', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_curative_document_list()
    {
        $params = array();

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['is_escrow'] = 0;
            $pageno = ($params['start'] / $params['length']) + 1;
            $curative_document_lists = $this->home_model->get_curative_document_list($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $curative_document_lists = $this->home_model->get_curative_document_list($params);
        }

        $data = array();

        if (isset($curative_document_lists['data']) && !empty($curative_document_lists['data'])) {
            $i = $params['start'] + 1;
            foreach ($curative_document_lists['data'] as $key => $value) {
                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $value['file_number'];
                $nestedData[] = $value['document_name'];
                $documentName = $value['document_name'];
                if ($value['api_document_id'] > 0) {
                    $nestedData[] = 'Yes';
                } else {
                    $nestedData[] = 'No';
                }

                // $nestedData[] = date("m/d/Y h:i:s A", strtotime($value['created']));
                $nestedData[] = convertTimezone($value['created']);
                if (env('AWS_ENABLE_FLAG') == 1) {
                    $documentUrl = env('AWS_PATH') . "curative/" . $documentName;
                    if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                        $nestedData[] = "<div style='display:flex;'><a href='#' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"curative"' . ");'><i class='fas fa-fw fa-download'></i></a>
                        <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                    }
                } else {
                    $documentUrl = base_url() . "uploads/curative/" . $documentName;
                    if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                        $nestedData[] = "<div style='display:flex;'><a href='$documentUrl' download><i class='fas fa-fw fa-download'></i></a>
                        <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                    }
                }

                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($curative_document_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($curative_document_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function file_document()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            if (!is_dir('uploads/file_document')) {
                mkdir('./uploads/file_document', 0777, true);
            }
            $error = '';
            if (!empty($_FILES['file']['name'])) {
                $document_name = date('YmdHis') . "_" . $_FILES['file']['name'];
                $config['upload_path'] = './uploads/file_document/';
                $config['allowed_types'] = 'doc|docx|gif|msg|pdf|tif|tiff|xls|xlsx|xml';
                $config['max_size'] = 12000;
                // $userdata = $this->session->userdata('user');
                $config['file_name'] = $document_name;
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload('file')) {
                    $error = $this->upload->display_errors();
                } else {
                    $data = $this->upload->data();
                    $contents = file_get_contents($data['full_path']);
                    $binaryData = base64_encode($contents);
                    $document_name = $data['file_name'];

                    $fileData = array(
                        'name' => $this->input->post('name'),
                        'file_path' => $document_name,
                        'description' => $this->input->post('description'),
                        'created_at' => date('Y-m-d H:i:s'),
                    );

                    $this->load->library('order/order');
                    $this->order->uploadDocumentOnAwsS3($document_name, 'file_document');
                    $this->load->model('order/fileDocument_model');
                    $inserted = $this->fileDocument_model->insert($fileData);
                    if ($inserted) {
                        $titleOfficers = $this->input->post('titleOfficers');
                        foreach ($titleOfficers as $titleOfficer) {
                            if ($titleOfficer == 'all') {
                                $this->load->model('order/title_model');
                                $titleOfficersInfo = $this->title_model->getTitleOfficers();
                                foreach ($titleOfficersInfo as $titleOfficerInfo) {
                                    $data = array(
                                        'form_id' => $inserted,
                                        'user_id' => $titleOfficerInfo['id'],
                                        'created_at' => date("Y-m-d H:i:s"),
                                    );
                                    $this->db->insert('pct_order_title_officers_forms', $data);
                                }
                            } else {
                                $data = array(
                                    'form_id' => $inserted,
                                    'user_id' => $titleOfficer,
                                    'created_at' => date("Y-m-d H:i:s"),
                                );
                                $this->db->insert('pct_order_title_officers_forms', $data);
                            }
                        }
                        $this->session->set_flashdata('success', 'File uploaded.');
                        redirect('order/admin/file-documents');
                    }
                }
            } else {
                $error = 'Please slect file to uplaod';
            }
            if ($error == '') {
                $error = 'Something went wrong. Please try again';
            }
            $this->session->set_flashdata('error', $error);
            redirect('order/admin/file-documents');
        }
        $data = array();
        $data['title'] = 'PCT Order: Files';
        $this->load->model('order/title_model');
        $data['titleOfficers'] = $this->title_model->getTitleOfficers();
        $this->admintemplate->addJS(base_url('assets/backend/js/cpl-document.js'));
        $this->admintemplate->show("order/home", "file_document", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/file_document', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_file_document_list()
    {
        $this->load->model('order/fileDocument_model');
        $userdata = $this->session->userdata('user');
        // $where = array('added_by'=>$userdata['id']);
        $files_data = $this->fileDocument_model->get_all();

        $tableData = array();
        foreach ($files_data as $key => $file_data) {
            $tmp_array = array();
            $tmp_array[] = ($key + 1);
            $tmp_array[] = $file_data->name;
            $tmp_array[] = $file_data->description;
            // $tmp_array[] = date('m/d/Y',strtotime($file_data->created_at));
            $tmp_array[] = convertTimezone($file_data->created_at, 'm/d/Y');
            $documentName = $file_data->file_path;
            $formId = $file_data->id;
            $documentUrl = env('AWS_PATH') . "file_document/" . $documentName;
            $action = "<div style='display:flex;'><!-- <a href='javascript::void();' onclick='editFormInfo($formId);'><i class='fas fa-fw fa-edit'></i></a>--><a href='javascript::void();' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"' . $documentName . '"' . ");'><i class='fas fa-fw fa-download'></i></a>
                <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a><a style='margin-left:10px;' href='javascript::void();' onclick='deleteForm($formId);'><i class='fas fa-fw fa-trash'></i></a></div>";
            $tmp_array[] = $action;
            $tableData[] = $tmp_array;
        }

        $json_data['recordsTotal'] = count($tableData);
        $json_data['recordsFiltered'] = count($tableData);
        $json_data['data'] = $tableData;
        echo json_encode($json_data);
    }

    public function changeLenderUserType()
    {
        $selectValue = $this->input->post('selectValue');
        $user_id = $this->input->post('user_id');
        $customerData = array('is_special_lender' => $selectValue);
        $condition = array('id' => $user_id);
        $update = $this->home_model->update($customerData, $condition);

        if ($update) {
            /** Save user Activity */
            $user = $this->home_model->get_user($condition);
            $activity = 'User type for user ' . $user['email_address'] . ' change to :- ' . (($selectValue == 1) ? 'Special' : 'Normal');
            $this->order->logAdminActivity($activity);
            /** End Save user activity */
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false));
        }
    }

    public function companies()
    {
        $data = array();
        $data['errors'] = '';
        $data['success'] = '';
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }
        $data['title'] = 'PCT Order: Companies';
        $this->admintemplate->addCSS(base_url('assets/frontend/css/smart-forms.css'));
        // $this->admintemplate->addJS( base_url('assets/backend/vendor/jquery/jquery.min.js'));
        // $this->admintemplate->addJS( base_url('assets/frontend/js/jquery-ui.min.js'));
        $this->admintemplate->addJS(base_url('assets/frontend/js/jquery-cloneya.min.js'));
        $this->admintemplate->addJS(base_url('assets/backend/js/companies.js?v=' . $this->version));
        $this->admintemplate->show("order/home", "companies", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/companies', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_companies_list()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $pageno = ($params['start'] / $params['length']) + 1;
            $company_lists = $this->home_model->get_companies_list($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $company_lists = $this->home_model->get_companies_list($params);
        }
        $data = array();
        if (isset($company_lists['data']) && !empty($company_lists['data'])) {
            $this->load->model('order/sales_model');
            $this->load->model('order/title_model');
            $sales_rep_lists = $this->sales_model->get_sales_reps(['sales_rep_enable' => 1]);
            $title_officer_lists = $this->title_model->get_title_officers([]);

            $salesRepList = '<select class="custom-select custom-select-sm form-control form-control-sm" onchange="updateTitleSalesUser(partner_id, this.value, \'sales\');" id="sales_rep" name="sales_rep">
                                    <option value="">Select Sales Rep</option>';
            if (isset($sales_rep_lists['data']) && !empty($sales_rep_lists['data'])) {
                foreach ($sales_rep_lists['data'] as $key => $sales_rep) {
                    $salesRepList .= '<option value="' . $sales_rep['id'] . '" data-partner-id="' . $sales_rep['partner_id'] . '">' . $sales_rep['first_name'] . ' ' . $sales_rep['last_name'] . '</option>';
                }
            }
            $salesRepList .= '</select>';

            $titleOfficerList = '<select class="custom-select custom-select-sm form-control form-control-sm" onchange="updateTitleSalesUser(partner_id, this.value,  \'title\' );" id="title_officer" name="title_officer">
                                    <option value="">Select Title Officer</option>';
            if (isset($title_officer_lists['data']) && !empty($title_officer_lists['data'])) {
                foreach ($title_officer_lists['data'] as $key => $title_officer) {
                    $titleOfficerList .= '<option value="' . $title_officer['id'] . '" data-partner-id="' . $title_officer['partner_id'] . '">' . $title_officer['first_name'] . ' ' . $title_officer['last_name'] . '</option>';
                }
            }
            $titleOfficerList .= '</select>';

            $i = $params['start'] + 1;
            foreach ($company_lists['data'] as $key => $value) {
                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $value['partner_id'];
                $nestedData[] = $value['partner_name'];
                $nestedData[] = $value['address1'] . ", " . $value['city'] . ", " . $value['state'] . ", " . $value['zip'];
                $salesRepSelection = $salesRepList;
                $salesRepSelection = str_replace('partner_id', $value['partner_id'], $salesRepSelection);
                if (!empty($value['sales_rep_id'])) {
                    $salesRepSelection = str_replace('value="' . $value['sales_rep_id'] . '"', 'value="' . $value['sales_rep_id'] . '" selected', $salesRepSelection);
                }

                $titleOfficerSelection = $titleOfficerList;
                $titleOfficerSelection = str_replace('partner_id', $value['partner_id'], $titleOfficerSelection);
                if (!empty($value['title_officer_id'])) {
                    $titleOfficerSelection = str_replace('value="' . $value['title_officer_id'] . '"', 'value="' . $value['title_officer_id'] . '" selected', $titleOfficerSelection);
                }

                $nestedData[] = $salesRepSelection;
                $nestedData[] = $titleOfficerSelection;
                if (!empty($value['loan_underwriter'])) {
                    $loan_underwriter = $value['loan_underwriter'];
                } else {
                    $loan_underwriter = '';
                }
                $loanUnderwriterSelection = '<select class="custom-select custom-select-sm form-control form-control-sm" onchange="updateUnderwriter(' . $value['partner_id'] . ',\'loan_underwriter\' ,this.value);" id="loan_underwriter" name="loan_underwriter">
                                    <option value="">Select</option>
                                    <option value="westcor">Westcor</option>
                                    <option value="north_american">North American</option>
                                    <option value="commonwealth">Commonwealth</option>
                                </select>';
                $loanUnderwriterSelection = str_replace('value="' . $loan_underwriter . '"', 'value="' . $loan_underwriter . '" selected', $loanUnderwriterSelection);
                $nestedData[] = $loanUnderwriterSelection;

                if (!empty($value['sales_underwriter'])) {
                    $sales_underwriter = $value['sales_underwriter'];
                } else {
                    $sales_underwriter = '';
                }
                $salesUnderwriterSelection = '<select class="custom-select custom-select-sm form-control form-control-sm" onchange="updateUnderwriter(' . $value['partner_id'] . ',\'sales_underwriter\', this.value);" id="sales_underwriter" name="sales_underwriter"><option value="">Select</option>
                                    <option value="westcor">Westcor</option>
                                    <option value="north_american">North American</option>
                                    <option value="commonwealth">Commonwealth</option>
                                </select>';
                $salesUnderwriterSelection = str_replace('value="' . $sales_underwriter . '"', 'value="' . $sales_underwriter . '" selected', $salesUnderwriterSelection);
                $nestedData[] = $salesUnderwriterSelection;
                if (!empty($value['deliverables'])) {
                    $deliverables = explode(',', $value['deliverables']);
                    $deliverablesInfo = '';
                    foreach ($deliverables as $deliverable) {
                        $deliverablesInfo .= $deliverable . "<br>";
                    }
                    $deliverablesInfo .= "<a href='javascript:void(0)' onclick='addOrUpdateDeliverables(" . $value['partner_id'] . ");'><i class='fas fa-edit'></i></a>";
                    $nestedData[] = $deliverablesInfo;
                } else {
                    $nestedData[] = "<a href='javascript:void(0)' onclick='addOrUpdateDeliverables(" . $value['partner_id'] . ")'><i class='fas fa-plus-circle'></i></a>";
                }
                $nestedData[] = "<div style='display: flex;justify-content: space-evenly;'><a href='javascript:void(0);' onclick='deleteCompany(" . $value['partner_id'] . ")' title='Delete Company'><i class='fas fa-trash' aria-hidden='true'></i></a> </div>";
                $data[] = $nestedData;
                $i++;
            }
        }

        $json_data['recordsTotal'] = intval($company_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($company_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function addCompany()
    {
        $this->load->model('order/apiLogs');
        $this->load->library('order/order');
        $this->load->library('order/resware');
        $data = array();
        $data['title'] = 'PCT Order: Add Company';
        $userdata = $this->session->userdata('admin');
        $userdata['email'] = $userdata['email_address'];
        $userdata['admin_api'] = 1;

        if ($this->input->post()) {
            $this->form_validation->set_rules('resware_company_id', 'Resware Partner Company Id', 'required', array('required' => 'Please Enter Resware Partner Company Id'));

            if ($this->form_validation->run() == true) {
                $partner_id = $this->input->post('resware_company_id');
                $endPoint = 'admin/partners/' . $partner_id;
                $userdata['email'] = $userdata['email_address'];
                $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_partner_information', env('RESWARE_ORDER_API') . $endPoint, array(), array(), 0, 0);
                $result = $this->resware->make_request('GET', $endPoint, array(), $userdata);
                $this->apiLogs->syncLogs($v['id'], 'resware', 'get_partner_information', env('RESWARE_ORDER_API') . $endPoint, array(), $result, 0, $logid);

                if (isset($result) && !empty($result)) {
                    $response = json_decode($result, true);

                    if (isset($response['AdminPartner']) && !empty($response['AdminPartner'])) {
                        $companyData = array(
                            'partner_id' => trim($response['AdminPartner']['PartnerCompanyID']),
                            'partner_name' => trim($response['AdminPartner']['PartnerName']),
                            'address1' => trim($response['AdminPartner']['MailingAddress']['Address1']),
                            'city' => trim($response['AdminPartner']['MailingAddress']['City']),
                            'state' => trim($response['AdminPartner']['MailingAddress']['State']),
                            'zip' => trim($response['AdminPartner']['MailingAddress']['Zip']),
                        );
                        $companyExist = $this->order->checkCompanyExist($partner_id);
                        if ($companyExist) {
                            $condition = array(
                                'partner_id' => $response['AdminPartner']['PartnerCompanyID'],
                            );
                            unset($companyData['partner_id']);
                            $update = $this->home_model->update($companyData, $condition, 'pct_order_partner_company_info');
                            $data['success_msg'] = 'Company information updated successfully.';
                            /** Save user Activity */
                            $activity = 'Company information updated: partner id: ' . $partner_id;
                            $this->order->logAdminActivity($activity);
                            /** End Save user activity */
                        } else {
                            $insert = $this->home_model->insert($companyData, 'pct_order_partner_company_info');
                            $data['success_msg'] = 'Company information added successfully.';
                            /** Save user Activity */
                            $activity = 'Company information added: partner id: ' . $partner_id;
                            $this->order->logAdminActivity($activity);
                            /** End Save user activity */
                        }

                    } else {
                        $data['error_msg'] = 'User is not found with this id on Resware side.';
                    }
                } else {
                    $data['error_msg'] = 'Something went wrong. Please try again.';
                }
            } else {
                $data['resware_company_id_error_msg'] = form_error('resware_company_id');
            }
        }
        $this->admintemplate->show("order/home", "add_company", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/add_company', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function deleteCompany()
    {
        $partner_id = $this->input->post('partner_id');
        if ($partner_id) {
            $this->db->select('*');
            $this->db->from('pct_order_partner_company_info');
            $this->db->where('partner_id', $partner_id);
            $query = $this->db->get();
            $result = $query->row_array();

            if (!empty($result)) {
                $customerData = array(
                    'status' => 0,
                );
                $condition = array('partner_id' => trim($partner_id));
                $update = $this->home_model->update($customerData, $condition, 'pct_order_partner_company_info');
                if ($update) {
                    $activity = 'Company: ' . $result['partner_name'] . ' deleted successfully';
                    $this->order->logAdminActivity($activity);
                    echo json_encode(['status' => 'success', 'message' => 'Company: ' . $result['partner_name'] . ' deleted successfully']);exit;
                } else {
                    echo json_encode(['status' => 'failed', 'message' => 'Something went wrong, Please contact administrative']);exit;
                }
            } else {
                echo json_encode(['status' => 'failed', 'message' => 'Recored not found']);exit;
            }
        } else {
            echo json_encode(['status' => 'failed', 'message' => 'Invalide details provided']);exit;
        }
    }

    public function primaryCheck()
    {
        $data = array();
        $data['title'] = 'PCT Order: Primary Account';
        $params = array();
        if (isset($_POST) && !empty($_POST)) {
            $keyword = $this->input->post('keyword');
            $params['keyword'] = $keyword;
        }

        $users = $this->home_model->get_user_with_duplicate_email($params);

        if (isset($users) && !empty($users)) {
            $new_users = array();
            foreach ($users as $key => $value) {
                $new_users[$value['email_address']][] = $value;
            }
        }

        $data['users'] = $new_users;
        $this->admintemplate->show("order/home", "users_check", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/users_check', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function make_customer_primary()
    {
        $this->load->model('order/apiLogs');
        $this->load->library('order/order');
        $this->load->library('order/resware');
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        $email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : '';
        $userdata = $this->session->userdata('admin');
        $userdata['email'] = $userdata['email_address'];
        $userdata['admin_api'] = 1;

        if ($id) {
            $userLists = $this->home_model->getUsersForEmail($email, $id);

            if (!empty($userLists)) {

                foreach ($userLists as $user) {

                    if (!empty($user['resware_user_id']) && !empty($user['partner_id']) && !empty($user['email_address'])) {
                        $endPoint = 'admin/partners/' . $user['partner_id'] . '/employees/' . $user['resware_user_id'];
                        $userUpdateData = array(
                            'Enabled' => false,
                            'WebsiteAccess' => false,
                            'FirstName' => $user['first_name'],
                            'LastName' => $user['last_name'],
                            'ContactInformation' => array(
                                'EmailAddress' => $user['email_address'],
                            ),
                        );
                        $userUpdateData = json_encode($userUpdateData);
                        $logid = $this->apiLogs->syncLogs($user['id'], 'resware', 'update_password', env('RESWARE_ORDER_API') . $endPoint, $userUpdateData, array(), 0, 0);
                        $result = $this->resware->make_request('PUT', $endPoint, $userUpdateData, $userdata);
                        $this->apiLogs->syncLogs($user['id'], 'resware', 'update_password', env('RESWARE_ORDER_API') . $endPoint, $userUpdateData, $result, 0, $logid);

                        if (isset($result) && !empty($result)) {
                            $response = json_decode($result, true);

                            if (isset($response['Employee']) && !empty($response['Employee'])) {
                                $condition = array(
                                    'id' => $user['id'],
                                );
                                $customerData = array(
                                    'is_password_updated' => 0,
                                    'is_new_user' => 0,
                                    'random_password' => '',
                                    'password' => 'Pacific1',
                                    'is_primary' => 0,
                                );
                                $update = $this->home_model->update($customerData, $condition, 'customer_basic_details');
                            } else {
                                $msg = 'Something went wrong. Please try again.';
                                $response = array('status' => 'error', 'message' => $msg);
                                echo json_encode($response);
                                exit;
                            }
                        } else {
                            $msg = 'Something went wrong. Please try again.';
                            $response = array('status' => 'error', 'message' => $msg);
                            echo json_encode($response);
                            exit;
                        }
                    }
                }
            }

            $params = array(
                'id' => $id,
            );
            $userInfo = $this->home_model->get_rows($params);
            $userUpdateData = array();

            if (!empty($userInfo['resware_user_id']) && !empty($userInfo['partner_id']) && !empty($userInfo['email_address'])) {
                $endPoint = 'admin/partners/' . $userInfo['partner_id'] . '/employees/' . $userInfo['resware_user_id'];
                $userUpdateData = array(
                    'Password' => 'Pacific1',
                    'Enabled' => true,
                    'Roles' => array(
                        0 => array(
                            'RoleID' => 5033,
                            'Name' => 'Web Services: Access All Files for ResWare-to-ResWare Services',
                        ),
                        1 => array(
                            'RoleID' => 6013,
                            'Name' => 'Web Services: Add Actions',
                        ),
                        2 => array(
                            'RoleID' => 6005,
                            'Name' => 'Web Services: Add Documents',
                        ),
                        3 => array(
                            'RoleID' => 6002,
                            'Name' => 'Web Services: Add Notes',
                        ),
                        4 => array(
                            'RoleID' => 6009,
                            'Name' => 'Web Services: Add Partners',
                        ),
                        5 => array(
                            'RoleID' => 6015,
                            'Name' => 'Web Services: Add WebURL Documents',
                        ),
                        6 => array(
                            'RoleID' => 5027,
                            'Name' => 'Web Services: Bypass Address Validation',
                        ),
                        7 => array(
                            'RoleID' => 6003,
                            'Name' => 'Web Services: Cancel Files',
                        ),
                        8 => array(
                            'RoleID' => 5023,
                            'Name' => 'Web Services: Estimate Costs as 2010 HUD',
                        ),
                        9 => array(
                            'RoleID' => 6016,
                            'Name' => 'Web Services: Expense Reports',
                        ),
                        10 => array(
                            'RoleID' => 6012,
                            'Name' => 'Web Services: Get Actions',
                        ),
                        11 => array(
                            'RoleID' => 6007,
                            'Name' => 'Web Services: Get Custom Fields',
                        ),
                        12 => array(
                            'RoleID' => 6006,
                            'Name' => 'Web Services: Get Documents',
                        ),
                        13 => array(
                            'RoleID' => 6001,
                            'Name' => 'Web Services: Get Notes',
                        ),
                        14 => array(
                            'RoleID' => 6010,
                            'Name' => 'Web Services: Get Partners',
                        ),
                        15 => array(
                            'RoleID' => 69,
                            'Name' => 'Web Services: Order Placement',
                        ),
                        16 => array(
                            'RoleID' => 6004,
                            'Name' => 'Web Services: Override Property Address Validation and Reformatting',
                        ),
                        17 => array(
                            'RoleID' => 6011,
                            'Name' => 'Web Services: Remove Partners',
                        ),
                        18 => array(
                            'RoleID' => 6014,
                            'Name' => 'Web Services: Search Files',
                        ),
                        19 => array(
                            'RoleID' => 6019,
                            'Name' => 'Web Services: Update Partner',
                        ),
                        20 => array(
                            'RoleID' => 6008,
                            'Name' => 'Web Services: Write Custom Fields',
                        ),
                        21 => array(
                            'RoleID' => 51,
                            'Name' => 'Website',
                        ),
                    ),
                    'WebsiteAccess' => true,
                    'Name' => $userInfo['email_address'],
                    'PasswordExpirationDate' => '/Date(3025656585000-0000)/',
                    'FirstName' => $userInfo['first_name'],
                    'LastName' => $userInfo['last_name'],
                    'ContactInformation' => array(
                        'EmailAddress' => $userInfo['email_address'],
                    ),
                );
                $userUpdateData = json_encode($userUpdateData);
                $logid = $this->apiLogs->syncLogs($userInfo['id'], 'resware', 'update_password', env('RESWARE_ORDER_API') . $endPoint, $userUpdateData, array(), 0, 0);
                $result = $this->resware->make_request('PUT', $endPoint, $userUpdateData, $userdata);
                $this->apiLogs->syncLogs($userInfo['id'], 'resware', 'update_password', env('RESWARE_ORDER_API') . $endPoint, $userUpdateData, $result, 0, $logid);

                /* Start add resware api logs */
                $reswareLogData = array(
                    'request_type' => 'update_password_to_resware_for_email_' . $userInfo['email_address'],
                    'request_url' => env('RESWARE_ORDER_API') . $endPoint,
                    'request' => $userUpdateData,
                    'response' => $result,
                    'status' => 'success',
                    'created_at' => date("Y-m-d H:i:s"),
                );
                $this->db->insert('pct_resware_log', $reswareLogData);
                /* End add resware api logs */

                if (isset($result) && !empty($result)) {
                    $response = json_decode($result, true);

                    if (isset($response['Employee']) && !empty($response['Employee'])) {
                        $random_password = $this->order->randomPassword();
                        $reswareUpdatePwdData = array(
                            'user_name' => $userInfo['email_address'],
                            'password' => 'Pacific1',
                            'new_password' => $random_password,
                        );

                        $logid = $this->apiLogs->syncLogs($userInfo['id'], 'resware', 'change_password', env('RESWARE_UPDATE_PWD_API'), $reswareUpdatePwdData, array(), 0, 0);
                        $updatePwdResult = $this->updatePasswordResware($reswareUpdatePwdData);
                        $this->apiLogs->syncLogs($userInfo['id'], 'resware', 'change_password', env('RESWARE_UPDATE_PWD_API'), $reswareUpdatePwdData, $updatePwdResult, 0, $logid);
                        $responsePwd = json_decode($updatePwdResult, true);
                        $condition = array(
                            'id' => $userInfo['id'],
                        );
                        $customerData = array(
                            'is_password_updated' => 0,
                            'random_password' => $random_password,
                            'password' => 'Pacific1',
                            'is_primary' => 1,
                        );

                        if (!empty($responsePwd['message'])) {
                            $customerData['is_password_updated'] = 0;
                            $customerData['resware_error_msg'] = $responsePwd['message'];
                            $response = array('status' => 'error', 'message' => 'Password update failed due to: ' . $responsePwd['message']);
                        } else {
                            $customerData['is_password_updated'] = 1;
                            $response = array('status' => 'success', 'message' => 'Password updated successfully for email user: ' . $userInfo['email_address']);
                            /** Save user Activity */
                            $activity = 'Password updated successfully for email user: ' . $userInfo['email_address'];
                            $this->order->logAdminActivity($activity);
                            /** End Save user activity */
                        }

                        $this->home_model->update($customerData, $condition, 'customer_basic_details');
                        echo json_encode($response);
                        exit;
                    } else {
                        $msg = $response['ResponseStatus']['Errors'][0]['Message'];
                        $condition = array(
                            'id' => $userInfo['id'],
                        );
                        $customerData = array(
                            'is_password_updated' => 0,
                            'password' => 'Pacific1',
                            'is_primary' => 1,
                            'resware_error_msg' => $msg,
                        );
                        $this->home_model->update($customerData, $condition, 'customer_basic_details');
                        $response = array('status' => 'error', 'message' => $msg);
                        echo json_encode($response);
                        exit;
                    }
                } else {
                    $msg = 'Something went wrong. Please try again.';
                    $response = array('status' => 'error', 'message' => $msg);
                    echo json_encode($response);
                    exit;
                }
            }
        } else {
            $msg = 'Customer ID is required.';
            $response = array('status' => 'error', 'message' => $msg);
        }
        echo json_encode($response);
    }

    public function incorrect_users()
    {
        $data = array();
        $data['title'] = 'PCT Order: Incorrect Users';
        $this->admintemplate->show("order/home", "incorrect_users", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/incorrect_users', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_incorrect_customer_list()
    {
        $params = array();

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['where']['status'] = 1;

            $pageno = ($params['start'] / $params['length']) + 1;

            $incorrect_customer_lists = $this->home_model->get_incorrect_customers($params);
            // $cnt = ($pageno == 1) ? ($params['start']+1) : (($pageno - 1) * $params['length']) + 1;

            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $incorrect_customer_lists = $this->home_model->get_incorrect_customers($params);
        }
        $data = array();

        if (isset($incorrect_customer_lists['data']) && !empty($incorrect_customer_lists['data'])) {
            foreach ($incorrect_customer_lists['data'] as $key => $value) {
                $nestedData = array();
                /*$nestedData[] = $value['customer_number'];*/
                $nestedData[] = $value['first_name'];
                $nestedData[] = $value['last_name'];
                $nestedData[] = $value['email_address'];

                $nestedData[] = $value['company_name'];
                // $nestedData[] = $value['street_address'].", ".$value['city'].", ". $value['zip_code'];
                $nestedData[] = $value['random_password'];

                $type = isset($value['is_escrow']) && !empty($value['is_escrow']) ? 'Escrow' : 'Lender';
                // $nestedData[] = $type;
                $nestedData[] = $value['resware_error_msg'];

                $action = "<a href='javascript:void(0);' onclick='resetPassword(" . $value['id'] . ")' class='btn btn-secondary'  title='Reset Password'>Reset</a>";
                $nestedData[] = $action;

                $data[] = $nestedData;
                // $cnt++;
            }
        }
        $json_data['recordsTotal'] = intval($incorrect_customer_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($incorrect_customer_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function reset_user_password()
    {
        $this->load->model('order/apiLogs');
        $id = $this->input->post('id');
        $params = array(
            'id' => $id,
        );
        $userInfo = $this->home_model->get_rows($params);
        $reswareUpdatePwdData = array(
            'user_name' => $userInfo['email_address'],
            'password' => 'Pacific1',
            'new_password' => $userInfo['random_password'],
        );
        $logid = $this->apiLogs->syncLogs($userInfo['id'], 'resware', 'change_password', env('RESWARE_UPDATE_PWD_API'), $reswareUpdatePwdData, array(), 0, 0);
        $updatePwdResult = $this->updatePasswordResware($reswareUpdatePwdData);
        $this->apiLogs->syncLogs($userInfo['id'], 'resware', 'change_password', env('RESWARE_UPDATE_PWD_API'), $reswareUpdatePwdData, $updatePwdResult, 0, $logid);
        $responsePwd = json_decode($updatePwdResult, true);
        $condition = array(
            'id' => $userInfo['id'],
        );
        $customerData = array(
            'password' => 'Pacific1',
        );

        if (!empty($responsePwd['message'])) {
            $customerData['is_password_updated'] = 0;
            $customerData['resware_error_msg'] = $responsePwd['message'];
            $response = array('status' => 'error', 'message' => 'Password update failed due to: ' . $responsePwd['message']);
        } else {
            $customerData['is_password_updated'] = 1;
            $response = array('status' => 'success', 'message' => 'Password updated successfully for email user: ' . $userInfo['email_address']);
            /** Save user Activity */
            $activity = 'Incorrect user password reset successfully: ' . $userInfo['email_address'];
            $this->order->logAdminActivity($activity);
            /** End Save user activity */
        }

        $this->home_model->update($customerData, $condition, 'customer_basic_details');
        echo json_encode($response);
        exit;
    }

    public function updatePasswordResware($postData)
    {
        $body_params = http_build_query($postData);
        $ch = curl_init(env('RESWARE_UPDATE_PWD_API'));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body_params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        $result = curl_exec($ch);
        return $result;
    }

    public function import_underwriters()
    {
        $data = array();
        $successMsg = '';
        $this->load->model('order/apiLogs');
        $this->load->library('order/resware');
        $data['title'] = 'PCT Order: Import Underwriters';

        if ($this->input->post()) {
            ini_set('max_execution_time', 0);
            ini_set('memory_limit', '2048M');
            $this->form_validation->set_rules('file', 'CSV file', 'callback_file_check');

            if ($this->form_validation->run($this) == true) {
                $insertCount = $updateCount = $rowCount = $notAddCount = 0;

                if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                    $this->load->library('CSVReader');
                    $csvData = $this->csvreader->parse_csv($_FILES['file']['tmp_name']);
                    $partnerIds = array();

                    if (!empty($csvData)) {

                        foreach ($csvData as $row) {
                            $rowCount++;

                            if (isset($row['Partner Company ID']) && !empty($row['Partner Company ID'])) {
                                $con = array(
                                    'where' => array(
                                        'partner_id' => trim($row['Partner Company ID']),
                                    ),
                                    'returnType' => 'count',
                                );
                                $prevCount = $this->home_model->get_company_rows($con);

                                if ($prevCount > 0) {

                                    if (strpos(strtolower(trim($row['Underwriter'])), 'commonwealth') !== false) {
                                        $underwriter = 'commonwealth';
                                    } else if (strpos(strtolower(trim($row['Underwriter'])), 'north american') !== false) {
                                        $underwriter = 'north_american';
                                    } else if (strpos(strtolower(trim($row['Underwriter'])), 'westcor') !== false) {
                                        $underwriter = 'westcor';
                                    } else {
                                        $underwriter = null;
                                    }
                                    $condition = array('partner_id' => trim($row['Partner Company ID']));
                                    if (strpos(strtolower(trim($row['Prod Type'])), 'sale') !== false) {
                                        $update = $this->home_model->update(array('sales_underwriter' => $underwriter), $condition, 'pct_order_partner_company_info');
                                    } else if (strpos(strtolower(trim($row['Prod Type'])), 'loan') !== false) {
                                        $update = $this->home_model->update(array('loan_underwriter' => $underwriter), $condition, 'pct_order_partner_company_info');
                                    }
                                    if ($update) {
                                        $insertCount++;
                                    }
                                } else {
                                    if (!in_array($row['Partner Company ID'], $partnerIds)) {
                                        $userdata = $this->session->userdata('admin');
                                        $partner_id = $row['Partner Company ID'];
                                        $endPoint = 'admin/partners/' . $partner_id;
                                        $userdata['email'] = $userdata['email_address'];
                                        $userdata['admin_api'] = 1;
                                        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_partner_information', env('RESWARE_ORDER_API') . $endPoint, array(), array(), 0, 0);
                                        $result = $this->resware->make_request('GET', $endPoint, array(), $userdata);
                                        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_partner_information', env('RESWARE_ORDER_API') . $endPoint, array(), $result, 0, $logid);

                                        if (isset($result) && !empty($result)) {
                                            $response = json_decode($result, true);

                                            if (isset($response['AdminPartner']) && !empty($response['AdminPartner'])) {
                                                $companyData = array(
                                                    'partner_id' => trim($response['AdminPartner']['PartnerCompanyID']),
                                                    'partner_name' => trim($response['AdminPartner']['PartnerName']),
                                                    'address1' => trim($response['AdminPartner']['MailingAddress']['Address1']),
                                                    'city' => trim($response['AdminPartner']['MailingAddress']['City']),
                                                    'state' => trim($response['AdminPartner']['MailingAddress']['State']),
                                                    'zip' => trim($response['AdminPartner']['MailingAddress']['Zip']),
                                                );

                                                if (strpos(strtolower(trim($row['Underwriter'])), 'commonwealth') !== false) {
                                                    $underwriter = 'commonwealth';
                                                } else if (strpos(strtolower(trim($row['Underwriter'])), 'north american') !== false) {
                                                    $underwriter = 'north_american';
                                                } else if (strpos(strtolower(trim($row['Underwriter'])), 'westcor') !== false) {
                                                    $underwriter = 'westcor';
                                                } else {
                                                    $underwriter = null;
                                                }

                                                if (strpos(strtolower(trim($row['Prod Type'])), 'sale') !== false) {
                                                    $companyData['sales_underwriter'] = $underwriter;
                                                    $companyData['loan_underwriter'] = null;
                                                } else if (strpos(strtolower(trim($row['Prod Type'])), 'loan') !== false) {
                                                    $companyData['loan_underwriter'] = $underwriter;
                                                    $companyData['sales_underwriter'] = null;
                                                }
                                                $this->home_model->insert($companyData, 'pct_order_partner_company_info');
                                            }
                                        }
                                        $partnerIds[] = $row['Partner Company ID'];
                                    }

                                }
                            }
                        }
                        $notAddCount = ($rowCount - ($insertCount + $updateCount));
                        $successMsg = 'Underwriters imported successfully. Total Rows (' . $rowCount . ') | Inserted (' . $insertCount . ') | Not Inserted (' . $notAddCount . ')';
                        $data['success_msg'] = $successMsg;
                    }
                } else {
                    $data['error_msg'] = 'Error on file upload, please try again.';
                }
            } else {
                $data['error_msg'] = 'Invalid file, please select only CSV file.';
            }
        }
        $this->admintemplate->show("order/home", "import_underwriter", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/import_underwriter', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function updateUnderwriter()
    {
        $partner_id = $this->input->post('partner_id');
        $underwriter = $this->input->post('underwriter');
        $underwriter_type = $this->input->post('underwriter_type');

        $updateData = array($underwriter_type => $underwriter);

        $condition = array('partner_id' => $partner_id);
        $this->home_model->update($updateData, $condition, 'pct_order_partner_company_info');

        /** Save user Activity */
        $activity = 'Partner comapny id: ' . $partner_id . ' Company details like  :- ' . $underwriter_type . ' Updated value:- ' . $underwriter;
        $this->order->logAdminActivity($activity);
        /** End Save user activity */

        $data = array('status' => 'success', 'msg' => 'Underwriter updated successfully.');
        echo json_encode($data);
    }

    public function updateTitleSalesCompany()
    {
        $partnerId = $this->input->post('partner_id');
        $userId = $this->input->post('user_id');
        $userType = $this->input->post('user_type');
        if (empty($partnerId) || empty($userType)) {
            $data = array('status' => 'error', 'msg' => 'Invalid details.');
            echo json_encode($data);exit();
        }

        $updateData = [];
        if ($userType === 'title') {
            $updateData['title_officer_id'] = $userId;
        } else if ($userType === 'sales') {
            $updateData['sales_rep_id'] = $userId;
        }
        // $updateData = array($underwriter_type => $underwriter);

        $condition = array('partner_id' => $partnerId);
        $this->home_model->update($updateData, $condition, 'pct_order_partner_company_info');
        // print_r($a);die;

        /** Save user Activity */
        $activity = 'Partner comapny id: ' . $partner_id . ' user type: ' . $userType . ' details  Updated value:- ' . $userId;
        $this->order->logAdminActivity($activity);
        /** End Save user activity */

        $data = array('status' => 'success', 'msg' => 'Details updated successfully.');
        echo json_encode($data);
    }

    public function updateSalesUserForOrder()
    {
        $transactionId = $this->input->post('transaction_id');
        $userId = $this->input->post('user_id');
        if (empty($transactionId)) {
            $data = array('status' => 'error', 'msg' => 'Invalid details.');
            echo json_encode($data);exit();
        }

        $updateData = [];
        $updateData['sales_representative'] = $userId;
        $condition = array('id' => $transactionId);
        $this->home_model->update($updateData, $condition, 'transaction_details');

        /** Save user Activity */
        $activity = 'For Transaction id: ' . $transactionId . ' Sales representative assigned:- ' . $userId;
        $this->order->logAdminActivity($activity);
        /** End Save user activity */

        $data = array('status' => 'success', 'msg' => 'Details updated successfully.');
        echo json_encode($data);
    }

    public function cplProposedUsers()
    {
        $data = array();
        $data['title'] = 'PCT Order: CPL/Proposed Users';
        $this->admintemplate->show("order/home", "cpl_proposed_users", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/cpl_proposed_users', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_cpl_proposed_users_list()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $pageno = ($params['start'] / $params['length']) + 1;
            $master_users_lists = $this->home_model->get_cpl_proposed_users_list($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $master_users_lists = $this->home_model->get_cpl_proposed_users_list($params);
        }

        $data = array();
        if (isset($master_users_lists['data']) && !empty($master_users_lists['data'])) {
            foreach ($master_users_lists['data'] as $key => $value) {
                $nestedData = array();
                $nestedData[] = $value['first_name'];
                $nestedData[] = $value['last_name'];
                $nestedData[] = $value['email_address'];
                $nestedData[] = $value['street_address'] . ", " . $value['city'] . ", " . $value['state'] . ", " . $value['zip_code'];
                if ($value['lender_cpl_proposed_status'] == 1) {
                    $lenderStatus = 'Approved';
                } else if ($value['lender_cpl_proposed_status'] == 2) {
                    $lenderStatus = 'Rejected';
                } else {
                    $lenderStatus = 'Pending';
                }
                $nestedData[] = $lenderStatus;
                if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $editUrl = base_url() . 'order/admin/edit-cpl-proposed-user/' . $value['id'];
                    $nestedData[] = "<a href='" . $editUrl . "'  title='Edit CPL/Proposed User'><span class='fas fa-edit' aria-hidden='true'></span></a>";
                }
                $data[] = $nestedData;
            }
        }

        $json_data['recordsTotal'] = intval($master_users_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($master_users_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function editCplProposedUser()
    {
        $data = array();
        $id = $this->uri->segment(4);
        $data['title'] = 'PCT Order: Edit CPL/Proposed User';
        $salesRepData = array();

        if (isset($id) && !empty($id)) {
            if ($this->input->post()) {
                $this->form_validation->set_rules('first_name', 'First Name', 'required', array('required' => 'Please Enter First Name'));
                $this->form_validation->set_rules('last_name', 'Last Name', 'required', array('required' => 'Please Enter Last Name'));
                $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email', array('required' => 'Please Enter Email', 'valid_email' => 'Please enter valid Email'));
                $this->form_validation->set_rules('company', 'Company', 'required', array('required' => 'Please Enter Company'));
                $this->form_validation->set_rules('address', 'Address', 'required', array('required' => 'Please Enter Address'));
                $this->form_validation->set_rules('city', 'City', 'required', array('required' => 'Please Enter City'));
                $this->form_validation->set_rules('state', 'State', 'required', array('required' => 'Please Enter State'));
                $this->form_validation->set_rules('zipcode', 'Zipcode', 'required', array('required' => 'Please Enter Zipcode'));
                $this->form_validation->set_rules('partner_id', 'Company', 'required', array('required' => 'Please select company based on search'));

                if ($this->form_validation->run() == true) {
                    $customerData = array(
                        'partner_id' => $this->input->post('partner_id'),
                        'resware_user_id' => 0,
                        'first_name' => $this->input->post('first_name'),
                        'last_name' => $this->input->post('last_name'),
                        'telephone_no' => $this->input->post('telephone_no'),
                        'email_address' => $this->input->post('email_address'),
                        'password' => 'Pacific1',
                        'company_name' => $this->input->post('company'),
                        'street_address' => $this->input->post('address'),
                        'city' => $this->input->post('city'),
                        'state' => $this->input->post('state'),
                        'zip_code' => $this->input->post('zipcode'),
                        'is_escrow' => 0,
                        'is_master' => 0,
                        'is_password_updated' => 0,
                        'is_new_user' => 0,
                        'status' => 1,
                    );
                    $response = $this->addNewUserToResware($customerData);

                    if ($response['success']) {
                        $customerData['resware_user_id'] = $response['resware_user_id'];
                        $customerData['random_password'] = $this->order->randomPassword();
                        $reswareUpdatePwdData = array(
                            'user_name' => $this->input->post('email_address'),
                            'password' => 'Pacific1',
                            'new_password' => $customerData['random_password'],
                        );
                        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'change_password', env('RESWARE_UPDATE_PWD_API'), $reswareUpdatePwdData, array(), 0, 0);
                        $updatePwdResult = $this->updatePasswordResware($reswareUpdatePwdData);
                        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'change_password', env('RESWARE_UPDATE_PWD_API'), $reswareUpdatePwdData, $updatePwdResult, 0, $logid);
                        $responsePwd = json_decode($updatePwdResult, true);

                        if (!empty($responsePwd['message'])) {
                            $customerData['resware_error_msg'] = $responsePwd['message'];
                            $data['error_msg'] = 'Password update failed due to: ' . $responsePwd['message'];
                        } else {
                            $customerData['is_password_updated'] = 1;
                            $customerData['is_added_lender_by_cpl_proposed'] = 0;
                            $customerData['lender_cpl_proposed_status'] = 1;
                            $data['success_msg'] = 'Password updated successfully for email user: ' . $this->input->post('email_address');
                        }
                        $updateCondition = array(
                            'id' => $id,
                        );
                        $update = $this->home_model->update($customerData, $updateCondition);
                        /** Save user Activity */
                        $activity = 'CPL/Proposed user approved successfully: ' . $this->input->post('email_address');
                        $this->order->logAdminActivity($activity);
                        /** End Save user activity */
                    } else {
                        $data['error_msg'] = $response['msg'];
                    }
                } else {
                    $data['first_name_error_msg'] = form_error('first_name');
                    $data['last_name_error_msg'] = form_error('last_name');
                    $data['email_address_error_msg'] = form_error('email_address');
                    $data['company_error_msg'] = form_error('company');
                    $data['user_type_error_msg'] = form_error('user_type');
                    $data['address_error_msg'] = form_error('address');
                    $data['city_error_msg'] = form_error('city');
                    $data['state_error_msg'] = form_error('state');
                    $data['zipcode_error_msg'] = form_error('zipcode');
                    if (empty(form_error('company')) && !empty(form_error('partner_id'))) {
                        $data['company_error_msg'] = "This partner company is not available on Resware side so please remove this company and select new company based on search.";
                    }
                }
            }
            $con = array('id' => $id);
            $data['cpl_proposed_user_info'] = $this->home_model->get_rows($con);
        }
        $this->admintemplate->show("order/home", "edit_cpl_proposed_user", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/edit_cpl_proposed_user', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function rejectCplProposedUser()
    {
        $data = array();
        $id = $this->uri->segment(4);
        $updateCondition = array(
            'id' => $id,
        );
        $customerData['lender_cpl_proposed_status'] = 2;
        $customerData['is_added_lender_by_cpl_proposed'] = 0;
        $update = $this->home_model->update($customerData, $updateCondition);
        /** Save user Activity */
        $user = $this->home_model->get_user($updateCondition);
        $activity = 'CPL/Proposed user rejected successfully: ' . $user['email_address'];
        $this->order->logAdminActivity($activity);
        /** End Save user activity */
        redirect(base_url() . 'order/admin/cpl-proposed-users');
    }

    public function sendPassword()
    {
        $data = array();
        $data['title'] = 'PCT Order: Send Password Listing';
        $this->admintemplate->addJS(base_url('assets/backend/js/password-listing.js'));
        $this->admintemplate->show("order/home", "password_listing", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/password_listing', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_password_list()
    {
        $params = array();
        $params['user_type'] = $this->input->post('user_type');
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $pageno = ($params['start'] / $params['length']) + 1;
            $customer_lists = $this->home_model->get_password_list($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $customer_lists = $this->home_model->get_password_list($params);
        }

        $data = array();
        if (isset($customer_lists['data']) && !empty($customer_lists['data'])) {
            foreach ($customer_lists['data'] as $key => $value) {
                $nestedData = array();
                $nestedData[] = $value['first_name'];
                $nestedData[] = $value['last_name'];
                $nestedData[] = $value['email_address'];
                if ($value['is_title_officer'] == 1) {
                    $nestedData[] = 'Title Officer';
                } else if ($value['is_sales_rep'] == 1) {
                    if ($value['is_sales_rep_manager'] == 1) {
                        $nestedData[] = 'Sales Rep. Manager';
                    } else {
                        $nestedData[] = 'Sales Rep';
                    }
                } else if ($value['is_special_lender'] == 1) {
                    $nestedData[] = 'Special Lender';
                } else if ($value['is_payoff_user'] == 1) {
                    $nestedData[] = 'Payoff User';
                } else if ($value['is_escrow'] == 1) {
                    $nestedData[] = 'Escrow User';
                } else {
                    $nestedData[] = 'Lender User';
                }
                $nestedData[] = $value['company_name'];
                $nestedData[] = $value['street_address'];
                $nestedData[] = $value['city'];
                $nestedData[] = $value['zip_code'];
                $user_id = $value['id'];

                if ($value['is_password_required'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                $nestedData[] = "<input $checked onclick='isPasswordRequired();' style='height:30px;width:20px;' type='checkbox' id='$user_id' name='$user_id'>";

                if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $action = "<a href='javascript:void(0);' onclick='sendPasswordMail(" . $value['id'] . ")'  title='Delete Customer'><span class='fa fa-envelope' aria-hidden='true'></span></a>";
                    $nestedData[] = $action;
                }
                $data[] = $nestedData;
            }
        }
        $json_data['recordsTotal'] = intval($customer_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($customer_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function sendPasswordMail()
    {
        $id = $this->input->post('id');

        if ($id) {
            $user = $this->home_model->get_user(array('id' => $id));
            $from_name = 'Pacific Coast Title Company';
            $from_mail = getenv('FROM_EMAIL');
            $message_body = "Hi " . $user['first_name'] . " " . $user['last_name'] . ", <br><br>";
            $message_body .= "Please login with tempoary password and change your password. <br><br>";
            $this->load->library('order/order');
            $randomPassword = $this->order->randomPassword();

            $message_body .= "Tempoary password: " . $randomPassword . "<br><br>";
            $message_body .= "Use this link for login: " . getenv('APP_URL') . "order/login <br><br>";

            $this->home_model->update(array('password' => password_hash($randomPassword, PASSWORD_DEFAULT), 'is_tmp_password' => 1), array('id' => $user['id']));
            $subject = 'Change Passsword';
            $to = $user['email_address'];
            // $to = 'ghernandez@pct.com';
            $cc = array();
            $bcc = array();
            $file = array();
            $this->load->helper('sendemail');
            $mail_result = send_email($from_mail, $from_name, $to, $subject, $message_body, $file, $cc, $bcc);
            if ($mail_result) {
                /** Save user Activity */
                $activity = 'Email sent to user to reset password: ' . $user['email_address'];
                $this->order->logAdminActivity($activity);
                /** End Save user activity */
                $response = array('status' => 'success', 'message' => 'Mail sent successfully.');
            } else {
                $response = array('status' => 'success', 'message' => 'Mail not sent due to some error. Please try again.');
            }
        } else {
            $msg = 'Customer ID is required.';
            $response = array('status' => 'error', 'message' => $msg);
        }
        echo json_encode($response);
    }

    public function reswareAdminCredential()
    {
        $this->load->library('order/order');
        $data = array();
        $data['title'] = 'PCT Order: Resware Admin Credential';
        $data['credResult'] = $this->order->get_resware_admin_credential();

        if ($this->input->post()) {
            $this->form_validation->set_rules('resware_username', 'Username', 'required', array('required' => 'Please Enter Username'));
            $this->form_validation->set_rules('resware_password', 'Password', 'required', array('required' => 'Please Enter Password'));
            if ($this->form_validation->run() == true) {
                $this->db->update('pct_resware_admin_credential', array('username' => $this->input->post('resware_username'), 'password' => $this->input->post('resware_password')));
                /** Save user Activity */
                $activity = 'ResWare Credential updated: Username' . $this->input->post('resware_username');
                $this->order->logAdminActivity($activity);
                /** End Save user activity */
                $data['success_msg'] = 'Resware Admin credentials updated successfully';
                $data['credResult'] = $this->order->get_resware_admin_credential();
            } else {
                $data['resware_username_error_msg'] = form_error('resware_username');
                $data['resware_password_error_msg'] = form_error('resware_password');
            }
        }
        $this->admintemplate->show("order/home", "resware_admin_credential", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/resware_admin_credential', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function importOrders($value = '')
    {
        $data = array();
        $data['title'] = 'PCT Order: Import Orders';
        $this->admintemplate->show("order/home", "import_order", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/import_order', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_import_order_customer_list()
    {
        $params = array();

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['where']['status'] = 1;

            $pageno = ($params['start'] / $params['length']) + 1;

            $incorrect_customer_lists = $this->home_model->get_import_order_users($params);
            // $cnt = ($pageno == 1) ? ($params['start']+1) : (($pageno - 1) * $params['length']) + 1;

            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $incorrect_customer_lists = $this->home_model->get_import_order_users($params);
        }
        $data = array();

        if (isset($incorrect_customer_lists['data']) && !empty($incorrect_customer_lists['data'])) {
            foreach ($incorrect_customer_lists['data'] as $key => $value) {
                $nestedData = array();
                /*$nestedData[] = $value['customer_number'];*/
                $nestedData[] = $value['first_name'];
                $nestedData[] = $value['last_name'];
                $nestedData[] = $value['email_address'];

                $nestedData[] = $value['company_name'];

                $action = "<a href='javascript:void(0);' onclick='importOrders(" . $value['id'] . ")' class='btn btn-secondary'  title='Import'>Import</a>";
                $nestedData[] = $action;

                $data[] = $nestedData;
                // $cnt++;
            }
        }
        $json_data['recordsTotal'] = intval($incorrect_customer_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($incorrect_customer_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function updateTransaction()
    {
        $partner_id = $this->input->post('partner_id');
        $transaction = $this->input->post('transaction');
        $condition = array('partner_id' => $partner_id);
        $this->home_model->update(array('transaction' => $transaction), $condition, 'pct_order_partner_company_info');
        $data = array('status' => 'success', 'msg' => 'Transaction updated successfully.');
        echo json_encode($data);
    }

    public function notifications()
    {
        $data = array();
        $data['title'] = 'PCT Order: Notification';
        $this->admintemplate->addJS(base_url('assets/backend/js/order.js'));
        $this->admintemplate->show("order/home", "notifications", $data);

        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/notifications', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_notifications_list()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $notification_lists = $this->home_model->get_notifications_list($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $notification_lists = $this->home_model->get_notifications_list($params);
        }

        $data = array();
        $count = $params['start'] + 1;
        if (isset($notification_lists['data']) && !empty($notification_lists['data'])) {
            foreach ($notification_lists['data'] as $key => $value) {
                $nestedData = array();
                $nestedData[] = $count;
                $nestedData[] = $value['name'];
                $notification_id = $value['id'];
                if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $nestedData[] = "<div style='display:flex;'><a href='javascript:void(0);' onclick='preview_email($notification_id)'><i class='fas fa-eye'></i></a>
                    </div>";
                }
                $data[] = $nestedData;
                $count++;
            }
        }
        $json_data['recordsTotal'] = intval($notification_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($notification_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function email_preview()
    {
        $data = array();
        $notificationId = $this->input->post('notificationId');
        if ($notificationId == 5) {
            $results = $this->load->view('emails/borrower', $data, true);
        } else if ($notificationId == 4) {
            $results = $this->load->view('emails/order', $data, true);
        } else if ($notificationId == 7) {
            $results = $this->load->view('emails/prelim', $data, true);
        } else if ($notificationId == 6) {
            $results = $this->load->view('emails/search_package', $data, true);
        } else {
            $results = "<div style='margin:50px;'><h3>User Details:</h3><p>Name: </p><p>Telephone: </p><p>Email Address: </p><p>Company Name: </p><p>Street Address: </p><p>City: </p><p>Zipcode: </p><p>Property Address: </p></div>";
        }

        echo json_encode($results, true);
    }

    public function getDeliverables()
    {
        $partner_id = $this->input->post('partner_id');
        $this->db->select('*');
        $this->db->from('pct_order_partner_company_info');
        $this->db->where('partner_id', $partner_id);
        $query = $this->db->get();
        $partnerInfo = $query->row_array();
        if (!empty($partnerInfo['deliverables'])) {
            $deliverables = explode(',', $partnerInfo['deliverables']);
            $result = array('deliverables' => $deliverables);
        } else {
            $result = array('deliverables' => array());
        }
        echo json_encode($result);
        exit;
    }

    public function storeDeliverables()
    {
        $AdditionalEmail = $this->input->post('AdditionalEmail');
        $partner_id = $this->input->post('partner_id');
        $condition = array(
            'partner_id' => $partner_id,
        );
        $this->home_model->update(array('deliverables' => implode(',', $AdditionalEmail)), $condition, 'pct_order_partner_company_info');
        $success = 'Deliverables added successfully.';
        $data = array(
            "success" => $success,
        );
        $this->session->set_userdata($data);
        redirect(base_url() . 'order/admin/companies');
    }

    public function escrow_officers()
    {
        $data = array();
        $data['title'] = 'PCT Order: Escrow Officers';
        $this->admintemplate->show("order/home", "escrow_officers", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/escrow_officers', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_escrow_officers_list()
    {
        $params = array();

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['where']['status'] = 1;

            $pageno = ($params['start'] / $params['length']) + 1;

            $escrow_officer_lists = $this->home_model->get_escrow_officers($params);
            // $cnt = ($pageno == 1) ? ($params['start']+1) : (($pageno - 1) * $params['length']) + 1;

            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $escrow_officer_lists = $this->home_model->get_escrow_officers($params);
        }

        $data = array();

        if (isset($escrow_officer_lists['data']) && !empty($escrow_officer_lists['data'])) {
            foreach ($escrow_officer_lists['data'] as $key => $value) {
                $nestedData = array();

                $nestedData[] = $value['partner_id'];
                $nestedData[] = $value['partner_type_id'];
                $nestedData[] = $value['partner_name'];
                $nestedData[] = $value['email'];

                $action = "";
                $editUrl = base_url() . 'order/admin/edit-escrow-officer/' . $value['id'];
                $action = "<div style='display: flex;justify-content: space-evenly;'><a href='" . $editUrl . "' class='edit-agent'title ='Edit Escrow Officer Detail'><i class='fas fa-edit' aria-hidden='true'></i></a>";

                $action .= "<a href='javascript:void(0);' onclick='deleteEscrowOfficer(" . $value['id'] . ")' title='Delete Escrow Officer'><i class='fas fa-trash' aria-hidden='true'></i></a></div>";
                $nestedData[] = $action;

                $data[] = $nestedData;
                // $cnt++;
            }
        }
        $json_data['recordsTotal'] = intval($escrow_officer_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($escrow_officer_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function add_escrow_officer()
    {
        $data = array();
        $data['title'] = 'PCT Order: Add Escrow Officer';
        $escrowData = array();
        if ($this->input->post()) {
            // echo "<pre>"; print_r($this->input->post()); exit;
            $this->form_validation->set_rules('partner_id', 'Partner Id', 'trim|required|numeric', array('required' => 'Please Enter Partner Id'));
            $this->form_validation->set_rules('partner_type_id', 'Partner Type Id', 'trim|required|numeric', array('required' => 'Please Enter Partner Type Id'));
            $this->form_validation->set_rules('partner_name', 'Partner Name', 'required', array('required' => 'Please Enter Partner Name'));
            $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email', array('required' => 'Please Enter Email', 'valid_email' => 'Please enter valid Email'));
            $this->form_validation->set_rules('address', 'Address', 'required', array('required' => 'Please Enter Address'));
            $this->form_validation->set_rules('city', 'City', 'required', array('required' => 'Please Enter City'));
            $this->form_validation->set_rules('state', 'State', 'required', array('required' => 'Please Enter State'));
            $this->form_validation->set_rules('zip', 'Zip', 'required', array('required' => 'Please Enter Zip'));
            $partner_type_ids = array();
            if ($this->form_validation->run() == true) {
                $partner_type_ids[] = 1;
                $partner_type_ids[] = $_POST['partner_type_id'];
                $escrowData = array(
                    'partner_id' => $_POST['partner_id'],
                    'partner_type_id' => implode(',', $partner_type_ids),
                    'partner_name' => $_POST['partner_name'],
                    'email' => $_POST['email_address'],
                    'address1' => $_POST['address'],
                    'city' => $_POST['city'],
                    'state' => $_POST['state'],
                    'zip' => $_POST['zip'],
                    'status' => 1,
                );

                $insert = $this->home_model->insert($escrowData, 'pct_order_partner_company_info');

                if ($insert) {
                    $data['success_msg'] = 'Escrow Officer added successfully.';
                    /** Save user Activity */
                    $activity = 'Escrow user added successfully :- ' . $_POST['email_address'];
                    $this->order->logAdminActivity($activity);
                    /** End save user activity */
                } else {
                    $data['error_msg'] = 'Escrow Officer not added.';
                }

            } else {
                $data['partner_id_error_msg'] = form_error('partner_id');
                $data['partner_type_id_error_msg'] = form_error('partner_type_id');
                $data['partner_name_error_msg'] = form_error('partner_name');
                $data['email_error_msg'] = form_error('email_address');
                $data['address_error_msg'] = form_error('address');
                $data['city_error_msg'] = form_error('city');
                $data['state_error_msg'] = form_error('state');
                $data['zip_error_msg'] = form_error('zip');
            }
        }
        $this->admintemplate->addJS(base_url('assets/vendor/jquery/jquery.min.js'));
        $this->admintemplate->addJS(base_url('assets/admin/js/jquery.validate.min.js'));
        $this->admintemplate->addJS(base_url('assets/backend/js/add-escrow.js'));
        $this->admintemplate->show("order/home", "add_escrow_officer", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/add_escrow_officer', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function delete_escrow_officer()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';

        if ($id) {
            $escrowData = array('status' => 0);

            $condition = array('id' => $id);

            $companyDetails = $this->home_model->get_user($condition, 'pct_order_partner_company_info');
            $update = $this->home_model->update($escrowData, $condition, 'pct_order_partner_company_info');

            if ($update) {
                /** Save user Activity */
                $activity = 'Escrow officer deleted successfully: Partner id :- ' . $companyDetails['partner_id'] . ' email :- ' . $companyDetails['email'];
                $this->order->logAdminActivity($activity);
                /** End save user activity */
                $successMsg = 'Escrow Officer deleted successfully.';
                $response = array('status' => 'success', 'message' => $successMsg);
            }
        } else {
            $msg = 'Escrow Officer ID is required.';
            $response = array('status' => 'error', 'message' => $msg);
        }

        echo json_encode($response);
    }

    public function edit_escrow_officer()
    {
        $data = array();
        $id = $this->uri->segment(4);
        $data['title'] = 'PCT Order: Edit Escrow Officer';
        $escrowData = array();

        if (isset($id) && !empty($id)) {
            if ($this->input->post()) {
                $this->form_validation->set_rules('partner_id', 'Partner Id', 'trim|required|numeric', array('required' => 'Please Enter Partner Id'));
                $this->form_validation->set_rules('partner_type_id', 'Partner Type Id', 'required', array('required' => 'Please Enter Partner Type Id'));
                $this->form_validation->set_rules('partner_name', 'Partner Name', 'required', array('required' => 'Please Enter Partner Name'));
                $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email', array('required' => 'Please Enter Email', 'valid_email' => 'Please enter valid Email'));
                $this->form_validation->set_rules('address', 'Address', 'required', array('required' => 'Please Enter Address'));
                $this->form_validation->set_rules('city', 'City', 'required', array('required' => 'Please Enter City'));
                $this->form_validation->set_rules('state', 'State', 'required', array('required' => 'Please Enter State'));
                $this->form_validation->set_rules('zip', 'Zip', 'required', array('required' => 'Please Enter Zip'));

                if ($this->form_validation->run() == true) {
                    $escrowData = array(
                        'partner_id' => $_POST['partner_id'],
                        'partner_type_id' => $_POST['partner_type_id'],
                        'partner_name' => $_POST['partner_name'],
                        'email' => $_POST['email_address'],
                        'address1' => $_POST['address'],
                        'city' => $_POST['city'],
                        'state' => $_POST['state'],
                        'zip' => $_POST['zip'],
                        'status' => 1,
                    );

                    $condition = array('id' => $id);
                    $update = $this->home_model->update($escrowData, $condition, 'pct_order_partner_company_info');

                    if ($update) {
                        /** Save user Activity */
                        $activity = 'Escrow Officer updated successfully: Partner id :- ' . $_POST['partner_id'] . ' - Email :- ' . $_POST['email'];
                        $this->order->logAdminActivity($activity);
                        /** End save user activity */
                        $data['success_msg'] = 'Escrow Officer updated successfully.';
                    } else {
                        $data['error_msg'] = 'Error occurred while updating Escrow Officer.';
                    }

                } else {
                    $data['partner_id_error_msg'] = form_error('partner_id');
                    $data['partner_name_error_msg'] = form_error('partner_name');
                    $data['email_error_msg'] = form_error('email_address');
                    $data['address_error_msg'] = form_error('address');
                    $data['city_error_msg'] = form_error('city');
                    $data['state_error_msg'] = form_error('state');
                    $data['zip_error_msg'] = form_error('zip');
                }
            }
            $con = array('id' => $id);
            $data['escrow_info'] = $this->home_model->get_escrow_officer($con);
        } else {
            redirect(base_url() . 'escrow-officers');
        }
        $this->admintemplate->addJS(base_url('assets/vendor/jquery/jquery.min.js'));
        $this->admintemplate->addJS(base_url('assets/admin/js/jquery.validate.min.js'));
        $this->admintemplate->addJS(base_url('assets/backend/js/add-escrow.js'));
        $this->admintemplate->show("order/home", "edit_escrow_officer", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/edit_escrow_officer', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function payoff_users()
    {
        $data = array();
        $data['title'] = 'PCT Order: Payoff Users';
        $this->admintemplate->addJS(base_url('assets/backend/js/payoff_user.js'));
        $this->admintemplate->show("order/payoff", "payoff_users", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/escrow_officers', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_payoff_users_list()
    {
        $params = array();
        $this->load->model('order/payoff_model');
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['where']['status'] = 1;

            $pageno = ($params['start'] / $params['length']) + 1;

            $payoff_users_list = $this->payoff_model->get_payoff_users($params);
            // $cnt = ($pageno == 1) ? ($params['start']+1) : (($pageno - 1) * $params['length']) + 1;

            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $payoff_users_list = $this->payoff_model->get_payoff_users($params);
        }

        $data = array();
        // echo "<pre>";
        // print_r($payoff_users_list);die;
        if (isset($payoff_users_list['data']) && !empty($payoff_users_list['data'])) {
            foreach ($payoff_users_list['data'] as $key => $value) {
                $nestedData = array();
                $user_id = $value['id'];
                $nestedData[] = $value['first_name'] . ' ' . $value['last_name'];
                $nestedData[] = $value['email_address'];
                $nestedData[] = $value['company_name'];
                $status = $value['status'];
                if ($status == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                // $nestedData[] = "<input $checked onclick='enablePayoffUser();' style='height:30px;width:20px;' type='checkbox' id='$user_id' name='$user_id'>";

                $action = "";
                $editUrl = base_url() . 'order/admin/edit-payoff-user/' . $value['id'];
                $action = "<div style='display: flex;justify-content: space-evenly;'><a href='" . $editUrl . "' class='edit-agent'title ='Edit Payoff User Detail'><i class='fas fa-edit' aria-hidden='true'></i></a>";

                $action .= "<a href='javascript:void(0);' onclick='deletePayoffUser(" . $value['id'] . ")' title='Delete Payoff User'><i class='fas fa-trash' aria-hidden='true'></i></a></div>";
                $nestedData[] = $action;

                $data[] = $nestedData;
                // $cnt++;
            }
        }
        $json_data['recordsTotal'] = intval($payoff_users_list['recordsTotal']);
        $json_data['recordsFiltered'] = intval($payoff_users_list['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function add_payoff_user()
    {
        $data = array();
        $data['title'] = 'PCT Order: Add Payoff User.';
        $data['pageTitle'] = 'Payoff User.';

        $titleOfficerData = array();

        if ($this->input->post()) {
            $this->form_validation->set_rules('first_name', 'First Name', 'required', array('required' => 'Please Enter First Name'));
            $this->form_validation->set_rules('last_name', 'Last Name', 'required', array('required' => 'Please Enter Last Name'));
            $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email', array('required' => 'Please Enter Email', 'valid_email' => 'Please enter valid Email'));
            $this->form_validation->set_rules('company_name', 'Company Name', 'required', array('required' => 'Please Enter Company Name'));
            $this->form_validation->set_rules('address', 'Address', 'required', array('required' => 'Please Enter Address'));
            $this->form_validation->set_rules('city', 'City', 'required', array('required' => 'Please Enter City'));
            $this->form_validation->set_rules('state', 'State', 'required', array('required' => 'Please Enter State'));
            $this->form_validation->set_rules('zip', 'Zip', 'required', array('required' => 'Please Enter Zip'));

            if ($this->form_validation->run() == true) {
                $payoffUserData = array(
                    'first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name'],
                    'email_address' => $_POST['email_address'],
                    'company_name' => $_POST['company_name'],
                    'street_address' => $_POST['address'],
                    'city' => $_POST['city'],
                    'state' => $_POST['state'],
                    'zip_code' => $_POST['zip'],
                    'is_password_updated' => 1,
                    'is_payoff_user' => 1,
                    'status' => 1,
                );
                $this->load->model('order/payoff_model');
                $insert = $this->payoff_model->insert($payoffUserData);
                /** Save user Activity */
                $activity = 'Payoff user created :- ' . $_POST['email_address'];
                $this->common->logAdminActivity($activity);
                /** End save user activity */
                if ($insert) {
                    $data['success_msg'] = 'Payoff User added successfully.';
                } else {
                    $data['error_msg'] = 'Payoff User not added.';
                }

            } else {
                $data['first_name_error_msg'] = form_error('first_name');
                $data['last_name_error_msg'] = form_error('last_name');
                $data['email_error_msg'] = form_error('email_address');
                $data['company_name_error_msg'] = form_error('company_name');
                $data['address_error_msg'] = form_error('address');
                $data['city_error_msg'] = form_error('city');
                $data['state_error_msg'] = form_error('state');
                $data['zip_error_msg'] = form_error('zip');
            }
        }

        $this->admintemplate->show("order/payoff", "add_payoff_user", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/title/add_title_officer', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function delete_payoff_user()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        if ($id) {
            $this->load->model('order/payoff_model');

            $payoffUserData = array('status' => 0);
            $condition = array('id' => $id);
            $delete = $this->payoff_model->delete($condition);

            if ($delete) {
                $payoffUser = $this->payoff_model->getPayoffUsers($condition);
                /** Save user Activity */
                $activity = 'Payoff user deleted :- ' . $payoffUser['email_address'];
                $this->common->logAdminActivity($activity);
                /** End save user activity */
                $successMsg = 'Payoff user deleted successfully.';
                $response = array('status' => 'success', 'message' => $successMsg);
            }
        } else {
            $msg = 'Payoff user ID is required.';
            $response = array('status' => 'error', 'message' => $msg);
        }
        echo json_encode($response);
    }

    public function edit_payoff_user()
    {
        $data = array();
        $data['title'] = 'PCT Order: Edit Payoff User';
        $data['pageTitle'] = 'Payoff User.';
        $id = $this->uri->segment('4');
        $this->load->model('order/payoff_model');
        if (isset($id) && !empty($id)) {
            if (isset($_POST) && !empty($_POST)) {

                $this->form_validation->set_rules('first_name', 'First Name', 'required', array('required' => 'Please Enter First Name'));
                $this->form_validation->set_rules('last_name', 'Last Name', 'required', array('required' => 'Please Enter Last Name'));
                $this->form_validation->set_rules('email_address', 'Email', 'trim|required|valid_email', array('required' => 'Please Enter Email', 'valid_email' => 'Please enter valid Email'));
                $this->form_validation->set_rules('company_name', 'Company Name', 'required', array('required' => 'Please Enter Company Name'));
                $this->form_validation->set_rules('street_address', 'Address', 'required', array('required' => 'Please Enter Address'));
                $this->form_validation->set_rules('city', 'City', 'required', array('required' => 'Please Enter City'));
                $this->form_validation->set_rules('state', 'State', 'required', array('required' => 'Please Enter State'));
                $this->form_validation->set_rules('zip', 'Zip', 'required', array('required' => 'Please Enter Zip'));

                if ($this->form_validation->run() == true) {
                    $payoffUserData = array(
                        'first_name' => $_POST['first_name'],
                        'last_name' => $_POST['last_name'],
                        'email_address' => $_POST['email_address'],
                        'company_name' => $_POST['company_name'],
                        'street_address' => $_POST['street_address'],
                        'city' => $_POST['city'],
                        'state' => $_POST['state'],
                        'zip_code' => $_POST['zip'],
                        'is_password_updated' => 1,
                        'is_payoff_user' => 1,
                        'status' => 1,
                    );
                    $condition = array('id' => $id);
                    $update = $this->payoff_model->update($payoffUserData, $condition);
                    /** Save user Activity */
                    $activity = 'Payoff User updated :- ' . $_POST['email_address'];
                    $this->common->logAdminActivity($activity);
                    /** End save user activity */
                    if ($update) {
                        $data['success_msg'] = 'Payoff User updated successfully.';
                    } else {
                        $data['error_msg'] = 'Error occurred while updating Payoff User';
                    }
                } else {
                    $data['first_name_error_msg'] = form_error('first_name');
                    $data['last_name_error_msg'] = form_error('last_name');
                    $data['email_error_msg'] = form_error('email_address');
                    $data['company_name_error_msg'] = form_error('company_name');
                    $data['street_address_error_msg'] = form_error('street_address');
                    $data['city_error_msg'] = form_error('city');
                    $data['state_error_msg'] = form_error('state');
                    $data['zip_error_msg'] = form_error('zip');
                }
            }
            $con = array('id' => $id);
            $payoff_user_info = $this->payoff_model->getPayoffUsers($con);
        } else {
            redirect('order/admin/payoff-users');
        }
        // echo "<pre>";
        // print_r($payoff_user_info);die;
        $data['payoff_user_info'] = $payoff_user_info;
        $this->admintemplate->show("order/payoff", "edit_payoff_user", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/title/edit_title_officer', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function updateUserStatus()
    {
        $user_id = $this->input->post('user_id');
        $status = $this->input->post('status');
        $data['status'] = $status;
        $data['updated_at'] = date("Y-m-d H:i:s");
        $condition = array(
            'id' => $user_id,
        );
        /** Save user Activity */
        $user = $this->home_model->get_user($condition);
        $activity = 'User ' . $user['email_address'] . 'status updated to :- ' . $status;
        $this->order->logAdminActivity($activity);
        /** End Save user activity */
        $res = $this->db->update('customer_basic_details', $data, $condition);
        // print_r($res);die;
        $data = array('status' => 'success', 'msg' => 'User\'s status updated successfully.');
        echo json_encode($data);
    }

    public function transactees_list()
    {
        $data = array();
        $data['title'] = 'PCT Order: Transactees List';
        $this->admintemplate->addJS(base_url('assets/backend/js/payoff_user.js'));
        $this->admintemplate->show("order/payoff", "transactees_list", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/escrow_officers', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_transactees_list()
    {
        $params = array();
        $this->load->model('order/home_model');
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['where']['status'] = 1;

            $pageno = ($params['start'] / $params['length']) + 1;

            $payoff_users_list = $this->home_model->get_payoff_users($params);

            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $payoff_users_list = $this->home_model->get_payoff_users($params);
        }

        $data = array();
        if (isset($payoff_users_list['data']) && !empty($payoff_users_list['data'])) {
            foreach ($payoff_users_list['data'] as $key => $value) {
                $nestedData = array();
                $user_id = $value['id'];
                $nestedData[] = $value['first_name'] . ' ' . $value['last_name'];
                $nestedData[] = $value['email_address'];
                $nestedData[] = $value['company_name'];
                $status = $value['status'];
                if ($status == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                // $nestedData[] = "<input $checked onclick='enablePayoffUser();' style='height:30px;width:20px;' type='checkbox' id='$user_id' name='$user_id'>";

                $action = "";
                $editUrl = base_url() . 'order/admin/edit-transactee-user/' . $value['id'];
                $action = "<div style='display: flex;justify-content: space-evenly;'><a href='" . $editUrl . "' class='edit-agent'title ='Edit Transactee Detail'><i class='fas fa-edit' aria-hidden='true'></i></a>";

                $action .= "<a href='javascript:void(0);' onclick='deleteTransactee(" . $value['id'] . ")' title='Delete Transactee'><i class='fas fa-trash' aria-hidden='true'></i></a></div>";
                $nestedData[] = $action;

                $data[] = $nestedData;
                // $cnt++;
            }
        }
        $json_data['recordsTotal'] = intval($payoff_users_list['recordsTotal']);
        $json_data['recordsFiltered'] = intval($payoff_users_list['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function downloadAwsDocument()
    {
        $url = $this->input->post('url');
        $binaryData = base64_encode(file_get_contents($url));
        echo $binaryData;
        exit;
    }

    public function updateAvoidDuplicationFlag()
    {
        $property_id = $this->input->post('property_id');
        $avoidFlag = $this->input->post('avoidFlag');
        $data['allow_duplication'] = $avoidFlag;
        $data['updated_at'] = date("Y-m-d H:i:s");
        $condition = array(
            'id' => $property_id,
        );
        $this->db->update('property_details', $data, $condition);
        /** Save user Activity */
        $propertyDetails = $this->db->select('full_address')->from('property_details')->where('id', $property_id)->get()->row_array();
        $avoidStatus = ($avoidFlag) ? 'Enabled' : 'Disabled';
        $userdata = $this->session->userdata('admin');
        $activity = $avoidStatus . ' Avoid Duplication for Property :- ' . $propertyDetails['full_address'];
        $this->order->logAdminActivity($activity);
        /** End Save user activity */
        $data = array('status' => 'success', 'msg' => 'Avoid duplication flag updated successfully.');
        echo json_encode($data);
    }

    public function updateMortgageUser()
    {
        $user_id = $this->input->post('user_id');
        $mortgageUserFlag = $this->input->post('mortgageUserFlag');
        $data['is_mortgage_user'] = $mortgageUserFlag;
        $data['updated_at'] = date("Y-m-d H:i:s");
        $condition = array(
            'id' => $user_id,
        );
        /** Save user Activity */
        $user = $this->home_model->get_user($condition);
        $activity = 'Mortgage user ' . $user['email_address'] . ' updated to :- ' . $mortgageUserFlag;
        $this->order->logAdminActivity($activity);
        /** End Save user activity */
        $this->db->update('customer_basic_details', $data, $condition);
        $data = array('status' => 'success', 'msg' => 'Mortgage user updated successfully.');
        echo json_encode($data);
    }

    public function mortgageBrokers()
    {
        $data = array();
        $data['title'] = 'PCT Order: Mortgage Brokers';
        $this->admintemplate->addJS(base_url('assets/backend/js/mortgage-brokers.js'));
        $this->admintemplate->show("order/home", "mortgage_brokers", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/mortgage_brokers', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_mortgage_brokers_list()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['is_escrow'] = 0;
            $pageno = ($params['start'] / $params['length']) + 1;
            $mortgage_lists = $this->home_model->get_mortgage_users($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $mortgage_lists = $this->home_model->get_cget_mortgage_usersustomers($params);
        }

        $data = array();
        if (isset($mortgage_lists['data']) && !empty($mortgage_lists['data'])) {
            foreach ($mortgage_lists['data'] as $key => $value) {
                $nestedData = array();
                $user_id = $value['id'];
                $nestedData[] = $value['first_name'];
                $nestedData[] = $value['last_name'];
                $nestedData[] = $value['email_address'];
                $nestedData[] = $value['company_name'];
                $nestedData[] = $value['street_address'] . ", " . $value['city'] . ", " . $value['state'] . ", " . $value['zip_code'];
                if ($value['is_primary_mortgage_user'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                $nestedData[] = "<input $checked onclick='isMortgagePrimaryUser();' style='height:30px;width:20px;' type='checkbox' id='$user_id' name='$user_id'>";
                if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $action = "<a href='javascript:void(0);' onclick='deleteCustomer(" . $value['id'] . ")' class='btn btn-action'  title='Delete Customer'><span class='fas fa-trash' aria-hidden='true'></span></a>";
                    $nestedData[] = $action;
                }
                $data[] = $nestedData;
            }
        }
        $json_data['recordsTotal'] = intval($mortgage_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($mortgage_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function clientList()
    {
        $data = array();
        $data['title'] = 'PCT Order: Client lists';
        $this->admintemplate->show("order/home", "client_list", $data);
    }

    public function get_active_client_users()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            // $params['is_escrow'] = 0;
            $pageno = ($params['start'] / $params['length']) + 1;
            $mortgage_lists = $this->home_model->get_active_client_users($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $mortgage_lists = $this->home_model->get_active_client_users($params);
        }

        $data = array();
        if (isset($mortgage_lists['data']) && !empty($mortgage_lists['data'])) {
            foreach ($mortgage_lists['data'] as $key => $value) {
                $nestedData = array();
                $user_id = $value['id'];
                $nestedData[] = $value['first_name'];
                $nestedData[] = $value['last_name'];
                $nestedData[] = $value['email_address'];
                $nestedData[] = $value['company_name'];
                $is_escrow = $is_lender = $is_mortgage = false;
                if ($value['is_escrow'] == 1) {
                    $is_escrow = true;
                }

                if ($value['is_mortgage_user'] == 1) {
                    $is_mortgage = true;
                }

                if ($value['is_mortgage_user'] == 0 && $value['is_escrow'] == 0) {
                    $is_lender = true;
                }

                $salesRepList = '<select class="custom-select custom-select-sm form-control form-control-sm" onchange="updateClientType(' . $user_id . ', this.value);" id="clientlist">
                                    <option value="">Select Client Type</option>
                                    <option ' . (($is_escrow) ? "selected" : "") . ' value="escrow">Escrow</option>
                                    <option ' . (($is_mortgage) ? "selected" : "") . ' value="mortgage">Mortgage Broker</option>
                                    <option ' . (($is_lender) ? "selected" : "") . ' value="lender">Lender</option>
                                </select>';
                $nestedData[] = $salesRepList;

                $data[] = $nestedData;
            }
        }
        $json_data['recordsTotal'] = intval($mortgage_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($mortgage_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function updateClientType()
    {
        $user_id = $this->input->post('user_id');
        $type = $this->input->post('type');
        if (empty($user_id) || empty($type)) {
            $data = array('status' => 'success', 'msg' => 'Invalide request !.');
            echo json_encode($data);exit();
        }

        $data = [
            'is_escrow' => 1,
            'is_mortgage_user' => 0,
        ];

        if ($type == 'escrow') {
            $data['is_escrow'] = 1;
            $data['is_mortgage_user'] = 0;
        }

        if (($type == 'lender')) {
            $data['is_escrow'] = 0;
            $data['is_mortgage_user'] = 0;
        }

        if (($type == 'mortgage')) {
            $data['is_escrow'] = 0;
            $data['is_mortgage_user'] = 1;
        }

        $data['updated_at'] = date("Y-m-d H:i:s");

        $condition = array(
            'id' => $user_id,
        );
        $this->db->update('customer_basic_details', $data, $condition);

        /** Save user Activity */
        $orderUser = $this->home_model->get_user($condition);
        $activity = 'Client Type for user :- ' . $orderUser['email_address'] . 'updated';
        $this->order->logAdminActivity($activity);
        /** End save user activity */

        $data = array('status' => 'success', 'msg' => 'Client type updated successfully.');
        echo json_encode($data);
    }

    public function isMortgagePrimaryUser()
    {
        $user_id = $this->input->post('user_id');
        $primaryMortgageUserFlag = $this->input->post('primaryMortgageUserFlag');
        $data['is_primary_mortgage_user'] = $primaryMortgageUserFlag;
        $data['updated_at'] = date("Y-m-d H:i:s");
        $condition = array(
            'id' => $user_id,
        );
        $this->db->update('customer_basic_details', $data, $condition);

        /** Save user Activity */
        $orderUser = $this->home_model->get_user($condition);
        $activity = 'Mortgage flag for user :- ' . $orderUser['email_address'] . 'updated';
        $this->order->logAdminActivity($activity);
        /** End save user activity */

        $data = array('status' => 'success', 'msg' => 'Mortgage user updated successfully.');
        echo json_encode($data);
    }

    public function getFormDetails()
    {
        $this->load->model('order/fileDocument_model');
        $this->load->model('order/titleOfficerForms');
        $formId = $this->input->post('formId');
        $formDetails = $this->fileDocument_model->get_rows(array('id' => $formId));
        $titleOfficers = $this->titleOfficerForms->getTitleOfficersForForm($formId);
        $response = array('status' => 'success', 'formDetails' => $formDetails, 'titleOfficers' => $titleOfficers);
        echo json_encode($response);
        exit;
    }

    public function deleteForm()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
        if ($id) {
            $formData = $this->home_model->get_rows(array('id' => $id), 'pct_file_documents');
            $this->db->delete('pct_file_documents', array('id' => $id));
            $this->db->delete('pct_order_title_officers_forms', array('form_id' => $id));
            $successMsg = 'Form deleted successfully.';
            $response = array('status' => 'success', 'message' => $successMsg);

            /** Save user Activity */
            $activity = 'Document form record deleted - Document Name :- ' . $formData['name'];
            $this->order->logAdminActivity($activity);
            /** End save user activity */

        } else {
            $msg = 'Form ID is required.';
            $response = array('status' => 'error', 'message' => $msg);
        }
        echo json_encode($response);
    }

    public function changePassword()
    {
        $this->load->model('order/apiLogs');
        $id = $this->input->post('id');
        $userdata = $this->session->userdata('admin');
        $params = array(
            'id' => $id,
        );
        $userInfo = $this->home_model->get_rows($params);
        $response = $this->addNewUserToResware($userInfo);
        if ($response['success']) {
            $customerData['resware_user_id'] = $response['resware_user_id'];
            $customerData['random_password'] = $this->order->randomPassword();
            $reswareUpdatePwdData = array(
                'user_name' => $userInfo['email_address'],
                'password' => 'Pacific1',
                'new_password' => $customerData['random_password'],
            );
            $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'change_password', env('RESWARE_UPDATE_PWD_API'), $reswareUpdatePwdData, array(), 0, 0);
            $updatePwdResult = $this->updatePasswordResware($reswareUpdatePwdData);
            $this->apiLogs->syncLogs($userdata['id'], 'resware', 'change_password', env('RESWARE_UPDATE_PWD_API'), $reswareUpdatePwdData, $updatePwdResult, 0, $logid);
            $responsePwd = json_decode($updatePwdResult, true);

            if (!empty($responsePwd['message'])) {
                $customerData['resware_error_msg'] = $responsePwd['message'];
                $data['error_msg'] = 'Password update failed due to: ' . $responsePwd['message'];
                $response = array('status' => 'error', 'message' => $data['error_msg']);
            } else {
                $customerData['is_password_updated'] = 1;
                $data['success_msg'] = 'Password updated successfully for email user: ' . $this->input->post('email_address');
                $response = array('status' => 'success', 'message' => $data['success_msg']);
            }

            $updateCondition = array(
                'partner_id' => $userInfo['partner_id'],
                'resware_user_id' => $userInfo['resware_user_id'],
            );
            $this->home_model->update($customerData, $updateCondition);
        } else {
            $data['error_msg'] = $response['msg'];
            $response = array('status' => 'error', 'message' => $data['error_msg']);
        }
        echo json_encode($response);
    }

    public function isPasswordRequired()
    {
        $user_id = $this->input->post('user_id');
        $is_password_required = $this->input->post('is_password_required');
        $data['is_password_required'] = $is_password_required;
        $data['updated_at'] = date("Y-m-d H:i:s");
        $condition = array(
            'id' => $user_id,
        );
        $this->db->update('customer_basic_details', $data, $condition);
        /** Save user Activity */
        $orderUser = $this->home_model->get_user($condition);
        $activity = 'Is password require to send password for user :- ' . $orderUser['email_address'] . ' : ' . (($is_password_required == 1) ? 'Yes' : 'No');
        $this->order->logAdminActivity($activity);
        /** End save user activity */
        $data = array('status' => 'success', 'msg' => 'Password required field updated successfully.');
        echo json_encode($data);
    }

    public function refreshExipredPasswords()
    {
        $command = "php " . FCPATH . "index.php frontend/order/cron passwordUpdateAll";
        if (substr(php_uname(), 0, 7) == "Windows") {
            pclose(popen("start /B " . $command, "r"));
        } else {
            exec($command . " > /dev/null &");
        }
        echo json_encode(array('status' => 'success', 'message' => 'Script execution is in process.'));

    }

    public function sendDailyProductionReport()
    {
        $this->load->library('order/order');
        $result = $this->order->sendDailyProductionReport(1);
        if ($result) {
            echo json_encode(array('status' => 'success', 'message' => 'Daily production mail sent successfully.'));
        } else {
            echo json_encode(array('status' => 'error'));
        }
    }

    public function sendLPReports()
    {
        // echo "admin/order/home";die;
        $this->load->library('order/order');
        $result = $this->order->sendLPReports(1);
        if ($result) {
            echo json_encode(array('status' => 'success', 'message' => 'LP Report mail sent successfully.'));
        } else {
            echo json_encode(array('status' => 'error'));
        }
    }

    public function updateDualCplUser()
    {
        $user_id = $this->input->post('user_id');
        $dualCplUserFlag = $this->input->post('dualCplUserFlag');
        $data['is_dual_cpl'] = $dualCplUserFlag;
        $data['updated_at'] = date("Y-m-d H:i:s");
        $condition = array(
            'id' => $user_id,
        );
        $this->db->update('customer_basic_details', $data, $condition);
        $data = array('status' => 'success', 'msg' => 'Dual Cpl value updated successfully for user.');
        /** Save user Activity */
        $orderUser = $this->home_model->get_user($condition);
        $activity = 'Dual Cpl value: ' . $dualCplUserFlag . ' updated successfully for user : ' . $orderUser['email_address'];
        $this->order->logAdminActivity($activity);
        /** End Save user activity */
        echo json_encode($data);
    }

    public function pre_listing_document()
    {
        $data = array();
        $data['title'] = 'PCT Order: Pre Listing Documents';
        $this->admintemplate->show("order/home", "pre_listing_document", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/pre_listing_document', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function lp_listing_document()
    {
        $data = array();
        $data['title'] = 'PCT Order: Pre Listing Report Documents';
        $this->admintemplate->show("order/home", "lp_listing_document", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/lp_listing_document', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_pre_listing_document_list()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['is_escrow'] = 0;
            $pageno = ($params['start'] / $params['length']) + 1;
            $pre_listing_document_lists = $this->home_model->get_pre_listing_document_list($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $pre_listing_document_lists = $this->home_model->get_pre_listing_document_list($params);
        }

        $data = array();

        if (isset($pre_listing_document_lists['data']) && !empty($pre_listing_document_lists['data'])) {
            $i = $params['start'] + 1;
            foreach ($pre_listing_document_lists['data'] as $key => $value) {
                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $value['lp_file_number'];
                $nestedData[] = $value['document_name'];
                $documentName = $value['document_name'];
                // if ($value['api_document_id'] > 0) {
                //     $nestedData[] = 'Yes';
                // } else {
                //     $nestedData[] = 'No';
                // }

                $nestedData[] = convertTimezone($value['created']);

                $documentUrl = env('AWS_PATH') . "pre-listing-doc/" . $documentName;
                if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $nestedData[] = "<div style='display:flex;'><a href='#' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"report"' . ");'><i class='fas fa-fw fa-download'></i></a>
                    <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                }
                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($pre_listing_document_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($pre_listing_document_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function get_lp_listing_document_list()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['is_escrow'] = 0;
            $pageno = ($params['start'] / $params['length']) + 1;
            $lp_listing_document_lists = $this->home_model->get_lp_listing_document_list($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $lp_listing_document_lists = $this->home_model->get_lp_listing_document_list($params);
        }

        $data = array();

        if (isset($lp_listing_document_lists['data']) && !empty($lp_listing_document_lists['data'])) {
            $i = $params['start'] + 1;
            foreach ($lp_listing_document_lists['data'] as $key => $value) {
                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $value['lp_file_number'];
                $nestedData[] = $value['document_name'];
                $documentName = $value['document_name'];

                $lp_report_status = $value['lp_report_status'];
                $lpReportStatusSelection = '<select onchange="updateLpReportStatus(' . $value['file_id'] . ',this.value);" id="lp_report_status" name="lp_report_status">
                                    <option value="">Select</option>
                                    <option value="pending">Pending</option>
                                    <option value="approved">Approved</option>
                                    <option value="denied">Denied</option>
                                </select>';
                $lpReportStatusSelection = str_replace('value="' . $lp_report_status . '"', 'value="' . $lp_report_status . '" selected', $lpReportStatusSelection);
                $nestedData[] = $lpReportStatusSelection;

                $nestedData[] = convertTimezone($value['created']);

                $documentUrl = env('AWS_PATH') . "pre-listing-doc/" . $documentName;
                if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $nestedData[] = "<div style='display:flex;'><a href='#' onclick='downloadDocumentFromAws(" . '"' . $documentUrl . '"' . ", " . '"report"' . ");'><i class='fas fa-fw fa-download'></i></a>
                    <a style='margin-left:10px;' target='_blank' href='$documentUrl'><i class='fas fa-fw fa-eye'></i></a></div>";
                }
                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($lp_listing_document_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($lp_listing_document_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function updateLpReportStatus()
    {
        $this->load->model('order/apiLogs');
        $this->load->library('order/twilio');
        $this->load->model('order/twilioMessage');
        $file_id = $this->input->post('file_id');
        $status = $this->input->post('status');
        $updateData = array('lp_report_status' => $status);
        $condition = array('file_id' => $file_id);
        $this->home_model->update($updateData, $condition, 'order_details');
        $order_details = $this->order_model->get_order_details($file_id);

        if (!empty($order_details['sales_rep_phone']) && $status == 'approved') {
            $sid = env('TWILIO_SID');
            $token = env('TWILIO_TOKEN');
            $from = env('TWILIO_FROM');

            $lpReportUrl = env('AWS_PATH') . "pre-listing-doc/pre_listing_report_" . $order_details['lp_file_number'] . ".pdf";
            $message = "LP Report is ready for file number <a href='$lpReportUrl'>" . $order_details['lp_file_number'] . "</a>";
            $logid = $this->apiLogs->syncLogs('', 'twilio', 'send_message', '', array('message' => $message, 'account_sid' => $sid, 'token' => $token, 'to' => $order_details['sales_rep_phone'], 'from' => $from), array(), 0, 0);

            //$order_details['sales_rep_phone'] = '12133097286';
            try {
                $result = $this->twilio->message($order_details['sales_rep_phone'], $message, '', array('from' => $from));
                $response = $result->toArray();
                $response['msg_status'] = 'success';
                $response['code'] = $code;

            } catch (Exception $e) {
                $response['sid'] = '';
                $response['to'] = $order_details['sales_rep_phone'];
                $response['msg_status'] = 'error';
                $response['errorCode'] = $e->getCode();
                $response['errorMessage'] = $e->getMessage();
            } catch (\Twilio\Exceptions\RestException $e) {
                $response['sid'] = '';
                $response['to'] = $order_details['sales_rep_phone'];
                $response['msg_status'] = 'error';
                $response['errorCode'] = $e->getCode();
                $response['errorMessage'] = $e->getMessage();
            }

            $this->apiLogs->syncLogs('', 'twilio', 'send_message', '', array('code' => $code, 'account_sid' => $sid, 'token' => $token, 'to' => $order_details['sales_rep_phone'], 'from' => $from), $response, 0, $logid);

            if ($response['msg_status'] == 'success') {
                $data = array(
                    'message' => $response['body'],
                    'sent_from' => $response['from'],
                    'sent_to' => $response['to'],
                    'status' => $response['status'],
                    'message_sid' => $response['sid'],
                    'error_code' => $response['errorCode'],
                    'error_message' => $response['errorMessage'],
                );
                $this->twilioMessage->insert($data);
                $result = array('msg_status' => 'success', 'message' => 'Code generated successfully.');
            } else {
                $result = array('msg_status' => 'error', 'error_message' => $response['errorMessage']);
            }

            $timezone = -8;
            $orderNumber = $order_details['lp_file_number'];
            $data = array(
                'orderNumber' => $order_details['lp_file_number'],
                'orderId' => $file_id,
                'OpenName' => $order_details['cust_first_name'] . ' ' . $order_details['cust_last_name'],
                'Opentelephone' => $order_details['cust_telephone_no'],
                'OpenEmail' => $order_details['cust_email_address'],
                'CompanyName' => $order_details['cust_company_name'],
                'StreetAddress' => $order_details['cust_street_address'],
                'City' => $order_details['cust_city'],
                'Zipcode' => $order_details['cust_zip_code'],
                'openAt' => gmdate("m-d-Y h:i A", strtotime($orderDetails['opened_date']) + 3600 * ($timezone + date("I"))),
                'PropertyAddress' => $order_details['address'],
                'FullProperty' => $order_details['full_address'],
                'APN' => $order_details['apn'],
                'County' => $order_details['county'],
                'LegalDescription' => $order_details['legal_description'],
                'PrimaryOwner' => $order_details['primary_owner'],
                'SecondaryOwner' => $order_details['secondary_owner'],
                'SalesRep' => $order_details['salerep_first_name'] . ' ' . $order_details['salerep_last_name'],
                'TitleOfficer' => $order_details['titleofficer_first_name'] . ' ' . $order_details['titleofficer_last_name'],
                'ProductType' => $order_details['product_type'],
                'SalesAmount' => $order_details['sales_amount'],
                'LoanAmount' => $order_details['loan_amount'],
                'LoanNumber' => $order_details['loan_number'],
                'EscrowNumber' => $order_details['escrow_officer_id'],
                'randomString' => $order_details['random_number'],
            );

            $buyerDetails = $listingDetails = $parties_email = array();

            if (isset($order_details['lender_id']) && !empty($order_details['lender_id'])) {
                $data['lender_details'] = array(
                    'name' => $order_details['lender_first_name'],
                    'email' => $order_details['lender_email'],
                    'telephone' => $order_details['lender_telephone_no'],
                    'company' => $order_details['lender_company_name'],
                );
            }

            if (isset($order_details['buyer_agent_id']) && !empty($order_details['buyer_agent_id'])) {
                $buyerDetails = $this->agent_model->get_agents(array('id' => $order_details['buyer_agent_id']));
                if (!empty($buyerDetails)) {
                    $data['buyers_agent'] = array(
                        'name' => $buyerDetails['name'],
                        'email' => $buyerDetails['email_address'],
                        'telephone' => $buyerDetails['telephone_no'],
                        'company' => $buyerDetails['company'],
                    );
                }

            }

            if (isset($order_details['listing_agent_id']) && !empty($order_details['listing_agent_id'])) {
                $listingDetails = $this->agent_model->get_agents(array('id' => $order_details['listing_agent_id']));
                if (!empty($listingDetails)) {
                    $data['listing_agent'] = array(
                        'name' => $listingDetails['name'],
                        'email' => $listingDetails['email_address'],
                        'telephone' => $listingDetails['telephone_no'],
                        'company' => $listingDetails['company'],
                    );
                }
            }

            $from_name = 'Pacific Coast Title Company';
            $from_mail = env('FROM_EMAIL');
            $order_message_body = $this->load->view('emails/order.php', $data, true);
            $message = $order_message_body;
            $subject = 'PDF:' . $orderNumber;
            $to = $order_details['cust_email_address'];
            $parties_email[] = $order_details['salerep_email_address'];
            $file = array();
            $lpReportName = 'pre_listing_report_' . $orderNumber . '.pdf';
            /**
             * Comment From Jerry on 26th July, 2024
             * Once the LP is approved we can send the Email with the LP and documents attached.
             */
            $lvfilename = $orderNumber . '.pdf';
            $deedfilename = $orderNumber . '.pdf';
            $taxfilename = $orderNumber . '.pdf';
            $file[] = env('AWS_PATH') . "legal-vesting/" . $lvfilename;
            $file[] = env('AWS_PATH') . "grant-deed/" . $deedfilename;
            $file[] = env('AWS_PATH') . "tax/" . $taxfilename;
            $file[] = env('AWS_PATH') . "pre-listing-doc/" . $lpReportName;

            $cc = isset($parties_email) && !empty($parties_email) ? $parties_email : array();
            $this->load->helper('sendemail');
            // $cc = array('piyush.j@crestinfosystems.net');
            // $to = 'piyush-crest@yopmail.com';

            $mailParams = array(
                'from_mail' => $from_mail,
                'from_name' => $from_name,
                'to' => $to,
                'subject' => $subject,
                'message' => json_encode($data),
                'file' => json_encode($file),
                'cc' => json_encode($cc),
            );

            if (isset($order_details['lp_file_number']) && !empty($order_details['lp_file_number'])) {
                $logid = $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_confirmation_LP_order_mail', '', $mailParams, array(), $order_details['order_id'], 0);
                try {
                    $mail_result = send_email($from_mail, $from_name, $to, $subject, $message, $file, $cc, array());
                } catch (Exception $e) {

                }
                $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_confirmation_LP_order_mail', '', $mailParams, array('status' => $mail_result), $order_details['order_id'], $logid);
            }
        }
        /** Save user Activity */
        $activity = $order_details['lp_file_number'] . '- LP Order status updated to : ' . $status;
        $this->order->logAdminActivity($activity);
        /** End Save user activity */
        $data = array('status' => 'success', 'msg' => 'Lp report status updated successfully.');
        echo json_encode($data);
        exit;
    }

    public function addNotesToResware($fileId)
    {

        $this->load->model('order/apiLogs');
        $this->load->library('order/resware');
        $userdata = $this->session->userdata('admin');
        // $fileId = isset($_POST['file_id']) && !empty($_POST['file_id']) ? $_POST['file_id'] : '';
        $subject = isset($_POST['note_subject']) && !empty($_POST['note_subject']) ? $_POST['note_subject'] : '';
        $body = isset($_POST['note']) && !empty($_POST['note']) ? $_POST['note'] : '';
        $orderDetails = $this->order->get_order_details($fileId);
        $orderId = isset($orderDetails['order_id']) && !empty($orderDetails['order_id']) ? $orderDetails['order_id'] : '';

        $request = array();
        $endPoint = 'files/' . $fileId . '/notes';
        $request['Subject'] = $subject;
        $request['Body'] = $body;
        $request['FileID'] = $fileId;
        $request['Expedite'] = true;
        $notes_data = json_encode($request);
        $user_data['admin_api'] = 1;
        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_ion_fraud_note', env('RESWARE_ORDER_API') . $endPoint, $notes_data, array(), $orderId, 0);
        $result = $this->resware->make_request('POST', $endPoint, $notes_data, $user_data);
        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_ion_fraud_note', env('RESWARE_ORDER_API') . $endPoint, $notes_data, $result, $orderId, $logid);
        // print_r($result);die;

        if (isset($result) && !empty($result)) {
            $response = json_decode($result, true);

            if (isset($response['ResponseStatus']) && !empty($response['ResponseStatus'])) {
                $msg = isset($response['ResponseStatus']['Message']) && !empty($response['ResponseStatus']['Message']) ? $response['ResponseStatus']['Message'] : '';
            } else {
                $noteId = isset($response['Note']['NoteID']) && !empty($response['Note']['NoteID']) ? $response['Note']['NoteID'] : '';
                $notesData = array(
                    'resware_note_id' => $noteId,
                    'subject' => $subject,
                    'note' => $body,
                    'user_id' => $userdata['id'],
                    'order_id' => $orderId,
                    'task_id' => isset($_POST['task_id']) ? $_POST['task_id'] : 0,
                );
                $id = $this->home_model->insert($notesData, 'pct_order_notes');

                if ($noteId && $id) {
                    $msg .= 'Note created successfully.';
                    // $syncStatus = $this->sendOrderToResware('notes');
                    // if ($syncStatus['status' == 'success']) {
                    //     $success .= $syncStatus['message'];
                    // } else {
                    //     $errors .= $syncStatus['message'];
                    // }
                    // $data = array(
                    //     "error" => $errors,
                    //     "success" => $success,
                    // );
                    // $this->session->set_userdata($data);

                    $data = array('status' => 'success', 'message' => $msg);
                    // redirect(base_url() . 'order/admin/lp-orders');
                } else {
                    $msg .= 'Something went wrong while syncing notes to resware.';
                    $data = array('status' => 'error', 'message' => $msg);
                }
            }
            /** Save user Activity */
            $activity = $msg . ' Sync to resware order number: ' . $orderDetails['file_number'];
            $this->order->logAdminActivity($activity);
            /** End Save user activity */
        }
        return $data;
        // echo json_encode($data);
        // exit;

    }

    public function sendOrderToResware()
    {
        $this->load->model('order/partnerApiLogs');

        $configData = $this->order->getConfigData();
        $addUnderwritenPartnerViaApi = $configData['add_underwriten_partner_via_api']['is_enable'];

        $file_id = $this->input->post('file_id');
        // echo "<pre>";
        // print_r($this->input->post());die;
        $order_details = $this->order_model->get_order_details($file_id);
        $lpFileNumber = $order_details['lp_file_number'];
        $splitName = explode(' ', $order_details['primary_owner']);
        $ownerLastName = end($splitName);
        $primaryName = array_slice($splitName, 0, -1);
        $ownerFirstName = implode(" ", $primaryName);
        $userdata = $this->session->userdata('admin');

        $place_order = array();
        $loanFlag = 1;
        $legalEntity = array(
            'EntityType' => 'INDIVIDUAL',
            'IsPrimaryTransactee' => 'true',
            'primary' => array(
                'First' => $ownerFirstName,
                'Last' => $ownerLastName,
            ),
            'Address' => array(
                'Address1' => $order_details['address'],
                'City' => $order_details['property_city'],
                'State' => $order_details['property_state'],
                'Zip' => $order_details['property_zip'],
            ),
        );

        if (strpos($order_details['product_type'], 'Loan') !== false) {
            $place_order['Buyers'][] = $legalEntity;
        } elseif (strpos($order_details['product_type'], 'Sale') !== false) {
            $borrowerName = explode(' ', $order_details['borrower']);
            $borrowerLastName = end($borrowerName);
            $borrowerPrimaryName = array_slice($borrowerName, 0, -1);
            $borrowerFirstName = implode(" ", $borrowerPrimaryName);
            $borrowers = array(
                'EntityType' => 'INDIVIDUAL',
                'IsPrimaryTransactee' => 'true',
                'primary' => array(
                    'First' => $borrowerFirstName,
                    'Last' => !empty($borrowerLastName) ? $borrowerLastName : $borrowerFirstName,
                ),
            );
            $place_order['Sellers'][] = $legalEntity;
            $place_order['Buyers'][] = $borrowers;
            $place_order['SalesPrice'] = $order_details['sales_amount'];
            $loanFlag = 0;
        }

        $place_order['TransactionProductType'] = array(
            "TransactionTypeID" => $order_details['transaction_type'],
            'ProductTypeID' => $order_details['purchase_type'],
        );
        $loan = array();
        if (isset($order_details['loan_amount']) && !empty($order_details['loan_amount'])) {
            $loan['LoanAmount'] = $order_details['loan_amount'];
        }

        if (isset($order_details['loan_number']) && !empty($order_details['loan_number'])) {
            $loan['LoanNumber'] = $loan_number;
        }

        if ($order_details['purchase_type'] == '4' || $order_details['purchase_type'] == '5' || $order_details['purchase_type'] == '36') {
            $loan['LienPosition'] = 0;
            $loan['LoanType'] = 'ConvIns';
            $place_order['SettlementStatementVersion'] = 'HUD';
        }

        $splitPropertyAddress = explode(' ', $order_details['address']);
        $streetNumber = isset($splitPropertyAddress[0]) && !empty($splitPropertyAddress[0]) ? $splitPropertyAddress[0] : '';
        $primaryStreetName = array_slice($splitPropertyAddress, 1);
        $streetName = isset($primaryStreetName) && !empty($primaryStreetName) ? implode(" ", $primaryStreetName) : '';

        $place_order['Loans'][] = $loan;
        $place_order['Properties'][] = array(
            'IsPrimary' => 'true',
            'StreetNumber' => $streetNumber,
            'StreetName' => $streetName,
            'City' => $order_details['property_city'],
            'State' => $order_details['property_state'],
            'County' => $order_details['county'],
            'Zip' => $order_details['property_zip'],
        );
        $place_order['Note']['APN'] = $order_details['apn'];
        $place_order['Note']['parcel_id'] = $order_details['apn'];
        $place_order['Note']['legal_description'] = $order_details['legal_description'];

        if (!empty($order_details['title_officer_name'])) {
            $place_order['Note']['title_Officer'] = $order_details['title_officer_name'];
        }

        if (!empty($order_details['sales_rep_name'])) {
            $place_order['Note']['sales_rep'] = $order_details['sales_rep_name'];
        }

        if (!empty($order_details['buyer_agent_id'])) {
            $buyers_agent_details = array(
                'name' => $order_details['buyer_agent_name'],
                'email' => $order_details['buyer_agent_email_address'],
                'telephone' => $order_details['buyer_agent_company'],
                'company' => $order_details['buyer_agent_telephone_no'],
            );
            $place_order['Note']['buyers_agent'] = $buyers_agent_details;
        }

        if (!empty($order_details['listing_agent_id'])) {
            $listing_agent_details = array(
                'name' => $order_details['listing_agent_name'],
                'email' => $order_details['listing_agent_email_address'],
                'telephone' => $order_details['listing_agent_company'],
                'company' => $order_details['listing_agent_telephone_no'],
            );
            $place_order['Note']['listing_agent'] = $listing_agent_details;
        }

        if (isset($order_details['lender_id']) && !empty($order_details['lender_id'])) {
            $lender_details = array(
                'name' => $order_details['lender_first_name'],
                'email' => $order_details['lender_email'],
                'telephone' => $order_details['lender_telephone_no'],
                'company' => $order_details['lender_company_name'],
            );
            $place_order['Note']['lender_details'] = $lender_details;
        }

        // if (!empty($escrow_details)) {
        //     $place_order['Note']['escrow_details'] = $escrow_details;
        // }

        if (!empty($order_details['escrow_number'])) {
            $place_order['Note']['EscrowNumber'] = $order_details['escrow_number'];
        }

        if (!empty($order_details['notes'])) {
            $place_order['Note']['Notes'] = $order_details['notes'];
        }

        $user_data = array();
        $orderUser = $this->home_model->get_user(array('id' => $order_details['customer_id']));
        $user_data['email'] = $orderUser['email_address'];
        $user_data['password'] = $orderUser['random_password'];
        $user_data['from_mail'] = 1;

        $is_escrow = $orderUser['is_escrow'];
        $con = array(
            'where' => array(
                'partner_id' => $orderUser['partner_id'],
            ),
        );
        $companyData = $this->home_model->get_company_rows($con);
        // echo "<pre>";
        // print_r($companyData);die;
        $order_data = json_encode($place_order);
        $this->load->library('order/resware');
        $this->load->model('order/apiLogs');

        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_order_from_admin', env('RESWARE_ORDER_API') . 'orders', $order_data, array(), 0, 0);
        $result = $this->resware->make_request('POST', 'orders', $order_data, $user_data);
        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_order_from_admin', env('RESWARE_ORDER_API') . 'orders', $order_data, $result, 0, $logid);
        if (isset($result) && !empty($result)) {
            $response = json_decode($result, true);
            if (isset($response['ResponseStatus']) && !empty($response['ResponseStatus'])) {
                $message = isset($response['ResponseStatus']['Message']) && !empty($response['ResponseStatus']['Message']) ? $response['ResponseStatus']['Message'] : '';
                $response = array('status' => 'error', 'message' => $message);
                /* Start add resware api logs */
                $reswareLogData = array(
                    'request_type' => 'create_order_from_admin_in_resware',
                    'request_url' => env('RESWARE_ORDER_API') . $endPoint,
                    'request' => $order_data,
                    'response' => $result,
                    'status' => $message,
                    'created_at' => date("Y-m-d H:i:s"),
                );
                $this->db->insert('pct_resware_log', $reswareLogData);
                /* End add resware api logs */
                echo json_encode($response);
                exit;
            } else {
                $orderNumber = $file_id = '';
                if (isset($response['FileID']) && !empty($response['FileID'])) {
                    $orderNumber = $fileNumber = isset($response['FileNumber']) && !empty($response['FileNumber']) ? $response['FileNumber'] : '';
                    $file_id = isset($response['FileID']) && !empty($response['FileID']) ? $response['FileID'] : '';
                }
                /* Start add resware api logs */
                $reswareLogData = array(
                    'request_type' => 'create_order_from_admin_in_resware',
                    'request_url' => env('RESWARE_ORDER_API') . $endPoint,
                    'request' => $order_data,
                    'response' => $result,
                    'file_id' => $file_id,
                    'file_number' => $fileNumber,
                    'status' => $response['ResponseStatus'],
                    'created_at' => date("Y-m-d H:i:s"),
                );
                $this->db->insert('pct_resware_log', $reswareLogData);
                /* End add resware api logs */
                $condition = array('id' => $order_details['order_id']);
                $data = array('file_id' => $file_id, 'file_number' => $fileNumber);
                $update = $this->order_model->update($data, $condition);
                /** Update in title point table */
                $this->db->select('id');
                $this->db->from('pct_order_title_point_data');
                $this->db->where('file_number', $lpFileNumber);
                $query = $this->db->get();
                $tpRecord = $query->row_array();
                if (!empty($tpRecord)) {
                    $tpRecordId = $tpRecord['id'];
                    $this->db->update('pct_order_title_point_data', array('file_number' => $fileNumber, 'file_id' => $file_id), array('id' => $tpRecordId));
                }
                /** End update in title point table */
                $order_details['file_id'] = $file_id;

                /** Party details */
                if ($orderNumber) {
                    $this->load->library('order/order');
                    $partners = array();
                    $partners[] = array(
                        'PartnerTypeID' => 10049,
                        'PartnerID' => 400023,
                        'PartnerType' => array(
                            'PartnerTypeID' => 10049,
                        ),
                    );
                    $endPoint = 'files/' . $file_id . '/partners';
                    $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_partners_from_admin', env('RESWARE_ORDER_API') . $endPoint, array(), array(), $file_id, 0);
                    $user_data['admin_api'] = 1;

                    $resultPartners = $this->resware->make_request('GET', $endPoint, '', $user_data);
                    $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_partners_from_admin', env('RESWARE_ORDER_API') . $endPoint, array(), $resultPartners, $file_id, $logid);
                    $resPartners = json_decode($resultPartners, true);

                    $secondaryEscrowPartners = array();
                    if (isset($order_details['lender_id']) && !empty($order_details['lender_id'])) {
                        $lender_resware_user_id = isset($order_details['lender_resware_user_id']) && !empty($order_details['lender_resware_user_id']) ? $order_details['lender_resware_user_id'] : '';
                        $lender_partner_id = isset($order_details['lender_partner_id']) && !empty($order_details['lender_partner_id']) ? $order_details['lender_partner_id'] : '';
                        $secondaryEmp[] = array('UserID' => $lender_resware_user_id);
                        $lenderPartnerTypeID = '3';
                        $secondaryLenderPartners = array(
                            'SecondaryEmployees' => $secondaryEmp,
                            'PartnerTypeID' => (int) $lenderPartnerTypeID,
                            'PartnerID' => (int) $lender_partner_id,
                            'PartnerType' => array(
                                'PartnerTypeID' => (int) $lenderPartnerTypeID,
                            ),
                        );
                    }

                    $removePartnerFlag = 0;
                    $key = '';
                    if (!empty($companyData)) {
                        if (!empty($resPartners)) {
                            $key = array_search(7, array_column($resPartners['Partners'], 'PartnerTypeID'));
                            if (str_contains($resPartners['Partners'][$key]['PartnerName'], 'Doma Title Insurance') || $resPartners['Partners'][$key]['PartnerName'] == 'North American Title Insurance Company') {
                                $underWriter = 'north_american';
                            } elseif ($resPartners['Partners'][$key]['PartnerName'] == 'Westcor Land Title Insurance Company') {
                                $underWriter = 'westcor';
                            } else if ($resPartners['Partners'][$key]['PartnerName'] == 'Commonwealth Land Title Insurance Company') {
                                $underWriter = 'commonwealth';
                            } else {
                                if ($key) {
                                    $underWriter = 'other';
                                } else {
                                    $underWriter = 'not_set';
                                }
                            }
                        }
                        if ($addUnderwritenPartnerViaApi == 1) {
                            if ($loanFlag == 1) {
                                if (!empty($underWriter)) {
                                    if ($companyData[0]['loan_underwriter'] == 'north_american') {
                                        if ($underWriter != 'north_american') {
                                            $partners[] = array(
                                                'PartnerTypeID' => 7,
                                                'PartnerID' => 39919,
                                                'PartnerType' => array(
                                                    'PartnerTypeID' => 7,
                                                ),
                                            );
                                            $removePartnerFlag = 1;
                                        } else {
                                            $removePartnerFlag = 0;
                                        }
                                    } else if ($companyData[0]['loan_underwriter'] == 'commonwealth') {
                                        if ($underWriter != 'commonwealth') {
                                            $partners[] = array(
                                                'PartnerTypeID' => 7,
                                                'PartnerID' => 6,
                                                'PartnerType' => array(
                                                    'PartnerTypeID' => 7,
                                                ),
                                            );
                                            $removePartnerFlag = 1;
                                        } else {
                                            $removePartnerFlag = 0;
                                        }
                                    } else if ($companyData[0]['loan_underwriter'] == 'westcor') {
                                        if ($underWriter != 'westcor') {
                                            $partners[] = array(
                                                'PartnerTypeID' => 7,
                                                'PartnerID' => 201324,
                                                'PartnerType' => array(
                                                    'PartnerTypeID' => 7,
                                                ),
                                            );
                                            $removePartnerFlag = 1;
                                        } else {
                                            $removePartnerFlag = 0;
                                        }
                                    } else {
                                        if ($underWriter == 'other') {
                                            $removePartnerFlag = 1;
                                            $partners[] = array(
                                                'PartnerTypeID' => 7,
                                                'PartnerID' => 201324,
                                                'PartnerType' => array(
                                                    'PartnerTypeID' => 7,
                                                ),
                                            );
                                            $underWriter = 'westcor';
                                        } else if ($underWriter == 'not_set') {
                                            $removePartnerFlag = 0;
                                            $partners[] = array(
                                                'PartnerTypeID' => 7,
                                                'PartnerID' => 201324,
                                                'PartnerType' => array(
                                                    'PartnerTypeID' => 7,
                                                ),
                                            );
                                            $underWriter = 'westcor';
                                        }
                                    }
                                }
                            } else {
                                if (!empty($underWriter)) {
                                    if ($companyData[0]['sales_underwriter'] == 'north_american') {
                                        if ($underWriter != 'north_american') {
                                            $partners[] = array(
                                                'PartnerTypeID' => 7,
                                                'PartnerID' => 39919,
                                                'PartnerType' => array(
                                                    'PartnerTypeID' => 7,
                                                ),
                                            );
                                            $removePartnerFlag = 1;
                                        } else {
                                            $removePartnerFlag = 0;
                                        }

                                    } else if ($companyData[0]['sales_underwriter'] == 'commonwealth') {
                                        if ($underWriter != 'commonwealth') {
                                            $partners[] = array(
                                                'PartnerTypeID' => 7,
                                                'PartnerID' => 6,
                                                'PartnerType' => array(
                                                    'PartnerTypeID' => 7,
                                                ),
                                            );
                                            $removePartnerFlag = 1;
                                        } else {
                                            $removePartnerFlag = 0;
                                        }
                                    } else if ($companyData[0]['sales_underwriter'] == 'westcor') {
                                        if ($underWriter != 'westcor') {
                                            $partners[] = array(
                                                'PartnerTypeID' => 7,
                                                'PartnerID' => 201324,
                                                'PartnerType' => array(
                                                    'PartnerTypeID' => 7,
                                                ),
                                            );
                                            $removePartnerFlag = 1;
                                        } else {
                                            $removePartnerFlag = 0;
                                        }
                                    } else {
                                        if ($underWriter == 'other') {
                                            $removePartnerFlag = 1;
                                            $partners[] = array(
                                                'PartnerTypeID' => 7,
                                                'PartnerID' => 201324,
                                                'PartnerType' => array(
                                                    'PartnerTypeID' => 7,
                                                ),
                                            );
                                            $underWriter = 'westcor';
                                        } else if ($underWriter == 'not_set') {
                                            $removePartnerFlag = 0;
                                            $partners[] = array(
                                                'PartnerTypeID' => 7,
                                                'PartnerID' => 201324,
                                                'PartnerType' => array(
                                                    'PartnerTypeID' => 7,
                                                ),
                                            );
                                            $underWriter = 'westcor';
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if ($removePartnerFlag == 1 && isset($key) && strlen($key) > 0) {
                        $removeExistingPartner = array(
                            'PartnerTypeID' => 7,
                            'PartnerID' => $resPartners['Partners'][$key]['PartnerID'],
                            'PartnerType' => array(
                                'PartnerTypeID' => 7,
                            ),
                        );
                        $removePartners[] = $removeExistingPartner;
                    }

                    $escrowKey = $escrowKey1 = $escrowKey2 = '';
                    if (isset($secondaryEscrowPartners) && !empty($secondaryEscrowPartners)) {
                        $escrowKey = array_search(9997, array_column($resPartners['Partners'], 'PartnerTypeID'));
                        $escrowKey1 = array_search(10006, array_column($resPartners['Partners'], 'PartnerTypeID'));
                        $escrowKey2 = array_search(10010, array_column($resPartners['Partners'], 'PartnerTypeID'));
                        if (isset($escrowKey) && strlen($escrowKey) > 0) {
                            $removeEscrowExistingPartner = array(
                                'PartnerTypeID' => 9997,
                                'PartnerID' => $resPartners['Partners'][$escrowKey]['PartnerID'],
                                'PartnerType' => array(
                                    'PartnerTypeID' => 9997,
                                ),
                            );
                            $removePartners[] = $removeEscrowExistingPartner;
                        }
                        if (isset($escrowKey1) && strlen($escrowKey1) > 0) {
                            $removeEscrowExistingPartner = array(
                                'PartnerTypeID' => 10006,
                                'PartnerID' => $resPartners['Partners'][$escrowKey1]['PartnerID'],
                                'PartnerType' => array(
                                    'PartnerTypeID' => 10006,
                                ),
                            );
                            $removePartners[] = $removeEscrowExistingPartner;
                        }
                        if (isset($escrowKey2) && strlen($escrowKey2) > 0) {
                            $removeEscrowExistingPartner = array(
                                'PartnerTypeID' => 10010,
                                'PartnerID' => $resPartners['Partners'][$escrowKey2]['PartnerID'],
                                'PartnerType' => array(
                                    'PartnerTypeID' => 10010,
                                ),
                            );
                            $removePartners[] = $removeEscrowExistingPartner;
                        }
                        $partners[] = $secondaryEscrowPartners;
                    }

                    $lenderKey = '';
                    if (isset($secondaryLenderPartners) && !empty($secondaryLenderPartners)) {
                        $lenderKey = array_search(3, array_column($resPartners['Partners'], 'PartnerTypeID'));
                        if (isset($lenderKey) && strlen($lenderKey) > 0) {
                            $removeLenderExistingPartner = array(
                                'PartnerTypeID' => 3,
                                'PartnerID' => $resPartners['Partners'][$lenderKey]['PartnerID'],
                                'PartnerType' => array(
                                    'PartnerTypeID' => 3,
                                ),
                            );
                            $removePartners[] = $removeLenderExistingPartner;
                        }
                        $partners[] = $secondaryLenderPartners;
                    }

                    $escrowOfficer = $order_details['escrow_officer_id'];
                    $escrowOfficerKey = '';
                    if (!empty($escrowOfficer)) {
                        $escrowOfficerKey = array_search(10010, array_column($resPartners['Partners'], 'PartnerTypeID'));
                        if (isset($escrowOfficerKey) && strlen($escrowOfficerKey) > 0) {
                            $removeEscrowOfcExistingPartner = array(
                                'PartnerTypeID' => 10010,
                                'PartnerID' => $resPartners['Partners'][$escrowOfficerKey]['PartnerID'],
                                'PartnerType' => array(
                                    'PartnerTypeID' => 10010,
                                ),
                            );
                            $removePartners[] = $removeEscrowOfcExistingPartner;
                        }
                        $partners[] = array(
                            'PartnerTypeID' => 10010,
                            'PartnerID' => (int) $escrowOfficer,
                            'PartnerType' => array(
                                'PartnerTypeID' => 10010,
                            ),
                        );
                    }

                    $buyerAgentKey = '';
                    $BuyerAgentId = $order_details['buyer_agent_id'];
                    $buyerAgentPartnerId = $order_details['buyer_agent_partner_id'];
                    if (isset($BuyerAgentId) && !empty($BuyerAgentId)) {
                        $buyerAgentKey = array_search(14, array_column($resPartners['Partners'], 'PartnerTypeID'));
                        if (isset($buyerAgentKey) && strlen($buyerAgentKey) > 0) {
                            $removeBuyerAgentExistingPartner = array(
                                'PartnerTypeID' => 14,
                                'PartnerID' => $resPartners['Partners'][$buyerAgentKey]['PartnerID'],
                                'PartnerType' => array(
                                    'PartnerTypeID' => 14,
                                ),
                            );
                            $removePartners[] = $removeBuyerAgentExistingPartner;
                        }
                        $partners[] = array(
                            'PartnerTypeID' => 14,
                            'PartnerID' => (int) $buyerAgentPartnerId,
                            'PartnerType' => array(
                                'PartnerTypeID' => 14,
                            ),
                        );
                    }

                    $listingAgentKey = '';
                    $ListingAgentId = $order_details['listing_agent_id'];
                    $listingAgentPartnerId = $order_details['listing_agent_partner_id'];
                    if (isset($ListingAgentId) && !empty($ListingAgentId)) {
                        $listingAgentKey = array_search(15, array_column($resPartners['Partners'], 'PartnerTypeID'));
                        if (isset($listingAgentKey) && strlen($listingAgentKey) > 0) {
                            $removelistingAgentExistingPartner = array(
                                'PartnerTypeID' => 15,
                                'PartnerID' => $resPartners['Partners'][$listingAgentKey]['PartnerID'],
                                'PartnerType' => array(
                                    'PartnerTypeID' => 15,
                                ),
                            );
                            $removePartners[] = $removelistingAgentExistingPartner;
                        }
                        $partners[] = array(
                            'PartnerTypeID' => 15,
                            'PartnerID' => (int) $listingAgentPartnerId,
                            'PartnerType' => array(
                                'PartnerTypeID' => 15,
                            ),
                        );
                    }

                    $salesRepKey = '';
                    $SalesRep = $order_details['sales_representative'];
                    $condition = array(
                        'id' => $SalesRep,
                    );
                    $salesRepDetails = $this->home_model->getSalesRepDetails($condition);
                    if (!empty($salesRepDetails)) {
                        if (!empty($salesRepDetails['partner_id']) && !empty($salesRepDetails['partner_type_id'])) {
                            $salesRepKey = array_search((int) $salesRepDetails['partner_type_id'], array_column($resPartners['Partners'], 'PartnerTypeID'));
                            if (isset($salesRepKey) && strlen($salesRepKey) > 0) {
                                $removeSalesRepExistingPartner = array(
                                    'PartnerTypeID' => (int) $salesRepDetails['partner_type_id'],
                                    'PartnerID' => $resPartners['Partners'][$salesRepKey]['PartnerID'],
                                    'PartnerType' => array(
                                        'PartnerTypeID' => (int) $salesRepDetails['partner_type_id'],
                                    ),
                                );
                                $removePartners[] = $removeSalesRepExistingPartner;
                            }
                            $partners[] = array(
                                'PartnerTypeID' => (int) $salesRepDetails['partner_type_id'],
                                'PartnerID' => (int) $salesRepDetails['partner_id'],
                                'PartnerType' => array(
                                    'PartnerTypeID' => (int) $salesRepDetails['partner_type_id'],
                                ),
                            );
                        }
                    }

                    $titleOfficerKey = '';
                    $TitleOfficer = $order_details['title_officer'];
                    $condition = array(
                        'id' => $TitleOfficer,
                    );
                    $titleOfficerDetails = $this->home_model->getTitleOfficerDetails($condition);
                    if (!empty($titleOfficerDetails)) {
                        if (!empty($titleOfficerDetails['partner_id']) && !empty($titleOfficerDetails['partner_type_id'])) {
                            $titleOfficerKey = array_search((int) $titleOfficerDetails['partner_type_id'], array_column($resPartners['Partners'], 'PartnerTypeID'));
                            if (isset($titleOfficerKey) && strlen($titleOfficerKey) > 0) {
                                $removeTitleOfficerExistingPartner = array(
                                    'PartnerTypeID' => (int) $titleOfficerDetails['partner_type_id'],
                                    'PartnerID' => $resPartners['Partners'][$titleOfficerKey]['PartnerID'],
                                    'PartnerType' => array(
                                        'PartnerTypeID' => (int) $titleOfficerDetails['partner_type_id'],
                                    ),
                                );
                                $removePartners[] = $removeTitleOfficerExistingPartner;
                            }
                            $partners[] = array(
                                'PartnerTypeID' => (int) $titleOfficerDetails['partner_type_id'],
                                'PartnerID' => (int) $titleOfficerDetails['partner_id'],
                                'PartnerType' => array(
                                    'PartnerTypeID' => (int) $titleOfficerDetails['partner_type_id'],
                                ),
                            );
                        }
                    }

                    $partnerUserData = array(
                        'admin_api' => 1,
                    );

                    if (!empty($removePartners)) {
                        $removePartnerData = json_encode(array('Partners' => $removePartners));
                        $endPoint = 'files/' . $file_id . '/partners';
                        $removeLogid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'delete_partner_from_admin', env('RESWARE_ORDER_API') . $endPoint, $removePartnerData, array(), 0, 0);
                        $resultRemovePartner = $this->resware->make_request('DELETE', $endPoint, $removePartnerData, $partnerUserData);
                        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'delete_partner_from_admin', env('RESWARE_ORDER_API') . $endPoint, $removePartnerData, $resultRemovePartner, 0, $removeLogid);
                        $resultRemovePartnerRes = json_decode($resultRemovePartner, true);
                        /* Start add resware api logs */
                        $reswareLogData = array(
                            'request_type' => 'delete_partner_from_admin_in_resware',
                            'request_url' => env('RESWARE_ORDER_API') . $endPoint,
                            'request' => $removePartnerData,
                            'response' => $resultRemovePartner,
                            'status' => '',
                            'created_at' => date("Y-m-d H:i:s"),
                        );
                        $this->db->insert('pct_resware_log', $reswareLogData);
                        /* End add resware api logs */
                        $partnerKey = '';
                        if (isset($resultRemovePartnerRes['ResponseStatus']['Message']) && !empty($resultRemovePartnerRes['ResponseStatus']['Message'])) {
                            if (str_contains($resultRemovePartnerRes['ResponseStatus']['Message'], 'Doma Title Insurance') || str_contains($resultRemovePartnerRes['ResponseStatus']['Message'], 'North American Title Insurance Company') || str_contains($resultRemovePartnerRes['ResponseStatus']['Message'], 'Westcor Land Title Insurance Company') || str_contains($resultRemovePartnerRes['ResponseStatus']['Message'], 'Commonwealth Land Title Insurance Company')) {
                                $partnerKey = array_search(7, array_column($partners, 'PartnerTypeID'));
                                if (strlen($partnerKey) > 0) {
                                    array_splice($partners, $partnerKey, 1);
                                    $removeParentKey = array_search(7, array_column($removePartners, 'PartnerTypeID'));
                                    if (strlen($removeParentKey) > 0) {
                                        array_splice($removePartners, $removeParentKey, 1);
                                        $removePartnerData = json_encode(array('Partners' => $removePartners));
                                        $endPoint = 'files/' . $file_id . '/partners';
                                        $removeLogid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'delete_partner', env('RESWARE_ORDER_API') . $endPoint, $removePartnerData, array(), 0, 0);
                                        $resultRemovePartner = $this->resware->make_request('DELETE', $endPoint, $removePartnerData, $partnerUserData);
                                        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'delete_partner', env('RESWARE_ORDER_API') . $endPoint, $removePartnerData, $resultRemovePartner, 0, $removeLogid);
                                    }
                                }
                            }
                        }
                    }

                    $partnerData = json_encode(array('Partners' => $partners));
                    $endPoint = 'files/' . $file_id . '/partners';
                    $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'add_partner_from_admin', env('RESWARE_ORDER_API') . $endPoint, $partnerData, array(), 0, 0);
                    $resultPartner = $this->resware->make_request('POST', $endPoint, $partnerData, $partnerUserData);
                    $this->apiLogs->syncLogs($userdata['id'], 'resware', 'add_partner_from_admin', env('RESWARE_ORDER_API') . $endPoint, $partnerData, $resultPartner, 0, $logid);

                    /* Start add resware api logs */
                    $reswareLogData = array(
                        'request_type' => 'add_partner_from_admin_in_resware',
                        'request_url' => env('RESWARE_ORDER_API') . $endPoint,
                        'request' => $partnerData,
                        'response' => $resultPartner,
                        'status' => '',
                        'created_at' => date("Y-m-d H:i:s"),
                    );
                    $this->db->insert('pct_resware_log', $reswareLogData);
                    /* End add resware api logs */

                    $remoteFileNumberData = json_encode(array('RemoteFileNumber' => $orderNumber));
                    $remoteFileEndPoint = 'files/' . $file_id . '/partners/' . $orderUser['partner_id'];

                    $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'remote_file_number_from_admin', env('RESWARE_ORDER_API') . $remoteFileEndPoint, $remoteFileNumberData, array(), 0, 0);
                    $resultRemotePartner = $this->resware->make_request('PUT', $remoteFileEndPoint, $remoteFileNumberData, $partnerUserData);
                    $this->apiLogs->syncLogs($userdata['id'], 'resware', 'remote_file_number_from_admin', env('RESWARE_ORDER_API') . $remoteFileEndPoint, $remoteFileNumberData, $resultRemotePartner, 0, $logid);

                    /* Start add resware api logs */
                    $reswareLogData = array(
                        'request_type' => 'remote_file_number_from_admin_in_resware',
                        'request_url' => env('RESWARE_ORDER_API') . $endPoint,
                        'request' => $remoteFileNumberData,
                        'response' => $resultRemotePartner,
                        'status' => '',
                        'created_at' => date("Y-m-d H:i:s"),
                    );
                    $this->db->insert('pct_resware_log', $reswareLogData);
                    /* End add resware api logs */
                    $resMessage = $errorMsg = '';
                    /** Add Notes to ResWare */
                    if (!empty($_POST['add_notes']) && $_POST['add_notes'] == "true") {
                        $addNotesRes = $this->addNotesToResware($file_id);
                        $resMessage = $addNotesRes['message'];
                        $letterDocName = $lpFileNumber . '-Letter.pdf';
                        $reportDocName = $lpFileNumber . '-Fraud.pdf';
                        if ($this->order->fileExistOrNotOnS3('ion-fraud/letter/' . $letterDocName)) {
                            $this->uploadIONFraudLetterDocToResware($letterDocName, $file_id, $order_details);
                        }
                        if ($this->order->fileExistOrNotOnS3('ion-fraud/report/' . $reportDocName)) {
                            $this->uploadIONFraudDocsToResware($reportDocName, $file_id, $order_details);
                        }
                    }
                    /** End Add Notes to ResWare */
                    /* Add partner api logs */
                    $partnerApiData = array(
                        'request_url' => env('RESWARE_ORDER_API') . $endPoint,
                        'request_data' => $partnerData,
                        'response_data' => $resultPartner,
                    );

                    $partnerApiId = $this->partnerApiLogs->insert($partnerApiData);
                    /* Add partner api logs */

                    /** Upload document to resware */
                    $lvfilename = $lpFileNumber . '.pdf';
                    $deedfilename = $lpFileNumber . '.pdf';
                    $taxfilename = $lpFileNumber . '.pdf';

                    if ($this->order->fileExistOrNotOnS3('legal-vesting/' . $lvfilename)) {
                        $this->uploadLvDocsToResware($lvfilename, $file_id, $order_details);
                    }

                    if ($this->order->fileExistOrNotOnS3('grant-deed/' . $deedfilename)) {
                        $this->uploadGrantDeedDocsToResware($deedfilename, $file_id, $order_details);
                    }

                    if ($this->order->fileExistOrNotOnS3('tax/' . $taxfilename)) {
                        $this->uploadTaxDocsToResware($taxfilename, $file_id, $order_details);
                    }
                    /** End upload document to resware */

                    /** Send email to sales rep */

                    $timezone = -8;
                    $data = array(
                        'orderNumber' => $orderNumber,
                        'orderId' => $file_id,
                        'OpenName' => $order_details['cust_first_name'] . ' ' . $order_details['cust_last_name'],
                        'Opentelephone' => $order_details['cust_telephone_no'],
                        'OpenEmail' => $order_details['cust_email_address'],
                        'CompanyName' => $order_details['cust_company_name'],
                        'StreetAddress' => $order_details['cust_street_address'],
                        'City' => $order_details['cust_city'],
                        'Zipcode' => $order_details['cust_zip_code'],
                        'openAt' => gmdate("m-d-Y h:i A", strtotime($order_details['opened_date']) + 3600 * ($timezone + date("I"))),
                        'PropertyAddress' => $order_details['address'],
                        'FullProperty' => $order_details['full_address'],
                        'APN' => $order_details['apn'],
                        'County' => $order_details['county'],
                        'LegalDescription' => $order_details['legal_description'],
                        'PrimaryOwner' => $order_details['primary_owner'],
                        'SecondaryOwner' => $order_details['secondary_owner'],
                        'SalesRep' => $order_details['salerep_first_name'] . ' ' . $order_details['salerep_last_name'],
                        'TitleOfficer' => $order_details['titleofficer_first_name'] . ' ' . $order_details['titleofficer_last_name'],
                        'ProductType' => $order_details['product_type'],
                        'SalesAmount' => $order_details['sales_amount'],
                        'LoanAmount' => $order_details['loan_amount'],
                        'LoanNumber' => $order_details['loan_number'],
                        'EscrowNumber' => $order_details['escrow_officer_id'],
                        'randomString' => $this->order->randomPassword(),
                    );
                    if (isset($order_details['lender_id']) && !empty($order_details['lender_id'])) {
                        $data['lender_details'] = $lender_details;
                    }

                    if (isset($order_details['buyer_agent_id']) && !empty($order_details['buyer_agent_id'])) {
                        $data['buyers_agent'] = $buyers_agent_details;
                    }

                    if (isset($order_details['listing_agent_id']) && !empty($order_details['listing_agent_id'])) {
                        $data['listing_agent'] = array(
                            'name' => $listingDetails['listing_agent_name'],
                            'email' => $listingDetails['listing_agent_email_address'],
                            'telephone' => $listingDetails['listing_agent_telephone_no'],
                            'company' => $listingDetails['listing_agent_company'],
                        );
                    }

                    $from_name = 'Pacific Coast Title Company';
                    $from_mail = env('FROM_EMAIL');
                    $order_message_body = $this->load->view('emails/order.php', $data, true);
                    $message = $order_message_body;
                    $addInSubject = '';
                    if (str_contains(strtolower($order_details['property_type']), 'vacant land')) {
                        $addInSubject = ' - APN: ' . $order_details['apn'];
                    }

                    $subject = $orderNumber . ' - PCT Title Order Placed' . $addInSubject;
                    $to = $order_details['salerep_email_address'];
                    // $to = 'piyush-crest@yopmail.com';
                    $mailParams = array(
                        'from_mail' => $from_mail,
                        'from_name' => $from_name,
                        'to' => $to,
                        'subject' => $subject,
                        'message' => json_encode($data),
                    );
                    $logid = $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_confirmation_mail_from_admin', '', $mailParams, array(), $order_details['order_id'], 0);
                    try {
                        $this->load->helper('sendemail');
                        $mail_result = send_email($from_mail, $from_name, $to, $subject, $message, [], [], array());
                    } catch (Exception $e) {
                    }
                    $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_confirmation_mail_from_admin', '', $mailParams, array('status' => $mail_result), $order_details['order_id'], $logid);

                    /** End send email to sales rep */
                }

                /** Save user Activity */
                $activity = $lpFileNumber . ' Sync to resware order number: ' . $fileNumber;
                $this->order->logAdminActivity($activity);
                /** End Save user activity */

                /** End party details */
                $resMessage .= 'Order synced successfully on Resware side with file number ' . $fileNumber;
                $data = array('status' => 'success', 'message' => $resMessage);
                echo json_encode($data);
                exit;
            }
        }
    }

    public function uploadIONFraudDocsToResware($document_name, $fileId, $orderDetails)
    {
        $this->load->model('order/document');
        $this->load->library('order/resware');
        $this->load->model('order/apiLogs');
        $userdata = $this->session->userdata('admin');
        if (env('AWS_ENABLE_FLAG') == 1) {
            $fileSize = filesize(env('AWS_PATH') . "ion-fraud/report/" . $document_name);
            $contents = file_get_contents(env('AWS_PATH') . "ion-fraud/report/" . $document_name);
        } else {
            $fileSize = filesize(FCPATH . 'uploads/ion-fraud/' . $document_name);
            $contents = file_get_contents(base_url() . 'uploads/ion-fraud/' . $document_name);
        }

        $binaryData = base64_encode($contents);

        $documentData = array(
            'document_name' => $document_name,
            'original_document_name' => $document_name,
            'document_type_id' => 1037,
            'document_size' => $fileSize,
            'user_id' => $userdata['id'],
            'order_id' => $orderDetails['order_id'],
            'description' => 'ION Fraud Report',
            'is_sync' => 1,
            'is_ion_fraud_doc' => 1,
        );
        $documentId = $this->document->insert($documentData);

        $endPoint = 'files/' . $orderDetails['file_id'] . '/documents';
        $documentApiData = array(
            'DocumentName' => $document_name,
            'DocumentType' => array(
                'DocumentTypeID' => 1037,
            ),
            'Description' => 'ION Fraud Report',
            'InternalOnly' => false,
            'DocumentBody' => $binaryData,
        );
        $document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);

        $user_data = array();
        $user_data['admin_api'] = 1;

        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_ion_fraud_report_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $orderDetails['order_id'], 0);
        $result = $this->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_ion_fraud_report_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $orderDetails['order_id'], $logid);
        $res = json_decode($result);
        /* Start add resware api logs */
        $reswareLogData = array(
            'request_type' => 'upload_ion_fraud_document_to_resware_' . $documentId,
            'request_url' => env('RESWARE_ORDER_API') . $endPoint,
            'request' => $document_api_data,
            'response' => $result,
            'status' => 'success',
            'created_at' => date("Y-m-d H:i:s"),
        );
        $this->db->insert('pct_resware_log', $reswareLogData);
        /* End add resware api logs */
        $this->document->update(array('api_document_id' => $res->Document->DocumentID), array('id' => $documentId));

    }

    public function uploadIONFraudLetterDocToResware($document_name, $fileId, $orderDetails)
    {
        $this->load->model('order/document');
        $this->load->library('order/resware');
        $this->load->model('order/apiLogs');
        $userdata = $this->session->userdata('admin');
        if (env('AWS_ENABLE_FLAG') == 1) {
            $fileSize = filesize(env('AWS_PATH') . "ion-fraud/letter/" . $document_name);
            $contents = file_get_contents(env('AWS_PATH') . "ion-fraud/letter/" . $document_name);
        } else {
            $fileSize = filesize(FCPATH . 'uploads/ion-fraud/' . $document_name);
            $contents = file_get_contents(base_url() . 'uploads/ion-fraud/' . $document_name);
        }

        $binaryData = base64_encode($contents);

        $documentData = array(
            'document_name' => $document_name,
            'original_document_name' => $document_name,
            'document_type_id' => 1037,
            'document_size' => $fileSize,
            'user_id' => $userdata['id'],
            'order_id' => $orderDetails['order_id'],
            'description' => 'ION Letter Document',
            'is_sync' => 1,
            'is_ion_fraud_doc' => 1,
        );
        $documentId = $this->document->insert($documentData);

        $endPoint = 'files/' . $orderDetails['file_id'] . '/documents';
        $documentApiData = array(
            'DocumentName' => $document_name,
            'DocumentType' => array(
                'DocumentTypeID' => 1037,
            ),
            'Description' => 'ION Fraud Document',
            'InternalOnly' => false,
            'DocumentBody' => $binaryData,
        );
        $document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);

        $user_data = array();
        $user_data['admin_api'] = 1;

        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_ion_fraud_letter_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $orderDetails['order_id'], 0);
        $result = $this->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_ion_fraud_letter_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $orderDetails['order_id'], $logid);
        $res = json_decode($result);

        /* Start add resware api logs */
        $reswareLogData = array(
            'request_type' => 'upload_ion_fraud_letter_document_to_resware',
            'request_url' => env('RESWARE_ORDER_API') . $endPoint,
            'request' => $document_api_data,
            'response' => $result,
            'status' => 'success',
            'created_at' => date("Y-m-d H:i:s"),
        );
        $this->db->insert('pct_resware_log', $reswareLogData);
        /* End add resware api logs */

        $this->document->update(array('api_document_id' => $res->Document->DocumentID), array('id' => $documentId));

    }

    public function uploadLvDocsToResware($document_name, $fileId, $orderDetails)
    {
        $this->load->library('order/resware');
        $this->load->model('order/apiLogs');

        $fileSize = filesize(env('AWS_PATH') . "legal-vesting/" . $document_name);
        $contents = file_get_contents(env('AWS_PATH') . "legal-vesting/" . $document_name);
        $binaryData = base64_encode($contents);

        $endPoint = 'files/' . $orderDetails['file_id'] . '/documents';
        $documentApiData = array(
            'DocumentName' => $document_name,
            'DocumentType' => array(
                'DocumentTypeID' => 1037,
            ),
            'Description' => 'Legal & Vesting Document',
            'InternalOnly' => false,
            'DocumentBody' => $binaryData,
        );
        $document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);

        $user_data = array();
        $user_data['admin_api'] = 1;

        $logid = $this->apiLogs->syncLogs(0, 'resware', 'create_lv_document_from_admin', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $orderDetails['order_id'], 0);
        $result = $this->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
        $this->apiLogs->syncLogs(0, 'resware', 'create_lv_document_from_admin', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $orderDetails['order_id'], $logid);
        $res = json_decode($result);
        /* Start add resware api logs */
        $reswareLogData = array(
            'request_type' => 'create_lv_document_from_admin_in_resware',
            'request_url' => env('RESWARE_ORDER_API') . $endPoint,
            'request' => $document_api_data,
            'response' => $result,
            'status' => '',
            'created_at' => date("Y-m-d H:i:s"),
        );
        $this->db->insert('pct_resware_log', $reswareLogData);
        /* End add resware api logs */
        $this->db->update('pct_order_documents', array('api_document_id' => $res->Document->DocumentID), array('order_id' => $orderDetails['order_id'], 'is_lv_doc' => 1));
        // $this->document->update(array('api_document_id' => $res->Document->DocumentID), array('id' => $documentId));
    }

    public function uploadGrantDeedDocsToResware($document_name, $fileId, $orderDetails)
    {
        // $this->load->model('order/document');
        $this->load->library('order/resware');
        $this->load->model('order/apiLogs');

        $fileSize = filesize(env('AWS_PATH') . "grant-deed/" . $document_name);
        $contents = file_get_contents(env('AWS_PATH') . "grant-deed/" . $document_name);
        $binaryData = base64_encode($contents);

        $endPoint = 'files/' . $orderDetails['file_id'] . '/documents';
        $documentApiData = array(
            'DocumentName' => $document_name,
            'DocumentType' => array(
                'DocumentTypeID' => 1037,
            ),
            'Description' => 'Grant Deed Document',
            'InternalOnly' => false,
            'DocumentBody' => $binaryData,
        );
        $document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);

        $user_data = array();
        $user_data['admin_api'] = 1;

        $logid = $this->apiLogs->syncLogs(0, 'resware', 'create_grant_deed_document_from_admin', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $orderDetails['order_id'], 0);
        $result = $this->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
        $this->apiLogs->syncLogs(0, 'resware', 'create_grant_deed_document_from_admin', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $orderDetails['order_id'], $logid);
        $res = json_decode($result);
        /* Start add resware api logs */
        $reswareLogData = array(
            'request_type' => 'create_grant_deed_document_from_admin_in_resware',
            'request_url' => env('RESWARE_ORDER_API') . $endPoint,
            'request' => $document_api_data,
            'response' => $result,
            'status' => '',
            'created_at' => date("Y-m-d H:i:s"),
        );
        $this->db->insert('pct_resware_log', $reswareLogData);
        /* End add resware api logs */
        $this->db->update('pct_order_documents', array('api_document_id' => $res->Document->DocumentID), array('order_id' => $orderDetails['order_id'], 'is_grant_doc' => 1));
        // $this->db->update('pct_order_documents', array('api_document_id' => $res->Document->DocumentID), array('id' => $documentId));
    }

    public function uploadTaxDocsToResware($document_name, $fileId, $orderDetails)
    {
        // $this->load->model('order/document');
        $this->load->library('order/resware');
        $this->load->model('order/apiLogs');

        $fileSize = filesize(env('AWS_PATH') . "tax/" . $document_name);
        $contents = file_get_contents(env('AWS_PATH') . "tax/" . $document_name);

        $binaryData = base64_encode($contents);

        $endPoint = 'files/' . $orderDetails['file_id'] . '/documents';
        $documentApiData = array(
            'DocumentName' => $document_name,
            'DocumentType' => array(
                'DocumentTypeID' => 1037,
            ),
            'Description' => 'Tax Document',
            'InternalOnly' => false,
            'DocumentBody' => $binaryData,
        );
        $document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);

        $user_data = array();
        $user_data['admin_api'] = 1;

        $logid = $this->apiLogs->syncLogs(0, 'resware', 'create_tax_document_from_admin', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $orderDetails['order_id'], 0);
        $result = $this->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
        $this->apiLogs->syncLogs(0, 'resware', 'create_tax_document_from_admin', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $orderDetails['order_id'], $logid);
        $res = json_decode($result);
        /* Start add resware api logs */
        $reswareLogData = array(
            'request_type' => 'create_tax_document_from_admin_in_resware',
            'request_url' => env('RESWARE_ORDER_API') . $endPoint,
            'request' => $document_api_data,
            'response' => $result,
            'status' => '',
            'created_at' => date("Y-m-d H:i:s"),
        );
        $this->db->insert('pct_resware_log', $reswareLogData);
        /* End add resware api logs */
        $this->db->update('pct_order_documents', array('api_document_id' => $res->Document->DocumentID), array('order_id' => $orderDetails['order_id'], 'is_tax_doc' => 1));
        // $this->db->update('pct_order_documents', array('api_document_id' => $res->Document->DocumentID), array('id' => $documentId));
    }

    public function importDocumentTypes()
    {
        $data = array();
        $data['title'] = 'PCT Order: Import Document Types';
        if ($this->input->post()) {
            $this->form_validation->set_rules('file', 'CSV file', 'callback_file_check');

            if ($this->form_validation->run($this) == true) {
                $insertCount = $updateCount = $rowCount = $notAddCount = 0;

                // If file uploaded
                if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                    // Load CSV reader library
                    $this->load->library('CSVReader');

                    // Parse data from CSV file
                    $csvData = $this->csvreader->parse_csv($_FILES['file']['tmp_name']);

                    $password = md5('Pacific1');

                    // Insert/update CSV data into database
                    if (!empty($csvData)) {
                        foreach ($csvData as $row) {
                            $rowCount++;
                            if (isset($row['Email']) && !empty($row['Email'])) {
                                $email_address = str_replace(' ', '', $row['Email']);
                                $email_address = strtolower($email_address);
                                // Prepare data for DB insertion
                                $customerData = array(
                                    'resware_user_id' => $row['Partner Employee ID'],
                                    'partner_id' => $row['Partner Company ID'],
                                    'first_name' => $row['First Name'],
                                    'last_name' => $row['Last Name'],
                                    'title' => $row['Title'],
                                    'telephone_no' => $row['Phone'],
                                    'email_address' => $email_address,
                                    'password' => $password,
                                    'company_name' => $row['Company Name'],
                                    'street_address' => $row['Street1'],
                                    'street_address_2' => $row['Street2'],
                                    'city' => $row['City'],
                                    'state' => $row['State'],
                                    'zip_code' => $row['Zip'],
                                    'is_escrow' => 1,
                                    'status' => 1,
                                );

                                $con = array(
                                    'where' => array(
                                        'email_address' => $email_address,
                                        'resware_user_id' => $row['Partner Employee ID'],
                                        'is_escrow' => 1,
                                    ),
                                    'returnType' => 'count',
                                );
                                $prevCount = $this->home_model->get_rows($con);

                                if ($prevCount > 0) {
                                    // Update member data
                                    // unset($customerData['customer_number']);
                                    $condition = array('email_address' => $email_address, 'resware_user_id' => $row['Partner Employee ID'], 'is_escrow' => 1);
                                    $update = $this->home_model->update($customerData, $condition);

                                    if ($update) {
                                        $updateCount++;
                                    }
                                } else {
                                    // Insert member data
                                    $insert = $this->home_model->insert($customerData);

                                    if ($insert) {
                                        $insertCount++;
                                    }
                                }
                            }

                        }

                        // Status message with imported data count
                        $notAddCount = ($rowCount - ($insertCount + $updateCount));
                        $successMsg = 'Customers imported successfully. Total Rows (' . $rowCount . ') | Inserted (' . $insertCount . ') | Updated (' . $updateCount . ') | Not Inserted (' . $notAddCount . ')';
                        // $this->session->set_userdata('success_msg', $successMsg);
                        $data['success_msg'] = $successMsg;
                    }
                } else {
                    // $this->session->set_userdata('error_msg', 'Error on file upload, please try again.');
                    $data['error_msg'] = 'Error on file upload, please try again.';
                }
            } else {
                // $this->session->set_userdata('error_msg', 'Invalid file, please select only CSV file.');
                $data['error_msg'] = 'Invalid file, please select only CSV file.';
            }
        }
        $this->admintemplate->show("order/home", "import", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/import', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function adminUserLogs()
    {
        $data = array();
        $data['title'] = 'PCT Order: Admin User Logs';
        $this->admintemplate->show("order/home", "admin_user_logs", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/admin_user_logs', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_admin_user_logs()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['is_escrow'] = 0;
            $pageno = ($params['start'] / $params['length']) + 1;
            $admin_logs_list = $this->home_model->get_admin_user_logs($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $admin_logs_list = $this->home_model->get_admin_user_logs($params);
        }

        $data = array();

        if (isset($admin_logs_list['data']) && !empty($admin_logs_list['data'])) {
            $i = $params['start'] + 1;
            foreach ($admin_logs_list['data'] as $key => $value) {
                $nestedData = array();
                $nestedData[] = $i;
                $nestedData[] = $value['first_name'];
                $nestedData[] = $value['last_name'];
                $nestedData[] = $value['message'];
                $nestedData[] = convertTimezone($value['created_at']);
                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($admin_logs_list['recordsTotal']);
        $json_data['recordsFiltered'] = intval($admin_logs_list['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function storeLpDocumentInfo()
    {
        $this->load->library('order/order');
        $this->load->model('order/titlePointData');
        $instrument_number_ids = $this->input->post('instrument_number_ids');
        $ves_instrument_number_ids = $this->input->post('ves_instrument_number_ids');
        $title_point_id = $this->input->post('title_point_id');
        $selectSection = $this->input->post('select_section');

        foreach ($selectSection as $key => $value) {
            $this->db->update('pct_title_point_document_records', array('display_in_section' => $value), array('id' => $key));
        }
        $this->db->select('*');
        $this->db->from('pct_order_title_point_data');
        $this->db->where('id', $title_point_id);
        $query = $this->db->get();
        $titlePointData = $query->row_array();

        // $isVesEnableData = $this->db->select('id')
        //     ->from('pct_title_point_document_records')
        //     ->where(array('title_point_id' => $title_point_id, 'is_ves_display' => 1))
        //     ->get()
        //     ->result_array();

        $this->db->update('pct_title_point_document_records', array('is_display' => 0), array('title_point_id' => $title_point_id));
        foreach ($instrument_number_ids as $instrument_number_id) {
            $this->db->update('pct_title_point_document_records', array('is_display' => 1), array('id' => $instrument_number_id));
        }

        $this->db->update('pct_title_point_document_records', array('is_ves_display' => 0), array('title_point_id' => $title_point_id));
        foreach ($ves_instrument_number_ids as $ves_instrument_number_id) {
            $this->db->update('pct_title_point_document_records', array('is_ves_display' => 1), array('id' => $ves_instrument_number_id));
        }

        $file_id = $titlePointData['file_id'];
        $this->order->createLpReport($titlePointData['file_number'], true, false);
        /** Save user Activity */
        $activity = 'Regenerated LP Order from popup: ' . $titlePointData['file_number'];
        $this->order->logAdminActivity($activity);
        /** End Save user activity */
        // $this->db->update('pct_title_point_document_records', array('is_ves_display' => 0), array('title_point_id' => $title_point_id));
        // foreach ($isVesEnableData as $id) {
        //     $this->db->update('pct_title_point_document_records', array('is_ves_display' => 1), array('id' => $id['id']));
        // }
        $successMsg = 'Document Data saved successfully and LP report generated successfully for new data.';
        $this->session->set_userdata('success', $successMsg);
        redirect(base_url() . 'order/admin/lp-orders');

    }

    public function settings()
    {
        $data = array();
        $data['title'] = 'PCT Order: Settings';
        if ($this->input->post()) {
            $input = $this->input->post();

            $escrow_commission = isset($input['escrow_commission']) && !empty($input['escrow_commission']) ? 1 : 0;

            $lpDocData = array(
                'is_enable' => $escrow_commission,
            );

            $this->db->update('pct_configs', $lpDocData, array('slug' => 'escrow_commission'));

            $title_point_shut_off = isset($input['title_point_shut_off']) && !empty($input['title_point_shut_off']) ? 1 : 0;
            $titlePointShutOffFlag = array(
                'is_enable' => $title_point_shut_off,
            );
            $this->db->update('pct_configs', $titlePointShutOffFlag, array('slug' => 'title_point_shut_off'));

            $loan_order_closed_email_send_off = isset($input['loan_order_closed_email_send_off']) && !empty($input['loan_order_closed_email_send_off']) ? 1 : 0;
            $loanOrderClosedEmailFlag = array(
                'is_enable' => $loan_order_closed_email_send_off,
            );
            $this->db->update('pct_configs', $loanOrderClosedEmailFlag, array('slug' => 'loan_order_closed_email_send_off'));

            $sale_order_closed_email_send_off = isset($input['sale_order_closed_email_send_off']) && !empty($input['sale_order_closed_email_send_off']) ? 1 : 0;
            $saleOrderClosedEmailFlag = array(
                'is_enable' => $sale_order_closed_email_send_off,
            );
            $this->db->update('pct_configs', $saleOrderClosedEmailFlag, array('slug' => 'sale_order_closed_email_send_off'));

            $enable_lv_with_address_apn = isset($input['enable_lv_with_address_apn']) && !empty($input['enable_lv_with_address_apn']) ? 1 : 0;
            $lvWithAddressApn = array(
                'is_enable' => $enable_lv_with_address_apn,
            );
            $this->db->update('pct_configs', $lvWithAddressApn, array('slug' => 'enable_lv_with_address_apn'));

            $enable_vesting_document_type_filter = isset($input['enable_vesting_document_type_filter']) && !empty($input['enable_vesting_document_type_filter']) ? 1 : 0;
            $vestingFlag = array(
                'is_enable' => $enable_vesting_document_type_filter,
            );
            $this->db->update('pct_configs', $vestingFlag, array('slug' => 'enable_vesting_document_type_filter'));

            $enable_create_order_submit_button = isset($input['enable_create_order_submit_button']) && !empty($input['enable_create_order_submit_button']) ? 1 : 0;
            $submitButtonFlag = array(
                'is_enable' => $enable_create_order_submit_button,
            );
            $this->db->update('pct_configs', $submitButtonFlag, array('slug' => 'enable_create_order_submit_button'));

            $enable_ion_fraud_checking = isset($input['enable_ion_fraud_checking']) && !empty($input['enable_ion_fraud_checking']) ? 1 : 0;
            $ionFraudFlag = array(
                'is_enable' => $enable_ion_fraud_checking,
            );
            $this->db->update('pct_configs', $ionFraudFlag, array('slug' => 'enable_ion_fraud_checking'));

            $add_underwriten_partner_via_api = isset($input['add_underwriten_partner_via_api']) && !empty($input['add_underwriten_partner_via_api']) ? 1 : 0;
            $underwrittenParnerAddFlag = array(
                'is_enable' => $add_underwriten_partner_via_api,
            );
            $this->db->update('pct_configs', $underwrittenParnerAddFlag, array('slug' => 'add_underwriten_partner_via_api'));

            $enable_survey_email = isset($input['enable_survey_email']) && !empty($input['enable_survey_email']) ? 1 : 0;
            $enableSurveyEmailFlag = array(
                'is_enable' => $enable_survey_email,
            );
            $this->db->update('pct_configs', $enableSurveyEmailFlag, array('slug' => 'enable_survey_email'));

            $msg = 'Setting updated';
            /** Save user Activity */
            $this->order->logAdminActivity($msg);
            /** End Save user activity */
            $successMsg = $msg . ' successfully';
            $this->session->set_flashdata('success', $successMsg);
            redirect(base_url() . 'order/admin/settings');
        }

        $this->db->select('is_enable, slug');
        $this->db->from('pct_configs');
        $this->db->where('slug !=', 'sales_rep_status_flag');
        $query = $this->db->get();

        $data = array();

        foreach ($query->result_array() as $row) {
            $data[$row['slug']] = $row;
        }

        $res['escrow_commission'] = $data['escrow_commission']['is_enable'];
        $res['title_point_shut_off'] = $data['title_point_shut_off']['is_enable'];
        $res['sale_order_closed_email_send_off'] = $data['sale_order_closed_email_send_off']['is_enable'];
        $res['loan_order_closed_email_send_off'] = $data['loan_order_closed_email_send_off']['is_enable'];
        $res['enable_lv_with_address_apn'] = $data['enable_lv_with_address_apn']['is_enable'];
        $res['enable_vesting_document_type_filter'] = $data['enable_vesting_document_type_filter']['is_enable'];
        $res['enable_create_order_submit_button'] = $data['enable_create_order_submit_button']['is_enable'];
        $res['enable_ion_fraud_checking'] = $data['enable_ion_fraud_checking']['is_enable'];
        $res['add_underwriten_partner_via_api'] = $data['add_underwriten_partner_via_api']['is_enable'];
        $res['enable_survey_email'] = $data['enable_survey_email']['is_enable'];

        // $data['is_lp_enable'] = $res->is_enable;
        $this->admintemplate->show("order/home", "settings", $res);
    }

    public function lpDocumentTypes()
    {
        $data = array();
        $data['title'] = 'PCT Order: LP Document Types';
        $this->admintemplate->addJS(base_url('assets/backend/js/lp-document-type.js'));
        $this->admintemplate->show("order/home", "lp_document_types", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/lp_document_types', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function lpAlert()
    {
        $data = array();
        $data['title'] = 'PCT Order: LP Alert';
        $this->admintemplate->show("order/home", "lp_alert", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/lp_alert', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function get_lp_document_list()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            if (isset($_POST['is_display']) && $_POST['is_display'] != null) {
                $params['is_display'] = $_POST['is_display'];
            }
            $params['is_escrow'] = 1;
            $pageno = ($params['start'] / $params['length']) + 1;
            $lp_document_lists = $this->home_model->get_lp_document_list($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $lp_document_lists = $this->home_model->get_customers($params);
        }

        $data = array();
        if (isset($lp_document_lists['data']) && !empty($lp_document_lists['data'])) {
            $i = $params['start'] + 1;
            foreach ($lp_document_lists['data'] as $key => $value) {
                $nestedData = array();
                $id = $value['id'];
                $nestedData[] = $i;
                $nestedData[] = $value['doc_type'];
                $nestedData[] = $value['doc_type_description'];
                $nestedData[] = ($value['subtype_flag'] == 1) ? 'Yes' : 'No';
                $nestedData[] = $value['sub_type_list'];
                $sectionG = ($value["display_in_section"] == "G") ? 'selected' : '';
                $sectionH = ($value["display_in_section"] == "H") ? 'selected' : '';
                $sectionI = ($value["display_in_section"] == "I") ? 'selected' : '';
                $sectionJ = ($value["display_in_section"] == "J") ? 'selected' : '';
                $lpDocTypeSectionSelection = '<select class="custom-select custom-select-sm form-control form-control-sm" onchange="updateDocumentSection(' . $value['id'] . ',this.value);" id="lp_document_section" name="lp_document_section">
                                <option value="">Select</option>
                                <option ' . $sectionG . ' value="G">Section G</option>
                                <option ' . $sectionH . ' value="H">Section H</option>
                                <option ' . $sectionI . ' value="I">Section I</option>
                                <option ' . $sectionJ . ' value="J">Section J</option>
                            </select>';
                $nestedData[] = $lpDocTypeSectionSelection;
                // $nestedData[] = $value['is_notice'] == 1 ? 'Yes' : 'No';
                if ($value['is_display'] == 1) {
                    $checked = 'checked';
                } else {
                    $checked = '';
                }
                if ($value['is_ves'] == 1) {
                    $vesChecked = 'checked';
                } else {
                    $vesChecked = '';
                }
                $nestedData[] = "<input $checked onclick='isDisplayDocumentType();' style='height:30px;width:20px;' type='checkbox' id='$id' name='$id'>";
                $nestedData[] = "<input $vesChecked onclick='isVesDocumentType();' style='height:30px;width:20px;' type='checkbox' id='$id' name='$id'>";
                if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $editUrl = base_url() . 'order/admin/edit-lp-document-type/' . $value['id'];
                    $action = "<div class='table-action'><a href='" . $editUrl . "' class='edit-document-type' title ='Edit Document Type Detail'><span class='fas fa-edit' aria-hidden='true'></span></a>";

                    $action .= "<a href='javascript:void(0);' onclick='deleteDocumentType(" . $value['id'] . ")' title='Delete Document Type'><span class='fas fa-trash' aria-hidden='true'></span></a></div>";
                    $nestedData[] = $action;
                }
                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($lp_document_lists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($lp_document_lists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function get_lp_alert_list()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';

            $pageno = ($params['start'] / $params['length']) + 1;
            $lp_alerts = $this->home_model->get_lp_alert_list($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $lp_alerts = $this->home_model->get_lp_alert_list($params);
        }

        $data = array();
        if (isset($lp_alerts['data']) && !empty($lp_alerts['data'])) {
            $i = $params['start'] + 1;
            foreach ($lp_alerts['data'] as $key => $value) {
                $nestedData = array();
                $id = $value['id'];
                $nestedData[] = $i;
                $nestedData[] = $value['days'];
                $nestedData[] = $value['description'];
                $nestedData[] = $value['color_code'] . "&nbsp; <input type='color' value= " . $value['color_code'] . " disabled>";
                $nestedData[] = $value['text_color'] . "&nbsp; <input type='color' value= " . $value['text_color'] . " disabled>";
                $nestedData[] = $value['regular_order_color_code'] . "&nbsp; <input disabled type='color' value= " . $value['regular_order_color_code'] . " >";
                $nestedData[] = ($value['delete'] == 1) ? 'Yes' : 'No';
                // $nestedData[] = "<input $checked onclick='isDisplayDocumentType();' style='height:30px;width:20px;' type='checkbox' id='$id' name='$id'>";
                if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $editUrl = base_url() . 'order/admin/edit-lp-alert/' . $value['id'];
                    $action = "<div class='table-action'><a href='" . $editUrl . "' class='edit-alert' title ='Edit Alert Detail'><span class='fas fa-edit' aria-hidden='true'></span></a>";

                    $action .= "<a href='javascript:void(0);' onclick='deleteAlert(" . $value['id'] . ")' title='Delete Alert'><span class='fas fa-trash' aria-hidden='true'></span></a></div>";
                    $nestedData[] = $action;
                }
                $data[] = $nestedData;
                $i++;
            }
        }
        $json_data['recordsTotal'] = intval($lp_alerts['recordsTotal']);
        $json_data['recordsFiltered'] = intval($lp_alerts['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function importLpDocumentTypes()
    {
        $data = array();
        $data['title'] = 'PCT Order: Import LP Document Types';
        if ($this->input->post()) {

            $this->form_validation->set_rules('file', 'CSV file', 'callback_file_check');
            if ($this->form_validation->run($this) == true) {
                $insertCount = $updateCount = $rowCount = $notAddCount = 0;

                if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                    $this->load->library('CSVReader');
                    $csvData = $this->csvreader->parse_csv($_FILES['file']['tmp_name']);
                    // echo "<pre>";
                    if (!empty($csvData)) {
                        foreach ($csvData as $row) {
                            $rowCount++;
                            $lpDocumentTypeData = array(
                                'doc_type' => trim($row['Doc Type']),
                                'doc_type_description' => trim($row['Doc Type Description']),
                                'subtype_flag' => trim($row['Subtype Flag']),
                                // 'doc_sub_type_description' => trim($row['Doc Subtype Description']),
                                // 'doc_sub_type' => $row['Doc Subtype'] ? trim($row['Doc Subtype']) : null,
                                'is_display' => ($row['Doc Type'] == 'DEG' || $row['Doc Type'] == 'TDD' || $row['Doc Type'] == 'ASE' || $row['Doc Type'] == 'LIS' || $row['Doc Type'] == 'FIN' || $row['Doc Type'] == 'NOC' || $row['Doc Type'] == 'NOD' || $row['Doc Type'] == 'NOT' || $row['Doc Type'] == 'NOS') ? 1 : 0,
                                // 'is_notice' => ($row['Doc Type'] == 'NOC' || $row['Doc Type'] == 'NOD' || $row['Doc Type'] == 'NOT' || $row['Doc Type'] == 'NOS') ? 1 : 0
                            );

                            // print_r($lpDocumentTypeData);die;
                            $con = array(
                                'where' => array(
                                    'doc_type' => trim($row['Doc Type']),
                                    'doc_type_description' => trim($row['Doc Type Description']),
                                    // 'doc_sub_type_description' => trim($row['Doc Subtype Description']),
                                    // 'doc_sub_type' => trim($row['Doc Subtype'])
                                ),
                                'returnType' => 'count',
                            );
                            $prevCount = $this->home_model->get_rows($con, 'pct_lp_document_types');

                            if ($prevCount > 0) {
                                $condition = array('doc_type' => trim($row['Doc Type']), 'doc_type_description' => trim($row['Doc Type Description']), 'doc_sub_type_description' => trim($row['Doc Subtype Description']), 'doc_sub_type' => ($row['Doc Subtype']) ? trim($row['Doc Subtype']) : null);
                                $update = $this->home_model->update($lpDocumentTypeData, $condition, 'pct_lp_document_types');
                                if ($update) {
                                    $updateCount++;
                                }
                            } else {
                                $insert = $this->home_model->insert($lpDocumentTypeData, 'pct_lp_document_types');
                                if ($insert) {
                                    $insertCount++;
                                }
                            }
                        }
                        $notAddCount = ($rowCount - ($insertCount + $updateCount));
                        $successMsg = 'LP Document Types imported successfully. Total Rows (' . $rowCount . ') | Inserted (' . $insertCount . ') | Updated (' . $updateCount . ') | Not Inserted (' . $notAddCount . ')';
                        $data['success_msg'] = $successMsg;
                    }
                } else {
                    $data['error_msg'] = 'Error on file upload, please try again.';
                }
            } else {
                $data['error_msg'] = 'Invalid file, please select only CSV file.';
            }
        }
        $this->admintemplate->show("order/home", "import_lp_document_types", $data);
        // $this->load->view('order/layout/header', $data);
        // $this->load->view('order/home/import_lp_document_types', $data);
        // $this->load->view('order/layout/footer', $data);
    }

    public function addLpDocumentTypes()
    {
        $this->load->model('order/apiLogs');
        $userdata = $this->session->userdata('admin');
        $data = array();
        $data['title'] = 'PCT Order: Add New LP Document Types';
        $data['subtypeList'] = $this->home_model->getSubtypeLPDocumentList();
        $salesRepData = array();
        if ($this->input->post()) {
            $this->form_validation->set_rules('doc_type_description', 'Doc Type Description', 'required', array('required' => 'Please Enter Doc Type Description'));
            $this->form_validation->set_rules('doc_type', 'Doc Type', 'required', array('required' => 'Please Enter Doc Type'));
            if ($this->form_validation->run() == true) {
                $input = $this->input->post();
                // echo "<pre>";
                // print_r($input);
                // die;
                $lpDocData = array(
                    'doc_type' => $input['doc_type'],
                    'doc_type_description' => $input['doc_type_description'],
                    'subtype_flag' => (isset($input['subtype_flag'])) ? $input['subtype_flag'] : 0,
                );

                if (isset($input['subtype'])) {
                    $lpDocData['sub_type_list'] = implode(',', $input['subtype']);
                }

                $this->home_model->insertLpDocType($lpDocData);

                $mapInSection = $input['map_in_section'];
                if (!isset($lpDocData['subtype_flag']) || empty($lpDocData['subtype_flag'])) {
                    foreach ($input['subtype'] as $key => $type) {
                        if (!empty($mapInSection[$key]) && !empty($type)) {
                            $updateData['map_in_section'] = $mapInSection[$key];
                            $condition = array('doc_type' => $type);
                            $this->home_model->updateLpDocType($updateData, $condition, 'pct_lp_document_types');
                        }
                    }
                }

                /** Save user Activity */
                $activity = 'Add Lp document:  ' . $input['doc_type'];
                $this->order->logAdminActivity($activity);
                /** End Save user activity */
                $successMsg = 'Document Data saved successfully';
                $this->session->set_userdata('success', $successMsg);
                redirect(base_url() . 'order/admin/lp-document-types');
            } else {
                $data['doc_type_description_error_msg'] = form_error('doc_type_description');
                $data['doc_sub_type_description_error_msg'] = form_error('doc_sub_type_description');
                $data['doc_type_error_msg'] = form_error('doc_type');
                $data['doc_sub_type_error_msg'] = form_error('doc_sub_type');
            }
        }
        // $this->admintemplate->addJS(base_url('assets/backend/hr/js/plugins/bootstrap-multiselect.min.js'));
        // $this->admintemplate->addJS('https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js');
        $this->admintemplate->addJS(base_url('assets/backend/js/selectize.min.js'));
        $this->admintemplate->addJS(base_url('assets/frontend/js/jquery-cloneya.min.js'));
        $this->admintemplate->show("order/home", "add_lp_document_types", $data);
    }

    public function deleteLpDocumentType()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';

        if ($id) {
            $condition = array('id' => $id);

            $lpDoc = $this->home_model->getLpDocType($condition);
            $status = $this->home_model->deleteLpDocType($condition, 'pct_lp_document_types');
            if ($status) {
                /** Save user Activity */
                $activity = 'Deleted lp document: ' . $lpDoc['doc_type'];
                $this->order->logAdminActivity($activity);
                /** End save user activity */
                $successMsg = 'Record deleted successfully.';
                $response = array('status' => 'success', 'message' => $successMsg);
            }
        } else {
            $msg = 'ID is required.';
            $response = array('status' => 'error', 'message' => $msg);
        }

        echo json_encode($response);
    }

    public function editLpDocumentType()
    {
        $data = array();
        $id = $this->uri->segment(4);
        $data['title'] = 'PCT Order: Edit LP Document Types';
        $lpDocData = array();
        $data['subtypeList'] = $this->home_model->getSubtypeLPDocumentList();
        $con = array('id' => $id);
        if (isset($id) && !empty($id)) {
            if ($this->input->post()) {
                $this->form_validation->set_rules('doc_type_description', 'Doc Type Description', 'required', array('required' => 'Please Enter Doc Type Description'));
                $this->form_validation->set_rules('doc_type', 'Doc Type', 'required', array('required' => 'Please Enter Doc Type'));

                if ($this->form_validation->run() == true) {
                    $input = $this->input->post();
                    $lpDocData = array(
                        'doc_type' => trim($this->input->post('doc_type')),
                        'doc_type_description' => trim($this->input->post('doc_type_description')),
                        'subtype_flag' => (isset($input['subtype_flag'])) ? $input['subtype_flag'] : 0,
                    );

                    if (isset($input['subtype'])) {
                        $lpDocData['sub_type_list'] = implode(',', $input['subtype']);
                    } else {
                        $lpDocData['sub_type_list'] = null;
                    }

                    $condition = array('id' => $id);
                    $update = $this->home_model->updateLpDocType($lpDocData, $condition, 'pct_lp_document_types');

                    $mapInSection = $input['map_in_section'];
                    if (!isset($lpDocData['subtype_flag']) || empty($lpDocData['subtype_flag'])) {
                        $lpDocDetails = $this->home_model->getLpDocType($con);
                        $mappedSubType = explode(',', $lpDocDetails['sub_type_list']);
                        foreach ($mappedSubType as $key => $type) {
                            $updateData['map_in_section'] = null;
                            $condition = array('doc_type' => $type);
                            $this->home_model->updateLpDocType($updateData, $condition, 'pct_lp_document_types');
                        }

                        foreach ($input['subtype'] as $key => $type) {
                            $updateData['map_in_section'] = $mapInSection[$key];
                            $condition = array('doc_type' => $type);
                            $this->home_model->updateLpDocType($updateData, $condition, 'pct_lp_document_types');
                        }
                    }

                    if ($update) {
                        /** Save user Activity */
                        $activity = 'Updated lp document :  ' . trim($this->input->post('doc_type'));
                        $this->order->logAdminActivity($activity);
                        /** End Save user activity */

                        $successMsg = 'LP Document Types updated successfully.';
                        $this->session->set_userdata('success', $successMsg);
                        redirect(base_url() . 'order/admin/lp-document-types');
                    } else {
                        $data['error_msg'] = 'Error occurred while updating LP Document Types.';
                    }
                } else {
                    $data['doc_type_description_error_msg'] = form_error('doc_type_description');
                    $data['doc_type_error_msg'] = form_error('doc_type');
                }
            }

            $lpDocDetails = $this->home_model->getLpDocType($con);
            $mappedSubType = explode(',', $lpDocDetails['sub_type_list']);
            $condition = array(
                'whereIn' => array(
                    'doc_type' => $mappedSubType,
                ),
            );
            $getMappedSubType = $this->home_model->getMappedDocType($condition);
            // print_r($getMappedSubType);
            // die;
            $data['lp_document_info'] = $lpDocDetails;
            $data['mapped_sub_type'] = $getMappedSubType;
            // echo "<pre>";
            // print_r($data);
            // die;
        } else {
            redirect(base_url() . 'order/admin/lp-document-types');
        }
        // $this->admintemplate->addJS('https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js');
        $this->admintemplate->addJS(base_url('assets/backend/js/selectize.min.js'));
        $this->admintemplate->show("order/home", "edit_lp_document_type", $data);
    }

    public function addLpAlert()
    {
        $this->load->model('order/apiLogs');
        $userdata = $this->session->userdata('admin');
        $data = array();
        $data['title'] = 'PCT Order: Add New LP Alert';

        $salesRepData = array();
        if ($this->input->post()) {
            $this->form_validation->set_rules('days', 'Days', 'required', array('required' => 'Please Enter Days'));

            if ($this->form_validation->run() == true) {
                $input = $this->input->post();
                $lpAlertData = array(
                    'days' => $input['days'],
                    'color_code' => $input['color_code'] ?? null,
                    'text_color' => $input['text_color'] ?? null,
                    'regular_order_color_code' => $input['regular_order_color_code'] ?? null,
                    'description' => $input['description'] ?? null,
                    'delete' => (isset($input['delete'])) ? $input['delete'] : 0,
                );

                $insert = $this->home_model->insertLpAlert($lpAlertData);
                /** Save user Activity */
                $activity = 'New Lp alert added for days ' . $input['days'];
                $this->order->logAdminActivity($activity);
                /** End Save user activity */
                $successMsg = 'Alert Data saved successfully';
                $this->session->set_userdata('success', $successMsg);
                redirect(base_url() . 'order/admin/lp-alert');
            } else {
                $data['days_error_msg'] = form_error('days');
            }
        }
        $this->admintemplate->show("order/home", "add_lp_alert", $data);
    }

    public function deleteLpAlert()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';

        if ($id) {
            $condition = array('id' => $id);
            $status = $this->home_model->deleteLpAlert($condition, 'pct_lp_alert');
            if ($status) {
                /** Save user Activity */
                $activity = 'Lp alert deleted id:  ' . $id;
                $this->order->logAdminActivity($activity);
                /** End Save user activity */
                $successMsg = 'Record deleted successfully.';
                $response = array('status' => 'success', 'message' => $successMsg);
            }
        } else {
            $msg = 'ID is required.';
            $response = array('status' => 'error', 'message' => $msg);
        }

        echo json_encode($response);
    }

    public function editLpAlert()
    {
        $data = array();
        $id = $this->uri->segment(4);
        $data['title'] = 'PCT Order: Edit LP Document Types';

        if (isset($id) && !empty($id)) {
            if ($this->input->post()) {
                $this->form_validation->set_rules('days', 'Days', 'required', array('required' => 'Please Enter Days'));

                if ($this->form_validation->run() == true) {
                    $input = $this->input->post();
                    $lpAlertData = array(
                        'days' => $input['days'],
                        'description' => $input['description'],
                        'color_code' => $input['color_code'] ?? null,
                        'text_color' => $input['text_color'] ?? null,
                        'regular_order_color_code' => $input['regular_order_color_code'] ?? null,
                        'delete' => (isset($input['delete'])) ? $input['delete'] : 0,
                    );
                    $condition = array('id' => $id);
                    $update = $this->home_model->updateLpAlert($lpAlertData, $condition, 'pct_lp_alert');

                    if ($update) {
                        /** Save user Activity */
                        $activity = 'Lp alert updated to days:  ' . $input['days'];
                        $this->order->logAdminActivity($activity);
                        /** End Save user activity */
                        $successMsg = 'LP Alert updated successfully.';
                        $this->session->set_userdata('success', $successMsg);
                        redirect(base_url() . 'order/admin/lp-alert');
                    } else {
                        $data['error_msg'] = 'Error occurred while updating LP Alert.';
                    }
                } else {
                    $data['days_error_msg'] = form_error('days');
                    $data['color_code_error_msg'] = form_error('color_code');
                }
            }
            $con = array('id' => $id);
            $data['lp_alert'] = $this->home_model->getLpAlert($con);
        } else {
            redirect(base_url() . 'order/admin/lp-alert');
        }
        $this->admintemplate->show("order/home", "edit_lp_alert", $data);
    }

    public function updateDocumentSection()
    {
        $id = $this->input->post('id');
        $section = $this->input->post('section');
        $updateData = array('display_in_section' => $section);
        $condition = array('id' => $id);
        $update = $this->home_model->updateLpDocType($updateData, $condition, 'pct_lp_document_types');

        if ($update) {
            $msg = 'LP Document Types section updated successfully.';
            $result = array('status' => 'success', 'message' => $msg);
        } else {
            $msg = 'Error occurred while updating LP Document Types.';
            $result = array('status' => 'error', 'error_message' => $msg);
        }

        echo json_encode($result);
        exit;
    }

    public function updateLpDocumentTypeFlag()
    {
        $lp_document_type_id = $this->input->post('lp_document_type_id');
        $displayFlag = $this->input->post('displayFlag');
        $data['is_display'] = $displayFlag;
        $data['updated_at'] = date("Y-m-d H:i:s");
        $condition = array(
            'id' => $lp_document_type_id,
        );
        $this->db->update('pct_lp_document_types', $data, $condition);
        $data = array('status' => 'success', 'msg' => 'LP document type flag updated successfully.');
        echo json_encode($data);
    }

    public function updateLpDocumentTypeIsVesFlag()
    {
        $lp_document_type_id = $this->input->post('lp_document_type_id');
        $isVesFlag = $this->input->post('isVesFlag');
        $data['is_ves'] = $isVesFlag;
        $data['updated_at'] = date("Y-m-d H:i:s");
        $condition = array(
            'id' => $lp_document_type_id,
        );
        $this->db->update('pct_lp_document_types', $data, $condition);
        $data = array('status' => 'success', 'msg' => 'LP document type Ves flag updated successfully.');
        echo json_encode($data);
    }

    public function getInstrumentData()
    {
        $file_id = $this->input->post('file_id');
        $this->load->library('order/order');
        $this->db->select('*');
        $this->db->from('pct_order_title_point_data');
        $this->db->where('file_id', $file_id);
        $query = $this->db->get();
        $titlePointData = $query->row();

        $this->db->select('*');
        $this->db->from('pct_title_point_document_records');
        $this->db->where('title_point_id', $titlePointData->id);
        $query = $this->db->get();
        $instrumentRecords = $query->result_array();

        $this->db->select('count(*) as ves_count');
        $this->db->from('pct_title_point_document_records');
        $this->db->where('title_point_id', $titlePointData->id);
        $this->db->where('is_ves_display', 1);
        $query = $this->db->get();
        $vesCountData = $query->row_array();

        if ($vesCountData['ves_count'] == 0) {
            $this->load->model('order/titlePointData');
            $titlePointInstrumentDetails = $this->titlePointData->getLatestGrantDeedInstrumentDetails($titlePointData->file_number);
        }

        $displayDocList = $this->home_model->getDocumetTypes();
        $getAllSubCategory = $this->home_model->getAllSubCategory();
        $getAllSubCategory = array_column($getAllSubCategory, 'doc_type');
        $filteredSubCategoryList = array_filter($displayDocList, function ($item) {
            return $item['subtype_flag'] == 1;
        });
        // echo "<pre>";
        // print_r($filteredSubCategoryList);
        // die;
        $filteredSubCateList = array_column($filteredSubCategoryList, 'doc_type');

        $filteredMainCategoryList = array_filter($displayDocList, function ($item) {
            return $item['subtype_flag'] == 0;
        });
        $filteredMainCateList = array_column($filteredMainCategoryList, 'doc_type');

        $displayNoticeDocList = $this->home_model->getNoticeDocumetTypes();

        $data = "<input type='hidden' id='title_point_id' name='title_point_id' value='$titlePointData->id'>
        <table class='table table-bordered' id='tbl-lp-orders-listing' width='100%' cellspacing='0'>
            <thead>
                <tr>
                    <th>Sr No</th>
                    <th>Document Name</th>
                    <th>Instrument</th>
                    <th>Recorded Date</th>
                    <th>Parties</th>
                    <th>Coupling</th>
                    <th>Remarks</th>
                    <th>Type</th>
                    <th>Sub Type</th>
                    <th>Display in Section</th>
                    <th>VES</th>
                    <th><label class='option block'><input type='checkbox' checked id='check_all' name='check_all'><span class='checkbox'></span></label></th>
                </tr>
            </thead>
        <tbody>";

        $i = 0;

        // echo "<pre>";
        // print_r($instrumentRecords);
        // die;
        // if (!empty($instrumentRecords)) {
        //     foreach ($instrumentRecords as $instrumentRecord) {
        //         if ((in_array($instrumentRecord['document_sub_type'], $getAllSubCategory) && in_array($instrumentRecord['document_sub_type'], $filteredSubCateList)) || (empty($instrumentRecord['document_sub_type']) && in_array($instrumentRecord['document_type'], $filteredMainCateList))) {
        //             $key = array_search($instrumentRecord['document_type'], array_column($displayDocList, 'doc_type'));
        //             $displaySection = (!empty($instrumentRecord['display_in_section'])) ? $instrumentRecord['display_in_section'] : $displayDocList[$key]['display_in_section'];

        //             if (isset($instrumentRecord['document_sub_type']) && !empty($instrumentRecord['document_sub_type'])) {
        //                 $keySubType = array_search($instrumentRecord['document_sub_type'], array_column($displayDocList, 'doc_type'));
        //                 $displaySection = ((!empty($instrumentRecord['display_in_section'])) ? ($instrumentRecord['display_in_section']) : (!empty($displayDocList[$keySubType]['map_in_section']) ? $displayDocList[$keySubType]['map_in_section'] : $displayDocList[$keySubType]['display_in_section']));
        //             }

        //             $instrumentRecords[$i]['display_in_section'] = $displaySection;
        //             if ($displaySection == 'G') {
        //                 if ($instrumentRecord['color_coding'] == 'FFFF00') {
        //                     $instrumentRecords[$i]['is_display'] = 1;
        //                 } else if ($instrumentRecord['color_coding'] == 'C0C0C0') {
        //                     if ($instrumentRecord['icon_text'] == 'Exx') {
        //                         $instrumentRecords[$i]['is_display'] = 1;
        //                     } else {
        //                         $instrumentRecords[$i]['is_display'] = 0;
        //                     }
        //                 } else {
        //                     $instrumentRecords[$i]['is_display'] = 0;
        //                 }
        //             } else {
        //                 if ($instrumentRecord['color_coding'] != 'A0A0FF') {
        //                     if ($instrumentRecord['color_coding'] == 'C0C0C0') {
        //                         if ($instrumentRecord['icon_text'] == 'Exx') {
        //                             $instrumentRecords[$i]['is_display'] = 1;
        //                         } else {
        //                             $instrumentRecords[$i]['is_display'] = 0;
        //                         }
        //                     } else {
        //                         $instrumentRecords[$i]['is_display'] = 1;
        //                     }
        //                 } else {
        //                     $instrumentRecords[$i]['is_display'] = 0;
        //                 }
        //             }
        //         } else {
        //             $instrumentRecords[$i]['is_display'] = 0;
        //         }

        //         $i++;
        //     }
        // }

        $i = 1;
        if (!empty($instrumentRecords)) {
            foreach ($instrumentRecords as $instrumentRecord) {
                // if (strlen(array_search($instrumentRecord['instrument'], array_column($filterArr, 'instrument'))) && $instrumentRecord['is_display'] == 1) {
                //     $checked = "checked";
                // } else if (strlen(array_search($instrumentRecord['instrument'], array_column($noticeArr, 'instrument'))) && $instrumentRecord['is_display'] == 1) {
                //     $checked = "checked";
                // } else {
                //     $checked = "";
                // }
                if ($instrumentRecord['is_display'] == 1) {
                    $checked = "checked";
                    // }  else {
                    //     $checked = "";
                    // }

                    //if ($vesCountData['ves_count'] == 0) {
                    //$vesChecked = $instrumentRecord['instrument'] == $titlePointInstrumentDetails[0]['instrument'] ? 'checked' : '';
                    //} else {
                    $vesChecked = $instrumentRecord['is_ves_display'] == 1 ? 'checked' : '';
                    //}

                    $document_name = $instrumentRecord['document_name'];
                    $document_type = $instrumentRecord['document_type'];
                    $document_sub_type = $instrumentRecord['document_sub_type'];
                    $fileName = $instrumentRecord['id'] . '.pdf';
                    $docUrl = env('AWS_PATH') . "title-point/" . $fileName;
                    $instrument = "<a target='_blank' href='$docUrl'>" . $instrumentRecord['instrument'] . "</a>";
                    $recorded_date = date("m/d/Y", strtotime($instrumentRecord['recorded_date']));
                    $parties = ucwords(strtolower($instrumentRecord['parties']));
                    $coupling = $instrumentRecord['coupling'] != '0' ? $instrumentRecord['coupling'] : '';
                    $remarks = ucwords(strtolower($instrumentRecord['remarks']));
                    $id = $instrumentRecord['id'];
                    $displaySection = $instrumentRecord['display_in_section'];
                    $displayInG = ($displaySection == 'G') ? 'selected' : '';
                    $displayInH = ($displaySection == 'H') ? 'selected' : '';
                    $displayInI = ($displaySection == 'I') ? 'selected' : '';
                    $data .= "<tr>
                                <td width='3%'>$i</td>
                                <td width='12%'>$document_name</td>
                                <td width='6%'>$instrument</td>
                                <td width='8%'>$recorded_date</td>
                                <td width='25%'>$parties</td>
                                <td width='6%'>$coupling</td>
                                <td width='6%'>$remarks</td>
                                <td width='4%'>$document_type</td>
                                <td width='5%'>$document_sub_type</td>
                                <td width='15%'><select class='custom-select custom-select-sm' name='select_section[" . $id . "]' class=" . $displaySection . " ><option value=''>Select Section</option><option value='G' " . $displayInG . " >Section G</option><option value='H' " . $displayInH . ">Section H</option><option value='I' " . $displayInI . " >Section I</option></select></td>
                                <td width='5%'><label class='option block'><input type='checkbox' id='$id' $vesChecked name='ves_instrument_number_ids[]' value='$id'><span class='checkbox'></span></label></td>
                                <td width='5%'><label class='option block'><input class='action_all' type='checkbox' id='$id' $checked name='instrument_number_ids[]' value='$id'><span class='checkbox'></span></label></td>
                            </tr>";
                    $i++;
                }
            }
        } else {
            $data .= "<tr><td colspan='5'>No records found.</td></tr>";
        }
        $data .= '</tbody></table>';
        if (!empty($data)) {
            $result = array('status' => 'success', 'data' => $data);
            // $result = array('status'=> 'success', 'data' => mb_convert_encoding($data, 'UTF-8', 'UTF-8'));
        } else {
            $result = array('status' => 'error', 'data' => $data);
        }
        echo json_encode($result);
        exit;
    }

    public function updateAllOnlyReswareOrder()
    {
        $user_id = $this->input->post('user_id');
        $allowOnlyReswareOrderFlag = $this->input->post('allowOnlyReswareOrderFlag');
        $data['is_allow_only_resware_orders'] = $allowOnlyReswareOrderFlag;
        $data['updated_at'] = date("Y-m-d H:i:s");
        $condition = array(
            'id' => $user_id,
        );
        $this->db->update('customer_basic_details', $data, $condition);
        $data = array('status' => 'success', 'msg' => 'Allow only Resware order value updated successfully for user.');
        /** Save user Activity */
        $orderUser = $this->home_model->get_user($condition);
        $activity = 'Allow only Resware order value updated successfully for user :' . $orderUser['email_address'];
        $this->order->logAdminActivity($activity);

        /** End Save user activity */
        echo json_encode($data);
    }

    public function regenerateReport()
    {
        $file_id = $this->input->post('file_id');
        $this->load->library('order/order');
        $this->db->select('*');
        $this->db->from('pct_order_title_point_data');
        $this->db->where('file_id', $file_id);
        $query = $this->db->get();
        $titlePointData = $query->row();

        $this->db->select('*');
        $this->db->from('pct_title_point_document_records');
        $this->db->where('title_point_id', $titlePointData->id);
        $query = $this->db->get();
        $instrumentRecords = $query->result_array();

        $displayDocList = $this->home_model->getDocumetTypes();

        $getAllSubCategory = $this->home_model->getAllSubCategory();
        $getAllSubCategory = array_column($getAllSubCategory, 'doc_type');

        $filteredSubCategoryList = array_filter($displayDocList, function ($item) {
            return $item['subtype_flag'] == 1;
        });
        $filteredSubCateList = array_column($filteredSubCategoryList, 'doc_type');

        $filteredMainCategoryList = array_filter($displayDocList, function ($item) {
            return $item['subtype_flag'] == 0;
        });
        $filteredMainCateList = array_column($filteredMainCategoryList, 'doc_type');

        if (!empty($instrumentRecords)) {
            foreach ($instrumentRecords as $val) {
                if ((in_array($val['document_sub_type'], $getAllSubCategory) && in_array($val['document_sub_type'], $filteredSubCateList)) || (empty($val['document_sub_type']) && in_array($val['document_type'], $filteredMainCateList))) {
                    // if (in_array($val['document_type'], array_column($displayDocList, 'doc_type'))) {
                    $key = array_search($val['document_type'], array_column($displayDocList, 'doc_type'));
                    $displaySection = $displayDocList[$key]['display_in_section'];

                    if (isset($val['document_sub_type']) && !empty($val['document_sub_type'])) {
                        $documentSubTypeList = $displayDocList[$key]['sub_type_list'];
                        if (!empty($documentSubTypeList)) {
                            $documentSubTypeListArr = explode(',', $documentSubTypeList);
                            if (in_array($val['document_sub_type'], $documentSubTypeListArr)) {
                                if (isset($displaySection) && $displaySection == 'G') {
                                    if ($val['color_coding'] == 'FFFF00') {
                                        //if (($val['document_type'] != 'TDD') || ($val['document_type'] == 'TDD' && $val['document_sub_type'] === null)) {
                                        if ($val['is_display'] == 0) {
                                            $this->db->update('pct_title_point_document_records', array('is_display' => 1), array('id' => $val['id']));
                                        }
                                        // } else {
                                        //     if ($val['is_display'] == 1) {
                                        //         $this->db->update('pct_title_point_document_records', array('is_display' => 0), array('id' => $val['id']));
                                        //     }
                                        // }
                                    } else if ($val['color_coding'] == 'C0C0C0') {
                                        if ($val['icon_text'] == 'Exx') {
                                            if ($val['is_display'] == 0) {
                                                $this->db->update('pct_title_point_document_records', array('is_display' => 1), array('id' => $val['id']));
                                            }
                                        } else {
                                            if ($val['is_display'] == 1) {
                                                $this->db->update('pct_title_point_document_records', array('is_display' => 0), array('id' => $val['id']));
                                            }
                                        }
                                    } else {
                                        if ($val['is_display'] == 1) {
                                            $this->db->update('pct_title_point_document_records', array('is_display' => 0), array('id' => $val['id']));
                                        }
                                    }
                                } else {
                                    if ($val['color_coding'] != 'A0A0FF') {
                                        if ($val['color_coding'] == 'C0C0C0') {
                                            if ($val['icon_text'] == 'Exx') {
                                                if ($val['is_display'] == 0) {
                                                    $this->db->update('pct_title_point_document_records', array('is_display' => 1), array('id' => $val['id']));
                                                }
                                            } else {
                                                if ($val['is_display'] == 1) {
                                                    $this->db->update('pct_title_point_document_records', array('is_display' => 0), array('id' => $val['id']));
                                                }
                                            }
                                        } else {
                                            if ($val['is_display'] == 0) {
                                                $this->db->update('pct_title_point_document_records', array('is_display' => 1), array('id' => $val['id']));
                                            }
                                        }
                                    } else {
                                        if ($val['is_display'] == 1) {
                                            $this->db->update('pct_title_point_document_records', array('is_display' => 0), array('id' => $val['id']));
                                        }
                                    }
                                }
                            } else {
                                if ($val['is_display'] == 1) {
                                    $this->db->update('pct_title_point_document_records', array('is_display' => 0), array('id' => $val['id']));
                                }
                            }
                        } else {
                            if (isset($displaySection) && $displaySection == 'G') {
                                if ($val['color_coding'] == 'FFFF00') {
                                    //if (($val['document_type'] != 'TDD') || ($val['document_type'] == 'TDD' && $val['document_sub_type'] === null)) {
                                    if ($val['is_display'] == 0) {
                                        $this->db->update('pct_title_point_document_records', array('is_display' => 1), array('id' => $val['id']));
                                    }
                                    // } else {
                                    //     if ($val['is_display'] == 1) {
                                    //         $this->db->update('pct_title_point_document_records', array('is_display' => 0), array('id' => $val['id']));
                                    //     }
                                    // }
                                } else if ($val['color_coding'] == 'C0C0C0') {
                                    if ($val['icon_text'] == 'Exx') {
                                        if ($val['is_display'] == 0) {
                                            $this->db->update('pct_title_point_document_records', array('is_display' => 1), array('id' => $val['id']));
                                        }
                                    } else {
                                        if ($val['is_display'] == 1) {
                                            $this->db->update('pct_title_point_document_records', array('is_display' => 0), array('id' => $val['id']));
                                        }
                                    }
                                } else {
                                    if ($val['is_display'] == 1) {
                                        $this->db->update('pct_title_point_document_records', array('is_display' => 0), array('id' => $val['id']));
                                    }
                                }
                            } else {
                                if ($val['color_coding'] != 'A0A0FF') {
                                    if ($val['color_coding'] == 'C0C0C0') {
                                        if ($val['icon_text'] == 'Exx') {
                                            if ($val['is_display'] == 0) {
                                                $this->db->update('pct_title_point_document_records', array('is_display' => 1), array('id' => $val['id']));
                                            }
                                        } else {
                                            if ($val['is_display'] == 1) {
                                                $this->db->update('pct_title_point_document_records', array('is_display' => 0), array('id' => $val['id']));
                                            }
                                        }
                                    } else {
                                        if ($val['is_display'] == 0) {
                                            $this->db->update('pct_title_point_document_records', array('is_display' => 1), array('id' => $val['id']));
                                        }
                                    }
                                } else {
                                    if ($val['is_display'] == 1) {
                                        $this->db->update('pct_title_point_document_records', array('is_display' => 0), array('id' => $val['id']));
                                    }
                                }
                            }
                        }
                    } else {
                        if (isset($displaySection) && $displaySection == 'G') {
                            if ($val['color_coding'] == 'FFFF00') {
                                //if (($val['document_type'] != 'TDD') || ($val['document_type'] == 'TDD' && $val['document_sub_type'] === null)) {
                                if ($val['is_display'] == 0) {
                                    $this->db->update('pct_title_point_document_records', array('is_display' => 1), array('id' => $val['id']));
                                }
                                // } else {
                                //     if ($val['is_display'] == 1) {
                                //         $this->db->update('pct_title_point_document_records', array('is_display' => 0), array('id' => $val['id']));
                                //     }
                                // }
                            } else if ($val['color_coding'] == 'C0C0C0') {
                                if ($val['icon_text'] == 'Exx') {
                                    if ($val['is_display'] == 0) {
                                        $this->db->update('pct_title_point_document_records', array('is_display' => 1), array('id' => $val['id']));
                                    }
                                } else {
                                    if ($val['is_display'] == 1) {
                                        $this->db->update('pct_title_point_document_records', array('is_display' => 0), array('id' => $val['id']));
                                    }
                                }
                            } else {
                                if ($val['is_display'] == 1) {
                                    $this->db->update('pct_title_point_document_records', array('is_display' => 0), array('id' => $val['id']));
                                }
                            }
                        } else {
                            if ($val['color_coding'] != 'A0A0FF') {
                                if ($val['color_coding'] == 'C0C0C0') {
                                    if ($val['icon_text'] == 'Exx') {
                                        if ($val['is_display'] == 0) {
                                            $this->db->update('pct_title_point_document_records', array('is_display' => 1), array('id' => $val['id']));
                                        }
                                    } else {
                                        if ($val['is_display'] == 1) {
                                            $this->db->update('pct_title_point_document_records', array('is_display' => 0), array('id' => $val['id']));
                                        }
                                    }
                                } else {
                                    if ($val['is_display'] == 0) {
                                        $this->db->update('pct_title_point_document_records', array('is_display' => 1), array('id' => $val['id']));
                                    }
                                }
                            } else {
                                if ($val['is_display'] == 1) {
                                    $this->db->update('pct_title_point_document_records', array('is_display' => 0), array('id' => $val['id']));
                                }
                            }
                        }
                    }
                } else {
                    if ($val['is_display'] == 1) {
                        $this->db->update('pct_title_point_document_records', array('is_display' => 0), array('id' => $val['id']));
                    }
                }
            }
        }
        $this->order->createLpReport($titlePointData->file_number, true, true);

        /** Save user Activity */
        $activity = 'Regenerated LP Order from regenerate button: ' . $titlePointData->file_number;
        $this->order->logAdminActivity($activity);
        /** End Save user activity */

        $data = array('status' => 'success', 'message' => 'Lp report regenerated successfully.');
        echo json_encode($data);
    }

    public function addVestingInfo()
    {
        $file_id = $this->input->post('file_id');
        $this->db->select('*');
        $this->db->from('pct_order_title_point_data');
        $this->db->where('file_id', $file_id);
        $query = $this->db->get();
        $titlePointData = $query->row_array();

        $vesting_info = $this->input->post('vesting_info');
        $this->db->update('pct_order_title_point_data', array('vesting_information' => $vesting_info), array('id' => $titlePointData['id']));
        $this->load->library('order/order');
        $this->order->createLpReport($titlePointData['file_number'], true, false);
        /** Save user Activity */
        $activity = 'Vesting info updated and New LP report generated: ' . $titlePointData['file_number'];
        $this->order->logAdminActivity($activity);
        /** End Save user activity */
        $successMsg = 'Vesting info saved successfully and LP report generated successfully for new data.';
        $this->session->set_userdata('success', $successMsg);
        redirect(base_url() . 'order/admin/lp-orders');
    }

    public function getVestingInfo()
    {
        $file_id = $this->input->post('file_id');
        $this->db->select('*');
        $this->db->from('pct_order_title_point_data');
        $this->db->where('file_id', $file_id);
        $query = $this->db->get();
        $titlePointData = $query->row_array();
        $data = array('status' => 'success', 'vesting_information' => $titlePointData['vesting_information']);
        echo json_encode($data);
        exit;
    }

    public function addInstrumentInfo()
    {
        $this->load->library('order/order');
        $config['upload_path'] = './uploads/title-point/';
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 12000;
        $this->load->library('upload', $config);
        if (!is_dir('/uploads/title-point')) {
            mkdir('./uploads/title-point', 0777, true);
        }
        if (!empty($_FILES['file_upload']['name'])) {
            if (!$this->upload->do_upload('file_upload')) {
                $errorMsg = $this->upload->display_errors();
                $this->session->set_userdata('error', $errorMsg);
                $file_upload_error_msg = 1;
            } else {
                $data = $this->upload->data();
                $file_id = $this->input->post('upload_file_id');
                $this->db->select('*');
                $this->db->from('pct_order_title_point_data');
                $this->db->where('file_id', $file_id);
                $query = $this->db->get();
                $titlePointData = $query->row_array();

                $instrumentData = array(
                    'title_point_id' => $titlePointData['id'],
                    'instrument' => $this->input->post('instrument_number'),
                    'recorded_date' => date("Y-m-d", strtotime($this->input->post('recorded_date'))),
                    'type' => 'REC',
                    'sub_type' => 'ALL',
                    'order_number' => null,
                    'document_name' => $this->input->post('document_name'),
                    'document_type' => $this->input->post('document_type'),
                    'document_sub_type' => $this->input->post('document_sub_type') ? $this->input->post('document_sub_type') : null,
                    'parties' => $this->input->post('parties') ? $this->input->post('parties') : null,
                    'coupling' => null,
                    'remarks' => null,
                    'color_coding' => 'FFFF00',
                    'icon_text' => null,
                    'loan_amount' => $this->input->post('amount') ? $this->input->post('amount') : null,
                    'created_at' => date("Y-m-d H:i:s"),
                    'amount' => 0,
                    'is_display' => 1,
                );
                $id = $this->home_model->insert($instrumentData, 'pct_title_point_document_records');

                $document_name = $id . '.pdf';
                rename(FCPATH . "/uploads/title-point/" . $data['file_name'], FCPATH . "/uploads/title-point/" . $document_name);
                $this->order->uploadDocumentOnAwsS3($document_name, 'title-point');
                //$this->order->createLpReport($titlePointData['file_number'], true, false);

                /** Save user Activity */
                $activity = 'New document uploaded for order : ' . $titlePointData['file_number'];
                $this->order->logAdminActivity($activity);
                /** End Save user activity */
                $successMsg = 'Document info saved successfully.';
                $this->session->set_userdata('success', $successMsg);
            }
        }
        redirect(base_url() . 'order/admin/lp-orders');
    }

    public function regenerateTaxDocument()
    {
        $this->load->library('order/titlepoint');
        $requestId = $this->input->post('request_id');
        $orderId = $this->input->post('order_id');
        $fileNumber = $this->input->post('file_number');
        $generateImgResponse = $this->titlepoint->generateTaxImage($requestId, $orderId);

        $generateImgResult = json_decode($generateImgResponse, true);
        $generateImgReturnStatus = isset($generateImgResult['ReturnStatus']) && !empty($generateImgResult['ReturnStatus']) ? $generateImgResult['ReturnStatus'] : '';
        $generateImgStatus = isset($generateImgResult['Status']) && !empty($generateImgResult['Status']) ? $generateImgResult['Status'] : '';

        $generateImgMsg = isset($generateImgResult['Message']) && !empty($generateImgResult['Message']) ? $generateImgResult['Message'] : '';
        $generateImgReturnStatus = strtolower($generateImgReturnStatus);
        $generateImgStatus = strtolower($generateImgStatus);
        if ($generateImgReturnStatus == 'success' && $generateImgStatus == 'success') {
            $base64_data = isset($generateImgResult['Data']) && !empty($generateImgResult['Data']) ? $generateImgResult['Data'] : '';

            if (isset($base64_data) && !empty($base64_data)) {
                $bin = base64_decode($base64_data, true);

                if (!is_dir('uploads/tax')) {
                    mkdir('./uploads/tax', 0777, true);
                }

                $pdfFilePath = './uploads/tax/' . $fileNumber . '.pdf';
                file_put_contents($pdfFilePath, $bin);
                $this->order->uploadDocumentOnAwsS3($fileNumber . '.pdf', 'tax');
            }

            $tpData = array(
                'tax_file_status' => $generateImgStatus,
                'tax_file_message' => $generateImgMsg,
                'tax_request_id' => $requestId,
            );

            $condition = array(
                'file_number' => $fileNumber,
            );

            $this->titlePointData->update($tpData, $condition);
            $data = array('status' => 'success', 'message' => 'Tax document generated successfully');
            echo json_encode($data);
            exit;
        } else if ($generateImgReturnStatus == 'success' && $generateImgStatus != 'success') {

            $tpData = array(
                'tax_file_status' => $generateImgStatus,
                'tax_file_message' => $generateImgMsg,
                'tax_request_id' => $requestId,
            );

            $condition = array(
                'file_number' => $fileNumber,
            );
            $this->titlePointData->update($tpData, $condition);
            $data = array('status' => 'success', 'message' => 'Tax document generation still in progress.');
            echo json_encode($data);
            exit;
        } else {
            $error = isset($generateImgResult['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($generateImgResult['ReturnErrors']['ReturnError']['ErrorDescription']) ? $generateImgResult['ReturnErrors']['ReturnError']['ErrorDescription'] : '';

            $tpData = array(
                'tax_file_status' => $generateImgReturnStatus,
                'tax_file_message' => $error,
                'tax_request_id' => $requestId,
            );
            $condition = array(
                'file_number' => $fileNumber,
            );
            $this->titlePointData->update($tpData, $condition);
            $data = array('status' => 'error', 'message' => $error);
            echo json_encode($data);
            exit;
        }
    }

    public function generateTaxDocument()
    {
        $this->load->library('order/titlepoint');
        $this->load->model('order/apiLogs');
        $serviceId = $this->input->post('cs3_service_id');
        $orderId = $this->input->post('order_id');
        $fileNumber = $this->input->post('file_number');

        $serviceId = isset($serviceId) && !empty($serviceId) ? $serviceId : '';
        $opts = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $context = stream_context_create($opts);

        if ($serviceId) {
            $requestParams = array(
                'username' => env('TP_USERNAME'),
                'password' => env('TP_PASSWORD'),
                'serviceId1' => $serviceId,
                'serviceId2' => '',
                'source' => '',
                'clientKey1' => '',
                'clientKey2' => '',
                'sortOrder' => '',
                'fileType' => 'pdf',
            );
            $requestUrl = env('TP_IMAGE_ENDPOINT');

            $request = $requestUrl . http_build_query($requestParams);

            $logid = $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'create_tax_image_request', $request, $requestParams, array(), $orderId, 0);
            $response = $this->order->curl_post($requestUrl, $requestParams);
            $result = json_decode($response, true);

            $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'create_tax_image_request', $request, $requestParams, $result, $orderId, $logid);

            $returnStatus = isset($result['ReturnStatus']) && !empty($result['ReturnStatus']) ? $result['ReturnStatus'] : '';
            $returnStatus = strtolower($returnStatus);

            if ($returnStatus == 'success') {
                $requestId = isset($result['RequestID']) && !empty($result['RequestID']) ? $result['RequestID'] : '';
                $requestOrderId = isset($result['OrderID']) && !empty($result['OrderID']) ? $result['OrderID'] : '';
                if (isset($requestId) && !empty($requestId)) {
                    $generateImgResponse = $this->titlepoint->generateTaxImage($requestId, $orderId);

                    $generateImgResult = json_decode($generateImgResponse, true);
                    $generateImgReturnStatus = isset($generateImgResult['ReturnStatus']) && !empty($generateImgResult['ReturnStatus']) ? $generateImgResult['ReturnStatus'] : '';
                    $generateImgStatus = isset($generateImgResult['Status']) && !empty($generateImgResult['Status']) ? $generateImgResult['Status'] : '';

                    $generateImgMsg = isset($generateImgResult['Message']) && !empty($generateImgResult['Message']) ? $generateImgResult['Message'] : '';
                    $generateImgReturnStatus = strtolower($generateImgReturnStatus);
                    $generateImgStatus = strtolower($generateImgStatus);
                    if ($generateImgReturnStatus == 'success' && $generateImgStatus == 'success') {
                        $base64_data = isset($generateImgResult['Data']) && !empty($generateImgResult['Data']) ? $generateImgResult['Data'] : '';
                        if (isset($base64_data) && !empty($base64_data)) {
                            $bin = base64_decode($base64_data, true);

                            if (!is_dir('uploads/tax')) {
                                mkdir('./uploads/tax', 0777, true);
                            }

                            $pdfFilePath = './uploads/tax/' . $fileNumber . '.pdf';
                            file_put_contents($pdfFilePath, $bin);
                            $this->order->uploadDocumentOnAwsS3($fileNumber . '.pdf', 'tax');
                        }

                        $tpData = array(
                            'tax_file_status' => $generateImgStatus,
                            'tax_file_message' => $generateImgMsg,
                            'tax_request_id' => $requestId,
                            'tax_order_id' => $requestOrderId,
                        );
                        $condition = array(
                            'file_number' => $fileNumber,
                        );
                        $this->titlePointData->update($tpData, $condition);
                        $data = array('status' => 'success', 'message' => 'Tax document generated successfully');
                        echo json_encode($data);
                        exit;
                    } else if ($generateImgReturnStatus == 'success' && $generateImgStatus != 'success') {

                        $tpData = array(
                            'tax_file_status' => $generateImgStatus,
                            'tax_file_message' => $generateImgMsg,
                            'tax_request_id' => $requestId,
                            'tax_order_id' => $requestOrderId,
                        );
                        $condition = array(
                            'file_number' => $fileNumber,
                        );
                        $this->titlePointData->update($tpData, $condition);
                        $data = array('status' => 'success', 'message' => 'Tax document generation still in progress.');
                        echo json_encode($data);
                        exit;
                    } else {
                        $error = isset($generateImgResult['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($generateImgResult['ReturnErrors']['ReturnError']['ErrorDescription']) ? $generateImgResult['ReturnErrors']['ReturnError']['ErrorDescription'] : '';

                        $tpData = array(
                            'tax_file_status' => $generateImgReturnStatus,
                            'tax_file_message' => $error,
                            'tax_request_id' => $requestId,
                            'tax_order_id' => $requestOrderId,
                        );
                        $condition = array(
                            'file_number' => $fileNumber,
                        );
                        $this->titlePointData->update($tpData, $condition);
                        $data = array('status' => 'error', 'message' => $error);
                        echo json_encode($data);
                        exit;
                    }
                }
            } else {
                $error = isset($result['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($result['ReturnErrors']['ReturnError']['ErrorDescription']) ? $result['ReturnErrors']['ReturnError']['ErrorDescription'] : '';

                $tpData = array(
                    'tax_file_status' => $returnStatus,
                    'tax_file_message' => $error,
                );
                $condition = array(
                    'file_number' => $fileNumber,
                );
                $this->titlePointData->update($tpData, $condition);
                $data = array('status' => 'error', 'message' => $error);
                echo json_encode($data);
                exit;
            }
        }
    }

    public function changeClient()
    {
        $file_id = $this->input->post('client_file_id');
        $customer_id = $this->input->post('client_id');
        $condition = array('file_id' => $file_id);
        $data = array('customer_id' => $customer_id);
        $this->home_model->update($data, $condition, 'order_details');
        $order_details = $this->order_model->get_order_details($file_id);
        /** Save user Activity */
        $activity = 'Client user changed successfully for order : ' . $order_details['lp_file_number'];
        $this->order->logAdminActivity($activity);
        /** End Save user activity */
        $successMsg = $activity;
        $this->session->set_userdata('success', $successMsg);
        redirect(base_url() . 'order/admin/lp-orders');
    }

    public function dailyEmailControl()
    {
        $data = array();
        $data['title'] = 'PCT Order: Daily email control';
        $this->admintemplate->show("order/dailyEmailReceiver", "daily-email-control", $data);
    }

    public function getDailyEmailer()
    {
        $params = array();
        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;
            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $pageno = ($params['start'] / $params['length']) + 1;
            $receiverLists = $this->home_model->get_daily_email_receiver($params);
            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $receiverLists = $this->home_model->get_daily_email_receiver($params);
        }

        $data = array();
        if (isset($receiverLists['data']) && !empty($receiverLists['data'])) {
            foreach ($receiverLists['data'] as $key => $value) {
                $nestedData = array();
                $nestedData[] = $key + 1;
                $nestedData[] = $value['email'];
                $nestedData[] = ($value['status']) ? 'Active' : 'Disabled';
                $nestedData[] = ucwords($value['branch']);
                if (isset($_POST['draw']) && !empty($_POST['draw'])) {
                    $editUrl = base_url() . 'order/admin/edit-daily-emailer/' . $value['id'];
                    $nestedData[] = "<div style='display: flex;justify-content: space-evenly;' ><a href='" . $editUrl . "'   title='Edit Receiver'><span class='fas fa-edit' aria-hidden='true'></span></a><a href='javascript:void(0);' onclick='deleteDailyReceiver(" . $value['id'] . ")'  title='Delete Receiver'><span class='fas fa-trash' aria-hidden='true'></span></a></div>";
                }
                $data[] = $nestedData;
            }
        }

        $json_data['recordsTotal'] = intval($receiverLists['recordsTotal']);
        $json_data['recordsFiltered'] = intval($receiverLists['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }

    public function addDailyEmailer()
    {
        $data = array();
        $data['title'] = 'PCT Order: Add New Master User';
        $salesRepData = array();
        $this->db->select('*')
            ->from('pct_order_partner_company_info');

        $query = $this->db->get();
        $data['companys'] = $query->result_array();

        if ($this->input->post()) {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[pct_daily_email_receiver_list.email]', array('required' => 'Please Enter Email', 'valid_email' => 'Please enter valid Email', 'is_unique' => 'The %s is already taken'));
            $this->form_validation->set_rules('branch', 'Branch', 'required', array('required' => 'Please Select Branch'));

            if ($this->form_validation->run() == true) {
                $customerData = array(
                    'email' => $this->input->post('email'),
                    'branch' => $this->input->post('branch'),
                    'status' => 1,
                );
                $insert = $this->home_model->insert($customerData, 'pct_daily_email_receiver_list');
                if ($insert) {
                    /** Save user Activity */
                    $activity = 'Daily email receiver created :- ' . $this->input->post('email') . ' - Branch: ' . $this->input->post('branch');
                    $this->order->logAdminActivity($activity);
                    /** End Save user activity */
                    $data['success_msg'] = 'Daily email receiver added successfully.';
                    $this->form_validation->reset_validation();
                    redirect(base_url() . 'order/admin/daily-email-control');
                } else {
                    $data['error_msg'] = 'User not added.';
                }
            } else {
                $data['email_error_msg'] = form_error('email');
                $data['branch_error_msg'] = form_error('branch');
            }
        }
        $this->admintemplate->show("order/dailyEmailReceiver", "add-daily-email-receiver", $data);
    }

    public function editDailyEmailer()
    {
        $data = array();
        $data['title'] = 'PCT Order: Edit Master User';
        $id = $this->uri->segment(4);

        if (isset($id) && !empty($id)) {
            if ($this->input->post()) {
                $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email', array('required' => 'Please Enter Email', 'valid_email' => 'Please enter valid Email'));
                $this->form_validation->set_rules('branch', 'Branch', 'required', array('required' => 'Please Select Branch'));

                if ($this->form_validation->run() == true) {
                    // print_r($this->input->post());die;
                    $customerData = array(
                        'email' => $this->input->post('email'),
                        'branch' => $this->input->post('branch'),
                        'status' => $this->input->post('status') ? 1 : 0,
                    );

                    $updateCondition = array(
                        'id' => $id,
                    );
                    $update = $this->home_model->update($customerData, $updateCondition, 'pct_daily_email_receiver_list');
                    if ($update) {
                        /** Save user Activity */
                        // $user =  $this->home_model->get_user($condition);
                        $activity = 'Daily email receiver :- ' . $this->input->post('email') . ' -  ' . $this->input->post('branch') . ' details updated';
                        $this->order->logAdminActivity($activity);
                        /** End Save user activity */
                        $data['success_msg'] = 'Daily email receiver updated successfully.';
                        $this->form_validation->reset_validation();
                        redirect(base_url() . 'order/admin/daily-email-control');
                    } else {
                        $data['error_msg'] = 'Daily email receiver not updated.';
                    }
                } else {
                    $data['email_error_msg'] = form_error('email');
                }
            }
            $con = array('id' => $id);
            $data['receiver_info'] = $this->home_model->get_rows($con, 'pct_daily_email_receiver_list');
        }
        $this->admintemplate->show("order/dailyEmailReceiver", "edit-daily-email-receiver", $data);
    }

    public function deleteDailyEmailerReceiver()
    {
        $id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';

        if ($id) {
            $condition = array('id' => $id);

            $lpDoc = $this->home_model->getLpDocType($condition);
            $status = $this->home_model->deleteLpDocType($condition, 'pct_daily_email_receiver_list');
            if ($status) {
                /** Save user Activity */
                $activity = 'Deleted daily email receiver: ' . $id;
                $this->order->logAdminActivity($activity);
                /** End save user activity */
                $successMsg = 'Record deleted successfully.';
                $response = array('status' => 'success', 'message' => $successMsg);
            }
        } else {
            $msg = 'ID is required.';
            $response = array('status' => 'error', 'message' => $msg);
        }

        echo json_encode($response);
    }

    public function manualReport()
    {
        if ($this->session->userdata('errors')) {
            $data['errors'] = $this->session->userdata('errors');
            $this->session->unset_userdata('errors');
        }
        if ($this->session->userdata('success')) {
            $data['success'] = $this->session->userdata('success');
            $this->session->unset_userdata('success');
        }
        $this->load->library('order/order');
        $data = array();
        $data['title'] = 'PCT Order: Manual Report';
        $data['salesUsers'] = $this->order->get_sales_users();
        $this->admintemplate->show("order/home", "manual_report", $data);
    }

    public function sendSummaryMailSalesRep()
    {
        $sales_rep = $this->input->post('sales_rep');
        $result = $this->order->sendSummaryMail($sales_rep);
        $this->session->set_userdata('success', 'Mail sent to successfully to sales rep.');
        redirect(base_url() . 'order/admin/manual-report');
    }

    public function sendNonOpenersEmail()
    {
        $sales_rep = $this->input->post('sales_rep');
        $result = $this->order->sendNonOpenersEmail($sales_rep);
        $this->session->set_userdata('success', 'Mail sent to successfully to sales rep.');
        redirect(base_url() . 'order/admin/manual-report');
    }

    public function exportSalesRepReports()
    {
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        if (empty($month) || empty($year)) {
            echo json_encode(array('status' => 'error', 'data' => 'Please specify month and year to generate reports.'));
            exit;
        }

        $ordersList = $this->home_model->get_sales_rep_report($month, $year);
        // echo "<pre>";
        // print_r($ordersList);die;
        if (isset($ordersList) && !empty($ordersList)) {
            // $export_data = array();
            foreach ($ordersList as $key => $value) {
                $file_id = isset($value['file_id']) && !empty(!empty($value['file_id'])) ? $value['file_id'] : '';
                if ($file_id) {

                    $export_data[] = array(
                        'transaction_status' => ($value['file_number'] == 0 && !empty($value['lp_file_number'])) ? ucfirst($value['lp_report_status']) : ucfirst($value['resware_status']), //$value['resware_status'], //(empty($value['sent_to_accounting_date'])) ? 'Open' : 'Closed',
                        'client_name' => $value['client_name'],
                        'client_email' => $value['client_email'],
                        'client_phone' => $value['client_phone'],
                        'transaction_type' => $value['transaction_type'],
                        'adrress' => $value['full_address'],
                        'unit' => $value['unit_number'],
                        'property_city' => $value['property_city'],
                        'property_state' => $value['property_state'],
                        'property_zip' => $value['property_zip'],
                        'order' => (!empty($value['file_number']) ? $value['file_number'] : $value['lp_file_number']),
                        'opened_date' => date('Y-m-d', strtotime($value['opened_date'])),
                        'list_price' => '$' . (($value['prod_type'] == 'sale') ? $value['order_sales_amount'] : $value['order_loan_amount']),
                        'created_date' => '', //date('Y-m-d', strtotime($value['opened_date'])),
                        'sales_price' => '', //'$' . (($value['prod_type'] == 'sale') ? $value['order_sales_amount'] : $value['order_loan_amount']),
                        'closing_date' => !empty($value['sent_to_accounting_date']) ? date('Y-m-d', strtotime($value['sent_to_accounting_date'])) : '',
                        'title_premium' => $value['premium'], //'title_premium static for now',
                        'escrow_fees' => '', //'escrow_fees static for now',
                        'fees' => '', //'fees static for now',
                        'notes' => '', //$value['notes'],
                        'owner_name' => $value['sales_rep_name'],
                        'owner_email' => $value['sales_rep_email'],

                        // 'prod_type' => $value['prod_type'],
                        // 'status' => ($value['file_number'] == 0 && !empty($value['lp_file_number'])) ? ucfirst($order['lp_report_status']) : ucfirst($order['resware_status']),

                    );
                }
            }
            if (isset($export_data) && !empty($export_data)) {
                if (!is_dir('uploads/orders')) {
                    mkdir('./uploads/orders', 0777, true);
                }

                $outputPath = './uploads/orders/sales_rep_report.csv';
                $output = fopen($outputPath, "w");

                $header = array("Transaction Status", "Client Name", "Client Email", "Client Phone", "Transaction Type", "Address", "Unit #", "City", "State", "Zipcode", "Order #", "Open Date", "List Price", "Created Date", "Sale Price", "Closing Date", "Title Premium", "Escrow Fee", "Fee", "Notes", "Owner Name", "Owner Email");
                fputcsv($output, $header);

                foreach ($export_data as $key => $value) {
                    fputcsv($output, $value);
                }

                header('Content-Type: application/json');
                $contents = file_get_contents($outputPath);
                $binaryData = base64_encode($contents);
                unlink($outputPath);
                fclose($output);

                $res = array('status' => 'success', 'data' => $binaryData);
            } else {
                $res = array('status' => 'error', 'data' => 'No data found.');
            }
        } else {
            $res = array('status' => 'error', 'data' => 'No data found.');
        }

        echo json_encode($res);
        exit;
    }

    public function ionFraud()
    {
        $data = array();
        $data['title'] = 'PCT Order: ION Fraud Log';
        $this->admintemplate->show("order/home", "ion_fraud_log", $data);
    }

    public function get_ion_fraud_listing_logs()
    {
        $params = array();

        if (isset($_POST['draw']) && !empty($_POST['draw'])) {
            $params['draw'] = isset($_POST['draw']) && !empty($_POST['draw']) ? $_POST['draw'] : 10;
            $params['length'] = isset($_POST['length']) && !empty($_POST['length']) ? $_POST['length'] : 10;
            $params['start'] = isset($_POST['start']) && !empty($_POST['start']) ? $_POST['start'] : 0;
            $params['orderColumn'] = isset($_POST['order'][0]['column']) && !empty($_POST['order'][0]['column']) ? $_POST['order'][0]['column'] : 0;
            $params['orderDir'] = isset($_POST['order'][0]['dir']) && !empty($_POST['order'][0]['dir']) ? $_POST['order'][0]['dir'] : 0;

            $params['searchvalue'] = isset($_POST['search']['value']) && !empty($_POST['search']['value']) ? $_POST['search']['value'] : '';
            $params['ionFraudStatus'] = isset($_POST['ionFraudStatus']) && !empty($_POST['ionFraudStatus']) ? $_POST['ionFraudStatus'] : '';
            $params['ionFraudProceedStatus'] = isset($_POST['ionFraudProceedStatus']) && !empty($_POST['ionFraudProceedStatus']) ? $_POST['ionFraudProceedStatus'] : '';

            $pageno = ($params['start'] / $params['length']) + 1;
            $logs_list = $this->home_model->getIonFraudListingLogs($params);

            $json_data['draw'] = intval($params['draw']);
        } else {
            $params['searchvalue'] = isset($_POST['keyword']) && !empty($_POST['keyword']) ? $_POST['keyword'] : '';
            $params['ionFraudStatus'] = isset($_POST['ionFraudStatus']) && !empty($_POST['ionFraudStatus']) ? $_POST['ionFraudStatus'] : '';
            $params['ionFraudProceedStatus'] = isset($_POST['ionFraudProceedStatus']) && !empty($_POST['ionFraudProceedStatus']) ? $_POST['ionFraudProceedStatus'] : '';

            $logs_list = $this->home_model->getIonFraudListingLogs($params);
        }
        $data = array();

        if (isset($logs_list['data']) && !empty($logs_list['data'])) {
            $count = $params['start'] + 1;
            foreach ($logs_list['data'] as $key => $value) {

                $nestedData = array();

                $nestedData[] = $count;
                $nestedData[] = $value['lp_file_number'];
                $nestedData[] = ucwords($value['ion_fraud_required_status']);
                $nestedData[] = ucwords($value['ion_fraud_proceed_status']);

                // $nestedData[] = date("m/d/Y h:i:s A", strtotime($value['created_at']));
                $nestedData[] = convertTimezone($value['created_at']);

                $data[] = $nestedData;
                $count++;

            }
        }
        $json_data['recordsTotal'] = intval($logs_list['recordsTotal']);
        $json_data['recordsFiltered'] = intval($logs_list['recordsFiltered']);
        $json_data['data'] = $data;
        echo json_encode($json_data);
    }
    
    // public function surveys()
    // {
    //     $data = array();
    //     $data['title'] = 'PCT Order: Surveys';
        
    //     $this->load->library('order/survey');
    //     $this->load->model('order/apiLogs');
    //     $endPoint = 'surveys';
    //     $userdata['email'] = $userdata['email_address'];
    //     // $logid = $this->apiLogs->syncLogs($userdata['id'], 'survey', 'get_survey', env('SURVEYMONKEY_API_URL') . $endPoint, array(), array(), 0, 0);
    //     $result = $this->survey->make_request('GET', $endPoint, array(), $userdata);
    //     // $this->apiLogs->syncLogs($v['id'], 'survey', 'get_survey', env('SURVEYMONKEY_API_URL') . $endPoint, array(), $result, 0, $logid);
    //     $survey = [];
    //     if (isset($result) && !empty($result)) {
    //         $response = json_decode($result, true);
    //         if (isset($response['data']) && !empty($response['data'])) {
    //             foreach ($response['data'] as $key => $value) {
    //                 $arr = [];
    //                 $arr['id'] = $value['id'];
    //                 $arr['title'] = $value['title'];
    //                 $arr['nickname'] = $value['nickname'];
    //                 $arr['href'] = $value['href'];
    //                 $endPoint = 'surveys/' . $value['id'] . '/responses/bulk';
    //                 $result = $this->survey->make_request('GET', $endPoint, array(), $userdata);
    //                 if (isset($result) && !empty($result)) {
    //                     $response = json_decode($result, true);
    //                     $questionAverages = [];
    //                     $textComment = [];
    //                     // echo "<pre>";
    //                     if (isset($response['data'])) {
    //                         foreach ($response['data'] as $res) {
    //                             foreach ($res['pages'] as $page) {
    //                                 foreach ($page['questions'] as $question) {
    //                                     $questionId = $question['id'];
    //                                     foreach ($question['answers'] as $answer) {
    //                                         if (isset($answer['choice_metadata']['weight'])) {
    //                                             $questionAverages[$questionId][] = (int)$answer['choice_metadata']['weight'];
    //                                         }
    //                                         if (isset($answer['text']) && !empty($answer['text'])) {
    //                                             $textComment[] = $answer['text'];
    //                                         }
    //                                     }
    //                                 }
    //                             }
    //                         }
    //                         // Calculate average for each question
    //                         $finalAverages = [];
    //                         $i = 1;
    //                         foreach ($questionAverages as $questionId => $weights) {
    //                             $finalAverages['Q'.$i] = array_sum($weights) / count($weights);
    //                             $i++;
    //                         }
    //                         // print_r($page);die;
    //                         $arr['avg'] = $finalAverages;
    //                         $arr['textComment'] = $textComment;
    //                     }
    //                 }
    //                 $survey[] = $arr;
    //                 // echo "<pre>";
    //                 // print_r($response);die;
    //                 // $surveyId = $value['id'];
    //                 // $endPoint = 'surveys/' . $surveyId . '/collect';
    //             }
    //         }
    //     }
    //     // echo "<pre>";
    //     // print_r($survey);die;
    //     $data['survey'] = $survey;
    //     $this->admintemplate->addCSS(base_url('assets/frontend/css/smart-forms.css?v=6'));
    //     $this->admintemplate->show("order/home", "surveys", $data);
    // }

    public function surveys()
    {
        $data = array();
        $data['title'] = 'PCT Order: Surveys';
        
        $this->load->library('order/survey');
        $this->load->model('order/apiLogs');
        $endPoint = 'surveys';
        // $userdata['email'] = $userdata['email_address'];
        // $logid = $this->apiLogs->syncLogs($userdata['id'], 'survey', 'get_survey', env('SURVEYMONKEY_API_URL') . $endPoint, array(), array(), 0, 0);
        $result = $this->survey->make_request('GET', $endPoint, array(), $userdata);
        // $this->apiLogs->syncLogs($v['id'], 'survey', 'get_survey', env('SURVEYMONKEY_API_URL') . $endPoint, array(), $result, 0, $logid);
        $survey = [];
        $titleOfficerList = [];

        if (isset($result) && !empty($result)) {
            $response = json_decode($result, true);
            // echo "<pre>";
            // print_r($response);die;
            if (isset($response['data']) && !empty($response['data'])) {
                foreach ($response['data'] as $key => $value) {
                    $arr = [];
                    $arr['id'] = $value['id'];
                    $arr['title'] = $value['title'];
                    $arr['nickname'] = $value['nickname'];
                    $arr['href'] = $value['href'];
                    $titleOfficerList[] = $arr;
                    // break;
                }
            }
        }
        $ratingData = [];
        if (!empty($titleOfficerList)) {
            // $titleOffSurveyId = "417131721"; 
            $titleOffSurveyId = $titleOfficerList['0']['id']; 
            $endPoint = 'surveys/' . $titleOffSurveyId . '/responses/bulk';
            $result = $this->survey->make_request('GET', $endPoint, array(), $userdata);
            if (isset($result) && !empty($result)) {
                $response = json_decode($result, true);
                $questionAverages = [];
                $textComment = [];
                $ratingArray = [];
                // echo "<pre>";
                if (isset($response['data'])) {
                    foreach ($response['data'] as $res) {
                        $ratingArr = [];
                        $ratingArr['sales_rep'] = '-';
                        if (isset($res['custom_variables']) && !empty($res['custom_variables'])) {
                            $orderId = $res['custom_variables']['order_id'];
                            $salesRepDetails = $this->order->getSalesRepForOrder($orderId);
                            // echo "<pre>";
                            // print_r($salesRepDetails);die;
                            $ratingArr['sales_rep'] = $salesRepDetails['first_name'] . ' ' . $salesRepDetails['last_name'];
                        }
                        $ratingData['titleOfficer'] = $titleOfficerList['0']['title'];
                        foreach ($res['pages'] as $page) {
                            foreach ($page['questions'] as $key => $question) {
                                $questionId = $question['id'];
                                foreach ($question['answers'] as $answer) {
                                    if (isset($answer['choice_metadata']['weight'])) {
                                        $ratingArr[$questionId] = (int)$answer['choice_metadata']['weight'];
                                        // $ratingArr['Q'.($key+1)] = (int)$answer['choice_metadata']['weight'];
                                        $questionAverages[$questionId][] = (int)$answer['choice_metadata']['weight'];
                                    }
                                    if (isset($answer['text']) && !empty($answer['text'])) {
                                        $ratingArr['comment'] = $answer['text'];
                                        $textComment[] = $answer['text'];
                                    }
                                }
                            }
                        }
                        $ratingArray[] = $ratingArr;
                    }
                    // Calculate average for each question
                    $finalAverages = [];
                    $i = 1;
                    foreach ($questionAverages as $questionId => $weights) {
                        $finalAverages['Q'.$i] = number_format(array_sum($weights) / count($weights), 2);
                        $i++;
                    }
                    $ratingData['rating'] = $ratingArray;
                    // print_r($page);die;
                    $ratingData['avg'] = $finalAverages;
                    $ratingData['textComment'] = $textComment;
                    $survey['survey_cards'] = $this->order->surveyReportCards($ratingData);
                    $survey['survey_rating_details'] = $this->order->surveyReportRating($ratingData);
                    $survey['title_officer_list'] = $titleOfficerList;
                }
            }
        }
        
        // echo "<pre>";
        // print_r($ratingData);
        // print_r($survey);die;
        // $data['survey'] = $survey;
        
        // $this->admintemplate->addCSS(base_url('assets/frontend/css/smart-forms.css?v=' . $this->version));
        $this->admintemplate->addCss(base_url('assets/frontend/css/sales-dashboard.css?v=' . $this->version));
        // $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order/sales_dashboard.js?v=' . $this->version));
        // $this->salesdashboardtemplate->show("order/common", "survey_result", $survey);
        $this->admintemplate->addJS(base_url('assets/backend/js/survey.js?v=' . $this->version));
        $this->admintemplate->show("order/home", "surveys", $survey);
    }

    public function sendSurveySampleEmail()
    {
        $input = $this->input->post();
        $data['email_address'] = $input['email_address'];
        $data['survey_link'] = 'https://www.surveymonkey.com/r/KR5G38W';
        $mail_result = $this->order->sendSurveySampleEmail($data);
        
        if ($mail_result) {
            echo json_encode(['status' => 'success', 'message' => 'Mail sent successfully to : ' . $input['email_address']]);
            exit;
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Something went wrong, Please try later']);
            exit;
        }
    }
}
