<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Westcor
{
    public static $CI;

    public function __construct($params = array())
    {
        $this->CI = &get_instance();
        $this->CI->load->database();
        $this->CI->load->library('email');
        $this->CI->load->library('session');
        $this->CI->load->model('order/home_model');
        self::$CI = $this->CI;
    }

    public function make_request($http_method, $endpoint, $body_params, $is_token_call = 0, $bearerToken = '')
    {
        $ch = curl_init(getenv('WESTCORE_URL') . $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http_method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body_params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($is_token_call == 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $bearerToken,
                'Content-Length: ' . strlen($body_params))
            );
        }
        $error_msg = curl_error($ch);
        $result = curl_exec($ch);
        return $result;
    }

    public function createToken($orderNumber, $is_branch_update = 0)
    {
        if (!empty($this->CI->session->userdata('user'))) {
            $userdata = $this->CI->session->userdata('user');
        } else if (!empty($this->CI->session->userdata('admin'))) {
            $userdata = $this->CI->session->userdata('admin');
        } else {
            $userdata = array();
            $userdata['id'] = 0;
        }
        $this->CI->load->model('order/apiLogs');
        $endPoint = 'Token';
        $postData = 'grant_type=' . getenv('WESTCORE_GRANT_TYPE') . '&username=' . getenv('WESTCORE_USERNAME') . '&password=' . getenv('WESTCORE_PASSWORD') . '&integrationpartner=' . getenv('WESTCORE_INTEGRATION_PARTNER');
        $logid = $this->CI->apiLogs->syncLogs($userdata['id'], 'westcor', 'create_token', getenv('WESTCORE_URL') . $endPoint, $postData, array(), $orderNumber, 0);
        $result = $this->CI->westcor->make_request('POST', $endPoint, $postData, 1);
        $this->CI->apiLogs->syncLogs($userdata['id'], 'westcor', 'create_token', getenv('WESTCORE_URL') . $endPoint, $postData, $result, $orderNumber, $logid);
        $resToken = json_decode($result, true);
        $groups = json_decode($resToken['groups'], true);
        if (!empty($resToken)) {
            $this->CI->db->empty_table('pct_order_westcore_token');
            $records = array(
                'token' => $resToken['access_token'],
                'first_name' => $resToken['firstName'],
                'last_name' => $resToken['lastName'],
                'email' => $resToken['email'],
                'role' => $resToken['role'],
                'servername' => $resToken['servername'],
                'create_token_time' => date('Y-m-d H:i:s'),
                'expires_in' => $resToken['expires_in'],
                'original_agent_number' => $resToken['agentNumber'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
            $this->CI->db->insert('pct_order_westcore_token', $records);
        }

        if ($is_branch_update == 1) {
            $branchesData = array();
            foreach ($groups as $group) {
                $this->CI->db->select('*');
                $this->CI->db->from('pct_order_westcore_branches');
                $this->CI->db->like('city', $group['city'] != '.' ? $group['city'] : 'Other');
                $query = $this->CI->db->get();
                $result = $query->row_array();
                if (!empty($result)) {
                    $condition = array(
                        'city' => $result['city'],
                    );
                    $branchData = array(
                        'agency_name' => $group['agencyName'],
                        'address' => $group['address'],
                        'state' => $group['state'],
                        'zip' => $group['zip'],
                        'phone' => $group['phone'],
                        'agent_number' => $group['agentNumber'],
                        'is_proposed_branch' => ($group['city'] == 'Glendale' || $group['city'] == 'Orange' || $group['city'] == 'Oxnard' || $group['city'] == 'San Diego') ? 1 : 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    $this->CI->db->update('pct_order_westcore_branches', $branchData, $condition);
                    $branchData['city'] = $group['city'] != '.' ? $group['city'] : 'Other';
                    $branchData['id'] = $result['id'] != '.' ? $group['city'] : 'Other';
                } else {
                    $branchData = array(
                        'agency_name' => $group['agencyName'],
                        'address' => $group['address'],
                        'city' => $group['city'] != '.' ? $group['city'] : 'Other',
                        'state' => $group['state'],
                        'zip' => $group['zip'],
                        'phone' => $group['phone'],
                        'agent_number' => $group['agentNumber'],
                        'is_proposed_branch' => ($group['city'] == 'Glendale' || $group['city'] == 'Orange' || $group['city'] == 'Oxnard' || $group['city'] == 'San Diego') ? 1 : 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );
                    $insert_id = $this->CI->db->insert('pct_order_westcore_branches', $branchData);
                    $branchData['id'] = $insert_id;
                }
                $branchesData[] = $branchData;
            }
            return $branchesData;
        } else {
            if (!empty($resToken['access_token'])) {
                return array('token' => $resToken['access_token'], 'original_agent_number' => $resToken['agentNumber']);
            } else {
                return array('token' => '');
            }
        }
    }

    public function get_token($orderNumber)
    {
        $this->CI->db->select('*');
        $this->CI->db->from('pct_order_westcore_token');
        $query = $this->CI->db->get();
        $result = $query->row_array();
        if (!empty($result)) {
            $date = new DateTime($result['create_token_time']);
            $date2 = new DateTime(date('Y-m-d H:i:s'));
            $diff = $date2->getTimestamp() - $date->getTimestamp();
            if ($diff < $result['expires_in']) {
                return $result;
            } else {
                $resToken = $this->createToken($orderNumber, 0);
                return $resToken;
            }
        }
    }

    public function getBranches($id = 0)
    {
        $this->CI->db->select('*');
        $this->CI->db->from('pct_order_westcore_branches');
        if (!empty($id)) {
            $this->CI->db->where('id', $id);
        }
        $query = $this->CI->db->get();
        if ($id == 0) {
            $result = $query->result_array();
        } else {
            $result = $query->row_array();
        }

        if (!empty($result)) {
            return $result;
        } else {
            return false;
        }
    }

    public function getBranchesFromApi()
    {
        $branchData = $this->createToken(0, 1);
        return $branchData;
    }

    public function generateCplDocument($fileId, $orderDetails)
    {
        $userdata = $this->CI->session->userdata('user');
        if (empty($userdata)) {
            $userdata['id'] = 0;
        }
        $errors = array();
        $success = array();
        $this->CI->load->model('order/home_model');
        $this->CI->load->model('order/apiLogs');
        $this->CI->load->library('order/order');
        $this->CI->load->model('order/document');
        $res = array();
        $resToken = $this->get_token($orderDetails['order_id']);
        $branchData = $this->getBranches($orderDetails['fnf_agent_id']);
        $orderUser = $this->CI->home_model->get_user(array('id' => $orderDetails['customer_id']));

        $propertyDetail = explode(",", $orderDetails['full_address']);
        $propery[] = array(
            'PropertyID' => 0,
            'tvid' => 0,
            'CountyName' => $orderDetails['county'] . ' County',
            'ShortLegal' => $orderDetails['legal_description'] ? $orderDetails['legal_description'] : null,
            'StreetAddress' => $orderDetails['cpl_proposed_property_address'],
            'City' => $orderDetails['cpl_proposed_property_city'],
            'State' => $orderDetails['cpl_proposed_property_state'],
            'Zip' => $orderDetails['cpl_proposed_property_zip'],
            'PropertyType' => 'R',
        );

        $primary_owner = explode(" ", $orderDetails['primary_owner']);
        $secondary_owner = explode(" ", $orderDetails['secondary_owner']);
        if ($orderDetails['sales_amount'] > 0) {
            $sellerBorrowerName = $orderDetails['primary_owner'];
            $sellers[] = array(
                'NameID' => $orderDetails['westcor_seller_id'] ? $orderDetails['westcor_seller_id'] : 0,
                'Last' => '-',
                'First' => $sellerBorrowerName,
                'NameType' => 2,
                'JoiningPhrase' => 'single',
                'tvid' => 0,
                'Sequence' => 1,
                'City' => null,
                'State' => null,
                'Zip' => null,
                'Address' => null,
            );

            $purchase_price = $orderDetails['sales_amount'];
            $buyers = array();

            if (!empty($orderDetails['borrowers_vesting'])) {

                $buyerBorrowerName = $orderDetails['borrowers_vesting'];

                if (!empty($orderDetails['secondary_borrower'])) {
                    $buyerBorrowerName .= " and " . $orderDetails['secondary_borrower'];
                }

                $buyers[] = array(
                    'NameID' => $orderDetails['westcor_secondary_buyer_id'] ? $orderDetails['westcor_secondary_buyer_id'] : 0,
                    'Last' => '-',
                    'First' => $buyerBorrowerName,
                    'NameType' => 1,
                    'JoiningPhrase' => 'single',
                    'tvid' => 0,
                    'Sequence' => 1,
                    'City' => null,
                    'State' => null,
                    'Zip' => null,
                    'Address' => null,
                );
            }
        } else {
            $buyers = array();
            $buyerBorrowerName = $orderDetails['borrowers_vesting'];
            $buyers[] = array(
                'NameID' => $orderDetails['westcor_buyer_id'] ? $orderDetails['westcor_buyer_id'] : 0,
                'Last' => '-',
                'First' => $buyerBorrowerName,
                'NameType' => 1,
                'JoiningPhrase' => 'single',
                'tvid' => 0,
                'Sequence' => 1,
                'City' => null,
                'State' => null,
                'Zip' => null,
                'Address' => null,
            );
            $purchase_price = $orderDetails['loan_amount'];
            $sellers = array();
        }

        $lenderDetails = $this->CI->home_model->get_user(array('id' => $orderDetails['cpl_lender_id']));
        $lenderFormData = $this->CI->session->has_userdata('lender_details') ? $this->CI->session->userdata('lender_details') : [];
        $name = !empty($lenderFormData) ? $lenderFormData['lender_fullname'] : $lenderDetails['first_name'] . " " . $lenderDetails['last_name'];
        if (!empty($lenderDetails)) {
            $lenders[] = array(
                'Id' => $orderDetails['westcor_lender_id'] ? $orderDetails['westcor_lender_id'] : 0,
                'tvid' => 0,
                'name' => !empty($lenderFormData) ? $lenderFormData['company_name'] : $lenderDetails['company_name'],
                'city' => !empty($lenderFormData) ? $lenderFormData['city'] : $lenderDetails['city'],
                'state' => !empty($lenderFormData) ? $lenderFormData['state'] : $lenderDetails['state'],
                'zip' => !empty($lenderFormData) ? $lenderFormData['zip_code'] : $lenderDetails['zip_code'],
                'address' => !empty($lenderFormData) ? $lenderFormData['street_address'] : $lenderDetails['street_address'],
                'phone' => $lenderDetails['telephone_no'],
                'email' => $lenderDetails['email_address'],
                'countyFIPS' => null,
                'assignment' => (!empty($lenderFormData) ? $lenderFormData['assignment_clause'] : ($lenderDetails['assignment_clause'] ? $lenderDetails['assignment_clause'] . "\n" . $name : "\n" . $name)),
                'mortgageType' => null,
                'amount' => 0,
                'loan_number' => $orderDetails['loan_number'] ? $orderDetails['loan_number'] : '',
                'vendorInternalID' => $lenderDetails['id'],
            );
        }

        $cplPostData = array(
            'tvid' => 0,
            'agentnumber' => $resToken['original_agent_number'],
            'agent_file_number' => $orderDetails['file_number'],
            'email_requestor' => isset($orderUser['email_address']) && !empty($orderUser['email_address']) ? $orderUser['email_address'] : 'cpl@pct.com',
            'purchase_price' => preg_replace('/[^0-9\-]/', '', $purchase_price),
            'property' => $propery,
            'buyers' => $buyers,
            'sellers' => $sellers,
            'lenders' => $lenders,
            'search' => null,
            'commitment' => null,
            'jacket' => null,
            'sdn' => null,
            'history' => null,
            'notes' => !empty($orderDetails['addtional_details']) ? $orderDetails['addtional_details'] : null,
            'messages' => array(
                'success' => [],
                'warning' => [],
                'error' => []
            ),
            'actions' => array(
                'sdn' => false,
                'update_base' => true,
                'update_property' => true,
                'update_lender' => true,
                'update_buyers' => true,
                'update_sellers' => true,
                'update_attorneys' => false,
                'update_cpls' => false,
                'update_jacket' => false,
                'update_search' => false,
                'update_reinsurance' => false,
                'update_priors' => false,
            ),
            'partnerCode' => (int) getenv('WESTCORE_INTEGRATION_PARTNER'),
            'cpl' => null,
            'priors' => null
        );

        if (empty($orderDetails['westcor_order_id'])) {
            $endPointCreateOrder = 'VendorApi/Order/Update/' . getenv('WESTCORE_INTEGRATION_PARTNER');
            $cplPostData = json_encode($cplPostData);
            $res = array();
            $logid = $this->CI->apiLogs->syncLogs($userdata['id'], 'westcor', 'create_cpl_order', getenv('WESTCORE_URL') . $endPointCreateOrder, $cplPostData, array(), $orderDetails['order_id'], 0);
            $result = $this->make_request('POST', $endPointCreateOrder, $cplPostData, 0, $resToken['token']);
            $this->CI->apiLogs->syncLogs($userdata['id'], 'westcor', 'create_cpl_order', getenv('WESTCORE_URL') . $endPointCreateOrder, $cplPostData, $result, $orderDetails['order_id'], $logid);
            $res = json_decode($result, true);
            if (is_array($res)) {
                if ($res['Message']) {
                    $errors[] = $res['Message'] . "<br> We are aware of the Error generated by our CPL form and that our Customer service team will be contacting them shortly.";
                    $cplErrorData = array(
                        'order_id' => $orderDetails['order_id'],
                        'file_number' => $orderDetails['file_number'],
                        'cpl_page' => $userdata['id'] > 0 ? 'Dashboard' : 'Generic Or Mail page',
                        'error' => $res['Message'],
                        'customer_id' => $orderDetails['customer_id'],
                        'property_address' => $orderDetails['full_address'],
                    );
                    $this->CI->order->storeCplError($cplErrorData);
                    $data = array(
                        "errors" => $errors,
                        "success" => $success,
                    );
                    return $data;
                }
                $order_details = array(
                    'westcor_order_id' => $res['tvid'],
                    'westcor_buyer_id' => !empty($res['buyers']) ? $res['buyers'][0]['NameID'] : 0,
                    'westcor_seller_id' => !empty($res['sellers']) ? $res['sellers'][0]['NameID'] : 0,
                    'westcor_secondary_buyer_id' => !empty($res['buyers']) ? $res['buyers'][1]['NameID'] : 0,
                    'westcor_secondary_seller_id' => !empty($res['sellers']) ? $res['sellers'][1]['NameID'] : 0,
                    'westcor_lender_id' => !empty($res['lenders']) ? $res['lenders'][0]['Id'] : 0,
                );
                $condition = array(
                    'id' => $orderDetails['order_id'],
                );
                if (!empty($buyers)) {
                    $buyers[0]['NameID'] = !empty($res['buyers']) ? $res['buyers'][0]['NameID'] : 0;

                }

                if (!empty($sellers)) {
                    $sellers[0]['NameID'] = !empty($res['sellers']) ? $res['sellers'][0]['NameID'] : 0;

                }

                $lenders[0]['Id'] = !empty($res['lenders']) ? $res['lenders'][0]['Id'] : 0;

                $this->CI->home_model->update($order_details, $condition, 'order_details');
                $this->CI->home_model->update(array('westcor_property_id' => !empty($res['property']) ? $res['property'][0]['PropertyID'] : 0), array('id' => $orderDetails['property_id']), 'property_details');
                $orderDetails['westcor_order_id'] = $res['tvid'];
                $orderDetails['westcor_buyer_id'] = !empty($res['buyers']) ? $res['buyers'][0]['NameID'] : 0;
                $orderDetails['westcor_seller_id'] = !empty($res['sellers']) ? $res['sellers'][0]['NameID'] : 0;
                $orderDetails['westcor_secondary_buyer_id'] = !empty($res['buyers']) ? $res['buyers'][1]['NameID'] : 0;
                $orderDetails['westcor_secondary_seller_id'] = !empty($res['sellers']) ? $res['sellers'][1]['NameID'] : 0;
                $orderDetails['westcor_lender_id'] = !empty($res['lenders']) ? $res['lenders'][0]['Id'] : 0;

            } else {
                $errors[] = $result . "<br> We are aware of the Error generated by our CPL form and that our Customer service team will be contacting them shortly.";
                $cplErrorData = array(
                    'order_id' => $orderDetails['order_id'],
                    'file_number' => $orderDetails['file_number'],
                    'cpl_page' => $userdata['id'] > 0 ? 'Dashboard' : 'Generic Or Mail page',
                    'error' => $result,
                    'customer_id' => $orderDetails['customer_id'],
                    'property_address' => $orderDetails['full_address'],
                );
                $this->CI->order->storeCplError($cplErrorData);
                $data = array(
                    "errors" => $errors,
                    "success" => $success,
                );
                return $data;
            }
        }

        if (!empty($orderDetails['westcor_order_id'])) {
            $res = array();
            $endPointGetOrdeData = 'VendorApi/Order/' . $orderDetails['westcor_order_id'] . '/' . getenv('WESTCORE_INTEGRATION_PARTNER');
            $logid = $this->CI->apiLogs->syncLogs($userdata['id'], 'westcor', 'get_order_data', getenv('WESTCORE_URL') . $endPointGetOrdeData, array(), array(), $orderDetails['order_id'], 0);
            $resultForGetOrderData = $this->make_request('GET', $endPointGetOrdeData, array(), 0, $resToken['token']);
            $this->CI->apiLogs->syncLogs($userdata['id'], 'westcor', 'get_order_data', getenv('WESTCORE_URL') . $endPointGetOrdeData, array(), $resultForGetOrderData, $orderDetails['order_id'], $logid);
            $res = json_decode($resultForGetOrderData, true);
            $res['cpl'] = array();

            $resCPL = array();
            $endPointForCPL = 'VendorApi/ClosingLetters/PrepareAddCPL/' . $orderDetails['westcor_order_id'] . '/' . getenv('WESTCORE_INTEGRATION_PARTNER');
            $logid = $this->CI->apiLogs->syncLogs($userdata['id'], 'westcor', 'get_cpl_data', getenv('WESTCORE_URL') . $endPointForCPL, array(), array(), $orderDetails['order_id'], 0);
            $cplData = $this->make_request('GET', $endPointForCPL, array(), 0, $resToken['token']);
            $this->CI->apiLogs->syncLogs($userdata['id'], 'westcor', 'get_cpl_data', getenv('WESTCORE_URL') . $endPointForCPL, array(), $cplData, $orderDetails['order_id'], $logid);
            $resCPL = json_decode($cplData, true);

            $resCPL['CPL']['LetterName'] = $resCPL['CPL']['Forms'][1]['FormName'];
            $resCPL['CPL']['FileInformation'] = null;
            $resCPL['CPL']['CPLID'] = -1;
            $resCPL['CPL']['LenderID'] = !empty($orderDetails['westcor_lender_id']) ? $orderDetails['westcor_lender_id'] : 0;
            $resCPL['CPL']['PolicyProducingAgentAddressID'] = $branchData['agent_number'];
            $resCPL['CPL']['PolicyProducingAgentAddress'] = $branchData['address'];
            $resCPL['CPL']['PolicyProducingAgentCity'] = $branchData['city'];
            $resCPL['CPL']['PolicyProducingAgentState'] = $branchData['state'];
            $resCPL['CPL']['PolicyProducingAgentZip'] = $branchData['zip'];
            $resCPL['CPL']['ProtectLender'] = true;
            // $resCPL['CPL']['ClosingAgentNumber'] = 'RI1026';
            $resCPL['CPL']['ClosingAgentNumber'] = 'CA1038';
            $resCPL['CPL']['IsDualCPL'] = false;

            $res['cpl'][] = $resCPL['CPL'];
            $res['property'] = $propery;
            $res['lenders'] = $lenders;
            $res['buyers'] = $buyers;
            $res['sellers'] = $sellers;
            $res['actions']['update_property'] = true;
            $res['actions']['update_cpls'] = true;
            $res['actions']['update_buyers'] = true;
            $res['actions']['update_sellers'] = true;
            $res['actions']['update_lender'] = true;
            $res['purchase_price'] = preg_replace('/[^0-9\-]/', '', $purchase_price);

            $generateCplPostData = json_encode($res);
            $endPointCreateCPL = 'VendorApi/Order/Update/' . getenv('WESTCORE_INTEGRATION_PARTNER');
            $resultResCPL = array();
            $logid = $this->CI->apiLogs->syncLogs($userdata['id'], 'westcor', 'generate_cpl', getenv('WESTCORE_URL') . $endPointCreateCPL, $generateCplPostData, array(), $orderDetails['order_id'], 0);
            $resultCPL = $this->make_request('POST', $endPointCreateCPL, $generateCplPostData, 0, $resToken['token']);
            $this->CI->apiLogs->syncLogs($userdata['id'], 'westcor', 'generate_cpl', getenv('WESTCORE_URL') . $endPointCreateCPL, $generateCplPostData, $resultCPL, $orderDetails['order_id'], $logid);
            $resultResCPL = json_decode($resultCPL, true);

            if (is_array($resultResCPL)) {
                if ($resultResCPL['messages']['error']) {
                    foreach ($resultResCPL['messages']['error'] as $error) {
                        if (empty($orderDetails['westcor_lender_id']) && strtolower($error) == 'lender could not be found.') {
                            $order_details = array(
                                'westcor_lender_id' => !empty($resultResCPL['lenders']) ? $resultResCPL['lenders'][0]['Id'] : 0,
                                'westcor_buyer_id' => !empty($resultResCPL['buyers']) ? $resultResCPL['buyers'][0]['NameID'] : 0,
                                'westcor_seller_id' => !empty($resultResCPL['sellers']) ? $resultResCPL['sellers'][0]['NameID'] : 0,
                                'westcor_secondary_buyer_id' => !empty($resultResCPL['buyers']) ? $resultResCPL['buyers'][1]['NameID'] : 0,
                                'westcor_secondary_seller_id' => !empty($resultResCPL['sellers']) ? $resultResCPL['sellers'][1]['NameID'] : 0,
                            );
                            $condition = array(
                                'id' => $orderDetails['order_id'],
                            );

                            $this->CI->home_model->update($order_details, $condition, 'order_details');
                            redirect(base_url() . "create-cpl/" . $fileId);
                        }
                        $errors[] = $error . "<br> We are aware of the Error generated by our CPL form and that our Customer service team will be contacting them shortly.";
                        $cplErrorData = array(
                            'order_id' => $orderDetails['order_id'],
                            'file_number' => $orderDetails['file_number'],
                            'cpl_page' => $userdata['id'] > 0 ? 'Dashboard' : 'Generic Or Mail page',
                            'error' => $error,
                            'customer_id' => $orderDetails['customer_id'],
                            'property_address' => $orderDetails['full_address'],
                        );
                        $this->CI->order->storeCplError($cplErrorData);
                    }

                    $data = array(
                        "errors" => $errors,
                        "success" => $success,
                    );
                    return $data;
                }

                $cplCount = count($resultResCPL['cpl']) - 1;

                $order_details = array(
                    'westcor_cpl_id' => $resultResCPL['cpl'][$cplCount]['CPLID'],
                    'westcor_file_id' => $resultResCPL['cpl'][$cplCount]['FileInformation']['FileAsDataVaultFileID'],
                    'westcor_buyer_id' => !empty($resultResCPL['buyers']) ? $resultResCPL['buyers'][0]['NameID'] : 0,
                    'westcor_seller_id' => !empty($resultResCPL['sellers']) ? $resultResCPL['sellers'][0]['NameID'] : 0,
                    'westcor_secondary_buyer_id' => !empty($resultResCPL['buyers']) ? $resultResCPL['buyers'][1]['NameID'] : 0,
                    'westcor_secondary_seller_id' => !empty($resultResCPL['sellers']) ? $resultResCPL['sellers'][1]['NameID'] : 0,
                );

                if (!empty($resultResCPL['cpl'][$cplCount]['FileInformation']['FileAsBase64'])) {
                    $cplDocumentCount = $this->CI->document->countCplDocument($orderDetails['order_id']);
                    $document_name = "westcor_" . $cplDocumentCount . "_" . $fileId . ".pdf";
                    if (!is_dir('uploads/documents')) {
                        mkdir('./uploads/documents', 0777, true);
                    }
                    file_put_contents('./uploads/documents/' . $document_name, base64_decode($resultResCPL['cpl'][$cplCount]['FileInformation']['FileAsBase64']));
                    $this->CI->home_model->update(array('cpl_document_name' => $document_name), array('file_id' => $fileId), 'order_details');
                }

                $condition = array(
                    'id' => $orderDetails['order_id'],
                );

                $this->CI->home_model->update($order_details, $condition, 'order_details');
                $this->CI->order->uploadCPLDocumentToResware($document_name, $orderDetails, $resultResCPL['cpl'][$cplCount]['FileInformation']['FileAsBase64']);
                $this->CI->order->uploadDocumentOnAwsS3($document_name, 'documents');
                $success[] = "Generated CPL request successfully for file number - " . $orderDetails['file_number'];
                if (!empty($userdata) && $userdata['id'] == $orderDetails['title_officer']) {
                    $message = 'CPL document generated for order number #' . $orderDetails['file_number'];
                    $notificationData = array(
                        'sent_user_id' => $orderDetails['customer_id'] ? $orderDetails['customer_id'] : 0,
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'created',
                    );
                    $this->CI->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->CI->order->sendNotification($message, 'created', $orderDetails['customer_id'], 0);
                } else if (!empty($userdata) && $userdata['id'] == $orderDetails['customer_id']) {
                    $message = 'CPL document generated for order number #' . $orderDetails['file_number'];
                    $notificationData = array(
                        'sent_user_id' => $orderDetails['title_officer'] ? $orderDetails['title_officer'] : 0,
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'created',
                    );
                    $this->CI->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->CI->order->sendNotification($message, 'created', $orderDetails['title_officer'], 0);
                } else {
                    $message = 'CPL document generated for order number #' . $orderDetails['file_number'];
                    $notificationData = array(
                        'sent_user_id' => $orderDetails['title_officer'] ? $orderDetails['title_officer'] : 0,
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'created',
                    );
                    $this->CI->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->CI->order->sendNotification($message, 'created', $orderDetails['title_officer'], 0);
                    $notificationData = array(
                        'sent_user_id' => $orderDetails['customer_id'] ? $orderDetails['customer_id'] : 0,
                        'message' => $message,
                        'is_admin' => 0,
                        'type' => 'created',
                    );
                    $this->CI->home_model->insert($notificationData, 'pct_order_notifications');
                    $this->CI->order->sendNotification($message, 'created', $orderDetails['customer_id'], 0);
                }
            } else {
                $errors[] = $resultCPL;
            }

            $data = array(
                "errors" => $errors,
                "success" => $success,
            );
            return $data;
        }
    }
}
