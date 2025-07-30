<?php

(defined('BASEPATH')) or exit('No direct script access allowed');

class Home extends MX_Controller
{
    private $version;
    public function __construct()
    {
        parent::__construct();
        $this->version = strtotime(date('Y-m-d'));
        $this->load->helper(array('file', 'url'));
        $this->load->library('session');
        $this->load->library('order/template');
        $this->load->library('order/salesDashboardTemplate');
        $this->load->model('order/home_model');
        $this->load->model('order/agent_model');
        $this->load->library('form_validation');
        $this->load->library('order/order');
        $this->load->library('order/ionFraud');
        $this->load->model('order/titlePointData');
        $this->load->model('order/productType');
        $this->order->is_user();

        // $this->load->model('order/apiLogs');
    }

    public function index()
    {
        $userdata = $this->session->userdata('user');

        $this->load->model('order/apiLogs');
        $this->load->model('order/titleOfficer');
        $this->load->model('order/partnerApiLogs');
        $this->load->library('order/titlepoint');
        if (isset($_POST) && !empty($_POST)) {
            $random_number = $this->input->post('random_number');

            if (isset($random_number) && !empty($random_number)) {
                $condition = array(
                    'where' => array(
                        'session_id' => 'tp_api_id_' . $random_number,
                    ),
                    'returnType' => 'count',
                );
                $count = $this->titlePointData->gettitlePointDetails($condition);
                if ($count != 1) {
                    $response = array('status' => 'error', 'message' => 'Something went wrong.Please hard refresh(Ctrl+F5) your page.');
                    echo json_encode($response);
                    exit;
                }
            } else {
                $response = array('status' => 'error', 'message' => 'Something went wrong.Please hard refresh (Ctrl+F5) your page.');
                echo json_encode($response);
                exit;
            }

            $this->form_validation->set_rules('OpenName', 'First Name', 'required', array('required' => 'Enter your first name'));
            $this->form_validation->set_rules('OpenLastName', 'Last Name', 'required', array('required' => 'Enter your last name'));
            $this->form_validation->set_rules('OpenEmail', 'Email Address', 'required', array('required' => 'Enter your email address'));

            if (!is_dir('uploads/curative')) {
                mkdir('./uploads/curative', 0777, true);
            }

            $config['upload_path'] = './uploads/curative/';
            $config['allowed_types'] = 'doc|docx|gif|msg|pdf|tif|tiff|xls|xlsx|xml';
            $config['max_size'] = 20000;
            $this->load->library('upload', $config);

            if (!empty($_FILES['upload_curative']['name'])) {
                if (!$this->upload->do_upload('upload_curative')) {
                    $response = array('status' => 'error', 'message' => $this->upload->display_errors());
                    echo json_encode($response);
                    exit;
                }
            }

            $result = $this->order->checkDuplicateOrder($this->input->post('apn'));
            if ($result) {
                $response = array('status' => 'error', 'message' => 'Order is already exist for this property.');
                echo json_encode($response);
                exit;
            }

            $parties_email = array();
            $ion_cc = array();
            if ($this->form_validation->run($this) == true) {
                $OpenName = $this->input->post('OpenName');
                $OpenLastName = $this->input->post('OpenLastName');
                $Opentelephone = $this->input->post('Opentelephone');
                $OpenEmail = $this->input->post('OpenEmail');
                $CompanyName = $this->input->post('CompanyName');
                $StreetAddress = $this->input->post('StreetAddress');
                $City = $this->input->post('City');
                $Zipcode = $this->input->post('Zipcode');

                $PropertyAddress = $this->input->post('Property');
                $SplitPropertyAddress = explode(' ', $PropertyAddress);
                $StreetNumber = isset($SplitPropertyAddress[0]) && !empty($SplitPropertyAddress[0]) ? $SplitPropertyAddress[0] : '';
                $PrimaryStreetName = array_slice($SplitPropertyAddress, 1);
                $StreetName = isset($PrimaryStreetName) && !empty($PrimaryStreetName) ? implode(" ", $PrimaryStreetName) : '';

                $PropertyState = $this->input->post('property-state');
                $PropertyCity = $this->input->post('property-city');
                $PropertyFips = $this->input->post('property-fips');
                $PropertyZip = $this->input->post('property-zip');
                $PropertyType = $this->input->post('property-type');
                $FullProperty = $this->input->post('FullProperty');

                $apn = $this->input->post('apn');
                $County = $this->input->post('County');
                $LegalDescription = $this->input->post('LegalDescription');
                $PrimaryOwner = $this->input->post('PrimaryOwner');
                $SplitName = explode(' ', $PrimaryOwner);
                $OwnerLastName = end($SplitName);
                $PrimaryName = array_slice($SplitName, 0, -1);
                $OwnerFirstName = implode(" ", $PrimaryName);
                $SecondaryOwner = $this->input->post('SecondaryOwner');
                $ionReportStatusRequired = $this->input->post('ion-report-status'); // Button Proceed or Report Fraud Status
                $ionFraudFoundStatus = $this->input->post('ion-fraud-status'); // ION Fraud found status

                $SalesRep = $this->input->post('SalesRep');
                $condition = array(
                    'id' => $SalesRep,
                );
                $salesRepDetails = $this->home_model->getSalesRepDetails($condition);
                if ($salesRepDetails["is_mail_notification"] == 1) {
                    $ion_cc[] = $parties_email[] = isset($salesRepDetails["email_address"]) && !empty($salesRepDetails["email_address"]) ? $salesRepDetails["email_address"] : '';
                }
                $salesRepName = isset($salesRepDetails["name"]) && !empty($salesRepDetails["name"]) ? $salesRepDetails["name"] : '';

                $TitleOfficer = $this->input->post('TitleOfficer');
                $condition = array(
                    'id' => $TitleOfficer,
                );
                $titleOfficerDetails = $this->titleOfficer->getTitleOfficerDetails($condition);
                $titleOfficerName = isset($titleOfficerDetails['name']) && !empty($titleOfficerDetails['name']) ? $titleOfficerDetails['name'] : '';

                $LoanAmount = $this->input->post('loanAmount');
                $LoanNumber = $this->input->post('loanNumber');
                $EscrowNumber = $this->input->post('escrowNumber');
                $Notes = $this->input->post('notes');
                $SalesAmount = $this->input->post('salesAmount');
                $SalesAmount = str_replace(',', '', $SalesAmount);
                $LoanAmount = str_replace(',', '', $LoanAmount);
                // print_r($LoanAmount);die;
                $ProductTypeTxt = $this->input->post('ProductType');
                $primaryBorrower = $this->input->post('primaryBorrower');
                $secondaryBorrower = $this->input->post('secondaryBorrower');
                $TransactionTypeID = isset($_POST["TransactionTypeID"]) && !empty($_POST["TransactionTypeID"]) ? $_POST["TransactionTypeID"] : 3;
                $ProductTypeID = $this->input->post('ProductTypeID');
                $CCR = isset($_POST["CCR"]) && !empty($_POST["CCR"]) ? 1 : 0;
                $Docs = isset($_POST["Docs"]) && !empty($_POST["Docs"]) ? 1 : 0;
                $Ease = isset($_POST["Ease"]) && !empty($_POST["Ease"]) ? 1 : 0;

                $sendermessage = $this->input->post('sendermessage');
                $BuyerAgentId = $this->input->post('BuyerAgentId');
                $agentDetailFlag = $this->input->post('add-agent-details');
                $user_data = array();

                $buyers_agent_details = $listing_agent_details = array();
                if ((isset($BuyerAgentId) && !empty($BuyerAgentId)) || isset($agentDetailFlag)) {
                    $BuyerAgentName = $this->input->post('BuyerAgentName');
                    $BuyerAgentEmailAddress = $this->input->post('BuyerAgentEmailAddress');
                    $BuyerAgentTelephone = $this->input->post('BuyerAgentTelephone');
                    $BuyerAgentCompany = $this->input->post('BuyerAgentCompany');
                    $parties_email[] = $BuyerAgentEmailAddress;
                    $buyers_agent_details = array('name' => $BuyerAgentName, 'email' => $BuyerAgentEmailAddress, 'telephone' => $BuyerAgentTelephone, 'company' => $BuyerAgentCompany);
                }

                $ListingAgentId = $this->input->post('ListingAgentId');
                if ((isset($ListingAgentId) && !empty($ListingAgentId)) || isset($agentDetailFlag)) {
                    $ListingAgentName = $this->input->post('ListingAgentName');
                    $ListingAgentEmailAddress = isset($_POST["ListingAgentEmailAddress"]) && !empty($_POST["ListingAgentEmailAddress"]) ? strip_tags(trim($_POST["ListingAgentEmailAddress"])) : '';
                    $ListingAgentTelephone = $this->input->post('ListingAgentTelephone');
                    $ListingAgentCompany = $this->input->post('ListingAgentCompany');
                    $parties_email[] = $ListingAgentEmailAddress;
                    $listing_agent_details = array('name' => $ListingAgentName, 'email' => $ListingAgentEmailAddress, 'telephone' => $ListingAgentTelephone, 'company' => $ListingAgentCompany);
                }

                $escrowId = $lenderId = $lenderPartnerTypeID = $escrowPartnerTypeID = '';
                $lender_details = $escrow_details = array();
                $lender_details_api = $escrow_details_api = array();

                if (isset($userdata['is_master']) && !empty($userdata['is_master'])) {
                    $orderUser = $this->home_model->get_user(array('id' => $_POST['id']));
                    $is_escrow = $orderUser['is_escrow'];
                    $user_data['email'] = $orderUser['email_address'];
                    $user_data['password'] = $orderUser['random_password'];
                    $con = array(
                        'where' => array(
                            'partner_id' => $orderUser['partner_id'],
                        ),
                    );
                    $companyData = $this->home_model->get_company_rows($con);
                } else {
                    $orderUser = $this->home_model->get_user(array('id' => $userdata['id']));
                    $con = array(
                        'where' => array(
                            'partner_id' => $orderUser['partner_id'],
                        ),
                    );
                    $companyData = $this->home_model->get_company_rows($con);
                }

                $cplLenderId = 0;
                $EscrowLenderId = 0;
                if (isset($_POST['EscrowId']) && !empty($_POST['EscrowId'])) {
                    $escrowId = $_POST['EscrowId'];
                    $EscrowLenderId = $_POST['EscrowId'];
                    $escrow_user_details = $this->home_model->get_user(array('id' => $escrowId));
                    $escrowCon = array(
                        'where' => array(
                            'partner_id' => $escrow_user_details['partner_id'],
                        ),
                    );
                    $escrowCompanyData = $this->home_model->get_company_rows($escrowCon);
                    $escrowName = isset($_POST['EscrowName']) && !empty($_POST['EscrowName']) ? $_POST['EscrowName'] : '';
                    $escrowEmail = isset($_POST['EscrowEmailAddress']) && !empty($_POST['EscrowEmailAddress']) ? $_POST['EscrowEmailAddress'] : '';
                    $escrowTelephone = $this->input->post('EscrowTelephone');
                    $escrowCompany = $this->input->post('EscrowCompany');
                    $escrow_details = array('name' => $escrowName, 'email' => $escrowEmail, 'telephone' => $escrowName, 'company' => $escrowCompany);
                    $escrow_details_api = array('name' => $escrowName, 'email' => $escrowEmail, 'phone' => $escrowTelephone, 'company' => $escrowCompany);
                    $partner_type_ids = explode(",", $escrowCompanyData[0]['partner_type_id']);

                    if (in_array("10006", $partner_type_ids)) {
                        $escrowPartnerTypeID = '10006';
                    }
                    if (in_array("9997", $partner_type_ids)) {
                        $escrowPartnerTypeID = '9997';
                    }
                    if (in_array("10010", $partner_type_ids)) {
                        $escrowPartnerTypeID = '10010';
                    }

                    if ($orderUser['is_primary_mortgage_user'] == 1) {
                        $cplLenderId = 0;
                    } else {
                        $cplLenderId = $orderUser['id'];
                    }
                }

                /* Partners API */
                $secondaryEscrowPartners = array();
                if (isset($escrowId) && !empty($escrowId)) {
                    $escrow_resware_user_id = isset($escrow_user_details['resware_user_id']) && !empty($escrow_user_details['resware_user_id']) ? $escrow_user_details['resware_user_id'] : '';
                    $escrow_partner_id = isset($escrow_user_details['partner_id']) && !empty($escrow_user_details['partner_id']) ? $escrow_user_details['partner_id'] : '';
                    $secondaryEmp[] = array('UserID' => $escrow_resware_user_id);
                    $secondaryEscrowPartners = array(
                        'SecondaryEmployees' => $secondaryEmp,
                        'PartnerTypeID' => (int) $escrowPartnerTypeID,
                        'PartnerID' => (int) $escrow_partner_id,
                        'PartnerType' => array(
                            'PartnerTypeID' => (int) $escrowPartnerTypeID,
                        ),
                    );
                }
                /* Partners API */

                if (isset($_POST['LenderId']) && !empty($_POST['LenderId'])) {
                    $lenderId = $_POST['LenderId'];
                    $lender_user_details = $this->home_model->get_user(array('id' => $lenderId));
                    $lenderName = isset($_POST['LenderName']) && !empty($_POST['LenderName']) ? $_POST['LenderName'] : '';
                    $lenderEmail = isset($_POST['LenderEmailAddress']) && !empty($_POST['LenderEmailAddress']) ? $_POST['LenderEmailAddress'] : '';
                    $lenderTelephone = $this->input->post('LenderTelephone');
                    $lenderCompany = $this->input->post('LenderCompany');
                    $lender_details = array('name' => $lenderName, 'email' => $lenderEmail, 'telephone' => $lenderTelephone, 'company' => $lenderCompany);
                    $lender_details_api = array('name' => $lenderName, 'email' => $lenderEmail, 'phone' => $lenderTelephone, 'company' => $lenderCompany);
                    $lenderPartnerTypeID = '3';
                    if ($orderUser['is_primary_mortgage_user'] == 1) {
                        $cplLenderId = $lenderId;
                    } else {
                        $EscrowLenderId = $lenderId;
                    }
                }

                /* Partners API */
                $secondaryLenderPartners = array();
                if (isset($lenderId) && !empty($lenderId)) {
                    $lender_resware_user_id = isset($lender_user_details['resware_user_id']) && !empty($lender_user_details['resware_user_id']) ? $lender_user_details['resware_user_id'] : '';
                    $lender_partner_id = isset($lender_user_details['partner_id']) && !empty($lender_user_details['partner_id']) ? $lender_user_details['partner_id'] : '';
                    $secondaryEmp[] = array('UserID' => $lender_resware_user_id);
                    $secondaryLenderPartners = array(
                        'SecondaryEmployees' => $secondaryEmp,
                        'PartnerTypeID' => (int) $lenderPartnerTypeID,
                        'PartnerID' => (int) $lender_partner_id,
                        'PartnerType' => array(
                            'PartnerTypeID' => (int) $lenderPartnerTypeID,
                        ),
                    );
                }
                /* Partners API */

                if (isset($escrowEmail) && !empty($escrowEmail)) {
                    $parties_email[] = $escrowEmail;
                }

                if (isset($lenderEmail) && !empty($lenderEmail)) {
                    $parties_email[] = $lenderEmail;
                }

                $AdditionalEmails = $this->input->post('AdditionalEmail');
                if (isset($AdditionalEmails) && !empty($AdditionalEmails)) {
                    foreach ($AdditionalEmails as $AdditionalEmail) {
                        $parties_email[] = $AdditionalEmail;
                    }
                }
                /** Start: Get ION Fraud api response to compare Owner name */
                $ionFraudRes = [];
                if ($ionReportStatusRequired) {
                    $ionFraudStatus = false;
                    $ionFraudRes = $this->ionfraud->getIONFraudPropertyDetails($PropertyAddress, $PropertyState);
                    if (!empty($ionFraudRes) && !empty($ionFraudRes['Property_Profile'])) {
                        $ionProfileData = $ionFraudRes['Property_Profile'];
                        // $ionOwnerName = $ionProfileData['Ownername'];
                        // if (!$this->ionfraud->getIONFraudPropertyDetails($ionOwnerName, $PrimaryOwner)) {
                        $ionFraudStatus = true;
                        // }
                    }
                }
                /** End: Get ION Fraud api response to compare Owner name */

                /** Start Get config value to check Lp Enable or not */
                $configData = $this->order->getConfigData();
                $addUnderwritenPartnerViaApi = $configData['add_underwriten_partner_via_api']['is_enable'];

                $isEnable = $configData['escrow_commission']['is_enable'];
                $titlePointShutOff = $configData['title_point_shut_off']['is_enable'];

                /** End Get config value to check Lp Enable or not */
                $underWriter = '';
                // print_r($ionReportStatusRequired);
                if ((empty($_POST['EscrowId']) && empty($_POST['escrow_officer']) && ($isEnable == 1 || ($SalesRep == '15340')) && ($orderUser['is_allow_only_resware_orders'] == 0) && ($orderUser['is_escrow'] != 1) && $ProductTypeID == '20') || ($ionReportStatusRequired == 'true')) {
                    $lpOrderFlag = 1;
                    $loanFlag = 1;
                    if (strpos($ProductTypeTxt, 'Sale') !== false) {
                        $loanFlag = 0;
                    }
                } else {
                    $place_order = array();
                    $loanFlag = 1;
                    $legalEntity = array('EntityType' => 'INDIVIDUAL', 'IsPrimaryTransactee' => 'true', 'primary' => array('First' => $OwnerFirstName, 'Last' => $OwnerLastName), 'Address' => array('Address1' => $PropertyAddress, 'City' => $PropertyCity, 'State' => $PropertyState, 'Zip' => $PropertyZip));

                    if (strpos($ProductTypeTxt, 'Loan') !== false) {
                        $place_order['Buyers'][] = $legalEntity;
                    } elseif (strpos($ProductTypeTxt, 'Sale') !== false) {
                        $borrowerName = explode(' ', $primaryBorrower);
                        $borrowerLastName = end($borrowerName);
                        $borrowerPrimaryName = array_slice($borrowerName, 0, -1);
                        $borrowerFirstName = implode(" ", $borrowerPrimaryName);
                        $borrowers = array('EntityType' => 'INDIVIDUAL', 'IsPrimaryTransactee' => 'true', 'primary' => array('First' => $borrowerFirstName, 'Last' => $borrowerLastName));
                        $place_order['Sellers'][] = $legalEntity;
                        $place_order['Buyers'][] = $borrowers;
                        $place_order['SalesPrice'] = $SalesAmount;
                        $loanFlag = 0;
                    }

                    $place_order['TransactionProductType'] = array("TransactionTypeID" => $TransactionTypeID, 'ProductTypeID' => $ProductTypeID);
                    $loan = array();
                    if (isset($LoanAmount) && !empty($LoanAmount)) {
                        $loan['LoanAmount'] = $LoanAmount;
                    }

                    if (isset($LoanNumber) && !empty($LoanNumber)) {
                        $loan['LoanNumber'] = $LoanNumber;
                    }

                    if ($ProductTypeID == '4' || $ProductTypeID == '5' || $ProductTypeID == '36') {
                        $loan['LienPosition'] = 0;
                        $loan['LoanType'] = 'ConvIns';
                        $place_order['SettlementStatementVersion'] = 'HUD';
                    }

                    $place_order['Loans'][] = $loan;
                    $place_order['Properties'][] = array('IsPrimary' => 'true', 'StreetNumber' => $StreetNumber, 'StreetName' => $StreetName, 'City' => $PropertyCity, 'State' => $PropertyState, 'County' => $County, 'Zip' => $PropertyZip);
                    $place_order['Note']['APN'] = $apn;
                    $place_order['Note']['parcel_id'] = $apn;
                    $place_order['Note']['legal_description'] = $LegalDescription;

                    if (!empty($TitleOfficer)) {
                        $place_order['Note']['title_Officer'] = $titleOfficerName;
                    }

                    if (!empty($SalesRep)) {
                        $place_order['Note']['sales_rep'] = $salesRepName;
                    }

                    if (!empty($buyers_agent_details)) {
                        $place_order['Note']['buyers_agent'] = $buyers_agent_details;
                    }

                    if (!empty($listing_agent_details)) {
                        $place_order['Note']['listing_agent'] = $listing_agent_details;
                    }

                    if (!empty($lender_details)) {
                        $place_order['Note']['lender_details'] = $lender_details;
                    }

                    if (!empty($escrow_details)) {
                        $place_order['Note']['escrow_details'] = $escrow_details;
                    }

                    if (!empty($EscrowNumber)) {
                        $place_order['Note']['EscrowNumber'] = $EscrowNumber;
                        $place_order['ClientFileNumber'] = $EscrowNumber;
                    }

                    if (!empty($Notes)) {
                        $place_order['Note']['Notes'] = $Notes;
                    }

                    $order_data = json_encode($place_order);
                    $this->load->library('order/resware');
                    $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_order', env('RESWARE_ORDER_API') . 'orders', $order_data, array(), 0, 0);
                    $result = $this->resware->make_request('POST', 'orders', $order_data, $user_data);
                    $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_order', env('RESWARE_ORDER_API') . 'orders', $order_data, $result, 0, $logid);
                    $lpOrderFlag = 0;

                    if (isset($result) && !empty($result)) {
                        $response = json_decode($result, true);

                        if (isset($response['ResponseStatus']) && !empty($response['ResponseStatus'])) {
                            $message = isset($response['ResponseStatus']['Message']) && !empty($response['ResponseStatus']['Message']) ? $response['ResponseStatus']['Message'] : '';
                            /* Start add resware api logs */
                            $reswareData = array(
                                'request_type' => 'create_order_in_resware',
                                'request_url' => env('RESWARE_ORDER_API') . 'orders',
                                'request' => $order_data,
                                'response' => $result,
                                'status' => $message,
                                'created_at' => date("Y-m-d H:i:s"),
                            );

                            $this->db->insert('pct_resware_log', $reswareData);
                            /* End add resware api logs */

                            $response = array('status' => 'error', 'message' => $message);
                            echo json_encode($response);
                            exit;
                        } else {
                            $orderNumber = $file_id = '';
                            if (isset($response['FileID']) && !empty($response['FileID'])) {
                                $orderNumber = isset($response['FileNumber']) && !empty($response['FileNumber']) ? $response['FileNumber'] : '';
                                $file_id = isset($response['FileID']) && !empty($response['FileID']) ? $response['FileID'] : '';
                            }
                            /* Start add resware api logs */
                            $reswareData = array(
                                'request_type' => 'create_order_in_resware',
                                'request_url' => env('RESWARE_ORDER_API') . 'orders',
                                'request' => $order_data,
                                'response' => $result,
                                'status' => $response['ResponseStatus'],
                                'file_id' => $file_id,
                                'file_number' => $orderNumber,
                                'created_at' => date("Y-m-d H:i:s"),
                            );

                            $this->db->insert('pct_resware_log', $reswareData);
                            /* End add resware api logs */
                            if ($orderNumber) {
                                $partners = array();
                                $partners[] = array(
                                    'PartnerTypeID' => 10049,
                                    'PartnerID' => 400023,
                                    'PartnerType' => array(
                                        'PartnerTypeID' => 10049,
                                    ),
                                );
                                $endPoint = 'files/' . $file_id . '/partners';
                                $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_partners', env('RESWARE_ORDER_API') . $endPoint, array(), array(), $file_id, 0);
                                $user_data['admin_api'] = 1;

                                $resultPartners = $this->resware->make_request('GET', $endPoint, '', $user_data);
                                $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_partners', env('RESWARE_ORDER_API') . $endPoint, array(), $resultPartners, $file_id, $logid);
                                $resPartners = json_decode($resultPartners, true);

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

                                $escrowOfficerFlag = $this->input->post('add-escrow-officer-details');
                                $escrowOfficer = $this->input->post('escrow_officer');
                                $escrowOfficerKey = '';
                                if (!empty($escrowOfficerFlag)) {
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
                                }

                                $buyerAgentKey = '';
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
                                        'PartnerID' => (int) $this->input->post('buyer_agent_partner_id'),
                                        'PartnerType' => array(
                                            'PartnerTypeID' => 14,
                                        ),
                                    );
                                }

                                $listingAgentKey = '';
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
                                        'PartnerID' => (int) $this->input->post('listing_agent_partner_id'),
                                        'PartnerType' => array(
                                            'PartnerTypeID' => 15,
                                        ),
                                    );
                                }

                                $salesRepKey = '';
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
                                    $removeLogid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'delete_partner', env('RESWARE_ORDER_API') . $endPoint, $removePartnerData, array(), 0, 0);
                                    $resultRemovePartner = $this->resware->make_request('DELETE', $endPoint, $removePartnerData, $partnerUserData);
                                    $this->apiLogs->syncLogs($userdata['id'], 'resware', 'delete_partner', env('RESWARE_ORDER_API') . $endPoint, $removePartnerData, $resultRemovePartner, 0, $removeLogid);

                                    /* Start add resware api logs */
                                    $reswareData = array(
                                        'request_type' => 'delete_partner_in_resware',
                                        'request_url' => env('RESWARE_ORDER_API') . $endPoint,
                                        'request' => $removePartnerData,
                                        'response' => $resultRemovePartner,
                                        'status' => 'success',
                                        'created_at' => date("Y-m-d H:i:s"),
                                    );

                                    $this->db->insert('pct_resware_log', $reswareData);
                                    /* End add resware api logs */

                                    $resultRemovePartnerRes = json_decode($resultRemovePartner, true);
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
                                $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'add_partner', env('RESWARE_ORDER_API') . $endPoint, $partnerData, array(), 0, 0);
                                $resultPartner = $this->resware->make_request('POST', $endPoint, $partnerData, $partnerUserData);
                                $this->apiLogs->syncLogs($userdata['id'], 'resware', 'add_partner', env('RESWARE_ORDER_API') . $endPoint, $partnerData, $resultPartner, 0, $logid);

                                /* Start add resware api logs */
                                $reswareData = array(
                                    'request_type' => 'add_partner_in_resware',
                                    'request_url' => env('RESWARE_ORDER_API') . $endPoint,
                                    'request' => $partnerData,
                                    'response' => $resultPartner,
                                    'status' => 'success',
                                    'created_at' => date("Y-m-d H:i:s"),
                                );
                                $this->db->insert('pct_resware_log', $reswareData);
                                /* End add resware api logs */

                                $remoteFileNumberData = json_encode(array('RemoteFileNumber' => $orderNumber));
                                $remoteFileEndPoint = 'files/' . $file_id . '/partners/' . $orderUser['partner_id'];

                                $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'remote_file_number', env('RESWARE_ORDER_API') . $remoteFileEndPoint, $remoteFileNumberData, array(), 0, 0);
                                $resultRemotePartner = $this->resware->make_request('PUT', $remoteFileEndPoint, $remoteFileNumberData, $partnerUserData);
                                $this->apiLogs->syncLogs($userdata['id'], 'resware', 'remote_file_number', env('RESWARE_ORDER_API') . $remoteFileEndPoint, $remoteFileNumberData, $resultRemotePartner, 0, $logid);

                                /* Start add resware api logs */
                                $reswareData = array(
                                    'request_type' => 'remote_file_number_in_resware',
                                    'request_url' => env('RESWARE_ORDER_API') . $endPoint,
                                    'request' => $remoteFileNumberData,
                                    'response' => $resultRemotePartner,
                                    'status' => 'success',
                                    'created_at' => date("Y-m-d H:i:s"),
                                );
                                $this->db->insert('pct_resware_log', $reswareData);
                                /* End add resware api logs */

                                /* Add partner api logs */
                                $partnerApiData = array(
                                    'request_url' => env('RESWARE_ORDER_API') . $endPoint,
                                    'request_data' => $partnerData,
                                    'response_data' => $resultPartner,
                                );

                                $partnerApiId = $this->partnerApiLogs->insert($partnerApiData);
                                /* Add partner api logs */
                            }
                        }
                    } else {
                        $response = array('status' => 'error', 'message' => 'Credentials error.');
                        echo json_encode($response);
                        exit;
                    }
                }

                $lp_file_number = null;
                $customer_id = isset($_POST['id']) && !empty($_POST['id']) ? $_POST['id'] : '';
                if ($lpOrderFlag == 1) {
                    $orderInfo = $this->home_model->getLastFileNumberForLpOrders();
                    if (empty($orderInfo)) {
                        $lp_file_number = 'LP-00000001';
                        $file_id = 90000001;
                    } else {
                        $lp_file_number = ++$orderInfo['lp_file_number'];
                        $file_id = ++$orderInfo['file_id'];
                        $splitNum = explode('-', $lp_file_number);
                        if (count($splitNum) > 1) {
                            $number = ltrim($splitNum[1], '0');
                            $file_id = 90000000 + (int) $number;
                        }
                    }

                }
                /* Buyers Agent */
                if (isset($BuyerAgentId) && !empty($BuyerAgentId)) {
                    $buyerData = array(
                        'name' => $BuyerAgentName,
                        'email_address' => $BuyerAgentEmailAddress,
                        'company' => $BuyerAgentCompany,
                        'telephone_no' => $BuyerAgentTelephone,
                        'status' => 1,
                    );
                    $condition = array(
                        'id' => $BuyerAgentId,
                    );
                    $this->agent_model->update($buyerData, $condition);
                } else if (isset($agentDetailFlag)) {
                    if (!empty($BuyerAgentName) && !empty($BuyerAgentEmailAddress) && !empty($BuyerAgentCompany) && !empty($BuyerAgentTelephone)) {
                        $buyerData = array(
                            'name' => $BuyerAgentName,
                            'email_address' => $BuyerAgentEmailAddress,
                            'company' => $BuyerAgentCompany,
                            'telephone_no' => $BuyerAgentTelephone,
                            'status' => 1,
                        );
                        $BuyerAgentId = $this->agent_model->insert($buyerData);
                    }
                }
                /* Buyers Agent */

                /* Listing Agent */
                if (isset($ListingAgentId) && !empty($ListingAgentId)) {
                    $listngAgentData = array(
                        'name' => $ListingAgentName,
                        'email_address' => $ListingAgentEmailAddress,
                        'company' => $ListingAgentCompany,
                        'telephone_no' => $ListingAgentTelephone,
                        'status' => 1,
                    );
                    $condition = array(
                        'id' => $ListingAgentId,
                    );
                    $this->agent_model->update($listngAgentData, $condition);
                } else if (isset($agentDetailFlag)) {
                    if (!empty($ListingAgentName) && !empty($ListingAgentEmailAddress) && !empty($ListingAgentCompany) && !empty($ListingAgentCompany)) {
                        $listngAgentData = array(
                            'name' => $ListingAgentName,
                            'email_address' => $ListingAgentEmailAddress,
                            'company' => $ListingAgentCompany,
                            'telephone_no' => $ListingAgentCompany,
                            'status' => 1,
                        );
                        $ListingAgentId = $this->agent_model->insert($listngAgentData);
                    }
                }
                /* Listing Agent */

                $propertyData = array(
                    'customer_id' => $customer_id,
                    'buyer_agent_id' => $BuyerAgentId,
                    'listing_agent_id' => $ListingAgentId,
                    'escrow_lender_id' => $EscrowLenderId,
                    'cpl_lender_id' => $cplLenderId,
                    'address' => removeMultipleSpace($PropertyAddress),
                    'city' => $PropertyCity,
                    'state' => $PropertyState,
                    'zip' => $PropertyZip,
                    'property_type' => $PropertyType,
                    'full_address' => removeMultipleSpace($FullProperty),
                    'apn' => $apn,
                    'county' => $County,
                    'legal_description' => $LegalDescription,
                    'primary_owner' => $PrimaryOwner,
                    'secondary_owner' => $SecondaryOwner,
                    'unit_number' => $this->input->post('unit_number'),
                    'status' => 1,
                );

                $propertyId = $this->home_model->insert($propertyData, 'property_details');

                $transactionData = array(
                    'customer_id' => $customer_id,
                    'sales_representative' => $SalesRep,
                    'title_officer' => $TitleOfficer,
                    'sales_amount' => $SalesAmount,
                    'loan_amount' => $LoanAmount,
                    'loan_number' => $LoanNumber,
                    'transaction_type' => $TransactionTypeID,
                    'purchase_type' => $ProductTypeID,
                    'is_ccr' => $CCR,
                    'is_underlying_docs' => $Docs,
                    'is_plotted_easements' => $Ease,
                    'additional_email' => implode(',', $AdditionalEmails),
                    'borrower' => $primaryBorrower,
                    'secondary_borrower' => $secondaryBorrower,
                    'escrow_number' => $EscrowNumber,
                    'status' => 1,
                );

                $transactionId = $this->home_model->insert($transactionData, 'transaction_details');
                $randomString = $this->order->randomPassword();
                $randomString = md5($orderUser['id'] . $orderUser['email_address'] . $randomString);
                $orderData = array(
                    'customer_id' => $customer_id,
                    'file_id' => $file_id,
                    'file_number' => isset($orderNumber) && !empty($orderNumber) ? $orderNumber : 0,
                    'lp_file_number' => $lp_file_number,
                    'property_id' => $propertyId,
                    'transaction_id' => $transactionId,
                    'partner_api_log_id' => $partnerApiId,
                    'created_by' => $userdata['id'],
                    'random_number' => $randomString,
                    'underwriter' => $underWriter,
                    'escrow_officer_id' => $this->input->post('escrow_officer'),
                    'prod_type' => $loanFlag == 1 ? 'loan' : 'sale',
                    'resware_status' => ($lpOrderFlag == 1) ? 'open' : '',
                    'status' => 1,
                    'ion_fraud_proceed_status' => ($ionReportStatusRequired == 'true') ? 'review fraud' : 'proceed',
                    'ion_fraud_required_status' => ($ionFraudFoundStatus == 'true') ? 'yes' : 'no',
                );

                $orderId = $this->home_model->insert($orderData, 'order_details');

                if ($userdata['is_master'] == 1) {
                    $message = 'Order number #' . $orderNumber . ' has assigned to you.';
                    $notificationData = array(
                        'sent_user_id' => $customer_id,
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'assigned',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'assigned', $customer_id, 0);
                }

                if ($lpOrderFlag == 1) {
                    $orderNumber = $lp_file_number;
                } else {
                    $postData['file_number'] = $orderNumber;
                    $postData['order_id'] = $orderId;
                    $postData['state'] = $PropertyState;
                    $postData['county'] = $County;
                    $postData['property'] = $PropertyAddress;
                    $postData['apn'] = $apn;
                    $postData['unit_number'] = $this->input->post('unit_number');

                    //$this->titlepoint->generateGeoDoc($postData, 1);
                    //$this->order->checkGrantDoc($orderNumber, false);
                }

                /* Escrow Details */
                if (isset($escrowId) && !empty($escrowId)) {
                    $name = explode(' ', $escrowName);
                    $first_name = $name[0];
                    $last_name = $name[1];
                    $escrowData = array(
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email_address' => $escrowEmail,
                        'company_name' => $escrowCompany,
                        'telephone_no' => $escrowTelephone,
                        'status' => 1,
                    );
                    $condition = array(
                        'id' => $escrowId,
                    );
                    $this->home_model->update($escrowData, $condition);
                    $message = 'You have added on order number #' . $orderNumber;
                    $notificationData = array(
                        'sent_user_id' => $escrowId,
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'added',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'added', $escrowId, 0);
                }
                /* Escrow Details */

                /* Lender Details */
                if (isset($lenderId) && !empty($lenderId)) {
                    $name = explode(' ', $lenderName);
                    $first_name = $name[0];
                    $last_name = $name[1];
                    $lenderData = array(
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'email_address' => $lenderEmail,
                        'company_name' => $lenderCompany,
                        'telephone_no' => $lenderTelephone,
                        'status' => 1,
                    );
                    $condition = array(
                        'id' => $lenderId,
                    );
                    $lenderId = $this->home_model->update($lenderData, $condition);
                    $message = 'You have added on order number #' . $orderNumber;
                    $notificationData = array(
                        'sent_user_id' => $lenderId,
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'added',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'added', $lenderId, 0);
                }
                /*Lender Details */

                if (!empty($SalesRep)) {
                    $message = 'You have added on order number #' . $orderNumber;
                    $notificationData = array(
                        'sent_user_id' => $SalesRep,
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'added',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'added', $SalesRep, 0);
                }

                if (!empty($TitleOfficer)) {
                    $message = 'You have added on order number #' . $orderNumber;
                    $notificationData = array(
                        'sent_user_id' => $TitleOfficer,
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'added',
                    );
                    $this->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->order->sendNotification($message, 'added', $TitleOfficer, 0);
                }

                if ($this->session->has_userdata('tp_api_id_' . $random_number)) {
                    $session_id = 'tp_api_id_' . $random_number;

                    $condition = array(
                        'session_id' => $session_id,
                    );
                    $tpData = array(
                        'file_id' => $file_id,
                        'file_number' => $orderNumber,
                    );
                    $this->titlePointData->update($tpData, $condition);

                    $this->load->library('order/titlepoint');

                    if ($lpOrderFlag == 0) {
                        $postData['file_number'] = $orderNumber;
                        $postData['order_id'] = $orderId;
                        $postData['state'] = $PropertyState;
                        $postData['county'] = $County;
                        $postData['property'] = $PropertyAddress;
                        $postData['apn'] = $apn;
                        $postData['unit_number'] = $this->input->post('unit_number');
                        $this->titlepoint->generateGeoDoc($postData, 1);
                        // $this->order->checkGrantDoc($orderNumber, false);
                    }

                    // $tax_file_path = FCPATH . 'uploads/tax/' . $session_id . '.pdf';
                    // if (file_exists($tax_file_path)) {
                    //     rename(FCPATH . "/uploads/tax/" . $session_id . '.pdf', FCPATH . "/uploads/tax/" . $orderNumber .'.pdf');
                    //     $this->order->uploadDocumentOnAwsS3($orderNumber .'.pdf', 'tax');
                    // }
                    $titlePointDetails = $this->titlePointData->gettitlePointDetails($condition);

                    /** If Title point shuf off is enabled from admin setting then Legal Vesting, Tax Doc, Grant deed title point api will not call */
                    /**
                     * Comment from Jerry (26-03-2024)
                     * The manual shutoff refrains from calling legal and vesting, tax document and grant deed. It  will allow for open order to be submitted to resware,
                     * order number retrieved, and email confirmation to go out to all parties
                     */
                    if (empty($titlePointShutOff) || $titlePointShutOff == 0) {
                        $tax_serviceId = isset($titlePointDetails['cs3_service_id']) && !empty($titlePointDetails['cs3_service_id']) ? $titlePointDetails['cs3_service_id'] : '';
                        $this->titlepoint->generateTaxDoc($tax_serviceId, $orderNumber, $orderId);

                        $serviceId = isset($titlePointDetails['cs4_service_id']) && !empty($titlePointDetails['cs4_service_id']) ? $titlePointDetails['cs4_service_id'] : '';
                        $this->titlepoint->generateImg($serviceId, $orderNumber, $orderId);

                        $instrumentNumber = isset($titlePointDetails['cs4_instrument_no']) && !empty($titlePointDetails['cs4_instrument_no']) ? $titlePointDetails['cs4_instrument_no'] : '';

                        $recordedDate = isset($titlePointDetails['cs4_recorded_date']) && !empty($titlePointDetails['cs4_recorded_date']) ? $titlePointDetails['cs4_recorded_date'] : '';
                        $fips = isset($titlePointDetails['fips']) && !empty($titlePointDetails['fips']) ? $titlePointDetails['fips'] : '';

                        $this->titlepoint->generateGrantDeed($instrumentNumber, $recordedDate, $fips, $orderNumber, $orderId);
                    }

                }

                $orderDetails = $this->order->get_order_details($file_id);
                /** Start Generate ION Fraud document */
                $ionFile = [];
                if (!empty($ionFraudRes) && !empty($ionFraudRes['Property_Profile'])) {
                    $ionProfileData['black_knight_owner_name'] = $PrimaryOwner;
                    $this->ionfraud->generateIONReport($orderNumber, $ionProfileData);
                    $ionfraudfilename = $orderNumber . '-Fraud.pdf';
                    $ionletterfilename = $orderNumber . '-Letter.pdf';
                    // $this->uploadIONFraudDocsToResware($ionfraudfilename, $file_id, $orderDetails);
                    $ionFile[] = env('AWS_PATH') . "ion-fraud/report/" . $ionfraudfilename;
                    $ionFile[] = env('AWS_PATH') . "ion-fraud/letter/" . $ionletterfilename;
                }
                /** End Generate ION Fraud document */

                // Convert to PST

                $timezone = -8;

                $opened_date = gmdate("m-d-Y h:i A", strtotime($orderDetails['opened_date']) + 3600 * ($timezone + date("I")));

                // Convert to PST

                $data = array(
                    'orderNumber' => $orderNumber,
                    'orderId' => $file_id,
                    'OpenName' => $OpenName . ' ' . $OpenLastName,
                    'Opentelephone' => $Opentelephone,
                    'OpenEmail' => $OpenEmail,
                    'CompanyName' => $CompanyName,
                    'StreetAddress' => $StreetAddress,
                    'City' => $City,
                    'Zipcode' => $Zipcode,
                    'openAt' => $opened_date,
                    'PropertyAddress' => $PropertyAddress,
                    'FullProperty' => $FullProperty,
                    'APN' => $apn,
                    'County' => $County,
                    'LegalDescription' => $LegalDescription,
                    'PrimaryOwner' => $PrimaryOwner,
                    'SecondaryOwner' => $SecondaryOwner,
                    'SalesRep' => $salesRepName,
                    'TitleOfficer' => $titleOfficerName,
                    'ProductType' => $ProductTypeTxt,
                    'SalesAmount' => $SalesAmount,
                    'LoanAmount' => $LoanAmount,
                    'LoanNumber' => $LoanNumber,
                    'EscrowNumber' => $EscrowNumber,
                    'Notes' => $Notes,
                    'sendermessage' => $sendermessage,
                    'buyers_agent' => $buyers_agent_details,
                    'listing_agent' => $listing_agent_details,
                    'lender_details' => $lender_details,
                    'escrow_details' => $escrow_details,
                    'currYear' => CURRENT_YEAR,
                    'randomString' => $randomString,
                    'titlePointDetails' => $titlePointDetails,
                    'titlePointShutOff' => $titlePointShutOff,
                    'IONFraudOwnerName' => $ionProfileData['Ownername'] ?? '',
                );

                $from_name = 'Pacific Coast Title Company';
                $from_mail = env('FROM_EMAIL');
                $order_message_body = $this->load->view('emails/order.php', $data, true);
                $message = $order_message_body;
                $addInSubject = '';
                if (str_contains(strtolower($PropertyType), 'vacant land')) {
                    $addInSubject = ' - APN: ' . $apn;
                }
                $subject = $orderNumber . ' - PCT Title Order Placed' . $addInSubject;
                $email_notification = $this->input->post('email_notification');

                $this->session->set_userdata('email_notification', $email_notification);
                if (($is_escrow == 0) && (isset($userdata['is_master']) && !empty($userdata['is_master'])) && (empty($email_notification))) {
                    $to = env('OPEN_ORDER_ADMIN_EMAIL');
                    /*$cc = array();*/
                } else {
                    $to = $OpenEmail;
                    $parties_email[] = env('OPEN_ORDER_ADMIN_EMAIL');
                }
                $file = array();
                $lvfilename = $orderNumber . '.pdf';
                $deedfilename = $orderNumber . '.pdf';
                $taxfilename = $orderNumber . '.pdf';

                if (!empty($_FILES['upload_curative']['name'])) {
                    $this->uploadCurativeDocsToResware($orderDetails);
                }

                if ((empty($titlePointShutOff) || $titlePointShutOff == 0) && $this->order->fileExistOrNotOnS3('legal-vesting/' . $lvfilename)) {
                    $file[] = env('AWS_PATH') . "legal-vesting/" . $lvfilename;
                    $this->uploadLvDocsToResware($lvfilename, $file_id, $orderDetails, $lpOrderFlag);
                }

                if ((empty($titlePointShutOff) || $titlePointShutOff == 0) && $this->order->fileExistOrNotOnS3('grant-deed/' . $deedfilename)) {
                    $file[] = env('AWS_PATH') . "grant-deed/" . $deedfilename;
                    $this->uploadGrantDeedDocsToResware($deedfilename, $file_id, $orderDetails, $lpOrderFlag);
                }

                if ((empty($titlePointShutOff) || $titlePointShutOff == 0) && $this->order->fileExistOrNotOnS3('tax/' . $taxfilename)) {
                    $file[] = env('AWS_PATH') . "tax/" . $taxfilename;
                    $this->uploadTaxDocsToResware($taxfilename, $file_id, $orderDetails, $lpOrderFlag);
                }

                $escrow_officer_email = '';
                if (isset($escrowOfficer) && !empty($escrowOfficer) && ($ProductTypeID == '4' || $ProductTypeID == '5' || $ProductTypeID == '36')) {
                    $con = array(
                        'where' => array(
                            'partner_id' => $escrowOfficer,
                        ),
                    );
                    $escrowCompanyData = $this->home_model->get_company_rows($con);
                    $escrow_officer_email = $escrowCompanyData[0]['email'];
                    //$escrow_officer_email = 'hitesh.p@crestinfosystems.com';
                    $parties_email[] = $escrow_officer_email;
                }

                $parties_email[] = 'openorders@pct.com';
                /*$cc = array(env('OPEN_ORDER_ADMIN_EMAIL'));*/
                //$parties_email[] = env('ORDER_ADMIN_EMAIL');
                $cc = isset($parties_email) && !empty($parties_email) ? $parties_email : array();
                $this->load->helper('sendemail');

                /** Start Send email for ION fraud document to all parties */
                $ionEmailTemplate = $this->load->view('emails/ion_report.php', $data, true);
                $ion_cc[] = 'piyush-crest@yopmail.com';
                //'to' => 'piyush.j@crestinfosystems.com',
                $ionMailParams = [
                    'from_mail' => $from_mail,
                    'from_name' => $from_name,
                    'to' => $OpenEmail,
                    'subject' => 'PCT-Fraud Review',
                    'message' => json_encode($data),
                    'file' => json_encode($ionFile),
                    'cc' => json_encode($ion_cc),
                ];
                if (!empty($ionFile)) {
                    $logid = $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'ion_fraud_email_to_all_parties_' . $orderNumber, '', $ionMailParams, array(), $orderId, 0);
                    $ion_mail_result = send_email($ionMailParams['from_mail'], $ionMailParams['from_name'], $ionMailParams['to'], $ionMailParams['subject'], $ionEmailTemplate, $ionFile, $cc, array());
                    $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'ion_fraud_email_to_all_parties_' . $orderNumber, '', $ionMailParams, array('status' => $ion_mail_result), $orderId, $logid);
                }
                /** End Send email for ION fraud document to all parties */
                $mailParams = array(
                    'from_mail' => $from_mail,
                    'from_name' => $from_name,
                    'to' => $to,
                    'subject' => $subject,
                    'message' => json_encode($data),
                    'file' => json_encode($file),
                    'cc' => json_encode($cc),
                );

                $condition = array(
                    'where' => array(
                        'file_number' => $orderNumber,
                    ),
                );
                $titlePointDetails = $this->titlePointData->gettitlePointDetails($condition);
                $lvDocStatus = strtolower($titlePointDetails[0]['lv_file_status']);
                $taxDocStatus = strtolower($titlePointDetails[0]['tax_file_status']);
                $taxDataStatus = strtolower($titlePointDetails[0]['tax_data_status']);
                $emailSentFlag = strtolower($titlePointDetails[0]['email_sent_status']);
                $this->apiLogs->syncLogs(0, 'email-check-order', 'email-check-order', $orderNumber, ['$titlePointShutOff' => $titlePointShutOff, '$emailSentFlag' => $emailSentFlag, '$taxDocStatus' => $taxDocStatus, 'tax_data_status' => $taxDataStatus, '$lvDocStatus' => $lvDocStatus, 'lp_file_number' => $orderDetails['lp_file_number']], array(), 0, 0);

                if ((!isset($orderDetails['lp_file_number']) || empty($orderDetails['lp_file_number'])) &&
                    $emailSentFlag != 1 &&
                    ((($lvDocStatus == 'success' || $lvDocStatus == 'failed' || $lvDocStatus == 'exception') &&
                        ($taxDocStatus == 'success' || $taxDocStatus == 'failed' || $taxDocStatus == 'exception')) || $titlePointShutOff == 1)
                ) {
                    // $to = 'hitesh.p@crestinfosystems.com';
                    // $cc = ['piyush.j@crestinfosystems.net'];
                    if (isset($titleOfficerDetails['email_address']) && !empty($titleOfficerDetails['email_address'])) {
                        $cc[] = $titleOfficerDetails['email_address'];
                    }
                    // $to = ['piyush.j@crestinfosystems.com'];
                    // $cc[] = 'piyush.j@crestinfosystems.com';
                    $logid = $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_confirmation_resware_order_mail_home_index_' . $orderNumber, '', $mailParams, array(), $orderId, 0);
                    $mail_result = send_email($from_mail, $from_name, $to, $subject, $message, $file, $cc, array());
                    $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_confirmation_resware_order_mail_home_index_' . $orderNumber, '', $mailParams, array('status' => $mail_result), $orderId, $logid);
                    // $taxDataStatus = 'falied';
                    if ($taxDataStatus != 'success') {
                        // $subject = $orderNumber . ' - PCT Title Order Placed But Tax details not found';
                        //send_email($from_mail, $from_name, $to, $subject, $message, $file, $cc, array());
                        //$this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_confirmation_order_mail_CS_notification', '', $mailParams, array('status' => $mail_result), $orderId, $logid);
                    }

                    $tpData = array(
                        'email_sent_status' => ($mail_result) ? 1 : 0,
                    );

                    $condition = array(
                        'file_number' => $orderNumber,
                    );
                    $this->titlePointData->update($tpData, $condition);
                }

                if ((!empty($escrowEmail) && $loanFlag == 1) || (!empty($escrow_officer_email))) {

                    $sales_rep_img = isset($salesRepDetails["sales_rep_profile_img"]) && !empty($salesRepDetails["sales_rep_profile_img"]) ? $salesRepDetails["sales_rep_profile_img"] : '';
                    if (!empty($sales_rep_img)) {
                        $sales_rep_img = env('AWS_PATH') . str_replace('uploads/', '', $sales_rep_img);
                    }

                    $email_data = array(
                        'orderNumber' => $orderNumber,
                        'PropertyAddress' => $PropertyAddress,
                        'randomString' => $randomString,
                        'headerImg' => $sales_rep_img,
                        'currYear' => CURRENT_YEAR,
                        'productTypeID' => $ProductTypeID,
                        'OpenEmail' => $OpenEmail,
                    );

                    $borrower_message_body = $this->load->view('emails/borrower.php', $email_data, true);
                    $message_body = $borrower_message_body;
                    $subject = $orderNumber . ' - Borrower Verification';

                    $mailParams = array(
                        'from_mail' => $from_mail,
                        'from_name' => $from_name,
                        'subject' => $subject,
                        'message' => json_encode($email_data),
                    );

                    if (!empty($escrowEmail) && $loanFlag == 1) {
                        $to = $escrowEmail;
                        $mailParams['to'] = $to;
                        $logid = $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_mail_to_escrow_client', '', $mailParams, array(), $orderId, 0);
                        //$escrow_mail_result = send_email($from_mail, $from_name, $to, $subject, $message_body);
                        $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_mail_to_escrow_client', '', $mailParams, array('status' => $escrow_mail_result), $orderId, $logid);
                    }

                    if (!empty($escrow_officer_email)) {
                        $to = $escrow_officer_email;
                        $mailParams['to'] = $to;
                        $logid = $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_mail_to_escrow_officer', '', $mailParams, array(), $orderId, 0);
                        //$escrow_mail_result = send_email($from_mail, $from_name, $to, $subject, $message_body);
                        $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_mail_to_escrow_officer', '', $mailParams, array('status' => $escrow_mail_result), $orderId, $logid);
                    }
                }

                /* Send notification to admin based on rules */
                $condition = array(
                    'where' => array(
                        'title' => 'Send notification for open orders',
                    ),
                );
                $rules = $this->home_model->get_rules_rows($condition);

                if (isset($rules) && !empty($rules)) {
                    $counties_rule = isset($rules[0]['value']) && !empty($rules[0]['value']) ? $rules[0]['value'] : array();
                    $counties_ids = explode(',', $counties_rule);

                    $counties = array();
                    foreach ($counties_ids as $key => $value) {
                        $condition = array(
                            'id' => $value,
                        );
                        $county_data = $this->home_model->get_counties_rows($condition);
                        $counties[] = $county_data['county'];

                        if (in_array($County, $counties)) {
                            $search_data = array(
                                'orderNumber' => $orderNumber,
                                'property_address' => $FullProperty,
                                'apn' => $apn,
                                'currYear' => CURRENT_YEAR,
                            );

                            $search_package_body = $this->load->view('emails/search_package.php', $search_data, true);
                            $search_package_message_body = $search_package_body;
                            $subject = 'Starter Need: ' . $PropertyAddress;
                            $to = env('ADMIN_EMAIL');
                            $mailParams = array(
                                'from_mail' => env('FROM_EMAIL'),
                                'to' => $to,
                                'subject' => $subject,
                                'message' => json_encode($search_data),
                            );

                            $logid = $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_mail_to_admin_for_search_package', '', $mailParams, array(), $orderId, 0);

                            $search_mail_result = send_email($from_mail, $from_name, $to, $subject, $search_package_message_body, array(), array(), array());

                            $this->apiLogs->syncLogs($userdata['id'], 'sendgrid', 'send_mail_to_admin_for_search_package', '', $mailParams, array('status' => $search_mail_result), $orderId, $logid);
                        }
                    }
                }
                /* Send notification to admin based on rules */

                /* Call HomeDocs API  */
                if (count($escrow_details_api) || count($lender_details_api)) {

                    $api_data = array();
                    $FullProperty = $this->input->post('property_address');
                    $api_data['escrow_details'] = $escrow_details_api;
                    $api_data['lender_details'] = $lender_details_api;
                    $api_data['borrwer_details'] = array();
                    $api_data['escrow_officer_details'] = array();

                    $api_data['property_details'] = array(
                        'address' => $this->input->post('property-full-address'),
                    );
                    $this->load->helper('homedocsapi');
                    // $result = true;

                    $result = call_homedocs_api($api_data);
                }
                /* Call HomeDocs API  */

                $response = array('status' => 'success', 'message' => 'Data saved successfully.', 'file_id' => $file_id);
                echo json_encode($response);
                exit;
            } else {
                $data['OpenName_error_msg'] = form_error('OpenName');
                $data['OpenLastName_error_msg'] = form_error('OpenLastName');
                $data['OpenEmail_error_msg'] = form_error('OpenEmail');
                $data['sendermessage_error_msg'] = form_error('sendermessage');
            }
        } else {
            $data['title'] = 'Open Order | Pacific Coast Title Company';
            $customer_data = $this->home_model->get_user(array('id' => $userdata['id']));
            $is_master = isset($customer_data['is_master']) && !empty($customer_data['is_master']) ? $customer_data['is_master'] : '';

            /*$condition = array(
            'where' => array(
            'status' => 1,
            'transaction_type_id' => 3
            )
            );

            $data['productType'] =  $this->productType->getProductTypes($condition);*/

            $condition = array(
                'where' => array(
                    'status' => 1,
                ),
            );

            $data['titleOfficer'] = $this->titleOfficer->getTitleOfficerDetails($condition);

            // $data['salesRep'] = $this->salesRep->getSalesRepDetails($condition);
            $condition = array(
                'where' => array(
                    'is_sales_rep' => 1,
                    'status' => 1,
                ),
            );
            $data['salesRep'] = $this->home_model->getSalesRepDetails($condition);
            $data['escrowOfficers'] = $this->home_model->getEscrowOfficerDetails();
            $configData = $this->order->getConfigData();
            $data['submitButtonFlag'] = $configData['enable_create_order_submit_button']['is_enable'];
            $data['ionFraudFlag'] = $configData['enable_ion_fraud_checking']['is_enable'];
            // $this->template->addJS('https://maps.googleapis.com/maps/api/js?key=' . env('GOOGLE_MAP_KEY') . '&libraries=places&sensor=false');
            // $this->template->addJS(base_url('assets/frontend/js/additional-methods.min.js'));
            // $this->template->addJS(base_url('assets/frontend/js/smart-form.js'));
            // $this->template->addJS(base_url('assets/frontend/js/jquery-cloneya.min.js'));
            // $this->template->addJS(base_url('assets/frontend/js/custom.js?v=' . $this->version));
            // $this->template->addJS(base_url('assets/frontend/js/order.js?v=' . $this->version));
            $this->salesdashboardtemplate->addJS('https://maps.googleapis.com/maps/api/js?key=' . env('GOOGLE_MAP_KEY') . '&libraries=places&sensor=false');
            $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/additional-methods.min.js'));
            $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/smart-form.js?v=1'));
            $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/jquery-cloneya.min.js'));
            $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/custom.js?v=' . $this->version));
            $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order.js?v=' . $this->version));
            $this->salesdashboardtemplate->addCss(base_url('assets/frontend/css/custom.css?v=' . $this->version));
            // $this->salesdashboardtemplate->addCss( base_url('assets/libs/bootstrap/bootstrap.css'));
            if ($is_master) {
                $this->salesdashboardtemplate->show("order", "master_order", $data);
                // $this->template->show("order", "master_order", $data);
            } else {
                $data['customer_data'] = $customer_data;
                $con = array(
                    'where' => array(
                        'partner_id' => $customer_data['partner_id'],
                    ),
                );
                $companyData = $this->home_model->get_company_rows($con);
                $data['deliverables'] = !empty($companyData[0]['deliverables']) ? explode(',', $companyData[0]['deliverables']) : array();
                $this->salesdashboardtemplate->show("order", "home", $data);
                // $this->template->show("order", "home", $data);
            }
        }
    }

    public function checkEmail()
    {
        $email = isset($_POST['CustomerEmail']) && !empty($_POST['CustomerEmail']) ? $_POST['CustomerEmail'] : '';

        $condition = array(
            'where' => array(
                'email_address' => $email,
            ),
            'returnType' => 'count',
        );
        $count = $this->home_model->get_customers($condition);

        if ($count > 0) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    public function getCustomerNumber()
    {
        $email = isset($_POST['email_address']) && !empty($_POST['email_address']) ? $_POST['email_address'] : '';

        $condition = array(
            'where' => array(
                'email_address' => $email,
            ),
        );
        $result = $this->home_model->get_customers($condition);

        $data = array();
        if (isset($result) && !empty($result)) {
            $customer_number = isset($result[0]['customer_number']) && !empty($result[0]['customer_number']) ? $result[0]['customer_number'] : '';
            $data['customer_number'] = $customer_number;
        }

        echo json_encode($data);
        exit;
    }

    public function getCustomerDetails()
    {
        $customer_no = isset($_POST['customer_no']) && !empty($_POST['customer_no']) ? $_POST['customer_no'] : '';

        $condition = array(
            'where' => array(
                'customer_number' => $customer_no,
            ),
        );
        $result = $this->home_model->get_customers($condition);

        $data = array();
        if (isset($result) && !empty($result)) {
            foreach ($result as $key => $value) {
                $data['id'] = isset($value['id']) && !empty($value['id']) ? $value['id'] : '';
                $data['customer_number'] = isset($value['customer_number']) && !empty($value['customer_number']) ? $value['customer_number'] : '';
                $data['first_name'] = isset($value['first_name']) && !empty($value['first_name']) ? $value['first_name'] : '';
                $data['last_name'] = isset($value['last_name']) && !empty($value['last_name']) ? $value['last_name'] : '';
                $data['telephone_no'] = isset($value['telephone_no']) && !empty($value['telephone_no']) ? $value['telephone_no'] : '';
                $data['email_address'] = isset($value['email_address']) && !empty($value['email_address']) ? $value['email_address'] : '';
                $data['company_name'] = isset($value['company_name']) && !empty($value['company_name']) ? $value['company_name'] : '';
                $data['street_address'] = isset($value['street_address']) && !empty($value['street_address']) ? $value['street_address'] : '';
                $data['city'] = isset($value['city']) && !empty($value['city']) ? $value['city'] : '';
                $data['zip_code'] = isset($value['zip_code']) && !empty($value['zip_code']) ? $value['zip_code'] : '';
                $data['is_escrow'] = isset($value['is_escrow']) && !empty($value['is_escrow']) ? $value['is_escrow'] : 0;
            }
        }

        echo json_encode($data);
    }

    public function orderSubmit()
    {
        $fileId = $this->uri->segment(2);
        $this->load->library('order/order');
        $data = array();
        if ($fileId) {
            $condition = array(
                'where' => array(
                    'file_id' => $fileId,
                ),
            );
            $titlePointDetails = $this->titlePointData->gettitlePointDetails($condition);

            $session_id = isset($titlePointDetails[0]['session_id']) && !empty($titlePointDetails[0]['session_id']) ? $titlePointDetails[0]['session_id'] : '';

            $this->session->unset_userdata($session_id);

            $orderDetails = $this->order->get_order_details($fileId);

            $file_number = isset($orderDetails['file_number']) && !empty($orderDetails['file_number']) ? $orderDetails['file_number'] : '';

            $property_id = isset($orderDetails['property_id']) && !empty($orderDetails['property_id']) ? $orderDetails['property_id'] : '';
            $customer_id = isset($orderDetails['customer_id']) && !empty($orderDetails['customer_id']) ? $orderDetails['customer_id'] : '';
            $propertyData = $this->home_model->get_property_details($property_id);
            $orderId = isset($orderDetails['order_id']) && !empty($orderDetails['order_id']) ? $orderDetails['order_id'] : '';
            $county = isset($propertyData['county']) && !empty($propertyData['county']) ? $propertyData['county'] : '';
            $apn = isset($propertyData['apn']) && !empty($propertyData['apn']) ? $propertyData['apn'] : '';
            $FullProperty = isset($propertyData['full_address']) && !empty($propertyData['full_address']) ? $propertyData['full_address'] : '';
            $address = isset($propertyData['address']) && !empty($propertyData['address']) ? $propertyData['address'] : '';

            $propertyState = isset($propertyData['state']) && !empty($propertyData['state']) ? $propertyData['state'] : '';
            $lpFileNumber = isset($orderDetails['lp_file_number']) && !empty($orderDetails['lp_file_number']) ? $orderDetails['lp_file_number'] : '';

            $propertyCity = isset($propertyData['city']) && !empty($propertyData['city']) ? $propertyData['city'] : '';
            $escrowId = isset($propertyData['escrow_lender_id']) && !empty($propertyData['escrow_lender_id']) ? $propertyData['escrow_lender_id'] : '';
            $fileNumber = ((isset($file_number) && !empty($file_number)) ? $file_number : ((isset($lpFileNumber) && !empty($lpFileNumber)) ? $lpFileNumber : ''));
            $lv_file_url = '';
            if (env('AWS_ENABLE_FLAG') == 1) {
                if ($this->order->fileExistOrNotOnS3('legal-vesting/' . $fileNumber . '.pdf')) {
                    $lv_file_url = env('AWS_PATH') . "legal-vesting/" . $fileNumber . '.pdf';
                }
            } else {
                $lv_file_path = FCPATH . 'uploads/legal-vesting/' . $fileNumber . '.pdf';
                if (file_exists($lv_file_path)) {
                    $lv_file_url = base_url() . 'uploads/legal-vesting/' . $fileNumber . '.pdf';
                }
            }
            $data['lv_file_url'] = $lv_file_url;

            $deed_file_url = '';
            if (env('AWS_ENABLE_FLAG') == 1) {
                if ($this->order->fileExistOrNotOnS3('grant-deed/' . $fileNumber . '.pdf')) {
                    $deed_file_url = env('AWS_PATH') . "grant-deed/" . $fileNumber . '.pdf';
                }
            } else {
                $deed_file_path = FCPATH . 'uploads/grant-deed/' . $fileNumber . '.pdf';
                if (file_exists($deed_file_path)) {
                    $deed_file_url = base_url() . 'uploads/grant-deed/' . $fileNumber . '.pdf';
                }
            }
            $data['deed_file_url'] = $deed_file_url;

            $tax_file_url = '';
            if (env('AWS_ENABLE_FLAG') == 1) {
                if ($this->order->fileExistOrNotOnS3('tax/' . $fileNumber . '.pdf')) {
                    $tax_file_url = env('AWS_PATH') . "tax/" . $fileNumber . '.pdf';
                }
            } else {
                $tax_file_path = FCPATH . 'uploads/tax/' . $fileNumber . '.pdf';
                if (file_exists($tax_file_path)) {
                    $tax_file_url = base_url() . 'uploads/tax/' . $fileNumber . '.pdf';
                }
            }
            $data['tax_file_url'] = $tax_file_url;
        }

        $data['tp_data'] = isset($titlePointDetails[0]) && !empty($titlePointDetails[0]) ? $titlePointDetails[0] : array();
        $data['state'] = isset($propertyState) && !empty($propertyState) ? $propertyState : '';
        $data['city'] = isset($propertyCity) && !empty($propertyCity) ? $propertyCity : '';
        $data['county'] = isset($county) && !empty($county) ? $county : '';
        $data['apn'] = isset($apn) && !empty($apn) ? $apn : '';
        $data['property'] = isset($FullProperty) && !empty($FullProperty) ? $FullProperty : '';
        $data['address'] = isset($address) && !empty($address) ? $address : '';
        $data['customer_id'] = isset($customer_id) && !empty($customer_id) ? $customer_id : '';
        $data['file_num'] = $file_number;
        $data['order_id'] = $orderId;
        $data['escrow_id'] = $escrowId;
        $data['lpFileNumber'] = $lpFileNumber;
        $data['lpFileStatus'] = $titlePointDetails[0]['lv_file_status'];
        $data['taxFileStatus'] = $titlePointDetails[0]['tax_file_status'];

        // $this->salesdashboardtemplate->addJS( base_url('assets/frontend/js/jquery-1.9.1.min.js?v=order_' . $this->version) );
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/jquery-cloneya.min.js?v=order_' . $this->version));
        $this->salesdashboardtemplate->addJS(base_url('assets/frontend/js/order.js?v=order_' . $this->version));
        $this->salesdashboardtemplate->show("order", "order-submission", $data);
        // $this->load->view('layout/head', $data);
        // $this->load->view('order/order-submission', $data);

    }

    public function checkDocument()
    {
        $fileNumber = $this->input->post('file_number');
        $docType = $this->input->post('doc_type');
        $url = null;
        if ($docType == 'tax') {
            if (env('AWS_ENABLE_FLAG') == 1) {
                if ($this->order->fileExistOrNotOnS3('tax/' . $fileNumber . '.pdf')) {
                    $url = env('AWS_PATH') . "tax/" . $fileNumber . '.pdf';
                }
            } else {
                $tax_file_path = FCPATH . 'uploads/tax/' . $fileNumber . '.pdf';
                if (file_exists($tax_file_path)) {
                    $url = base_url() . 'uploads/tax/' . $fileNumber . '.pdf';
                }
            }
        }

        if ($docType == 'lv') {
            if (env('AWS_ENABLE_FLAG') == 1) {
                if ($this->order->fileExistOrNotOnS3('legal-vesting/' . $fileNumber . '.pdf')) {
                    $url = env('AWS_PATH') . "legal-vesting/" . $fileNumber . '.pdf';
                }
            } else {
                $lv_file_path = FCPATH . 'uploads/legal-vesting/' . $fileNumber . '.pdf';
                if (file_exists($lv_file_path)) {
                    $url = base_url() . 'uploads/legal-vesting/' . $fileNumber . '.pdf';
                }
            }
        }
        echo json_encode(['url' => $url]);
        exit;
    }

    public function preListingDocs()
    {
        $userdata = $this->session->userdata('user');
        $this->load->library('order/titlepoint');
        $this->load->model('order/note');
        $this->load->library('order/resware');
        $escrowId = (!empty($_POST['escrow_id'])) ? $_POST['escrow_id'] : '';
        if (!empty($escrowId)) {
            $orderUser = $this->home_model->get_user(array('id' => $escrowId));
        }

        if ((!isset($escrowId) || empty($escrowId)) || (!empty($orderUser) && $orderUser['is_escrow'] == 0)) {
            $fileNumber = $_POST['file_number'];

            $this->order->createLpReport($fileNumber, false, true);

            $this->order->sendOrderEmail($fileNumber);

            /** Start Execute all document creation in background */
            try {
                //code...
                $command = "php " . FCPATH . "index.php frontend/order/cron generatealldocumentfromtitlepoint $fileNumber > /dev/null &";
                exec($command);
            } catch (\Throwable $th) {
                // print_r($th->getMessages());
            }
            /** End Execute all document creation in background */

        }

    }

    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->unset_userdata('user');
        redirect(base_url() . 'order');
    }

    public function notifyAdmin()
    {
        if ($this->input->post()) {
            $customer_id = $this->input->post('customer_id');
            $subject = $this->input->post('subject');

            if (isset($customer_id) && !empty($customer_id)) {
                $condition = array(
                    'id' => $customer_id,
                );
                $customerDetails = $this->home_model->get_customers($condition);
                $first_name = isset($customerDetails['first_name']) && !empty($customerDetails['first_name']) ? $customerDetails['first_name'] : '';
                $last_name = isset($customerDetails['last_name']) && !empty($customerDetails['last_name']) ? $customerDetails['last_name'] : '';
                $telephone_no = isset($customerDetails['telephone_no']) && !empty($customerDetails['telephone_no']) ? $customerDetails['telephone_no'] : '';
                $email_address = isset($customerDetails['email_address']) && !empty($customerDetails['email_address']) ? $customerDetails['email_address'] : '';
                $company_name = isset($customerDetails['company_name']) && !empty($customerDetails['company_name']) ? $customerDetails['company_name'] : '';
                $street_address = isset($customerDetails['street_address']) && !empty($customerDetails['street_address']) ? $customerDetails['street_address'] : '';
                $city = isset($customerDetails['city']) && !empty($customerDetails['city']) ? $customerDetails['city'] : '';
                $zipcode = isset($customerDetails['zip_code']) && !empty($customerDetails['zip_code']) ? $customerDetails['zip_code'] : '';

                $property = $this->input->post('property');

                $message = '<h3>User Details:</h3><p>Name: ' . $first_name . ' ' . $last_name . '</p><p>Telephone: ' . $telephone_no . '</p><p>Email Address: ' . $email_address . '</p><p>Company Name: ' . $company_name . '</p><p>Street Address: ' . $street_address . '</p><p>City: ' . $city . '</p><p>Zipcode: ' . $zipcode . '</p><p>Property Address: ' . $property . '</p>';

                $from_name = 'Pacific Coast Title Company';
                $from_mail = env('FROM_EMAIL');
                $subject = 'Notification for ' . $subject;
                $to = env('ADMIN_EMAIL');

                $this->load->helper('sendemail');

                $mail_result = send_email($from_mail, $from_name, $to, $subject, $message);

                if ($mail_result) {
                    echo 'success';
                    exit;
                } else {
                    echo 'error';
                    exit;
                }
            }
        }
    }

    public function getProductTypes()
    {
        $this->load->model('order/apiLogs');
        $userdata = $this->session->userdata('user');

        $email = isset($_POST['email']) && !empty($_POST['email']) ? $_POST['email'] : '';
        $customerId = isset($_POST['customerId']) && !empty($_POST['customerId']) ? $_POST['customerId'] : '';

        $condition = array(
            'id' => $customerId,
        );
        $customerDetails = $this->home_model->get_customers($condition);

        $email_address = isset($customerDetails['email_address']) && !empty($customerDetails['email_address']) ? $customerDetails['email_address'] : '';
        $password = isset($customerDetails['random_password']) && !empty($customerDetails['random_password']) ? $customerDetails['random_password'] : '';
        $data = array('email' => $email_address, 'password' => $password);

        $resware_user_id = isset($customerDetails['resware_user_id']) && !empty($customerDetails['resware_user_id']) ? $customerDetails['resware_user_id'] : '';
        $endPoint = 'types/products?ClientsClientID=' . $resware_user_id;
        $logid = $this->apiLogs->syncLogs($customerDetails['id'], 'resware', 'get_product_types', env('RESWARE_ORDER_API') . $endPoint, $data, array(), 0, 0);
        $this->load->library('order/resware');
        $result = $this->resware->make_request('GET', $endPoint, '', $data);
        $this->apiLogs->syncLogs($customerDetails['id'], 'resware', 'get_product_types', env('RESWARE_ORDER_API') . $endPoint, $data, $result, 0, $logid);
        $response = json_decode($result, true);
        $product_types = array();

        if (isset($response) && !empty($response)) {
            foreach ($response as $key => $value) {
                if (isset($value['TransactionTypeID']) && $value['TransactionTypeID'] == 3) {
                    $con = array(
                        'where' => array(
                            'product_type_id' => $value['ProductTypeID'],
                            'status' => 1,
                        ),
                        'returnType' => 'count',
                    );
                    $prevCount = $this->home_model->get_product_types($con);
                    if (empty($prevCount)) {
                        $productData = array(
                            'transaction_type' => trim($value['TransactionType']),
                            'transaction_type_id' => $value['TransactionTypeID'],
                            'product_type' => trim($value['ProductType']),
                            'product_type_id' => $value['ProductTypeID'],
                            'state' => 'CA',
                            'status' => 1,
                        );
                        $insert = $this->home_model->insert($productData, 'pct_order_product_types');
                    }

                    $product_types[$value['ProductTypeID']] = $value['ProductType'];
                }
            }
        }
        echo json_encode($product_types);
        exit;
    }

    public function uploadLvDocsToResware($document_name, $fileId, $orderDetails, $lpOrderFlag)
    {
        $this->load->model('order/document');
        $this->load->library('order/resware');
        $this->load->model('order/apiLogs');
        $userdata = $this->session->userdata('user');
        if (env('AWS_ENABLE_FLAG') == 1) {
            $fileSize = filesize(env('AWS_PATH') . "legal-vesting/" . $document_name);
            $contents = file_get_contents(env('AWS_PATH') . "legal-vesting/" . $document_name);
        } else {
            $fileSize = filesize(FCPATH . 'uploads/legal-vesting/' . $document_name);
            $contents = file_get_contents(base_url() . 'uploads/legal-vesting/' . $document_name);
        }
        $binaryData = base64_encode($contents);

        $documentData = array(
            'document_name' => $document_name,
            'original_document_name' => $document_name,
            'document_type_id' => 1037,
            'document_size' => $fileSize,
            'user_id' => $userdata['id'],
            'order_id' => $orderDetails['order_id'],
            'description' => 'Legal & Vesting Document',
            'is_sync' => 1,
            'is_prelim_document' => 0,
            'is_lv_doc' => 1,
        );
        $documentId = $this->document->insert($documentData);
        if ($lpOrderFlag == 0) {
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

            if ($userdata['is_master'] == 1) {
                $orderUser = $this->home_model->get_user(array('id' => $orderDetails['customer_id']));
                $user_data['email'] = $orderUser['email_address'];
                $user_data['password'] = $orderUser['random_password'];
            } else {
                $user_data = array();
            }

            $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $orderDetails['order_id'], 0);
            $result = $this->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
            $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $orderDetails['order_id'], $logid);
            $res = json_decode($result);
            /* Start add resware api logs */
            $reswareLogData = array(
                'request_type' => 'upload_lv_document_to_resware',
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
    }

    // public function uploadIONFraudDocsToResware($document_name, $fileId, $orderDetails)
    // {
    //     $this->load->model('order/document');
    //     $this->load->library('order/resware');
    //     $this->load->model('order/apiLogs');
    //     $userdata = $this->session->userdata('user');
    //     if (env('AWS_ENABLE_FLAG') == 1) {
    //         $fileSize = filesize(env('AWS_PATH') . "ion-fraud/report/" . $document_name);
    //         $contents = file_get_contents(env('AWS_PATH') . "ion-fraud/report/" . $document_name);
    //     } else {
    //         $fileSize = filesize(FCPATH . 'uploads/ion-fraud/' . $document_name);
    //         $contents = file_get_contents(base_url() . 'uploads/ion-fraud/' . $document_name);
    //     }

    //     $binaryData = base64_encode($contents);

    //     $documentData = array(
    //         'document_name' => $document_name,
    //         'original_document_name' => $document_name,
    //         'document_type_id' => 1037,
    //         'document_size' => $fileSize,
    //         'user_id' => $userdata['id'],
    //         'order_id' => $orderDetails['order_id'],
    //         'description' => 'ION Fraud Document',
    //         'is_sync' => 1,
    //         'is_ion_fraud_doc' => 1,
    //     );
    //     $documentId = $this->document->insert($documentData);

    //     $endPoint = 'files/' . $orderDetails['file_id'] . '/documents';
    //     $documentApiData = array(
    //         'DocumentName' => $document_name,
    //         'DocumentType' => array(
    //             'DocumentTypeID' => 1037,
    //         ),
    //         'Description' => 'ION Fraud Document',
    //         'InternalOnly' => false,
    //         'DocumentBody' => $binaryData,
    //     );
    //     $document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);

    //     if ($userdata['is_master'] == 1) {
    //         $orderUser = $this->home_model->get_user(array('id' => $orderDetails['customer_id']));
    //         $user_data['email'] = $orderUser['email_address'];
    //         $user_data['password'] = $orderUser['random_password'];
    //     } else {
    //         $user_data = array();
    //     }

    //     $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_ion_fraud_report_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $orderDetails['order_id'], 0);
    //     $result = $this->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
    //     $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_ion_fraud_report_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $orderDetails['order_id'], $logid);
    //     $res = json_decode($result);
    //     /* Start add resware api logs */
    //     $reswareLogData = array(
    //         'request_type' => 'upload_ion_fraud_document_to_resware_' . $documentId,
    //         'request_url' => env('RESWARE_ORDER_API') . $endPoint,
    //         'request' => $document_api_data,
    //         'response' => $result,
    //         'status' => 'success',
    //         'created_at' => date("Y-m-d H:i:s"),
    //     );
    //     $this->db->insert('pct_resware_log', $reswareLogData);
    //     /* End add resware api logs */
    //     $this->document->update(array('api_document_id' => $res->Document->DocumentID), array('id' => $documentId));

    // }

    public function uploadGrantDeedDocsToResware($document_name, $fileId, $orderDetails, $lpOrderFlag)
    {
        $this->load->model('order/document');
        $this->load->library('order/resware');
        $this->load->model('order/apiLogs');
        $userdata = $this->session->userdata('user');
        if (env('AWS_ENABLE_FLAG') == 1) {
            $fileSize = filesize(env('AWS_PATH') . "grant-deed/" . $document_name);
            $contents = file_get_contents(env('AWS_PATH') . "grant-deed/" . $document_name);
        } else {
            $fileSize = filesize(FCPATH . 'uploads/grant-deed/' . $document_name);
            $contents = file_get_contents(base_url() . 'uploads/grant-deed/' . $document_name);
        }
        $binaryData = base64_encode($contents);

        $documentData = array(
            'document_name' => $document_name,
            'original_document_name' => $document_name,
            'document_type_id' => 1037,
            'document_size' => $fileSize,
            'user_id' => $userdata['id'],
            'order_id' => $orderDetails['order_id'],
            'description' => 'Grant Deed Document',
            'is_sync' => 1,
            'is_prelim_document' => 0,
            'is_grant_doc' => 1,
        );
        $documentId = $this->document->insert($documentData);
        if ($lpOrderFlag == 0) {
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

            if ($userdata['is_master'] == 1) {
                $orderUser = $this->home_model->get_user(array('id' => $orderDetails['customer_id']));
                $user_data['email'] = $orderUser['email_address'];
                $user_data['password'] = $orderUser['random_password'];
            } else {
                $user_data = array();
            }

            $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $orderDetails['order_id'], 0);
            $result = $this->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
            $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $orderDetails['order_id'], $logid);
            $res = json_decode($result);
            /* Start add resware api logs */
            $reswareLogData = array(
                'request_type' => 'upload_grantdeed_document_to_resware',
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

    }

    public function uploadTaxDocsToResware($document_name, $fileId, $orderDetails, $lpOrderFlag)
    {
        $this->load->model('order/document');
        $this->load->library('order/resware');
        $this->load->model('order/apiLogs');
        $userdata = $this->session->userdata('user');
        if (env('AWS_ENABLE_FLAG') == 1) {
            $fileSize = filesize(env('AWS_PATH') . "tax/" . $document_name);
            $contents = file_get_contents(env('AWS_PATH') . "tax/" . $document_name);
        } else {
            $fileSize = filesize(FCPATH . 'uploads/tax/' . $document_name);
            $contents = file_get_contents(base_url() . 'uploads/tax/' . $document_name);
        }

        $binaryData = base64_encode($contents);

        $documentData = array(
            'document_name' => $document_name,
            'original_document_name' => $document_name,
            'document_type_id' => 1037,
            'document_size' => $fileSize,
            'user_id' => $userdata['id'],
            'order_id' => $orderDetails['order_id'],
            'description' => 'Tax Document',
            'is_sync' => 1,
            'is_prelim_document' => 0,
            'is_tax_doc' => 1,
        );
        $documentId = $this->document->insert($documentData);

        if ($lpOrderFlag == 0) {
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

            if ($userdata['is_master'] == 1) {
                $orderUser = $this->home_model->get_user(array('id' => $orderDetails['customer_id']));
                $user_data['email'] = $orderUser['email_address'];
                $user_data['password'] = $orderUser['random_password'];
            } else {
                $user_data = array();
            }

            $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $orderDetails['order_id'], 0);
            $result = $this->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
            $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $orderDetails['order_id'], $logid);
            $res = json_decode($result);
            /* Start add resware api logs */
            $reswareLogData = array(
                'request_type' => 'upload_tax_document_to_resware',
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

    }

    public function uploadPreListingDocsToResware($document_name, $fileId, $orderDetails)
    {
        $this->load->model('order/document');
        $this->load->library('order/resware');
        $this->load->model('order/apiLogs');
        $userdata = $this->session->userdata('user');
        $fileSize = filesize(env('AWS_PATH') . "pre-listing-doc/" . $document_name);
        $contents = file_get_contents(env('AWS_PATH') . "pre-listing-doc/" . $document_name);
        $binaryData = base64_encode($contents);

        $documentData = array(
            'document_name' => $document_name,
            'original_document_name' => $document_name,
            'document_type_id' => 1037,
            'document_size' => $fileSize,
            'user_id' => $userdata['id'],
            'order_id' => $orderDetails['order_id'],
            'description' => 'Pre Listing Document',
            'is_sync' => 1,
            'is_prelim_document' => 0,
            'is_pre_listing_doc' => 1,
        );
        $condition = array('is_pre_listing_doc' => 1, 'order_id' => $orderDetails['order_id']);
        $this->document->delete($documentData, $condition);
        $documentId = $this->document->insert($documentData);

        /** Upload records to resware  */

        /*
    $endPoint = 'files/'.$orderDetails['file_id'].'/documents';

    $documentApiData = array(
    'DocumentName' => $document_name,
    'DocumentType' => array(
    'DocumentTypeID' => 1037,
    ),
    'Description' => 'Pre Listing Document',
    'InternalOnly' => false,
    'DocumentBody' => $binaryData
    );
    $document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);

    if ($userdata['is_master'] == 1) {
    $orderUser =  $this->home_model->get_user(array('id' => $orderDetails['customer_id']));
    $user_data['email'] = $orderUser['email_address'];
    $user_data['password'] = $orderUser['random_password'];
    // $user_data['admin_api'] = 1;
    } else {
    $user_data = array();
    }

    $user_data['admin_api'] = 1;
    $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API').$endPoint, $documentApiData, array(), $orderDetails['order_id'], 0);
    $result = $this->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
    $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API').$endPoint, $documentApiData, $result, $orderDetails['order_id'], $logid);
    $res = json_decode($result);
    $this->document->update(array('api_document_id' => $res->Document->DocumentID), array('id' => $documentId));*/
    }

    public function checkDuplicateOrder()
    {
        $apn = $this->input->post('apn');
        $result = $this->order->checkDuplicateOrder($apn);
        if ($result) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false));
        }
    }

    public function getIonReport()
    {
        $property = $this->input->post('address');
        $state = $this->input->post('state');
        $ionFraudRes = $this->ionfraud->getIONFraudPropertyDetails($property, $state);
        if (!empty($ionFraudRes) && !empty($ionFraudRes['Property_Profile'])) {
            $ionProfileData = $ionFraudRes['Property_Profile'];
            $ionOwnerName = $ionProfileData['Ownername'];
            echo json_encode(array('status' => true, 'data' => $ionProfileData));

        } else {
            echo json_encode(array('status' => false, 'data' => []));
        }
    }

    public function uploadCurativeDocsToResware($orderDetails)
    {
        $this->load->model('order/document');
        $this->load->model('order/apiLogs');
        $this->load->library('order/resware');
        $userdata = $this->session->userdata('user');
        $data = $this->upload->data();
        $contents = file_get_contents($data['full_path']);
        $binaryData = base64_encode($contents);
        $document_name = date('YmdHis') . "_" . $data['file_name'];
        rename(FCPATH . "/uploads/curative/" . $data['file_name'], FCPATH . "/uploads/curative/" . $document_name);

        $documentData = array(
            'document_name' => $document_name,
            'original_document_name' => $data['file_name'],
            'document_type_id' => 1033,
            'document_size' => ($data['file_size'] * 1000),
            'user_id' => $userdata['id'],
            'order_id' => $orderDetails['order_id'],
            'description' => 'Curative Documents',
            'is_sync' => 1,
            'is_curative_doc' => 1,
        );

        $documentId = $this->document->insert($documentData);

        $endPoint = 'files/' . $orderDetails['file_id'] . '/documents';

        $documentApiData = array(
            'DocumentName' => $data['file_name'],
            'DocumentType' => array(
                'DocumentTypeID' => 1033,
            ),
            'Description' => 'Curative Documents',
            'InternalOnly' => false,
            'DocumentBody' => $binaryData,
        );
        $document_api_data = json_encode($documentApiData, JSON_UNESCAPED_SLASHES);

        if ($userdata['is_master'] == 1) {
            $orderUser = $this->home_model->get_user(array('id' => $orderDetails['customer_id']));
            $user_data['email'] = $orderUser['email_address'];
            $user_data['password'] = $orderUser['random_password'];
        } else {
            $user_data = array();
        }

        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, array(), $orderDetails['order_id'], 0);
        $result = $this->resware->make_request('POST', $endPoint, $document_api_data, $user_data);
        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'create_document', env('RESWARE_ORDER_API') . $endPoint, $documentApiData, $result, $orderDetails['order_id'], $logid);
        $res = json_decode($result);

        /* Start add resware api logs */
        $reswareLogData = array(
            'request_type' => 'upload_curative_document_to_resware',
            'request_url' => env('RESWARE_ORDER_API') . $endPoint,
            'request' => $document_api_data,
            'response' => $result,
            'status' => 'success',
            'created_at' => date("Y-m-d H:i:s"),
        );
        $this->db->insert('pct_resware_log', $reswareLogData);
        /* End add resware api logs */

        $this->document->update(array('api_document_id' => $res->Document->DocumentID), array('id' => $documentId));
        $this->order->uploadDocumentOnAwsS3($document_name, 'curative');
    }

    public function send_invite()
    {

        $this->form_validation->set_rules('borrower_email', 'Email', 'required|valid_email', array('required' => 'Enter borrower Email', 'valid_email' => 'Enter valid Email'));
        $this->form_validation->set_rules('borrower_name', 'Name', 'required', array('required' => 'Enter borrower Name'));

        $response_data = array(
            'status' => false,
            'message' => 'Something went wrong',
        );

        if ($this->form_validation->run() === true) {

            $borrower_details = array('name' => $this->input->post('borrower_name'), 'email' => $this->input->post('borrower_email'));

            /* Call HomeDocs API  */
            $api_data = array();
            $FullProperty = $this->input->post('property_address');
            $api_data['escrow_details'] = array();
            $api_data['lender_details'] = array();
            $api_data['borrwer_details'] = $borrower_details;
            $api_data['escrow_officer_details'] = array();
            $api_data['property_details'] = array(
                'address' => $FullProperty,
            );

            $this->load->helper('homedocsapi');
            // $result = true;

            $result = call_homedocs_api($api_data);
            /* Call HomeDocs API  */
            if ($result) {
                $update_data = array();
                $update_data['borrower_invited'] = true;
                $where['id'] = $this->input->post('invite_order_id');
                $this->order->update($update_data, $where);
                $response_data['status'] = true;
                $response_data['message'] = 'Success';
            }

        } else {
            $response_data['message'] = validation_errors();
        }

        echo json_encode($response_data);
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
}
