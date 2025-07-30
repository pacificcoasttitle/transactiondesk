<?php

(defined('BASEPATH')) or exit('No direct script access allowed');

use phpseclib3\Net\SFTP;

class Cron extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('order/home_model');
        $this->load->model('order/titlePointData');
        $this->load->model('order/productType');
        $this->load->model('order/apiLogs');
        $this->load->library('order/titlepoint');
    }

    public $taxcount = 0;
    public $lvcount = 0;

    public function import_orders_all_users()
    {
        $condition = array(
            'where' => array(
                'status' => 1,
                'is_master' => 0,
                'is_password_updated' => 1,
                'is_sales_rep' => 0,
            ),
        );
        $customers = $this->home_model->get_customers($condition);
        if (!empty($customers)) {
            foreach ($customers as $customer) {
                $this->import_orders($customer);
            }
        }
        echo "All orders synced successfully for all users";exit;
    }

    public function import_orders($user = array())
    {
        $order_status = array('open', 'closed');

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');
        $this->load->library('order/resware');
        $this->load->model('order/apiLogs');

        if ($this->input->post('is_admin') == 1) {
            $id = $this->input->post('id');
            $userdata = $this->home_model->get_user(array('id' => $id));
            $userdata['email'] = $userdata['email_address'];
        } else {
            if (empty($user)) {
                $userdata = $this->session->userdata('user');
            } else {
                $userdata = $user;
                $userdata['email'] = $userdata['email_address'];
            }
        }
        if (isset($userdata) && !empty($userdata)) {
            $msg = '';
            foreach ($order_status as $key => $value) {
                $status = array();
                if ($value == 'closed') {
                    $status['Statuses'][] = array('StatusID' => 9, 'Name' => 'Closed');
                } else {
                    $status['Statuses'][] = array('StatusID' => 2, 'Name' => 'Open');
                }

                $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_orders', env('RESWARE_ORDER_API') . 'files/search', json_encode($status), array(), 0, 0);

                $res = $this->make_request('POST', 'files/search', json_encode($status), $userdata);

                $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_orders', env('RESWARE_ORDER_API') . 'files/search', json_encode($status), $res, 0, $logid);

                $result = json_decode($res, true);

                if (isset($result['Files']) && !empty($result['Files'])) {
                    $this->db->simple_query('SET SESSION group_concat_max_len=150000');
                    $this->db->select('GROUP_CONCAT(file_id) as file_ids');
                    $this->db->from('order_details');
                    $this->db->where('customer_id', $userdata['id']);
                    $this->db->group_by('customer_id');
                    $query = $this->db->get();
                    $filesResult = $query->row_array();

                    if (!empty($filesResult)) {
                        $file_ids = explode(',', $filesResult['file_ids']);
                    } else {
                        $syncFlag = 0;
                    }

                    foreach ($result['Files'] as $res) {
                        $partner_fname = $res['Partners'][0]['PrimaryEmployee']['FirstName'];
                        $partner_lname = $res['Partners'][0]['PrimaryEmployee']['LastName'];
                        $partner_name = $res['Partners'][0]['PartnerName'];
                        if ($partner_name == $userdata['company_name'] && strpos($userdata['last_name'], $partner_lname) !== false && strpos($userdata['first_name'], $partner_fname) !== false) {
                            if (!empty($file_ids)) {
                                if (in_array((int) $res['FileID'], $file_ids)) {
                                    $syncFlag = 1;
                                } else {
                                    $syncFlag = 0;
                                }
                            }

                            if ($syncFlag == 0) {
                                $FullProperty = $res['Properties'][0]['StreetNumber'] . " " . $res['Properties'][0]['StreetDirection'] . " " . $res['Properties'][0]['StreetName'] . " " . $res['Properties'][0]['StreetSuffix'] . ", " . $res['Properties'][0]['City'] . ", " . $res['Properties'][0]['State'] . ", " . $res['Properties'][0]['Zip'];
                                $address = $res['Properties'][0]['StreetNumber'] . " " . $res['Properties'][0]['StreetDirection'] . " " . $res['Properties'][0]['StreetName'] . " " . $res['Properties'][0]['StreetSuffix'];

                                /* get blackkight data */
                                $locale = $res['Properties'][0]['City'];

                                if (($locale)) {
                                    if (!empty($res['Properties'][0]['State'])) {
                                        $locale .= ', ' . $res['Properties'][0]['State'];
                                    } else {
                                        $locale .= ', CA';
                                    }
                                }

                                $property_details = $this->getSearchResult($address, $locale);

                                $property_type = isset($property_details['property_type']) && !empty($property_details['property_type']) ? $property_details['property_type'] : '';
                                $LegalDescription = isset($property_details['legaldescription']) && !empty($property_details['legaldescription']) ? $property_details['legaldescription'] : '';
                                $apn = isset($property_details['apn']) && !empty($property_details['apn']) ? $property_details['apn'] : '';
                                /* get blackkight data */

                                $propertyData = array(
                                    'customer_id' => $userdata['id'],
                                    'buyer_agent_id' => 0,
                                    'listing_agent_id' => 0,
                                    'escrow_lender_id' => 0,
                                    'parcel_id' => $res['Properties'][0]['ParcelID'],
                                    'address' => removeMultipleSpace($address),
                                    'city' => $res['Properties'][0]['City'],
                                    'state' => $res['Properties'][0]['State'],
                                    'zip' => $res['Properties'][0]['Zip'],
                                    'property_type' => $property_type,
                                    'full_address' => removeMultipleSpace($FullProperty),
                                    'apn' => $apn,
                                    'county' => $res['Properties'][0]['County'],
                                    'legal_description' => $LegalDescription,
                                    /*'primary_owner' => $primary_owner,
                                    'secondary_owner' => $SecondaryOwner,*/
                                    // 'additional_details'=> '',
                                    // 'is_imported'=> 1,
                                    'status' => 1,
                                );

                                $transactionData = array(
                                    'customer_id' => $userdata['id'],
                                    'sales_amount' => !empty($res['SalesPrice']) ? $res['SalesPrice'] : 0,
                                    // 'sales_representative' =>  $userdata['id'],
                                    'loan_number' => !empty($res['Loans'][0]['LoanNumber']) ? $res['Loans'][0]['LoanNumber'] : 0,
                                    'loan_amount' => !empty($res['Loans'][0]['LoanAmount']) ? $res['Loans'][0]['LoanAmount'] : 0,
                                    'transaction_type' => $res['TransactionProductType']['TransactionTypeID'],
                                    'purchase_type' => $res['TransactionProductType']['ProductTypeID'],
                                    'status' => 1,
                                );

                                $primary_owner = isset($res['Buyers'][0]['Primary']['First']) && !empty($res['Buyers'][0]['Primary']['First']) ? $res['Buyers'][0]['Primary']['First'] : '';

                                $primary_owner .= isset($res['Buyers'][0]['Primary']['Middle']) && !empty($res['Buyers'][0]['Primary']['Middle']) ? " " . $res['Buyers'][0]['Primary']['Middle'] : '';
                                $primary_owner .= isset($res['Buyers'][0]['Primary']['Last']) && !empty($res['Buyers'][0]['Primary']['Last']) ? " " . $res['Buyers'][0]['Primary']['Last'] : '';

                                $secondary_owner = isset($res['Buyers'][0]['Secondary']['First']) && !empty($res['Buyers'][0]['Secondary']['First']) ? $res['Buyers'][0]['Secondary']['First'] : '';
                                $secondary_owner .= isset($res['Buyers'][0]['Secondary']['Middle']) && !empty($res['Buyers'][0]['Secondary']['Middle']) ? $res['Buyers'][0]['Secondary']['Middle'] : '';
                                $secondary_owner .= isset($res['Buyers'][0]['Secondary']['Last']) && !empty($res['Buyers'][0]['Secondary']['Last']) ? " " . $res['Buyers'][0]['Secondary']['Last'] : '';

                                $ProductTypeTxt = $res['TransactionProductType']['ProductType'];
                                if (strpos($ProductTypeTxt, 'Loan') !== false) {
                                    $propertyData['primary_owner'] = $primary_owner;
                                    $propertyData['secondary_owner'] = $secondary_owner;
                                } elseif (strpos($ProductTypeTxt, 'Sale') !== false) {
                                    $transactionData['borrower'] = $primary_owner;
                                    $transactionData['secondary_borrower'] = $secondary_owner;

                                    $propertyData['primary_owner'] = isset($property_info['primary_owner']) && !empty($property_info['primary_owner']) ? $property_info['primary_owner'] : '';
                                    $propertyData['secondary_owner'] = isset($property_info['secondary_owner']) && !empty($property_info['secondary_owner']) ? $property_info['secondary_owner'] : '';
                                }

                                $propertyId = $this->home_model->insert($propertyData, 'property_details');

                                $transactionId = $this->home_model->insert($transactionData, 'transaction_details');

                                $time = round((int) (str_replace("-0000)/", "", str_replace("/Date(", "", $res['Dates']['OpenedDate']))) / 1000);

                                $created_date = date('Y-m-d H:i:s', $time);

                                $orderData = array(
                                    'customer_id' => $userdata['id'],
                                    'file_id' => $res['FileID'],
                                    'file_number' => $res['FileNumber'],
                                    'property_id' => $propertyId,
                                    'transaction_id' => $transactionId,
                                    'created_at' => $created_date,
                                    'status' => 1,
                                    'is_imported' => 1,
                                    'is_sales_rep_order' => 0,
                                    'resware_status' => strtolower($res['Status']['Name']),
                                );

                                $orderId = $this->home_model->insert($orderData, 'order_details');
                            } else if ($syncFlag == 1) {
                                $orderData = array(
                                    'resware_status' => strtolower($res['Status']['Name']),
                                );
                                $condition = array(
                                    'file_id' => $res['FileID'],
                                    'file_number' => $res['FileNumber'],
                                );

                                $orderId = $this->home_model->update($orderData, $condition, 'order_details');
                            }
                        }

                    }
                    $import_status = 'success';
                    $msg .= "All orders with status " . $value . " synced successfully for user: " . $userdata['email'] . "<br>";
                    // $response = array('status'=>'success','msg'=>"All orders synced successfully for user: ".$userdata['email']);
                } else {
                    // $response = array('status'=>'error','msg'=> "No orders found for user: ".$userdata['email']);

                    $import_status = 'error';
                    $msg .= "No orders found with status " . $value . " for user: " . $userdata['email'] . "<br>";
                }
            } // end foreach
            $response = array('status' => $import_status, 'msg' => $msg);
        } else {
            $response = array('status' => 'error', 'msg' => "User not selected.");
        }

        echo json_encode($response);
    }

    public function make_request($http_method, $endpoint, $body_params = '', $userdata)
    {

        if ($userdata['email'] == 'admin@pct24.com' || (isset($userdata['admin_api']) && $userdata['admin_api'] == 1)) {
            $this->load->library('order/order');
            $credResult = $this->order->get_resware_admin_credential();
            $login = $credResult['username'];
            $password = $credResult['password'];
        } else {
            $login = $userdata['email'];
            $password = $userdata['random_password'];
        }
        $ch = curl_init(env('RESWARE_ORDER_API') . $endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $http_method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body_params);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($body_params))
        );
        $error_msg = curl_error($ch);
        $result = curl_exec($ch);
        return $result;
    }

    public function getSearchResult($address, $locale)
    {
        $data = new stdClass();
        $data->Address = $address;
        $data->LastLine = (string) $locale;
        $data->ClientReference = '<CustCompFilter><CompNum>8</CompNum><MonthsBack>12</MonthsBack></CustCompFilter>';
        $data->OwnerName = '';
        $data->key = env('BLACK_KNIGHT_KEY');
        $data->ReportType = '187';

        $request = 'http://api.sitexdata.com/sitexapi/sitexapi.asmx/AddressSearch?';

        $requestUrl = $request . http_build_query($data);

        $getsortedresults = isset($_GET['getsortedresults']) ? $_GET['getsortedresults'] : 'false';

        $opts = array(
            'http' => array(
                'header' => "User-Agent:MyAgent/1.0\r\n",
            ),
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $context = stream_context_create($opts);
        $file = file_get_contents($requestUrl, false, $context);
        $xmlData = simplexml_load_string($file);
        $response = json_encode($xmlData);
        $result = json_decode($response, true);
        $property_info = array();
        if (isset($result['Status']) && !empty($result['Status']) && $result['Status'] == 'OK') {
            $reportUrl = (isset($result['ReportURL']) && !empty($result['ReportURL'])) ? $result['ReportURL'] : '';

            if ($reportUrl) {
                $rdata = new stdClass();
                $rdata->key = env('BLACK_KNIGHT_KEY');
                $requestUrl = $reportUrl . http_build_query($rdata);
                $reportFile = file_get_contents($requestUrl, false, $context);
                $reportData = simplexml_load_string($reportFile);
                $response = json_encode($reportData);
                $details = json_decode($response, true);

                $property_info['property_type'] = isset($details['PropertyProfile']['PropertyCharacteristics']['UseCode']) && !empty($details['PropertyProfile']['PropertyCharacteristics']['UseCode']) ? $details['PropertyProfile']['PropertyCharacteristics']['UseCode'] : '';
                $property_info['legaldescription'] = isset($details['PropertyProfile']['LegalDescriptionInfo']['LegalBriefDescription']) && !empty($details['PropertyProfile']['LegalDescriptionInfo']['LegalBriefDescription']) ? $details['PropertyProfile']['LegalDescriptionInfo']['LegalBriefDescription'] : '';
                $property_info['apn'] = isset($details['PropertyProfile']['APN']) && !empty($details['PropertyProfile']['APN']) ? $details['PropertyProfile']['APN'] : '';

                $property_info['unit_no'] = isset($details['PropertyProfile']['SiteUnit']) && !empty($details['PropertyProfile']['SiteUnit']) ? $details['PropertyProfile']['SiteUnit'] : '';

                $property_info['fips'] = isset($details['SubjectValueInfo']['FIPS']) && !empty($details['SubjectValueInfo']['FIPS']) ? $details['SubjectValueInfo']['FIPS'] : '';

                $primaryOwner = isset($details['PropertyProfile']['PrimaryOwnerName']) && !empty($details['PropertyProfile']['PrimaryOwnerName']) ? $details['PropertyProfile']['PrimaryOwnerName'] : '';
                $secondaryOwner = isset($details['PropertyProfile']['SecondaryOwnerName']) && !empty($details['PropertyProfile']['SecondaryOwnerName']) ? $details['PropertyProfile']['SecondaryOwnerName'] : '';
                $property_info['primary_owner'] = $primaryOwner;
                $property_info['secondary_owner'] = $secondaryOwner;
            }
        }

        return $property_info;
    }

    public function getTPData($address, $city, $fips)
    {
        $requestParams = array(
            'userID' => env('TP_USERNAME'),
            'password' => env('TP_PASSWORD'),
            'orderNo' => '',
            'customerRef' => '',
            'company' => '',
            'department' => '',
            'titleOfficer' => '',
            'orderComment' => '',
            'starterRemarks' => '',
        );

        $requestParams['serviceType'] = env('SERVICE_TYPE');
        $requestParams['parameters'] = 'Address1=' . $address . ';City=' . $city . ';LvLookup=Address;LvLookupValue=' . $address . ', ' . $city . ';LvReportFormat=LV;IncludeTaxAssessor=true';
        $requestParams['fipsCode'] = $fips;
        $requestUrl = env('TP_CREATE_SERVICE_ENDPOINT');
        $request = $requestUrl . http_build_query($requestParams);

        $opts = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $context = stream_context_create($opts);
        $file = file_get_contents($request, false, $context);

        $xmlData = simplexml_load_string($file);
        $response = json_encode($xmlData);
        $result = json_decode($response, true);
        $responseStatus = isset($result['ReturnStatus']) && !empty($result['ReturnStatus']) ? $result['ReturnStatus'] : '';

        if ($responseStatus == 'Success') {
            $tpData = array();
            $requestId = isset($result['RequestID']) && !empty($result['RequestID']) ? $result['RequestID'] : '';
            if ($requestId) {
                $summary_requestParams = array(
                    'userID' => env('TP_USERNAME'),
                    'password' => env('TP_PASSWORD'),
                    'company' => '',
                    'department' => '',
                    'titleOfficer' => '',
                    'requestId' => $requestId,
                    'maxWaitSeconds' => 20,
                );

                $summary_request = env('TP_REQUEST_SUMMARY_ENDPOINT') . http_build_query($summary_requestParams);

                $context = stream_context_create($opts);
                $summary_file = file_get_contents($summary_request, false, $context);
                $summary_xmlData = simplexml_load_string($summary_file);
                $summary_response = json_encode($summary_xmlData);
                $summary_result = json_decode($summary_response, true);

                $summary_responseStatus = isset($summary_result['ReturnStatus']) && !empty($summary_result['ReturnStatus']) ? $summary_result['ReturnStatus'] : '';
                if ($summary_responseStatus == 'Success') {
                    $resultId = isset($summary_result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail'][0]['ID']) && !empty($summary_result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail'][0]['ID']) ? $summary_result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail'][0]['ID'] : '';
                    $serviceId = isset($summary_result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID']) && !empty($summary_result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID']) ? $summary_result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID'] : '';

                    $tpData['cs4_result_id'] = $resultId;
                    $tpData['cs4_service_id'] = $serviceId;

                    $output_requestParams = array(
                        'userID' => env('TP_USERNAME'),
                        'password' => env('TP_PASSWORD'),
                        'company' => '',
                        'department' => '',
                        'titleOfficer' => '',
                        'resultID' => $resultId,
                    );

                    $output_resultUrl = env('TP_GET_RESULT_BY_ID');

                    $output_request = $output_resultUrl . http_build_query($output_requestParams);
                    $context = stream_context_create($opts);
                    $output_file = file_get_contents($output_request, false, $context);

                    $output_xmlData = simplexml_load_string($output_file);
                    $output_response = json_encode($output_xmlData);
                    $output_result = json_decode($output_response, true);

                    $output_responseStatus = isset($output_result['ReturnStatus']) && !empty($output_result['ReturnStatus']) ? $output_result['ReturnStatus'] : '';
                    if ($output_responseStatus == 'Success') {
                        $briefLegal = isset($output_result['Result']['BriefLegal']) && !empty($output_result['Result']['BriefLegal']) ? $output_result['Result']['BriefLegal'] : 'No data found.';

                        $vesting = isset($output_result['Result']['Vesting']) && !empty($output_result['Result']['Vesting']) ? $output_result['Result']['Vesting'] : 'No data found.';

                        $instrumentNumber = isset($output_result['Result']['LvDeeds']['LegalAndVesting2DeedInfo'][0]['InstrumentNumber']) && !empty($output_result['Result']['LvDeeds']['LegalAndVesting2DeedInfo'][0]['InstrumentNumber']) ? $output_result['Result']['LvDeeds']['LegalAndVesting2DeedInfo'][0]['InstrumentNumber'] : '';
                        $recordedDate = isset($output_result['Result']['LvDeeds']['LegalAndVesting2DeedInfo'][0]['RecordedDate']) && !empty($output_result['Result']['LvDeeds']['LegalAndVesting2DeedInfo'][0]['RecordedDate']) ? $output_result['Result']['LvDeeds']['LegalAndVesting2DeedInfo'][0]['RecordedDate'] : '';

                        $tpData['legal_description'] = $briefLegal;
                        $tpData['vesting_information'] = $vesting;
                        $tpData['cs4_instrument_no'] = $instrumentNumber;
                        $tpData['cs4_recorded_date'] = $recordedDate;
                    }
                }
            }
            $tpData['cs4_request_id'] = $requestId;
        }

        return $tpData;
    }

    public function import_product_types()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');
        if (empty($user)) {
            $userdata = $this->session->userdata('user');
        } else {
            $userdata = $user;
            $userdata['email'] = $userdata['email_address'];
        }

        $counties = array('Alameda', 'Alpine', 'Amador', 'Butte', 'Calaveras', 'Colusa', 'Contra Costa', 'Del Norte', 'El Dorado', 'Fresno', 'Glenn', 'Humboldt', 'Imperial', 'Inyo', 'Kern', 'Kings', 'Lake', 'Lassen', 'Los Angeles', 'Madera', 'Marin', 'Mariposa', 'Mendocino', 'Merced', 'Modoc', 'Mono', 'Monterey', 'Napa', 'Nevada', 'Orange', 'Placer', 'Plumas', 'Riverside', 'Sacramento', 'San Benito', 'San Bernardino', 'San Diego', 'San Francisco', 'San Joaquin', 'San Luis', 'San Mateo', 'Santa Barbara', 'Santa Clara', 'Santa Cruz', 'Shasta', 'Sierra', 'Siskiyou', 'Solano', 'Sonoma', 'Stanislaus', 'Sutter', 'Tehama', 'Trinity', 'Tulare', 'Tuolumne', 'Ventura', 'Yolo', 'Yuba');

        $endPoint = 'types/products';

        if (isset($counties) && !empty($counties)) {
            $insertCount = $updateCount = $rowCount = $notAddCount = 0;
            foreach ($counties as $k => $v) {
                $requestParams = json_encode(array('State' => 'CA', 'County' => $v));

                $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_product_types', env('RESWARE_ORDER_API') . $endPoint, $requestParams, array(), 0, 0);
                $result = $this->make_request('GET', $endPoint, $requestParams, $userdata);

                $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_product_types', env('RESWARE_ORDER_API') . $endPoint, array(), $result, 0, $logid);
                $response = json_decode($result, true);

                if (isset($response) && !empty($response)) {
                    foreach ($response as $key => $value) {
                        $rowCount++;
                        // $display_name = '';
                        $status = 0;
                        /*if((isset($value['ProductTypeID']) && $value['ProductTypeID'] == 19) && (isset($value['TransactionTypeID']) && $value['TransactionTypeID'] == 3))
                        {
                        $display_name = 'Loan: Refinance';
                        $status = 1;
                        }
                        elseif((isset($value['ProductTypeID']) && $value['ProductTypeID'] == 20) && (isset($value['TransactionTypeID']) && $value['TransactionTypeID'] == 3))
                        {
                        $display_name = 'Sales: Purchase';
                        $status = 1;
                        }*/

                        if (isset($value['TransactionTypeID']) && $value['TransactionTypeID'] == 3) {
                            $status = 1;
                        }

                        $data = array(
                            'transaction_type' => trim($value['TransactionType']),
                            'transaction_type_id' => trim($value['TransactionTypeID']),
                            'product_type' => trim($value['ProductType']),
                            'product_type_id' => trim($value['ProductTypeID']),
                            'county' => trim($v),
                            'state' => 'CA',
                            'product_type_id' => trim($value['ProductTypeID']),
                            // 'display_name' => $display_name,
                            'status' => $status,
                        );

                        $con = array(
                            'where' => array(
                                'transaction_type_id' => $value['TransactionTypeID'],
                                'product_type_id' => $value['ProductTypeID'],
                                'county' => $v,
                                'state' => 'CA',
                                'status' => $status,
                            ),
                            'returnType' => 'count',
                        );
                        $prevCount = $this->productType->getProductTypes($con);
                        if ($prevCount > 0) {
                            $condition = array(
                                'transaction_type_id' => $value['TransactionTypeID'],
                                'product_type_id' => $value['ProductTypeID'],
                            );

                            $update = $this->productType->update($data, $condition);
                            if ($update) {
                                $updateCount++;
                            }
                        } else {
                            $insert = $this->productType->insert($data);
                            if ($insert) {
                                $insertCount++;
                            }
                        }
                        $notAddCount = ($rowCount - ($insertCount + $updateCount));
                        $successMsg = 'Product types imported successfully. Total Rows (' . $rowCount . ') | Inserted (' . $insertCount . ') | Updated (' . $updateCount . ') | Not Inserted (' . $notAddCount . ')';
                    }
                }
            }
        }

        echo $successMsg;
    }

    public function check_update_password()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');
        $userdata = $this->session->userdata('admin');
        $this->load->library('order/order');
        if ($this->input->post('new_users') == 1) {
            $condition = array(
                'where' => array(
                    'status' => 1,
                    'is_master' => 0,
                    'is_new_user' => 1,
                ),
            );
        } else {
            $condition = array(
                'where' => array(
                    'status' => 1,
                    'is_master' => 0,
                ),
            );
        }

        $customer_lists = $this->home_model->get_customers($condition);
        $insertCount = $updateCount = $rowCount = $notUpdatePasswordCount = 0;

        if (isset($customer_lists) && !empty($customer_lists)) {

            foreach (array_chunk($customer_lists, 50) as $key => $value) {

                if (isset($value) && !empty($value)) {

                    foreach ($value as $k => $v) {
                        $userdata = $v;
                        $userdata['email'] = $v['email_address'];
                        $condition = array(
                            'id' => $userdata['id'],
                        );

                        if (!empty($v['random_password']) && $v['is_sales_rep'] == 0 && $v['is_title_officer'] == 0) {
                            $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'password_check', env('RESWARE_ORDER_API') . 'me', array(), array(), 0, 0);
                            $result = $this->make_request('GET', 'me', '', $userdata);
                            $this->apiLogs->syncLogs($userdata['id'], 'resware', 'password_check', env('RESWARE_ORDER_API') . 'me', array(), $result, 0, $logid);

                            if (isset($result) && !empty($result)) {
                                $response = json_decode($result, true);

                                if (isset($response['Me']) && !empty($response['Me'])) {
                                    $customerData = array(
                                        'is_password_updated' => 1,
                                        'resware_error_msg' => null,
                                    );
                                    $update = $this->home_model->update($customerData, $condition, 'customer_basic_details');
                                    /** Save user Activity */
                                    $activity = 'From Cron - check_update_password : Password updated status 1 for :- ' . $v['email_address'];
                                    $this->order->logAdminActivity($activity);
                                    /** End Save user activity */
                                    if ($update) {
                                        $updateCount++;
                                    }
                                } else {
                                    if ($v['is_sales_rep'] == 0 && $v['is_title_officer'] == 0) {
                                        $customerData = array(
                                            'is_password_updated' => 0,
                                        );
                                        $update = $this->home_model->update($customerData, $condition, 'customer_basic_details');
                                        /** Save user Activity */
                                        $activity = 'From Cron - check_update_password : Password updated status 0 for :- ' . $v['email_address'];
                                        $this->order->logAdminActivity($activity);
                                        /** End Save user activity */
                                        $notUpdatePasswordCount++;
                                    }
                                }
                            } else {
                                if ($v['is_sales_rep'] == 0 && $v['is_title_officer'] == 0) {
                                    $customerData = array(
                                        'is_password_updated' => 0,
                                    );
                                    $update = $this->home_model->update($customerData, $condition, 'customer_basic_details');
                                    /** Save user Activity */
                                    $activity = 'From Cron - check_update_password : Password updated status 0 for :- ' . $v['email_address'];
                                    $this->order->logAdminActivity($activity);
                                    /** End Save user activity */
                                    $notUpdatePasswordCount++;
                                }
                            }
                        } else {
                            if ($v['is_sales_rep'] == 0 && $v['is_title_officer'] == 0) {
                                $customerData = array(
                                    'is_password_updated' => 0,
                                );
                                $update = $this->home_model->update($customerData, $condition, 'customer_basic_details');
                                /** Save user Activity */
                                $activity = 'From Cron - check_update_password : Password updated status 0 for :- ' . $v['email_address'];
                                $this->order->logAdminActivity($activity);
                                /** End Save user activity */
                                $notUpdatePasswordCount++;
                            }
                        }
                    }
                    $successMsg = 'Password updated successfully. Total Rows (' . $rowCount . ') | Updated (' . $updateCount . ') | NotUpdated (' . $notUpdatePasswordCount . ')';
                }
            }
            $data = array('status' => 'success', 'msg' => $successMsg);
            echo json_encode($data);
        } else {
            $data = array('status' => 'success', 'msg' => 'No records found for credential check.');
            echo json_encode($data);
        }
    }

    public function update_user_details()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');

        $condition = array(
            'where' => array(
                'status' => 1,
                'is_master' => 0,
            ),
        );

        $customer_lists = $this->home_model->get_customers($condition);

        $updateCount = $rowCount = $notAddCount = 0;
        if (isset($customer_lists) && !empty($customer_lists)) {

            foreach (array_chunk($customer_lists, 50) as $key => $value) {
                if (isset($value) && !empty($value)) {
                    foreach ($value as $k => $v) {
                        $rowCount++;
                        $userdata = $v;
                        $userdata['email'] = $v['email_address'];
                        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'update_user_partner_id', env('RESWARE_ORDER_API') . 'me', $userdata, array(), 0, 0);

                        $result = $this->make_request('GET', 'me?IncludeCompany=true', '', $userdata);

                        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'validate_user', env('RESWARE_ORDER_API') . 'me', array(), $result, 0, $logid);

                        if (isset($result) && !empty($result)) {
                            $response = json_decode($result, true);

                            if (isset($response['Me']) && !empty($response['Me'])) {
                                $condition = array(
                                    'id' => $userdata['id'],
                                );
                                $userId = isset($response['Me']['UserID']) && !empty($response['Me']['UserID']) ? $response['Me']['UserID'] : '';
                                $partnerId = isset($response['MyCompany']['PartnerID']) && !empty($response['MyCompany']['PartnerID']) ? $response['MyCompany']['PartnerID'] : '';
                                $customerData = array(
                                    'resware_user_id' => $userId,
                                    'partner_id' => $partnerId,
                                );

                                $update = $this->home_model->update($customerData, $condition, 'customer_basic_details');
                                if ($update) {
                                    $updateCount++;
                                }
                                $successMsg = 'Password updated successfully. Total Rows (' . $rowCount . ') | Updated (' . $updateCount . ')';
                            }
                        }
                    }
                }

            }

            echo $successMsg;
        }
    }

    public function updatePassword()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');
        $this->load->library('order/order');
        $userdata = $this->session->userdata('admin');
        $condition = array(
            'where' => array(
                'status' => 1,
                'is_master' => 0,
                'is_password_updated' => 0,
            ),
        );
        $customer_lists = $this->home_model->get_customers($condition);
        $updatePasswordCount = $notUpdatePasswordCount = 0;

        if (isset($customer_lists) && !empty($customer_lists)) {

            foreach (array_chunk($customer_lists, 50) as $key => $value) {

                if (isset($value) && !empty($value)) {

                    foreach ($value as $k => $v) {

                        if (!empty($v['resware_user_id']) && !empty($v['partner_id']) && !empty($v['email_address'])) {
                            $endPoint = 'admin/partners/' . $v['partner_id'] . '/employees/' . $v['resware_user_id'];
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
                                'Name' => $v['email_address'],
                                'PasswordExpirationDate' => '/Date(3025656585000-0000)/',
                                'FirstName' => $v['first_name'],
                                'LastName' => $v['last_name'],
                                'ContactInformation' => array(
                                    'EmailAddress' => $v['email_address'],
                                ),
                            );
                            $userdata['email'] = $userdata['email_address'];
                            $userUpdateData = json_encode($userUpdateData);

                            $logid = $this->apiLogs->syncLogs($v['id'], 'resware', 'update_password', env('RESWARE_ORDER_API') . $endPoint, $userUpdateData, array(), 0, 0);
                            $result = $this->make_request('PUT', $endPoint, $userUpdateData, $userdata);
                            $this->apiLogs->syncLogs($v['id'], 'resware', 'update_password', env('RESWARE_ORDER_API') . $endPoint, $userUpdateData, $result, 0, $logid);
                            if (isset($result) && !empty($result)) {
                                $response = json_decode($result, true);

                                if (isset($response['Employee']) && !empty($response['Employee'])) {
                                    $random_password = $this->order->randomPassword();
                                    $condition = array(
                                        'id' => $v['id'],
                                    );
                                    $customerData = array(
                                        'is_password_updated' => 0,
                                        'random_password' => $random_password,
                                        'password' => md5('Pacific1'),
                                    );
                                    $update = $this->home_model->update($customerData, $condition, 'customer_basic_details');

                                    if ($update) {
                                        $updatePasswordCount++;
                                    }
                                } else {
                                    $notUpdatePasswordCount++;
                                }
                            } else {
                                $notUpdatePasswordCount++;
                            }
                            $successMsg = 'Password updated successfully. Updated (' . $updatePasswordCount . ') | Not Updated (' . $notUpdatePasswordCount . ')';
                        }
                    }
                }
            }
            echo $successMsg;
        }
    }

    public function getCompanyInformation()
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');
        $userdata = $this->session->userdata('admin');
        $this->db->select('company_name, partner_id');
        $this->db->group_by('company_name,partner_id');
        $customer_lists = $this->db->get('customer_basic_details')->result_array();
        $insertedPartnerInfo = $updatedPartnerInfo = $notInsertedPartnerInfo = 0;

        if (isset($customer_lists) && !empty($customer_lists)) {
            foreach (array_chunk($customer_lists, 50) as $key => $value) {
                if (isset($value) && !empty($value)) {
                    foreach ($value as $k => $v) {
                        if (!empty($v['company_name']) && !empty($v['partner_id'])) {
                            $endPoint = 'admin/partners/' . $v['partner_id'];
                            $userdata['email'] = $userdata['email_address'];
                            $logid = $this->apiLogs->syncLogs($v['id'], 'resware', 'get_partner_information', env('RESWARE_ORDER_API') . $endPoint, array(), array(), 0, 0);
                            $result = $this->make_request('GET', $endPoint, array(), $userdata);
                            $this->apiLogs->syncLogs($v['id'], 'resware', 'get_partner_information', env('RESWARE_ORDER_API') . $endPoint, array(), $result, 0, $logid);

                            if (isset($result) && !empty($result)) {
                                $response = json_decode($result, true);

                                if (isset($response['AdminPartner']) && !empty($response['AdminPartner'])) {

                                    $con = array(
                                        'where' => array(
                                            'partner_id' => trim($response['AdminPartner']['PartnerCompanyID']),
                                        ),
                                        'returnType' => 'count',
                                    );
                                    $prevCount = $this->home_model->get_company_rows($con);

                                    $partnerTyepIds = array();
                                    if (!empty($response['AdminPartner']['PartnerTypes'])) {
                                        foreach ($response['AdminPartner']['PartnerTypes'] as $partnerType) {
                                            $partnerTyepIds[] = $partnerType['PartnerTypeID'];
                                        }
                                    }

                                    if ($prevCount > 0) {
                                        $customerData = array(
                                            'partner_name' => trim($response['AdminPartner']['PartnerName']),
                                            'email' => !empty($response['AdminPartner']['ContactInformation']['EmailAddress']) ? $response['AdminPartner']['ContactInformation']['EmailAddress'] : null,
                                            'address1' => trim($response['AdminPartner']['MailingAddress']['Address1']),
                                            'city' => trim($response['AdminPartner']['MailingAddress']['City']),
                                            'state' => trim($response['AdminPartner']['MailingAddress']['State']),
                                            'zip' => trim($response['AdminPartner']['MailingAddress']['Zip']),
                                            'partner_type_id' => implode(",", $partnerTyepIds),
                                        );
                                        $condition = array('partner_id' => trim($response['AdminPartner']['PartnerCompanyID']));
                                        $update = $this->home_model->update($customerData, $condition, 'pct_order_partner_company_info');

                                        if ($update) {
                                            $updatedPartnerInfo++;
                                        }
                                    } else {
                                        $customerData = array(
                                            'partner_id' => trim($response['AdminPartner']['PartnerCompanyID']),
                                            'email' => !empty($response['AdminPartner']['ContactInformation']['EmailAddress']) ? $response['AdminPartner']['ContactInformation']['EmailAddress'] : null,
                                            'partner_name' => trim($response['AdminPartner']['PartnerName']),
                                            'address1' => trim($response['AdminPartner']['MailingAddress']['Address1']),
                                            'city' => trim($response['AdminPartner']['MailingAddress']['City']),
                                            'state' => trim($response['AdminPartner']['MailingAddress']['State']),
                                            'zip' => trim($response['AdminPartner']['MailingAddress']['Zip']),
                                            'partner_type_id' => implode(",", $partnerTyepIds),
                                        );
                                        $insert = $this->home_model->insert($customerData, 'pct_order_partner_company_info');

                                        if ($insert) {
                                            $insertedPartnerInfo++;
                                        }
                                    }
                                } else {
                                    $notInsertedPartnerInfo++;
                                }
                            } else {
                                $notInsertedPartnerInfo++;
                            }
                            $successMsg = 'Partner Information updated successfully. Inserted (' . $insertedPartnerInfo . ') | Updated (' . $updatedPartnerInfo . ') | Not Inserted (' . $notInsertedPartnerInfo . ')';
                        }
                    }
                }
            }
        }

        $agents_lists = $this->db->get('agents')->result_array();
        if (isset($agents_lists) && !empty($agents_lists)) {
            foreach (array_chunk($agents_lists, 50) as $key => $value) {
                if (isset($value) && !empty($value)) {
                    foreach ($value as $k => $v) {
                        if (!empty($v['company']) && !empty($v['partner_id'])) {
                            $endPoint = 'admin/partners/' . $v['partner_id'];
                            $userdata['email'] = $userdata['email_address'];
                            $logid = $this->apiLogs->syncLogs($v['id'], 'resware', 'get_partner_information', env('RESWARE_ORDER_API') . $endPoint, array(), array(), 0, 0);
                            $result = $this->make_request('GET', $endPoint, array(), $userdata);
                            $this->apiLogs->syncLogs($v['id'], 'resware', 'get_partner_information', env('RESWARE_ORDER_API') . $endPoint, array(), $result, 0, $logid);

                            if (isset($result) && !empty($result)) {
                                $response = json_decode($result, true);
                                if (isset($response['AdminPartner']) && !empty($response['AdminPartner'])) {
                                    $con = array(
                                        'where' => array(
                                            'partner_id' => trim($response['AdminPartner']['PartnerCompanyID']),
                                        ),
                                        'returnType' => 'count',
                                    );
                                    $prevCount = $this->home_model->get_company_rows($con);

                                    $partnerTyepIds = array();
                                    if (!empty($response['AdminPartner']['PartnerTypes'])) {
                                        foreach ($response['AdminPartner']['PartnerTypes'] as $partnerType) {
                                            $partnerTyepIds[] = $partnerType['PartnerTypeID'];
                                        }
                                    }

                                    if ($prevCount > 0) {
                                        $customerData = array(
                                            'partner_name' => trim($response['AdminPartner']['PartnerName']),
                                            'email' => !empty($response['AdminPartner']['ContactInformation']['EmailAddress']) ? $response['AdminPartner']['ContactInformation']['EmailAddress'] : null,
                                            'address1' => trim($response['AdminPartner']['MailingAddress']['Address1']),
                                            'city' => trim($response['AdminPartner']['MailingAddress']['City']),
                                            'state' => trim($response['AdminPartner']['MailingAddress']['State']),
                                            'zip' => trim($response['AdminPartner']['MailingAddress']['Zip']),
                                            'partner_type_id' => implode(",", $partnerTyepIds),
                                        );
                                        $condition = array('partner_id' => trim($response['AdminPartner']['PartnerCompanyID']));
                                        $update = $this->home_model->update($customerData, $condition, 'pct_order_partner_company_info');

                                        if ($update) {
                                            $updatedPartnerInfo++;
                                        }
                                    } else {
                                        $customerData = array(
                                            'partner_id' => trim($response['AdminPartner']['PartnerCompanyID']),
                                            'email' => !empty($response['AdminPartner']['ContactInformation']['EmailAddress']) ? $response['AdminPartner']['ContactInformation']['EmailAddress'] : null,
                                            'partner_name' => trim($response['AdminPartner']['PartnerName']),
                                            'address1' => trim($response['AdminPartner']['MailingAddress']['Address1']),
                                            'city' => trim($response['AdminPartner']['MailingAddress']['City']),
                                            'state' => trim($response['AdminPartner']['MailingAddress']['State']),
                                            'zip' => trim($response['AdminPartner']['MailingAddress']['Zip']),
                                            'partner_type_id' => implode(",", $partnerTyepIds),
                                        );
                                        $insert = $this->home_model->insert($customerData, 'pct_order_partner_company_info');

                                        if ($insert) {
                                            $insertedPartnerInfo++;
                                        }
                                    }
                                } else {
                                    $notInsertedPartnerInfo++;
                                }
                            } else {
                                $notInsertedPartnerInfo++;
                            }
                            $successMsg = 'Partner Information updated successfully. Inserted (' . $insertedPartnerInfo . ') | Updated (' . $updatedPartnerInfo . ') | Not Inserted (' . $notInsertedPartnerInfo . ')';
                        }
                    }
                }
            }
            $data = array('status' => 'success', 'msg' => $successMsg);
            echo json_encode($data);exit;
        }
    }

    public function updateRemoteFileNumberForAllOrders()
    {
        $condition = array(
            'where' => array(
                'status' => 1,
                'is_master' => 0,
            ),
        );
        $customers = $this->home_model->get_customers($condition);
        $this->db->select('*');
        $this->db->from('order_details');
        $this->db->where('is_imported', 0);
        $query = $this->db->get();
        $orderDetails = $query->result_array();

        if (!empty($orderDetails)) {
            foreach ($orderDetails as $orderDetail) {
                $key = array_search($orderDetail['customer_id'], array_column($customers, 'id'));
                $userdata['email'] = 'admin@pct24.com';
                $remoteFileNumberData = json_encode(array('RemoteFileNumber' => $orderDetail['file_number']));
                $remoteFileEndPoint = 'files/' . $orderDetail['file_id'] . '/partners/' . $customers[$key]['partner_id'];
                $logid = $this->apiLogs->syncLogs($orderDetail['customer_id'], 'resware', 'remote_file_number', env('RESWARE_ORDER_API') . $remoteFileEndPoint, $remoteFileNumberData, array(), 0, 0);
                $resultRemotePartner = $this->make_request('PUT', $remoteFileEndPoint, $remoteFileNumberData, $userdata);
                $this->apiLogs->syncLogs($orderDetail['customer_id'], 'resware', 'remote_file_number', env('RESWARE_ORDER_API') . $remoteFileEndPoint, $remoteFileNumberData, $resultRemotePartner, 0, $logid);

            }
            echo "Updated remote file number for all orders";exit;
        } else {
            echo "No orders found to update remote file number";exit;
        }
    }

    public function import_all_sales_rep_orders()
    {
        // $order_status = $this->uri->segment(2);

        $condition = array(
            'where' => array(
                'status' => 1,
                'is_sales_rep' => 1,
            ),
        );

        $customer_lists = $this->home_model->get_customers($condition);

        if (isset($customer_lists) && !empty($customer_lists)) {
            foreach ($customer_lists as $key => $salesRep) {
                $this->import_sales_rep_orders($salesRep);
            }
        }

    }

    public function import_sales_rep_orders($salesRep = array())
    {
        // $order_status = $this->uri->segment(2);
        $order_status = array('open', 'closed');

        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');

        $this->load->library('order/resware');
        $this->load->model('order/apiLogs');

        if (empty($salesRep)) {
            $userdata = $this->session->userdata('user');

        } else {
            $userdata = $salesRep;
            $userdata['email'] = $userdata['email_address'];
        }

        if (isset($userdata) && !empty($userdata)) {
            /* Fetch records from resware */
            foreach ($order_status as $key => $value) {
                $status = array();
                if ($value == 'closed') {
                    $status['Statuses'][] = array('StatusID' => 9, 'Name' => 'Closed');
                } else {
                    $status['Statuses'][] = array('StatusID' => 2, 'Name' => 'Open');
                }

                $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_sales_rep_orders', env('RESWARE_ORDER_API') . 'files/search', json_encode($status), array(), 0, 0);

                $res = $this->make_request('POST', 'files/search', json_encode($status), $userdata);

                $this->apiLogs->syncLogs($userdata['id'], 'resware', 'get_sales_rep_orders', env('RESWARE_ORDER_API') . 'files/search', json_encode($status), $res, 0, $logid);

                $result = json_decode($res, true);

                if (isset($result['Files']) && !empty($result['Files'])) {
                    $this->db->simple_query('SET SESSION group_concat_max_len=150000');
                    $this->db->select('GROUP_CONCAT(file_id) as file_ids');
                    $this->db->from('order_details');
                    $this->db->where('transaction_details.sales_representative', $userdata['id']);
                    $this->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id', 'left');
                    $this->db->group_by('transaction_details.sales_representative');
                    $query = $this->db->get();

                    $filesResult = $query->row_array();

                    if (!empty($filesResult)) {
                        $file_ids = explode(',', $filesResult['file_ids']);
                    } else {
                        $syncFlag = 0;
                    }
                    foreach ($result['Files'] as $res) {
                        if (!empty($file_ids)) {
                            if (in_array((int) $res['FileID'], $file_ids)) {
                                $syncFlag = 1;
                            } else {
                                $syncFlag = 0;
                            }
                        }

                        if ($syncFlag == 0) {
                            $FullProperty = $res['Properties'][0]['StreetNumber'] . " " . $res['Properties'][0]['StreetDirection'] . " " . $res['Properties'][0]['StreetName'] . " " . $res['Properties'][0]['StreetSuffix'] . ", " . $res['Properties'][0]['City'] . ", " . $res['Properties'][0]['State'] . ", " . $res['Properties'][0]['Zip'];
                            $address = $res['Properties'][0]['StreetNumber'] . " " . $res['Properties'][0]['StreetDirection'] . " " . $res['Properties'][0]['StreetName'] . " " . $res['Properties'][0]['StreetSuffix'];

                            /* get blackkight data */
                            $locale = $res['Properties'][0]['City'];

                            if (($locale)) {
                                if (!empty($res['Properties'][0]['State'])) {
                                    $locale .= ', ' . $res['Properties'][0]['State'];
                                } else {
                                    $locale .= ', CA';
                                }
                            }

                            $property_details = $this->getSearchResult($address, $locale);

                            $property_type = isset($property_details['property_type']) && !empty($property_details['property_type']) ? $property_details['property_type'] : '';
                            $LegalDescription = isset($property_details['legaldescription']) && !empty($property_details['legaldescription']) ? $property_details['legaldescription'] : '';
                            $apn = isset($property_details['apn']) && !empty($property_details['apn']) ? $property_details['apn'] : '';
                            /* get blackkight data */

                            $propertyData = array(
                                'customer_id' => 0,
                                'buyer_agent_id' => 0,
                                'listing_agent_id' => 0,
                                'escrow_lender_id' => 0,
                                'parcel_id' => $res['Properties'][0]['ParcelID'],
                                'address' => removeMultipleSpace($address),
                                'city' => $res['Properties'][0]['City'],
                                'state' => $res['Properties'][0]['State'],
                                'zip' => $res['Properties'][0]['Zip'],
                                'property_type' => $property_type,
                                'full_address' => removeMultipleSpace($FullProperty),
                                'apn' => $apn,
                                'county' => $res['Properties'][0]['County'],
                                'legal_description' => $LegalDescription,
                                /*'primary_owner' => $primary_owner,
                                'secondary_owner' => $SecondaryOwner,*/
                                // 'additional_details'=> '',
                                // 'is_imported'=> 1,
                                'status' => 1,
                            );

                            $transactionData = array(
                                'customer_id' => 0,
                                'sales_amount' => !empty($res['SalesPrice']) ? $res['SalesPrice'] : 0,
                                'sales_representative' => $userdata['id'],
                                'loan_number' => !empty($res['Loans'][0]['LoanNumber']) ? $res['Loans'][0]['LoanNumber'] : 0,
                                'loan_amount' => !empty($res['Loans'][0]['LoanAmount']) ? $res['Loans'][0]['LoanAmount'] : 0,
                                'transaction_type' => $res['TransactionProductType']['TransactionTypeID'],
                                'purchase_type' => $res['TransactionProductType']['ProductTypeID'],
                                'status' => 1,
                            );

                            $primary_owner = isset($res['Buyers'][0]['Primary']['First']) && !empty($res['Buyers'][0]['Primary']['First']) ? $res['Buyers'][0]['Primary']['First'] : '';

                            $primary_owner .= isset($res['Buyers'][0]['Primary']['Middle']) && !empty($res['Buyers'][0]['Primary']['Middle']) ? " " . $res['Buyers'][0]['Primary']['Middle'] : '';
                            $primary_owner .= isset($res['Buyers'][0]['Primary']['Last']) && !empty($res['Buyers'][0]['Primary']['Last']) ? " " . $res['Buyers'][0]['Primary']['Last'] : '';

                            $secondary_owner = isset($res['Buyers'][0]['Secondary']['First']) && !empty($res['Buyers'][0]['Secondary']['First']) ? $res['Buyers'][0]['Secondary']['First'] : '';
                            $secondary_owner .= isset($res['Buyers'][0]['Secondary']['Middle']) && !empty($res['Buyers'][0]['Secondary']['Middle']) ? $res['Buyers'][0]['Secondary']['Middle'] : '';
                            $secondary_owner .= isset($res['Buyers'][0]['Secondary']['Last']) && !empty($res['Buyers'][0]['Secondary']['Last']) ? " " . $res['Buyers'][0]['Secondary']['Last'] : '';

                            $ProductTypeTxt = $res['TransactionProductType']['ProductType'];
                            if (strpos($ProductTypeTxt, 'Loan') !== false) {
                                $propertyData['primary_owner'] = $primary_owner;
                                $propertyData['secondary_owner'] = $secondary_owner;
                            } elseif (strpos($ProductTypeTxt, 'Sale') !== false) {
                                $transactionData['borrower'] = $primary_owner;
                                $transactionData['secondary_borrower'] = $secondary_owner;

                                $propertyData['primary_owner'] = isset($property_info['primary_owner']) && !empty($property_info['primary_owner']) ? $property_info['primary_owner'] : '';
                                $propertyData['secondary_owner'] = isset($property_info['secondary_owner']) && !empty($property_info['secondary_owner']) ? $property_info['secondary_owner'] : '';
                            }

                            $propertyId = $this->home_model->insert($propertyData, 'property_details');

                            $transactionId = $this->home_model->insert($transactionData, 'transaction_details');

                            $time = round((int) (str_replace("-0000)/", "", str_replace("/Date(", "", $res['Dates']['OpenedDate']))) / 1000);

                            $created_date = date('Y-m-d H:i:s', $time);

                            $orderData = array(
                                'customer_id' => 0,
                                'file_id' => $res['FileID'],
                                'file_number' => $res['FileNumber'],
                                'property_id' => $propertyId,
                                'transaction_id' => $transactionId,
                                'created_at' => $created_date,
                                'status' => 1,
                                'is_imported' => 1,
                                'is_sales_rep_order' => 1,
                                'resware_status' => strtolower($res['Status']['Name']),
                            );

                            $orderId = $this->home_model->insert($orderData, 'order_details');

                            /* TP call */
                            $random_number = time() + (floor(rand() * (10000 - 1 + 1)) + 1);

                            $propertyData['fipsCode'] = isset($property_details['fips']) && !empty($property_details['fips']) ? $property_details['fips'] : '';

                            $propertyData['address'] = $address;

                            $propertyData['city'] = isset($res['Properties'][0]['City']) && !empty($res['Properties'][0]['City']) ? $res['Properties'][0]['City'] : '';

                            $propertyData['unit_no'] = isset($property_details['unit_no']) && !empty($property_details['unit_no']) ? $property_details['unit_no'] : '';
                            $propertyData['apn'] = $apn;

                            $propertyData['state'] = isset($res['Properties'][0]['State']) && !empty($res['Properties'][0]['State']) ? $res['Properties'][0]['State'] : '';
                            $propertyData['county'] = isset($res['Properties'][0]['County']) && !empty($res['Properties'][0]['County']) ? $res['Properties'][0]['County'] : '';

                            $this->tpCreateService(4, $random_number, $propertyData, $userdata);

                            $this->tpCreateService(3, $random_number, $propertyData, $userdata);

                            $session_id = 'tp_api_id_' . $random_number;

                            $tp_condition = array(
                                'session_id' => $session_id,
                            );

                            $tpData = array(
                                'file_id' => $res['FileID'],
                                'file_number' => $res['FileNumber'],
                            );

                            $this->titlePointData->update($tpData, $tp_condition);

                            $titlePointDetails = $this->titlePointData->gettitlePointDetails($tp_condition);

                            $serviceId = isset($titlePointDetails['cs4_service_id']) && !empty($titlePointDetails['cs4_service_id']) ? $titlePointDetails['cs4_service_id'] : '';
                            $this->titlepoint->generateImg($serviceId, $res['FileNumber'], $orderId);
                            $instrumentNumber = isset($titlePointDetails['cs4_instrument_no']) && !empty($titlePointDetails['cs4_instrument_no']) ? $titlePointDetails['cs4_instrument_no'] : '';

                            $recordedDate = isset($titlePointDetails['cs4_recorded_date']) && !empty($titlePointDetails['cs4_recorded_date']) ? $titlePointDetails['cs4_recorded_date'] : '';
                            $fips = isset($titlePointDetails['fips']) && !empty($titlePointDetails['fips']) ? $titlePointDetails['fips'] : '';

                            $this->titlepoint->generateGrantDeed($instrumentNumber, $recordedDate, $fips, $res['FileNumber'], $orderId);

                            $tax_serviceId = isset($titlePointDetails['cs3_service_id']) && !empty($titlePointDetails['cs3_service_id']) ? $titlePointDetails['cs3_service_id'] : '';

                            $this->titlepoint->generateTaxDoc($tax_serviceId, $res['FileNumber'], $orderId);

                            /* TP call */
                        } else if ($syncFlag == 1) {
                            $orderData = array(
                                'resware_status' => strtolower($res['Status']['Name']),
                            );
                            $condition = array(
                                'file_id' => $res['FileID'],
                                'file_number' => $res['FileNumber'],
                            );

                            $orderId = $this->home_model->update($orderData, $condition, 'order_details');
                        }
                    }
                    // echo "All orders with status ".$value." synced successfully for user: ".$userdata['email']."<br>";
                    $response[$value] = array('status' => 'success', 'msg' => "All orders with status " . $value . " synced successfully for user: " . $userdata['email'] . "<br>");
                } else {
                    // echo "No orders found for user: ".$userdata['email']."<br>";
                    $response[$value] = array('status' => 'error', 'msg' => "No orders found with status " . $value . " for user: " . $userdata['email'] . "<br>");
                }
            }

            echo json_encode($response);
            /* Fetch records from resware */
        }
    }

    public function tpCreateService($methodId, $random_number, $propertyData = array(), $userdata = array())
    {
        /* Insert into table */
        if ($random_number) {
            $session_id = 'tp_api_id_' . $random_number;

            $con = array(
                'where' => array(
                    'session_id' => $session_id,
                ),
                'returnType' => 'count',
            );

            $prevCount = $this->titlePointData->gettitlePointDetails($con);
            if ($prevCount < 0) {
                $tpData = array(
                    'session_id' => $session_id,
                );
                $tpId = $this->titlePointData->insert($tpData);
            }

        }
        /* Insert into table */

        $requestParams = array(
            'userID' => env('TP_USERNAME'),
            'password' => env('TP_PASSWORD'),
            'orderNo' => '',
            'customerRef' => '',
            'company' => '',
            'department' => '',
            'titleOfficer' => '',
            'orderComment' => '',
            'starterRemarks' => '',
        );

        if ($methodId == 4) {
            $fipsCode = isset($propertyData['fipsCode']) && !empty($propertyData['fipsCode']) ? $propertyData['fipsCode'] : '';
            $address = isset($propertyData['address']) && !empty($propertyData['address']) ? $propertyData['address'] : '';
            $city = isset($propertyData['city']) && !empty($propertyData['city']) ? $propertyData['city'] : '';
            $unit_no = isset($propertyData['unit_no']) && !empty($propertyData['unit_no']) ? $propertyData['unit_no'] : '';
            $apn = isset($propertyData['apn']) && !empty($propertyData['apn']) ? $propertyData['apn'] : '';
            if ($unit_no) {
                $unitinfo = 'UnitNumber ' . $unit_no . ', ';
            }

            $requestParams['serviceType'] = env('SERVICE_TYPE');

            $requestParams['parameters'] = 'Address1=' . $address . ';City=' . $city . ';Pin=' . $apn . ';LvLookup=Address;LvLookupValue=' . $address . ', ' . $unitinfo . $city . ';LvReportFormat=LV;IncludeTaxAssessor=true';
            $requestParams['fipsCode'] = $fipsCode;
            $requestUrl = env('TP_CREATE_SERVICE_ENDPOINT');
            $request_type = 'sales_create_service_4';
        } else if ($methodId == 3) {
            $apn = isset($propertyData['apn']) && !empty($propertyData['apn']) ? $propertyData['apn'] : '';
            $apn = str_replace('0000', '0-000', $apn);

            $state = isset($propertyData['state']) && !empty($propertyData['state']) ? $propertyData['state'] : '';

            $county = isset($propertyData['county']) && !empty($propertyData['county']) ? $propertyData['county'] : '';

            $requestParams['serviceType'] = env('TAX_SEARCH_SERVICE_TYPE');

            $requestParams['parameters'] = 'Tax.APN=' . $apn . ';General.AutoSearchTaxes=true;General.AutoSearchProperty=false';
            $requestParams['state'] = $state;
            $requestParams['county'] = $county;
            $requestUrl = env('TP_TAX_INSTRUMENT_CREATE_SERVICE_ENDPOINT');
            $request_type = 'sales_create_service_3';
        }
        $request = $requestUrl . http_build_query($requestParams);

        $logid = $this->apiLogs->syncLogs(0, 'titlepoint', $request_type, $request, $requestParams, array(), $random_number, 0);

        $opts = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );

        $context = stream_context_create($opts);
        $file = file_get_contents($request, false, $context);

        $xmlData = simplexml_load_string($file);
        $response = json_encode($xmlData);
        $result = json_decode($response, true);

        $this->apiLogs->syncLogs(0, 'titlepoint', $request_type, $request, $requestParams, $result, $random_number, $logid);
        $session_id = 'tp_api_id_' . $random_number;
        $con = array(
            'where' => array(
                'session_id' => $session_id,
            ),
            'returnType' => 'count',
        );
        $prevCount = $this->titlePointData->gettitlePointDetails($con);

        if (isset($result) && empty($result)) {
            $tpData = array(
                'cs4_message' => 'Failed',
            );

            if ($prevCount > 0) {
                $session_id = 'tp_api_id_' . $random_number;
                $condition = array(
                    'session_id' => $session_id,
                );
                $this->titlePointData->update($tpData, $condition);
            } else {
                $tpData['session_id'] = 'tp_api_id_' . $random_number;

                $tpId = $this->titlePointData->insert($tpData);
            }
        } else {

            $responseStatus = isset($result['ReturnStatus']) && !empty($result['ReturnStatus']) ? $result['ReturnStatus'] : '';

            if ($methodId == 4) {
                if ($responseStatus == 'Success') {
                    $requestId = isset($result['RequestID']) && !empty($result['RequestID']) ? $result['RequestID'] : '';
                    $tpData = array(
                        'cs4_request_id' => $requestId,
                    );

                    if ($prevCount > 0) {
                        $condition = array(
                            'session_id' => $session_id,
                        );
                        $this->titlePointData->update($tpData, $condition);
                    } else {
                        $tpData['session_id'] = 'tp_api_id_' . $random_number;

                        $tpId = $this->titlePointData->insert($tpData);
                    }

                    /* Get Request Summary */
                    $response = $this->tpGetRequestSummaries(4, $requestId, $random_number);
                    /* Get Request Summary */
                } else {
                    $error = isset($result['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($result['ReturnErrors']['ReturnError']['ErrorDescription']) ? $result['ReturnErrors']['ReturnError']['ErrorDescription'] : '';
                    $this->addLogs($methodId, $responseStatus, '', $error, $random_number);
                }

            }
            if ($methodId == 3) {
                if ($responseStatus == 'Success') {
                    $requestId = isset($result['RequestID']) && !empty($result['RequestID']) ? $result['RequestID'] : '';

                    $tpData = array(
                        'cs3_request_id' => $requestId,
                    );

                    if ($prevCount > 0) {
                        $condition = array(
                            'session_id' => $session_id,
                        );
                        $this->titlePointData->update($tpData, $condition);
                    } else {
                        $tpData['session_id'] = 'tp_api_id_' . $random_number;

                        $tpId = $this->titlePointData->insert($tpData);
                    }

                    /* Get Request Summary */
                    $response = $this->tpGetRequestSummaries(3, $requestId, $random_number);
                    /* Get Request Summary */
                } else {
                    $error = isset($result['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($result['ReturnErrors']['ReturnError']['ErrorDescription']) ? $result['ReturnErrors']['ReturnError']['ErrorDescription'] : '';
                    $this->addLogs($methodId, $responseStatus, '', $error, $random_number);
                }
            }
        }
    }

    public function addLogs($methodId, $returnStatus, $status = '', $error, $random_number)
    {
        if ($returnStatus == 'Failed') {
            if ($methodId == 4) {
                $tpData = array(
                    'cs4_message' => $error,
                );
            } elseif ($methodId == 3) {
                $tpData = array(
                    'cs3_message' => $error,
                );
            }

        } else {
            if ($methodId == 4) {
                $tpData = array(
                    'cs4_message' => $status,
                );
            } elseif ($methodId == 3) {
                $tpData = array(
                    'cs3_message' => $status,
                );
            }
        }

        $session_id = 'tp_api_id_' . $random_number;
        $con = array(
            'where' => array(
                'session_id' => $session_id,
            ),
            'returnType' => 'count',
        );
        $prevCount = $this->titlePointData->gettitlePointDetails($con);

        if ($prevCount > 0) {
            $condition = array(
                'session_id' => $session_id,
            );
            $this->titlePointData->update($tpData, $condition);
        } else {
            $tpData['session_id'] = 'tp_api_id_' . $random_number;

            $tpId = $this->titlePointData->insert($tpData);
        }

    }

    public function tpGetRequestSummaries($methodId, $requestId, $random_number)
    {
        $requestParams = array(
            'userID' => env('TP_USERNAME'),
            'password' => env('TP_PASSWORD'),
            'company' => '',
            'department' => '',
            'titleOfficer' => '',
            'requestId' => $requestId,
            'maxWaitSeconds' => 20,
        );

        $request = env('TP_REQUEST_SUMMARY_ENDPOINT') . http_build_query($requestParams);

        $logid = $this->apiLogs->syncLogs(0, 'titlepoint', 'get_request_summary_' . $methodId, $request, $requestParams, array(), $random_number, 0);

        $opts = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $context = stream_context_create($opts);
        $file = file_get_contents($request, false, $context);
        $xmlData = simplexml_load_string($file);
        $response = json_encode($xmlData);
        $result = json_decode($response, true);

        $this->apiLogs->syncLogs(0, 'titlepoint', 'sales_get_request_summary_' . $methodId, $request, $requestParams, $result, $random_number, $logid);

        $session_id = 'tp_api_id_' . $random_number;

        $con = array(
            'where' => array(
                'session_id' => $session_id,
            ),
            'returnType' => 'count',
        );
        $prevCount = $this->titlePointData->gettitlePointDetails($con);

        if (isset($result) && empty($result)) {
            $tpData = array(
                'cs4_message' => 'Failed',
            );

            if ($prevCount > 0) {
                $condition = array(
                    'session_id' => $session_id,
                );
                $this->titlePointData->update($tpData, $condition);
            } else {
                $tpData['session_id'] = 'tp_api_id_' . $random_number;

                $tpId = $this->titlePointData->insert($tpData);
            }
        } else {
            $responseStatus = isset($result['ReturnStatus']) && !empty($result['ReturnStatus']) ? $result['ReturnStatus'] : '';

            $session_data = array();

            if ($methodId == 4) {
                if ($responseStatus == 'Success') {
                    $status = isset($result['RequestSummaries']['RequestSummary']['Status']) && !empty($result['RequestSummaries']['RequestSummary']['Status']) ? $result['RequestSummaries']['RequestSummary']['Status'] : '';

                    if ($status == 'Complete') {
                        $resultId = isset($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail'][0]['ID']) && !empty($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail'][0]['ID']) ? $result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail'][0]['ID'] : '';
                        $serviceId = isset($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID']) && !empty($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID']) ? $result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID'] : '';

                        $tpData = array(
                            'cs4_result_id' => $resultId,
                            'cs4_service_id' => $serviceId,
                        );
                    } else {
                        $tpData = array(
                            'cs4_message' => $status,
                        );
                    }

                    if ($prevCount > 0) {
                        $condition = array(
                            'session_id' => $session_id,
                        );
                        $this->titlePointData->update($tpData, $condition);
                    } else {
                        $tpData['session_id'] = 'tp_api_id_' . $random_number;

                        $tpId = $this->titlePointData->insert($tpData);
                    }

                    if ($status == 'Complete') {
                        $this->tpGetResultById(4, $resultId, $random_number);
                    }

                } else {
                    $error = isset($result['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($result['ReturnErrors']['ReturnError']['ErrorDescription']) ? $result['ReturnErrors']['ReturnError']['ErrorDescription'] : '';
                    $this->addLogs($methodId, $responseStatus, '', $error, $random_number);
                }
            }
            if ($methodId == 3) {
                if ($responseStatus == 'Success') {
                    $status = isset($result['RequestSummaries']['RequestSummary']['Status']) && !empty($result['RequestSummaries']['RequestSummary']['Status']) ? $result['RequestSummaries']['RequestSummary']['Status'] : '';

                    if ($status == 'Complete') {
                        $resultId = isset($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail']['ID']) && !empty($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail']['ID']) ? $result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail']['ID'] : '';
                        $serviceId = isset($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID']) && !empty($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID']) ? $result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID'] : '';
                        $tpData = array(
                            'cs3_result_id' => $resultId,
                            'cs3_service_id' => $serviceId,
                        );
                    } else {
                        $tpData = array(
                            'cs3_message' => $status,
                        );
                    }

                    if ($prevCount > 0) {
                        $condition = array(
                            'session_id' => $session_id,
                        );
                        $this->titlePointData->update($tpData, $condition);
                    } else {
                        $tpData['session_id'] = 'tp_api_id_' . $random_number;

                        $tpId = $this->titlePointData->insert($tpData);
                    }
                    if ($status == 'Complete') {
                        $this->tpGetResultById(3, $resultId, $random_number);
                    }
                } else {
                    $error = isset($result['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($result['ReturnErrors']['ReturnError']['ErrorDescription']) ? $result['ReturnErrors']['ReturnError']['ErrorDescription'] : '';
                    $this->addLogs($methodId, $responseStatus, '', $error, $random_number);
                }
            }
        }
    }

    public function tpGetResultById($methodId, $resultId, $random_number)
    {
        $requestParams = array(
            'userID' => env('TP_USERNAME'),
            'password' => env('TP_PASSWORD'),
            'company' => '',
            'department' => '',
            'titleOfficer' => '',
            'resultID' => $resultId,
        );

        $resultUrl = env('TP_GET_RESULT_BY_ID');

        if ($methodId == 3) {
            $requestParams['requestingTPXML'] = 'true';
            $resultUrl = env('TP_GET_RESULT_BY_ID_3');
        }

        $request = $resultUrl . http_build_query($requestParams);

        $logid = $this->apiLogs->syncLogs(0, 'titlepoint', 'sales_get_result_by_id_' . $methodId, $request, $requestParams, array(), $random_number, 0);

        $opts = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $context = stream_context_create($opts);
        $file = file_get_contents($request, false, $context);

        $xmlData = simplexml_load_string($file);
        $response = json_encode($xmlData);
        $result = json_decode($response, true);

        $this->apiLogs->syncLogs(0, 'titlepoint', 'sales_get_result_by_id_' . $methodId, $request, $requestParams, $result, $random_number, $logid);

        $session_id = 'tp_api_id_' . $random_number;

        $con = array(
            'where' => array(
                'session_id' => $session_id,
            ),
            'returnType' => 'count',
        );
        $prevCount = $this->titlePointData->gettitlePointDetails($con);

        if (isset($result) && empty($result)) {
            $tpData = array(
                'cs4_message' => 'Failed',
            );

            if ($prevCount > 0) {
                $condition = array(
                    'session_id' => $session_id,
                );
                $this->titlePointData->update($tpData, $condition);
            } else {
                $tpData['session_id'] = 'tp_api_id_' . $random_number;

                $tpId = $this->titlePointData->insert($tpData);
            }
        } else {
            $responseStatus = isset($result['ReturnStatus']) && !empty($result['ReturnStatus']) ? $result['ReturnStatus'] : '';
            $session_data = array();

            if ($methodId == 4) {
                if ($responseStatus == 'Success') {
                    $briefLegal = isset($result['Result']['BriefLegal']) && !empty($result['Result']['BriefLegal']) ? $result['Result']['BriefLegal'] : '';

                    $vesting = isset($result['Result']['Vesting']) && !empty($result['Result']['Vesting']) ? $result['Result']['Vesting'] : '';

                    $fips = isset($result['Result']['Fips']) && !empty($result['Result']['Fips']) ? $result['Result']['Fips'] : '';

                    $legal_vesting_info = isset($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']) && !empty($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']) ? $result['Result']['LvDeeds']['LegalAndVesting2DeedInfo'] : array();

                    if (count($legal_vesting_info) == count($legal_vesting_info, COUNT_RECURSIVE)) {
                        $docType = isset($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['DocType']) && !empty($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['DocType']) ? $result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['DocType'] : '';
                        $docType = strtolower($docType);

                        if ($docType == 'grant deed' || $docType == 'intrafamily transfer & dissolution' || $docType == 'quit claim deed' || $docType == 'intra-family transfer or dissolution') {
                            $instrumentNumber = isset($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['InstrumentNumber']) && !empty($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['InstrumentNumber']) ? $result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['InstrumentNumber'] : '';
                            $recordedDate = isset($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['RecordedDate']) && !empty($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['RecordedDate']) ? $result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['RecordedDate'] : '';
                        }

                    } else {
                        foreach ($legal_vesting_info as $key => $value) {
                            $docType = isset($value['DocType']) && !empty($value['DocType']) ? $value['DocType'] : '';
                            $docType = strtolower($docType);

                            if ($docType == 'grant deed' || $docType == 'intrafamily transfer & dissolution' || $docType == 'quit claim deed' || $docType == 'intra-family transfer or dissolution') {
                                $instrumentNumber = isset($value['InstrumentNumber']) && !empty($value['InstrumentNumber']) ? $value['InstrumentNumber'] : '';
                                $recordedDate = isset($value['RecordedDate']) && !empty($value['RecordedDate']) ? $value['RecordedDate'] : '';
                                break;
                            }
                        }
                    }
                    $status = isset($result['Result']['Status']) && !empty($result['Result']['Status']) ? $result['Result']['Status'] : '';

                    $tpData = array(
                        'legal_description' => $briefLegal,
                        'vesting_information' => $vesting,
                        'cs4_instrument_no' => $instrumentNumber,
                        'cs4_recorded_date' => $recordedDate,
                        'grant_deed_type' => $docType,
                        'fips' => $fips,
                        // 'cs4_result_id_status' => $status,
                    );

                    if ($prevCount > 0) {
                        $condition = array(
                            'session_id' => $session_id,
                        );
                        $this->titlePointData->update($tpData, $condition);
                    } else {
                        $tpData['session_id'] = 'tp_api_id_' . $random_number;

                        $tpId = $this->titlePointData->insert($tpData);

                    }
                    $this->addLogs($methodId, $responseStatus, $status, $error, $random_number);
                } else {
                    $error = isset($result['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($result['ReturnErrors']['ReturnError']['ErrorDescription']) ? $result['ReturnErrors']['ReturnError']['ErrorDescription'] : '';
                    $this->addLogs($methodId, $responseStatus, '', $error, $random_number);
                }
            }
            if ($methodId == 3) {
                if ($responseStatus == 'Success') {
                    $firstInstallment = $secondInstallment = array();
                    if (isset($result['Result']['TaxReport']['Installments']['Item'][0]) && !empty($result['Result']['TaxReport']['Installments']['Item'][0])) {
                        $firstInstallment = $result['Result']['TaxReport']['Installments']['Item'][0];
                    }

                    if (isset($result['Result']['TaxReport']['Installments']['Item'][1]) && !empty($result['Result']['TaxReport']['Installments']['Item'][1])) {
                        $secondInstallment = $result['Result']['TaxReport']['Installments']['Item'][1];
                    }

                    $status = isset($result['Result']['TaxReport']['Status']) && !empty($result['Result']['TaxReport']['Status']) ? $result['Result']['TaxReport']['Status'] : '';
                    if ($status == 'Success') {
                        $message = 'Success';
                    } else {
                        $message = isset($result['Result']['TaxReport']['WarningMessage']) && !empty($result['Result']['TaxReport']['WarningMessage']) ? $result['Result']['TaxReport']['WarningMessage'] : '';
                    }

                    $tpData = array(
                        'first_installment' => json_encode($firstInstallment),
                        'second_installment' => json_encode($secondInstallment),
                    );

                    if ($prevCount > 0) {
                        $condition = array(
                            'session_id' => $session_id,
                        );
                        $this->titlePointData->update($tpData, $condition);
                    } else {
                        $tpData['session_id'] = 'tp_api_id_' . $random_number;

                        $tpId = $this->titlePointData->insert($tpData);
                    }
                    $this->addLogs($methodId, $responseStatus, $message, $error, $random_number);
                } else {
                    $error = isset($result['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($result['ReturnErrors']['ReturnError']['ErrorDescription']) ? $result['ReturnErrors']['ReturnError']['ErrorDescription'] : '';
                    $this->addLogs($methodId, $responseStatus, '', $error, $random_number);
                }
            }
        }
    }

    public function removeApiLogs()
    {
        $this->db->where("DATE(created) < (curdate() - INTERVAL " . getenv('NO_OF_DAYS_TO_KEEP_API_LOGS') . " DAY)");
        $this->db->delete('pct_order_api_logs');
        $this->db->query('OPTIMIZE TABLE pct_order_api_logs');
    }

    public function exportUsers()
    {
        define('USE_AUTHENTICATION', 1);
        define('USERNAME', 'ghernandez@pct.com');
        define('PASSWORD', 'hsk@12dhk');

        if (USE_AUTHENTICATION == 1) {
            if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW']) ||
                $_SERVER['PHP_AUTH_USER'] != USERNAME || $_SERVER['PHP_AUTH_PW'] != PASSWORD) {
                header('WWW-Authenticate: Basic realm="WINCACHE Log In!"');
                header('HTTP/1.0 401 Unauthorized');
                exit;
            } else {
                $user = $this->uri->segment(2);
                $this->db->select('*');

                if (isset($user) && $user == 'escrows') {
                    $this->db->from('customer_basic_details');
                    $this->db->where('is_password_updated', 1);
                    $this->db->where('status', 1);
                    $this->db->where('is_escrow', 1);
                } else if (isset($user) && $user == 'lenders') {
                    $this->db->from('customer_basic_details');
                    $this->db->where('is_password_updated', 1);
                    $this->db->where('status', 1);
                    $this->db->where('is_escrow', 0);
                } else if (isset($user) && $user == 'realtors') {
                    $this->db->from('agents');
                    $this->db->where('status', 1);
                }
                $query = $this->db->get();
                $result = $query->result_array();

                if (!empty($result)) {
                    $delimiter = ",";

                    if (isset($user) && $user == 'escrows') {
                        $filename = "escrows_" . date('Y-m-d') . ".csv";
                    } else if (isset($user) && $user == 'lenders') {
                        $filename = "lenders_" . date('Y-m-d') . ".csv";
                    } else if (isset($user) && $user == 'realtors') {
                        $filename = "realtors_" . date('Y-m-d') . ".csv";
                    }

                    $f = fopen('php://memory', 'w');

                    if (isset($user) && $user == 'realtors') {
                        $fields = array('Sr no', 'Name', 'Email', 'Company', 'Street Address', 'City', 'Zip code', 'List Unit', 'List Volume', 'Selected Revenue', 'Telephone No');
                        fputcsv($f, $fields, $delimiter);

                        $i = 1;
                        foreach ($result as $res) {
                            $lineData = array($i, $res['name'], $res['email_address'], $res['company'], $res['address'], $res['city'], $res['zipcode'], $res['list_unit'], $res['list_volume'], $res['selected_revenue'], $res['telephone_no']);
                            fputcsv($f, $lineData, $delimiter);
                            $i++;
                        }
                    } else {
                        $fields = array('Sr no', 'First Name', 'Last Name', 'Password', 'Phone', 'Company Name', 'Email', 'Street Address', 'City', 'State', 'Zip code');
                        fputcsv($f, $fields, $delimiter);

                        $i = 1;
                        foreach ($result as $res) {
                            $lineData = array($i, $res['first_name'], $res['last_name'], $res['random_password'], $res['telephone_no'], $res['company_name'], $res['email_address'], $res['street_address'], $res['city'], $res['state'], $res['zip_code']);
                            fputcsv($f, $lineData, $delimiter);
                            $i++;
                        }
                    }
                    fseek($f, 0);
                    header('Content-Type: text/csv');
                    header('Content-Disposition: attachment; filename="' . $filename . '";');
                    fpassthru($f);
                }
                exit;
            }
        }
    }

    public function getOrderInformation()
    {
        $login = 'ghernandez@pct.com';
        $pass = 'hsk@12dhk';
        $fileId = $this->uri->segment(2);
        $response = array();

        if (($_SERVER['PHP_AUTH_PW'] != $pass || $_SERVER['PHP_AUTH_USER'] != $login) || !$_SERVER['PHP_AUTH_USER']) {
            header('WWW-Authenticate: Basic realm="Test auth"');
            header('HTTP/1.0 401 Unauthorized');
            $response = array('success' => false, 'error_msg' => 'Please enter proper Authorization details.');
        } else {
            $this->load->library('order/order');
            $orderDetails = $this->order->get_order_details($fileId);
            if (!empty($orderDetails)) {
                if ($orderDetails['sales_amount'] > 0) {
                    if (!empty($orderDetails['borrower'])) {
                        $orderDetails['primary_owner_name'] = $orderDetails['borrower'];
                    } else {
                        $orderDetails['primary_owner_name'] = '';
                    }

                    if (!empty($orderDetails['secondary_borrower'])) {
                        $orderDetails['secondary_owner_name'] = $orderDetails['secondary_borrower'];
                    } else {
                        $orderDetails['secondary_owner_name'] = '';
                    }
                } else {
                    if (!empty($orderDetails['primary_owner'])) {
                        $orderDetails['primary_owner_name'] = $orderDetails['primary_owner'];
                    } else {
                        $orderDetails['primary_owner_name'] = '';
                    }

                    if (!empty($orderDetails['secondary_owner'])) {
                        $orderDetails['secondary_owner_name'] = $orderDetails['secondary_owner'];
                    } else {
                        $orderDetails['secondary_owner_name'] = '';
                    }
                }

                $orderUser = $this->home_model->get_user(array('id' => $orderDetails['customer_id']));

                if (!empty($orderUser) && $orderUser['is_escrow'] == 1) {
                    if (!empty($orderDetails['cpl_lender_id'])) {
                        $lenderDetails = $this->home_model->get_user(array('id' => $orderDetails['cpl_lender_id']));
                        $orderDetails['lender_first_name'] = $lenderDetails['first_name'] ? $lenderDetails['first_name'] : '';
                        $orderDetails['lender_last_name'] = $lenderDetails['last_name'] ? $lenderDetails['last_name'] : '';
                        $orderDetails['lender_email'] = $lenderDetails['email_address'] ? $lenderDetails['email_address'] : '';
                        $orderDetails['lender_state'] = $lenderDetails['state'] ? $lenderDetails['state'] : '';
                        $orderDetails['lender_company_name'] = $lenderDetails['company_name'] ? $lenderDetails['company_name'] : '';
                        $orderDetails['lender_address'] = $lenderDetails['street_address'] ? $lenderDetails['street_address'] : '';
                        $orderDetails['lender_city'] = $lenderDetails['city'] ? $lenderDetails['city'] : '';
                        $orderDetails['lender_zipcode'] = $lenderDetails['zip_code'] ? $lenderDetails['zip_code'] : '';
                        $orderDetails['lender_assignment_clause'] = $lenderDetails['assignment_clause'] ? $lenderDetails['assignment_clause'] : '';
                        $orderDetails['lender_id'] = $lenderDetails['id'] ? $lenderDetails['id'] : '';
                    } else {
                        $orderDetails['lender_first_name'] = $orderDetails['lender_first_name'] ? $orderDetails['lender_first_name'] : '';
                        $orderDetails['lender_last_name'] = $orderDetails['lender_last_name'] ? $orderDetails['lender_last_name'] : '';
                        $orderDetails['lender_email'] = $orderDetails['lender_email'] ? $orderDetails['lender_email'] : '';
                        $orderDetails['lender_state'] = $orderDetails['lender_state'] ? $orderDetails['lender_state'] : '';
                        $orderDetails['lender_company_name'] = $orderDetails['lender_company_name'] ? $orderDetails['lender_company_name'] : '';
                        $orderDetails['lender_address'] = $orderDetails['lender_address'] ? $orderDetails['lender_address'] : '';
                        $orderDetails['lender_city'] = $orderDetails['lender_city'] ? $orderDetails['lender_city'] : '';
                        $orderDetails['lender_zipcode'] = $orderDetails['lender_zipcode'] ? $orderDetails['lender_zipcode'] : '';
                        $orderDetails['lender_assignment_clause'] = $orderDetails['lender_assignment_clause'] ? $orderDetails['lender_assignment_clause'] : '';
                        $orderDetails['lender_id'] = $orderDetails['lender_id'] ? $orderDetails['lender_id'] : '';
                    }
                } else {
                    if (!empty($orderDetails['cpl_lender_id'])) {
                        $lenderDetails = $this->home_model->get_user(array('id' => $orderDetails['cpl_lender_id']));
                        $orderDetails['lender_first_name'] = $lenderDetails['first_name'] ? $lenderDetails['first_name'] : '';
                        $orderDetails['lender_last_name'] = $lenderDetails['last_name'] ? $lenderDetails['last_name'] : '';
                        $orderDetails['lender_email'] = $lenderDetails['email_address'] ? $lenderDetails['email_address'] : '';
                        $orderDetails['lender_state'] = $lenderDetails['state'] ? $lenderDetails['state'] : '';
                        $orderDetails['lender_company_name'] = $lenderDetails['company_name'] ? $lenderDetails['company_name'] : '';
                        $orderDetails['lender_address'] = $lenderDetails['street_address'] ? $lenderDetails['street_address'] : '';
                        $orderDetails['lender_city'] = $lenderDetails['city'] ? $lenderDetails['city'] : '';
                        $orderDetails['lender_zipcode'] = $lenderDetails['zip_code'] ? $lenderDetails['zip_code'] : '';
                        $orderDetails['lender_assignment_clause'] = $lenderDetails['assignment_clause'] ? $lenderDetails['assignment_clause'] : '';
                        $orderDetails['lender_id'] = $lenderDetails['id'] ? $lenderDetails['id'] : '';
                    } else {
                        $orderDetails['lender_first_name'] = $orderUser['first_name'] ? $orderUser['first_name'] : '';
                        $orderDetails['lender_last_name'] = $orderUser['last_name'] ? $orderUser['last_name'] : '';
                        $orderDetails['lender_email'] = $orderUser['email_address'] ? $orderUser['email_address'] : '';
                        $orderDetails['lender_state'] = $orderUser['state'] ? $orderUser['state'] : '';
                        $orderDetails['lender_company_name'] = $orderUser['company_name'] ? $orderUser['company_name'] : '';
                        $orderDetails['lender_address'] = $orderUser['street_address'] ? $orderUser['street_address'] : '';
                        $orderDetails['lender_city'] = $orderUser['city'] ? $orderUser['city'] : '';
                        $orderDetails['lender_zipcode'] = $orderUser['zip_code'] ? $orderUser['zip_code'] : '';
                        $orderDetails['lender_assignment_clause'] = $orderUser['assignment_clause'] ? $orderUser['assignment_clause'] : '';
                        $orderDetails['lender_id'] = $orderUser['id'] ? $orderUser['id'] : '';
                    }
                    $orderUser = $this->home_model->get_user(array('id' => $orderDetails['customer_id']));
                }

                $orderData = array(
                    'Loans' => array(
                        'LoanNumber' => $orderDetails['loan_number'],
                        'SalesAmount' => $orderDetails['sales_amount'],
                        'LoanAmount' => $orderDetails['loan_amount'],
                    ),
                    'FileNumber' => $orderDetails['file_number'],
                    'FileID' => $orderDetails['file_id'],
                    'Product' => $orderDetails['product_type'],
                    'Borrower' => array(
                        'PrimaryName' => $orderDetails['primary_owner_name'],
                        'SecondaryName' => $orderDetails['secondary_owner_name'],
                        'Email' => 'ghernandez@pct.com',
                        'Mobile' => '(213) 309-7286',
                    ),
                    'Properties' => array(
                        'Address' => $orderDetails['address'],
                        'City' => $orderDetails['property_city'],
                        'State' => $orderDetails['property_state'],
                        'County' => $orderDetails['county'],
                        'Zip' => $orderDetails['property_zip'],
                    ),
                    'LenderInformations' => array(
                        'FirstName' => $orderDetails['lender_first_name'],
                        'LastName' => $orderDetails['lender_last_name'],
                        'CompanyName' => $orderDetails['lender_company_name'],
                        'Email' => $orderDetails['lender_email'],
                        'Address' => $orderDetails['lender_address'],
                        'City' => $orderDetails['lender_city'],
                        'State' => $orderDetails['lender_state'],
                        'Zip' => $orderDetails['lender_zipcode'],
                    ),
                );
                $response = array('FileInformations' => $orderData);
            } else {
                $response = array('success' => false, 'error_msg' => 'Please enter the correct file id to get order information.');
            }
        }
        header('Content-type: application/json');
        echo json_encode($response, true);
        exit;
    }

    public function updateOrderStatus()
    {
        $this->db->select('file_id, customer_id, file_number, on_hold_mail_sent');
        $this->db->from('order_details');
        $query = $this->db->get();
        $filesResult = $query->result_array();

        $status = array();
        $userdata['admin_api'] = 1;
        $status['Statuses'][] = array('StatusID' => 6, 'Name' => 'Hold');
        $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_orders', env('RESWARE_ORDER_API') . 'files/search', json_encode($status), array(), 0, 0);
        $res = $this->make_request('POST', 'files/search', json_encode($status), $userdata);
        $this->apiLogs->syncLogs(0, 'resware', 'get_orders', env('RESWARE_ORDER_API') . 'files/search', json_encode($status), $res, 0, $logid);
        $result = json_decode($res, true);
        $file_ids = array();

        if (isset($result['Files']) && !empty($result['Files'])) {
            foreach ($result['Files'] as $res) {
                $key = array_search($res['FileID'], array_column($filesResult, 'file_id'));
                if ($key) {
                    $on_hold_mail_sent = $filesResult[$key]['on_hold_mail_sent'];
                    if ($on_hold_mail_sent == 0) {
                        $file_ids[] = $filesResult[$key]['file_id'];
                        $filesResult[$key]['file_number'];

                        $file_number = $filesResult[$key]['file_number'];
                        /*$customer_id = $filesResult[$key]['customer_id'];
                        $condition = array(
                        'id' => $customer_id
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
                        $property = $res['Properties'][0]['StreetNumber']." ".$res['Properties'][0]['StreetDirection']." ".$res['Properties'][0]['StreetName']." ".$res['Properties'][0]['StreetSuffix'].", ".$res['Properties'][0]['City'].", ".$res['Properties'][0]['State'].", ".$res['Properties'][0]['Zip'];
                        $message = '<h3>User Details:</h3><p>Name: '.$first_name.' '.$last_name.'</p><p>Telephone: '.$telephone_no.'</p><p>Email Address: '.$email_address.'</p><p>Company Name: '.$company_name.'</p><p>Street Address: '.$street_address.'</p><p>City: '.$city.'</p><p>Zipcode: '.$zipcode.'</p><p>Property Address: '.$property.'</p><p>File Number: '.$file_number.'</p>';*/

                        $data['file_number'] = $file_number;
                        $message = $this->load->view('emails/onhold.php', $data, true);
                        $from_name = 'Pacific Coast Title Company';
                        $from_mail = env('FROM_EMAIL');
                        $subject = 'Notification For On Hold Order';
                        $to = 'cs@pct.com';
                        $cc = array('ghernandez@pct.com');
                        $this->load->helper('sendemail');
                        send_email($from_mail, $from_name, $to, $subject, $message, $cc);
                    }
                }
            }
            if (!empty($file_ids)) {
                $updateData = array('resware_status' => 'hold', 'on_hold_mail_sent' => 1);
                $this->db->set($updateData);
                $this->db->where_in('file_id', $file_ids);
                $this->db->update('order_details');
                echo "All orders with status hold updated successfully";exit;
            } else {
                echo "No orders found with status hold";exit;
            }
        } else {
            echo "No orders found with status hold";exit;
        }
    }

    public function getOrderStatus()
    {
        echo date('Y-m-d H:i:s') . "----";
        $this->db->select('file_id, customer_id, file_number, on_hold_mail_sent, resware_status');
        $this->db->from('order_details');
        $this->db->where('resware_status != "closed" OR resware_status IS NULL');
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        $filesResult = $query->result_array();

        if (isset($filesResult) && !empty($filesResult)) {
            foreach ($filesResult as $file) {
                $data = array();
                $userdata['admin_api'] = 1;
                $data = array('FileNumber' => $file['file_number']);
                $res = $this->make_request('POST', 'files/search', json_encode($data), $userdata);
                $result = json_decode($res, true);

                if (strtolower($result['Files'][0]['Status']['Name']) != $file['resware_status']) {
                    $orderData = array(
                        'resware_status' => strtolower($result['Files'][0]['Status']['Name']),
                    );

                    if (strtolower($result['Files'][0]['Status']['Name']) == 'closed') {
                        $orderData['resware_closed_status_date'] = date('Y-m-d H:i:s');
                    }

                    $condition = array(
                        'file_id' => $file['file_id'],
                        'file_number' => $file['file_number'],
                    );

                    $this->home_model->update($orderData, $condition, 'order_details');
                }
            }
        }
        echo date('Y-m-d H:i:s');exit;
    }

    public function updateSafewireStatusForAllorders()
    {
        $this->db->select('*');
        $this->db->from('order_details');
        $this->db->where('is_create_order_on_safewire = 1');
        $query = $this->db->get();
        $result = $query->result_array();

        if (!empty($result)) {
            foreach ($result as $res) {
                $url = env('SAFEWIRE_URL') . $res['file_id'] . '/status';
                $logid = $this->apiLogs->syncLogs(0, 'safewire', 'get_order_status', $url, array(), array(), $res['id'], 0);
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_POSTFIELDS, array());
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Api-Key: ' . env('SAFEWIRE_API_KEY'),
                    'Content-Type: application/json',
                )
                );
                $error_msg = curl_error($ch);
                $result = curl_exec($ch);
                $this->apiLogs->syncLogs(0, 'safewire', 'get_wire_detail_pdf', $url, array(), $result, $res['id'], $logid);

                $resultSafewire = json_decode($result, true);
                if (isset($resultSafewire['order_id']) && !empty($resultSafewire['order_id'])) {
                    $this->home_model->update(array('safewire_order_status' => $resultSafewire['status']), array('file_id' => $res['file_id']), 'order_details');
                    if ($resultSafewire['status'] == 'completed' && $res['safewire_order_status'] != 'completed') {
                        $this->load->library('order/order');
                        $orderDetails = $this->order->get_order_details($res['file_id']);
                        $this->order->syncSafewireDocuments($resultSafewire['order_details'], $resultSafewire['wire_instruction_details'], $orderDetails);
                    }
                }
                $response = array('success' => true, 'message' => 'Safewire order status updated successfully.');
            }
        } else {
            $response = array('success' => false, 'message' => 'No Order found for update status');
        }
        echo json_encode($response);exit;
    }

    public function sendMailEscrowUsers()
    {
        $month = sprintf('%02d', date('m') - 1);
        $this->db->select('order_details.file_id,
            order_details.file_number,
            order_details.id as order_id,
            order_details.resware_status,
            order_details.resware_closed_status_date,
            property_details.full_address,
            customer_basic_details.first_name,
            customer_basic_details.last_name,
            customer_basic_details.email_address as sales_email,
            customer_basic_details.sales_rep_profile_thank_you_img,
            property_details.escrow_lender_id,
            escrow_details.email_address,
            transaction_details.sales_representative');
        $this->db->from('order_details');
        $this->db->where('MONTH(order_details.resware_closed_status_date)', $month);
        $this->db->where('YEAR(order_details.resware_closed_status_date)', date('Y'));
        $this->db->where('order_details.prod_type', 'loan');
        $this->db->where('property_details.escrow_lender_id != ""');
        $this->db->where('transaction_details.sales_representative != ""');
        $this->db->join('property_details', 'order_details.property_id = property_details.id', 'inner');
        $this->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id', 'inner');
        $this->db->join('customer_basic_details', 'customer_basic_details.id = transaction_details.sales_representative', 'inner');
        $this->db->join('customer_basic_details as escrow_details', 'escrow_details.id = property_details.escrow_lender_id', 'inner');
        $this->db->order_by('transaction_details.sales_representative asc, property_details.escrow_lender_id asc');
        $query = $this->db->get();
        $result = $query->result_array();

        if (!empty($result)) {
            $checkFlag = 0;
            $data = array();
            $i = 0;
            foreach ($result as $res) {
                if ($checkFlag == 0) {
                    $sales_rep_user_id = $res['sales_representative'];
                    $escrow_user_id = $res['escrow_lender_id'];
                    $escrow_email_address = $res['email_address'];
                    $checkFlag = 1;
                }
                if ($res['sales_representative'] == $sales_rep_user_id && $res['escrow_lender_id'] == $escrow_user_id) {
                    $data['order_info'][$i]['order_number'] = $res['file_number'];
                    $data['order_info'][$i]['address'] = $res['full_address'];
                    $data['order_info'][$i]['resware_status'] = $res['resware_status'] ? $res['resware_status'] : 'closed';
                    $data['order_info'][$i]['closed_date'] = date("m/d/Y", strtotime($res['resware_closed_status_date']));
                    $data['sales_rep_profile_thank_you_img'] = !empty($res['sales_rep_profile_thank_you_img']) ? $res['sales_rep_profile_thank_you_img'] : '';
                    if (!empty($data['sales_rep_profile_thank_you_img'])) {
                        $data['sales_rep_profile_thank_you_img'] = env('AWS_PATH') . str_replace('uploads/', '', $data['sales_rep_profile_thank_you_img']);
                    }
                    $data['sales_email'] = !empty($res['sales_email']) ? $res['sales_email'] : '';
                    $i++;
                } else {
                    $message = $this->load->view('emails/thank_you_escrow.php', $data, true);
                    $from_name = 'Pacific Coast Title Company';
                    $from_mail = env('FROM_EMAIL');
                    $subject = 'Thank You!';
                    $to = $escrow_email_address;
                    $cc = array('ghernandez@pct.com', $data['sales_email']);
                    $mailParams = array(
                        'from_mail' => $from_mail,
                        'from_name' => $from_name,
                        'to' => $to,
                        'subject' => $subject,
                        'message' => json_encode($data),
                        'cc' => $data['sales_email'],
                    );
                    //$to = 'hitesh.p@crestinfosystems.com';
                    //$cc = array();
                    $this->load->helper('sendemail');
                    $logid = $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_escrow_user', '', $mailParams, array(), $res['order_id'], 0);
                    $escrow_mail_result = send_email($from_mail, $from_name, $to, $subject, $message, array(), $cc);
                    $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_escrow_user', '', $mailParams, array('status' => $escrow_mail_result), $res['orderId'], $logid);
                    $data = array();
                    $sales_rep_user_id = $res['sales_representative'];
                    $escrow_user_id = $res['escrow_lender_id'];
                    $escrow_email_address = $res['email_address'];
                    $data['order_info'][$i]['order_number'] = $res['file_number'];
                    $data['order_info'][$i]['address'] = $res['full_address'];
                    $data['order_info'][$i]['resware_status'] = $res['resware_status'] ? $res['resware_status'] : 'closed';
                    $data['order_info'][$i]['closed_date'] = date("m/d/Y", strtotime($res['resware_closed_status_date']));
                    $data['sales_rep_profile_thank_you_img'] = !empty($res['sales_rep_profile_thank_you_img']) ? $res['sales_rep_profile_thank_you_img'] : '';
                    if (!empty($data['sales_rep_profile_thank_you_img'])) {
                        $data['sales_rep_profile_thank_you_img'] = env('AWS_PATH') . str_replace('uploads/', '', $data['sales_rep_profile_thank_you_img']);
                    }
                    $data['sales_email'] = !empty($res['sales_email']) ? $res['sales_email'] : '';
                    $i++;
                }
                $sales_email = $res['sales_email'];
                $order_id = $res['order_id'];
            }
            if (!empty($data)) {
                $message = $this->load->view('emails/thank_you_escrow.php', $data, true);
                $from_name = 'Pacific Coast Title Company';
                $from_mail = env('FROM_EMAIL');
                $subject = 'Thank You!';
                $to = $escrow_email_address;

                $cc = array('ghernandez@pct.com', $sales_email);
                $this->load->helper('sendemail');
                $mailParams = array(
                    'from_mail' => $from_mail,
                    'from_name' => $from_name,
                    'to' => $to,
                    'subject' => $subject,
                    'message' => json_encode($data),
                    'cc' => $sales_email,
                );
                //$to = 'hitesh.p@crestinfosystems.com';
                //$cc = array();

                $logid = $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_escrow_user', '', $mailParams, array(), $order_id, 0);
                $escrow_mail_result = send_email($from_mail, $from_name, $to, $subject, $message, array(), $cc);
                $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_escrow_user', '', $mailParams, array('status' => $escrow_mail_result), $order_id, $logid);
            }
            echo "Mails sent successfully to Escow user ";exit;
        }
    }

    public function transferAllFilesOnAws()
    {
        //$this->load->library('order/order');
        //$credResult = $this->order->uploadDocumentOnAwsS3('3795645.pdf', 'test');
        //echo $credResult;exit;

        $bucket = env('AWS_BUCKET');
        try {
            $s3Client = new Aws\S3\S3Client([
                'region' => env('AWS_REGION'),
                'version' => '2006-03-01',
                'credentials' => [
                    'key' => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ],
            ]);
            $dir = FCPATH . "/uploads";
            $keyPrefix = '';

            $result = $s3Client->uploadDirectory($dir, $bucket, $keyPrefix, array(
                'params' => array('ACL' => 'public-read'),
                'concurrency' => 50,
                'debug' => true,
            ));

        } catch (Aws\Exception\AwsException $e) {
            return $e->getMessage() . "\n";
        }
        echo "All files uploaded successfully on AWS S3.";exit;

        /*$this->load->library('order/order');
    $folders = array();
    $path = FCPATH."/uploads/";
    $sub_folder = scandir($path);
    $num = count($sub_folder);
    for ($i = 2; $i < $num; $i++) {
    if (is_file($path.'\\'.$sub_folder[$i])) {
    $syncResult = $this->order->uploadDocumentOnAwsS3($sub_folder[$i]);
    echo $syncResult;
    } else {
    $folders[] = $sub_folder[$i];
    }
    }

    foreach ($folders as $folder) {
    $fileSystemIterator = new FilesystemIterator(FCPATH."/uploads/".$folder."/");
    foreach ($fileSystemIterator as $fileInfo) {
    $credResult = $this->order->uploadDocumentOnAwsS3($fileInfo->getFilename(), $folder);
    echo $credResult;
    }
    }*/

    }

    public function importDataFromCsvFile()
    {
        $sftp = new SFTP(env('SFTP_HOST'));
        $username = env('SFTP_USERNAME');
        $password = env('SFTP_PASSWORD');

        if (!$sftp->login($username, $password)) {
            exit('Login Failed');
        }

        if (!($files = $sftp->nlist('/' . env('SFTP_FOLDER') . '/open-closed-orders/', true))) {
            die("Cannot read directory contents");
        }

        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && $file != '.protected') {
                if (!is_dir('uploads/open-closed-orders')) {
                    mkdir('./uploads/open-closed-orders', 0777, true);
                }
                $sftp->get(env('SFTP_FOLDER') . '/open-closed-orders/' . $file, FCPATH . 'uploads/open-closed-orders/' . trim($file) . ".csv");
                chmod(FCPATH . 'uploads/open-closed-orders/' . $file . ".csv", 0755);
                $sftp->delete(env('SFTP_FOLDER') . '/open-closed-orders/' . $file);
            }
        }
        $files = glob("uploads/open-closed-orders/*csv");

        $this->db->select("id, LOWER(CONCAT_WS(' ', first_name, last_name)) AS sales_name, LOWER(email_address) as email");
        $this->db->from('customer_basic_details');
        $this->db->where('is_sales_rep', 1);
        $query = $this->db->get();
        $salesUsers = $query->result_array();

        if (is_array($files) && count($files) > 0) {
            foreach ($files as $filePath) {
                $row = 1;
                $headerColumns = array();
                if (($handle = fopen($filePath, "r")) !== false) {
                    $documentName = pathinfo($filePath);
                    $file_numbers = array();
                    $salesRepNameArr = array();
                    $titleOfficerNameArr = array();
                    $i = 0;
                    $j = 0;
                    while (($data = fgetcsv($handle, 1000, ",", '"')) !== false) {
                        $num = count($data);
                        if ($row == 1) {
                            for ($c = 0; $c < $num; $c++) {
                                $headerColumns[] = trim($data[$c]);
                            }
                        }

                        $fileKey = '';
                        $prodkey = '';
                        $premiumkey = '';
                        $saleskey = '';
                        $emailkey = '';
                        $closedDate = '';
                        $salesRepId = 0;
                        $sales_rep_img = '';
                        $escrow_email = '';
                        $titleOfficerId = 0;
                        $titleOfficerColumnFlag = 0;
                        $salesRepColumnFlag = 0;

                        if (in_array('File Number', $headerColumns)) {
                            $fileKey = array_search("File Number", $headerColumns);
                            $file_number = $data[$fileKey];
                        }

                        if (in_array('Prod Type', $headerColumns)) {
                            $prodkey = array_search("Prod Type", $headerColumns);
                            $prodType = $data[$prodkey];
                        }

                        if (in_array('Total Premium', $headerColumns)) {
                            $premiumkey = array_search("Total Premium", $headerColumns);
                            $premium = $data[$premiumkey];
                            $premium = str_replace('$', '', $premium);
                            $premium = str_replace(',', '', $premium);
                        }

                        if (in_array('Email', $headerColumns)) {
                            $emailkey = array_search("Email", $headerColumns);
                            $sales_email = strtolower(trim($data[$emailkey]));
                            if (!empty($sales_email)) {
                                $saleUserKey = array_search($sales_email, array_column($salesUsers, 'email'));
                                if (isset($saleUserKey) && !empty($saleUserKey)) {
                                    $salesRepId = $salesUsers[$saleUserKey]['id'];
                                }
                            }
                        }

                        if ($salesRepId == 0) {
                            $salesRepName = '';
                            if (in_array('Sales Rep', $headerColumns)) {
                                $saleskey = array_search("Sales Rep", $headerColumns);
                                $salesRepName = strtolower(trim($data[$saleskey]));

                                if (!empty($salesRepName)) {
                                    $saleUserKey = array_search($salesRepName, array_column($salesUsers, 'sales_name'));
                                    if (isset($saleUserKey) && !empty($saleUserKey)) {
                                        $salesRepId = $salesUsers[$saleUserKey]['id'];
                                    }
                                }

                                // $key = array_search($salesRepName, array_column($salesRepNameArr, 'name'));
                                // if (isset($key) && !empty($key)) {
                                // $salesRepId =  $salesRepNameArr[$key]['id'];
                                // $sales_rep_img = $salesRepNameArr[$key]['sales_rep_img'];
                                // } else {
                                //     $salesRepNameArr[$i]['name'] =  $salesRepName;
                                // }
                                // $salesRepColumnFlag = 1;
                            }
                        }

                        $titleOfficerName = '';
                        if (in_array('Title Officer', $headerColumns)) {
                            $titleOfficerkey = array_search("Title Officer", $headerColumns);
                            $titleOfficerName = $data[$titleOfficerkey];
                            $titleOfficerName = str_replace(' ', '_', $titleOfficerName);
                            $titleOfficerName = preg_replace('/[^A-Za-z0-9&\_-]/', '', $titleOfficerName);
                            $titleOfficerName = str_replace('_', ' ', $titleOfficerName);
                            $titleOfckey = array_search($titleOfficerName, array_column($titleOfficerNameArr, 'name'));
                            if (isset($titleOfckey) && !empty($titleOfckey)) {
                                $titleOfficerId = $titleOfficerNameArr[$titleOfckey]['id'];
                            } else {
                                $titleOfficerNameArr[$j]['name'] = $titleOfficerName;
                            }
                            $titleOfficerColumnFlag = 1;
                        }

                        if (in_array('Sent To External Accounting', $headerColumns)) {
                            $closedDatekey = array_search("Sent To External Accounting", $headerColumns);
                            $closedDate = $data[$closedDatekey];
                        }

                        if ($row != 1) {
                            //echo $file_number."---".$prodType."----".$premium."----".$salesRepName."---".$closedDate;exit;
                            // $resultSales = array();
                            // if(!empty($salesRepName)) {
                            //     if ($salesRepId == 0) {
                            //         $this->db->select('*');
                            //         $this->db->from('customer_basic_details');
                            //         $this->db->like("CONCAT_WS(' ', first_name, last_name)", $salesRepName);
                            //         $this->db->where('is_sales_rep', 1);
                            //         $query = $this->db->get();
                            //         $resultSales = $query->row_array();
                            //         if (!empty($resultSales)) {
                            //             $salesRepId =  $resultSales['id'];
                            //             $sales_rep_img = isset($resultSales["sales_rep_profile_img"]) && !empty($resultSales["sales_rep_profile_img"]) ? $resultSales["sales_rep_profile_img"] : '';
                            //             if(!empty($sales_rep_img)) {
                            //                 $sales_rep_img = env('AWS_PATH').str_replace('uploads/', '', $sales_rep_img);
                            //             }
                            //             $salesRepNameArr[$i]['id'] = $salesRepId;
                            //             $salesRepNameArr[$i]['sales_rep_img'] = $sales_rep_img;
                            //             $i++;
                            //         } else {
                            //             if (!empty(trim($salesRepNameArr[$i]['name']))) {
                            //                 $salesRepNameArr[$i]['id'] = 0;
                            //                 $i++;
                            //             }
                            //         }
                            //     }
                            // }

                            $resultTitleOfficer = array();
                            if (!empty($titleOfficerName)) {
                                if ($titleOfficerId == 0) {
                                    $this->db->select('*');
                                    $this->db->from('customer_basic_details');
                                    $this->db->like("CONCAT_WS(' ', first_name, last_name)", $titleOfficerName);
                                    $this->db->where('is_title_officer', 1);
                                    $query = $this->db->get();
                                    $resultTitleOfficer = $query->row_array();
                                    if (!empty($resultTitleOfficer)) {
                                        $titleOfficerId = $resultTitleOfficer['id'];
                                        $titleOfficerNameArr[$j]['id'] = $titleOfficerId;
                                        $j++;
                                    } else {
                                        if (!empty(trim($titleOfficerNameArr[$i]['name']))) {
                                            $titleOfficerNameArr[$j]['id'] = 0;
                                            $j++;
                                        }
                                    }
                                }
                            }

                            $completed_date = null;
                            if (!empty($closedDate)) {
                                $myDateTime = DateTime::createFromFormat('M d, Y', $closedDate);
                                $completed_date = $myDateTime->format('Y-m-d H:i:s');
                            }

                            if (!empty($file_number)) {
                                $condition = array(
                                    'where' => array(
                                        'file_number' => $file_number,
                                    ),
                                );
                                $order = $this->order->get_order($condition);

                                if (!empty($order)) {
                                    if (in_array($file_number, $file_numbers)) {
                                        if (!empty($premium)) {
                                            $premium = (float) $premium + $order[0]['premium'];
                                        }
                                    }
                                    $file_numbers[] = $file_number;
                                    $orderData = array();

                                    if (!empty($prodType)) {
                                        $orderData['prod_type'] = strtolower($prodType);
                                    }

                                    if (!empty($premium)) {
                                        $orderData['premium'] = (float) $premium;
                                    }

                                    if (!empty($completed_date)) {
                                        $orderData['sent_to_accounting_date'] = $completed_date;
                                    }

                                    if (!empty($orderData)) {
                                        $this->home_model->update(
                                            $orderData,
                                            array(
                                                'id' => $order[0]['id'],
                                            ),
                                            'order_details'
                                        );
                                    }

                                    $orderDetails = $this->order->get_order_details($order[0]['file_id']);
                                    $propertyAddress = $orderDetails['address'];
                                    $productTypeID = $orderDetails['purchase_type'];
                                    $orderId = $order[0]['id'];
                                    if (!empty($salesRepId)) {
                                        $this->home_model->update(
                                            array(
                                                'sales_representative' => $salesRepId,
                                            ),
                                            array(
                                                'id' => $orderDetails['transaction_id'],
                                            ),
                                            'transaction_details'
                                        );
                                    } else {
                                        if ($salesRepColumnFlag == 1) {
                                            $this->home_model->update(
                                                array(
                                                    'sales_representative' => 0,
                                                ),
                                                array(
                                                    'id' => $orderDetails['transaction_id'],
                                                ),
                                                'transaction_details'
                                            );
                                        }
                                    }

                                    if (!empty($titleOfficerId)) {
                                        $this->home_model->update(
                                            array(
                                                'title_officer' => $titleOfficerId,
                                            ),
                                            array(
                                                'id' => $orderDetails['transaction_id'],
                                            ),
                                            'transaction_details'
                                        );
                                    } else {
                                        if ($titleOfficerColumnFlag == 1) {
                                            $this->home_model->update(
                                                array(
                                                    'title_officer' => 0,
                                                ),
                                                array(
                                                    'id' => $orderDetails['transaction_id'],
                                                ),
                                                'transaction_details'
                                            );
                                        }
                                    }
                                } else {
                                    $file_numbers[] = $file_number;
                                    $data = json_encode(array('FileNumber' => $file_number));
                                    $userData = array(
                                        'admin_api' => 1,
                                    );
                                    $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_order_information', env('RESWARE_ORDER_API') . 'files/search', $data, array(), 0, 0);
                                    $res = $this->make_request('POST', 'files/search', $data, $userData);
                                    $this->apiLogs->syncLogs(0, 'resware', 'get_order_information', env('RESWARE_ORDER_API') . 'files/search', $data, $res, 0, $logid);
                                    $result = json_decode($res, true);

                                    if (isset($result['Files']) && !empty($result['Files'])) {
                                        foreach ($result['Files'] as $res) {
                                            if (count($result['Files']) > 1 && strtolower($res['Status']['Name']) == 'cancelled') {
                                                continue;
                                            }
                                            $partner_fname = $res['Partners'][0]['PrimaryEmployee']['FirstName'];
                                            $partner_lname = $res['Partners'][0]['PrimaryEmployee']['LastName'];
                                            $partner_name = $res['Partners'][0]['PartnerName'];
                                            $condition = array(
                                                'first_name' => $partner_fname,
                                                'last_name' => $partner_lname,
                                                'company_name' => $partner_name,
                                                'is_pass' => $partner_name,
                                            );
                                            $user_details = $this->home_model->get_user_by_name($condition);
                                            $customerId = 0;

                                            if (isset($user_details) && !empty($user_details)) {
                                                $customerId = $user_details['id'];
                                            }

                                            $FullProperty = $res['Properties'][0]['StreetNumber'] . " " . $res['Properties'][0]['StreetDirection'] . " " . $res['Properties'][0]['StreetName'] . " " . $res['Properties'][0]['StreetSuffix'] . ", " . $res['Properties'][0]['City'] . ", " . $res['Properties'][0]['State'] . ", " . $res['Properties'][0]['Zip'];
                                            $address = $res['Properties'][0]['StreetNumber'] . " " . $res['Properties'][0]['StreetDirection'] . " " . $res['Properties'][0]['StreetName'] . " " . $res['Properties'][0]['StreetSuffix'];
                                            $locale = $res['Properties'][0]['City'];

                                            if (($locale)) {
                                                if (!empty($res['Properties'][0]['State'])) {
                                                    $locale .= ', ' . $res['Properties'][0]['State'];
                                                } else {
                                                    $locale .= ', CA';
                                                }
                                            }

                                            $property_details = $this->getSearchResult($address, $locale);
                                            $property_type = isset($property_details['property_type']) && !empty($property_details['property_type']) ? $property_details['property_type'] : '';
                                            $LegalDescription = isset($property_details['legaldescription']) && !empty($property_details['legaldescription']) ? $property_details['legaldescription'] : '';
                                            $apn = isset($property_details['apn']) && !empty($property_details['apn']) ? $property_details['apn'] : '';
                                            $propertyData = array(
                                                'customer_id' => $customerId,
                                                'buyer_agent_id' => 0,
                                                'listing_agent_id' => 0,
                                                'escrow_lender_id' => 0,
                                                'parcel_id' => $res['Properties'][0]['ParcelID'],
                                                'address' => removeMultipleSpace($address),
                                                'city' => $res['Properties'][0]['City'],
                                                'state' => $res['Properties'][0]['State'],
                                                'zip' => $res['Properties'][0]['Zip'],
                                                'property_type' => $property_type,
                                                'full_address' => removeMultipleSpace($FullProperty),
                                                'apn' => $apn,
                                                'county' => $res['Properties'][0]['County'],
                                                'legal_description' => $LegalDescription,
                                                'status' => 1,
                                            );

                                            $transactionData = array(
                                                'customer_id' => $customerId,
                                                'sales_amount' => !empty($res['SalesPrice']) ? $res['SalesPrice'] : 0,
                                                'loan_number' => !empty($res['Loans'][0]['LoanNumber']) ? $res['Loans'][0]['LoanNumber'] : 0,
                                                'loan_amount' => !empty($res['Loans'][0]['LoanAmount']) ? $res['Loans'][0]['LoanAmount'] : 0,
                                                'transaction_type' => $res['TransactionProductType']['TransactionTypeID'],
                                                'purchase_type' => $res['TransactionProductType']['ProductTypeID'],
                                                'sales_representative' => $salesRepId,
                                                'title_officer' => $titleOfficerId,
                                                'status' => 1,
                                            );

                                            $propertyAddress = $address;
                                            $productTypeID = $res['TransactionProductType']['ProductTypeID'];
                                            $primary_owner = ($res['Buyers'][0]['Primary']['First'] && $res['Buyers'][0]['Primary']['First']) ? $res['Buyers'][0]['Primary']['First'] : '';
                                            $primary_owner .= ($res['Buyers'][0]['Primary']['Middle'] && $res['Buyers'][0]['Primary']['Middle']) ? " " . $res['Buyers'][0]['Primary']['Middle'] : '';
                                            $primary_owner .= ($res['Buyers'][0]['Primary']['Last'] && $res['Buyers'][0]['Primary']['Last']) ? " " . $res['Buyers'][0]['Primary']['Last'] : '';
                                            $secondary_owner = ($res['Buyers'][0]['Secondary']['First'] && $res['Buyers'][0]['Secondary']['First']) ? $res['Buyers'][0]['Secondary']['First'] : '';
                                            $secondary_owner .= ($res['Buyers'][0]['Secondary']['Middle'] && $res['Buyers'][0]['Secondary']['Middle']) ? $res['Buyers'][0]['Secondary']['Middle'] : '';
                                            $secondary_owner .= ($res['Buyers'][0]['Secondary']['Last'] && $res['Buyers'][0]['Secondary']['Last']) ? " " . $res['Buyers'][0]['Secondary']['Last'] : '';
                                            $ProductTypeTxt = $res['TransactionProductType']['ProductType'];

                                            if (strpos($ProductTypeTxt, 'Loan') !== false) {
                                                $propertyData['primary_owner'] = $primary_owner;
                                                $propertyData['secondary_owner'] = $secondary_owner;
                                            } elseif (strpos($ProductTypeTxt, 'Sale') !== false) {
                                                $transactionData['borrower'] = $primary_owner;
                                                $transactionData['secondary_borrower'] = $secondary_owner;
                                                $propertyData['primary_owner'] = isset($property_details['primary_owner']) && !empty($property_details['primary_owner']) ? $property_details['primary_owner'] : '';
                                                $propertyData['secondary_owner'] = isset($property_details['secondary_owner']) && !empty($property_details['secondary_owner']) ? $property_details['secondary_owner'] : '';
                                            }

                                            $propertyId = $this->home_model->insert($propertyData, 'property_details');
                                            $transactionId = $this->home_model->insert($transactionData, 'transaction_details');
                                            $time = round((int) (str_replace("-0000)/", "", str_replace("/Date(", "", $res['Dates']['OpenedDate']))) / 1000);
                                            $created_date = date('Y-m-d H:i:s', $time);
                                            $randomString = $this->order->randomPassword();
                                            $randomString = md5($randomString);

                                            $completed_date = null;
                                            if (empty($closedDate)) {
                                                if (!empty($res['Dates']['FileCompletedDate'])) {
                                                    $time = round((int) (str_replace("-0000)/", "", str_replace("/Date(", "", $res['Dates']['FileCompletedDate']))) / 1000);
                                                    $completed_date = date('Y-m-d H:i:s', $time);
                                                }
                                            }

                                            $orderData = array(
                                                'customer_id' => $customerId,
                                                'file_id' => $res['FileID'],
                                                'file_number' => $res['FileNumber'],
                                                'property_id' => $propertyId,
                                                'transaction_id' => $transactionId,
                                                'created_at' => $created_date,
                                                'prod_type' => strtolower($prodType),
                                                'premium' => $premium,
                                                'status' => 1,
                                                'is_imported' => 1,
                                                'is_sales_rep_order' => 1,
                                                'random_number' => $randomString,
                                                'resware_closed_status_date' => $completed_date,
                                                'resware_status' => strtolower($res['Status']['Name']),
                                                'sent_to_accounting_date' => $completed_date,
                                            );

                                            if (!empty($premium)) {
                                                $orderData['premium'] = (float) $premium;
                                            }

                                            if (!empty($completed_date)) {
                                                $orderData['sent_to_accounting_date'] = $completed_date;
                                            }

                                            $orderId = $this->home_model->insert($orderData, 'order_details');
                                        }
                                    }
                                }
                                if (!empty($escrow_email)) {
                                    $from_name = 'Pacific Coast Title Company';
                                    $from_mail = env('FROM_EMAIL');
                                    $email_data = array(
                                        'orderNumber' => $file_number,
                                        'PropertyAddress' => $propertyAddress,
                                        'randomString' => $randomString,
                                        'headerImg' => $sales_rep_img,
                                        'currYear' => CURRENT_YEAR,
                                        'productTypeID' => $productTypeID,
                                    );
                                    $borrower_message_body = $this->load->view('emails/borrower.php', $email_data, true);
                                    $message_body = $borrower_message_body;
                                    $subject = $file_number . ' - Borrower Verification';
                                    //$escrow_email = 'hitesh.p@crestinfosystems.com';
                                    $mailParams = array(
                                        'from_mail' => $from_mail,
                                        'from_name' => $from_name,
                                        'to' => $escrow_email,
                                        'subject' => $subject,
                                        'message' => json_encode($email_data),
                                    );
                                    $logid = $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_escrow_officer', '', $mailParams, array(), $orderId, 0);
                                    //$escrow_mail_result = send_email($from_mail,$from_name, $escrow_email, $subject, $message_body);
                                    $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_escrow_officer', '', $mailParams, array('status' => $escrow_mail_result), $orderId, $logid);
                                }
                            }
                        }
                        $row++;
                    }
                    fclose($handle);
                }
                $documentName = pathinfo($filePath);
                $fileName = date('YmdHis') . "_" . $documentName['basename'];
                rename(FCPATH . "/uploads/open-closed-orders/" . $documentName['basename'], FCPATH . "/uploads/open-closed-orders/" . $fileName);
                $this->order->uploadDocumentOnAwsS3($fileName, 'open-closed-orders', 1);
            }
        } else {
            echo "No files found";exit;
        }
        echo "All data exported successfully";exit;
    }

    public function sendMailEscrowUsersForBorrowerVerification()
    {
        $this->db->select('order_details.file_id,
            order_details.file_number,
            order_details.id as orderId,
            order_details.random_number,
            property_details.address,
            pct_order_partner_company_info.email,
            transaction_details.sales_representative');
        $this->db->from('order_details');
        $this->db->where('((order_details.resware_status != "closed" AND order_details.resware_status != "cancelled") OR order_details.resware_status IS NULL)');
        $this->db->where('transaction_details.purchase_type = 4');
        $this->db->where('order_details.escrow_officer_id IS NOT NULL');
        $this->db->where('order_details.borrower_information_document_name IS NULL');
        $this->db->where('order_details.file_number IN (10231167,10232453,10232646,10230875,10231110,10231114,10231186,10231250,10231659,10231758,10231974,10231981,10231999,10232000,10232286,10232287,10232400,10232448,10232543,10232547,10232595,10232597)');
        $this->db->join('property_details', 'order_details.property_id = property_details.id', 'inner');
        $this->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id', 'inner');
        $this->db->join('pct_order_partner_company_info', 'pct_order_partner_company_info.partner_id = order_details.escrow_officer_id', 'inner');
        $query = $this->db->get();
        $result = $query->result_array();

        if (!empty($result)) {
            foreach ($result as $res) {
                $salesRepDetails = array();
                if (!empty($res['sales_representative'])) {
                    $condition = array(
                        'id' => $res['sales_representative'],
                    );
                    $salesRepDetails = $this->home_model->getSalesRepDetails($condition);
                }

                $sales_rep_img = isset($salesRepDetails["sales_rep_profile_img"]) && !empty($salesRepDetails["sales_rep_profile_img"]) ? $salesRepDetails["sales_rep_profile_img"] : '';
                if (!empty($sales_rep_img)) {
                    $sales_rep_img = env('AWS_PATH') . str_replace('uploads/', '', $sales_rep_img);
                }
                $email_data = array(
                    'orderNumber' => $res['file_number'],
                    'PropertyAddress' => $res['address'],
                    'randomString' => $res['random_number'],
                    'headerImg' => $sales_rep_img,
                    'currYear' => CURRENT_YEAR,
                    'productTypeID' => 4,
                );

                $borrower_message_body = $this->load->view('emails/borrower.php', $email_data, true);
                $from_name = 'Pacific Coast Title Company';
                $from_mail = env('FROM_EMAIL');

                $message_body = $borrower_message_body;
                $subject = $res['file_number'] . ' - Borrower Verification';
                $to = $res['email'];
                $cc = array('ghernandez@pct.com');
                //$cc = array();
                //$to = 'hitesh.p@crestinfosystems.com';
                $mailParams = array(
                    'from_mail' => $from_mail,
                    'from_name' => $from_name,
                    'to' => $to,
                    'subject' => $subject,
                    'message' => json_encode($email_data),
                );

                $logid = $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_escrow_officer', '', $mailParams, array(), $res['orderId'], 0);
                $this->load->helper('sendemail');
                $escrow_mail_result = send_email($from_mail, $from_name, $to, $subject, $message_body, array(), $cc);
                $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_escrow_officer', '', $mailParams, array('status' => $escrow_mail_result), $res['orderId'], $logid);
            }
        }
    }

    public function importOrdersUsingFileNumber()
    {
        $ordersInfo = array(
            array(
                'file_number' => 10231167,
                'sales_person' => 'Jared Armas',
            ),
            array(
                'file_number' => 10232453,
                'sales_person' => 'Malay Wadhwa',
            ),
            array(
                'file_number' => 10232646,
                'sales_person' => 'Jared Armas',
            ),
            array(
                'file_number' => 10230875,
                'sales_person' => 'Lisa Lee',
            ),
            array(
                'file_number' => 10231110,
                'sales_person' => 'Max Galindo',
            ),
            array(
                'file_number' => 10231114,
                'sales_person' => 'Max Galindo',
            ),
            array(
                'file_number' => 10231186,
                'sales_person' => 'Louis Morreale',
            ),
            array(
                'file_number' => 10231250,
                'sales_person' => 'Cibeli Tregembo',
            ),
            array(
                'file_number' => 10231659,
                'sales_person' => 'Jared Armas',
            ),
            array(
                'file_number' => 10231758,
                'sales_person' => 'Jared Armas',
            ),
            array(
                'file_number' => 10231974,
                'sales_person' => 'Max Galindo',
            ),
            array(
                'file_number' => 10231981,
                'sales_person' => 'Daphne Alt',
            ),
            array(
                'file_number' => 10231999,
                'sales_person' => 'Cibeli Tregembo',
            ),
            // array(
            //     'file_number' => 10232000,
            //     'sales_person' => 'In House - SoCal Ventura',
            // ),
            array(
                'file_number' => 10232286,
                'sales_person' => 'Jared Armas',
            ),
            array(
                'file_number' => 10232287,
                'sales_person' => 'Cibeli Tregembo',
            ),
            array(
                'file_number' => 10232400,
                'sales_person' => 'Justin Nouri',
            ),
            array(
                'file_number' => 10232448,
                'sales_person' => 'Cibeli Tregembo',
            ),
            array(
                'file_number' => 10232543,
                'sales_person' => 'Louis Morreale',
            ),
            array(
                'file_number' => 10232547,
                'sales_person' => 'Louis Morreale',
            ),
        );
        foreach ($ordersInfo as $orderInfo) {
            $data = array();
            $data['FileNumber'] = $orderInfo['file_number'];
            $this->db->select('*');
            $this->db->from('order_details');
            $this->db->where('file_number', $orderInfo['file_number']);
            $query = $this->db->get();
            $resultorders = $query->row_array();

            $sales_rep_name = explode(' ', $orderInfo['sales_person']);
            $this->db->select('*');
            $this->db->from('customer_basic_details');
            $this->db->like('first_name', $sales_rep_name[0]);
            $this->db->like('last_name', $sales_rep_name[1]);
            $this->db->where('is_sales_rep', 1);
            $query = $this->db->get();
            $salesResult = $query->row_array();

            if (empty($resultorders)) {
                $data = json_encode(array('FileNumber' => $orderInfo['file_number']));
                $userData = array(
                    'admin_api' => 1,
                );
                $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_order_information', env('RESWARE_ORDER_API') . 'files/search', $data, array(), 0, 0);
                $res = $this->make_request('POST', 'files/search', $data, $userData);
                $this->apiLogs->syncLogs(0, 'resware', 'get_order_information', env('RESWARE_ORDER_API') . 'files/search', $data, $res, 0, $logid);
                $result = json_decode($res, true);

                if (isset($result['Files']) && !empty($result['Files'])) {
                    foreach ($result['Files'] as $res) {
                        $partner_fname = $res['Partners'][0]['PrimaryEmployee']['FirstName'];
                        $partner_lname = $res['Partners'][0]['PrimaryEmployee']['LastName'];
                        $partner_name = $res['Partners'][0]['PartnerName'];
                        $condition = array(
                            'first_name' => $partner_fname,
                            'last_name' => $partner_lname,
                            'company_name' => $partner_name,
                            'is_pass' => $partner_name,
                        );
                        $user_details = $this->home_model->get_user_by_name($condition);
                        $customerId = 0;

                        if (isset($user_details) && !empty($user_details)) {
                            $customerId = $user_details['id'];
                        }

                        $FullProperty = $res['Properties'][0]['StreetNumber'] . " " . $res['Properties'][0]['StreetDirection'] . " " . $res['Properties'][0]['StreetName'] . " " . $res['Properties'][0]['StreetSuffix'] . ", " . $res['Properties'][0]['City'] . ", " . $res['Properties'][0]['State'] . ", " . $res['Properties'][0]['Zip'];
                        $address = $res['Properties'][0]['StreetNumber'] . " " . $res['Properties'][0]['StreetDirection'] . " " . $res['Properties'][0]['StreetName'] . " " . $res['Properties'][0]['StreetSuffix'];
                        $locale = $res['Properties'][0]['City'];

                        if (($locale)) {
                            if (!empty($res['Properties'][0]['State'])) {
                                $locale .= ', ' . $res['Properties'][0]['State'];
                            } else {
                                $locale .= ', CA';
                            }
                        }

                        $property_details = $this->getSearchResult($address, $locale);

                        $property_type = isset($property_details['property_type']) && !empty($property_details['property_type']) ? $property_details['property_type'] : '';
                        $LegalDescription = isset($property_details['legaldescription']) && !empty($property_details['legaldescription']) ? $property_details['legaldescription'] : '';
                        $apn = isset($property_details['apn']) && !empty($property_details['apn']) ? $property_details['apn'] : '';

                        $propertyData = array(
                            'customer_id' => $customerId,
                            'buyer_agent_id' => 0,
                            'listing_agent_id' => 0,
                            'escrow_lender_id' => 0,
                            'parcel_id' => $res['Properties'][0]['ParcelID'],
                            'address' => removeMultipleSpace($address),
                            'city' => $res['Properties'][0]['City'],
                            'state' => $res['Properties'][0]['State'],
                            'zip' => $res['Properties'][0]['Zip'],
                            'property_type' => $property_type,
                            'full_address' => removeMultipleSpace($FullProperty),
                            'apn' => $apn,
                            'county' => $res['Properties'][0]['County'],
                            'legal_description' => $LegalDescription,
                            'status' => 1,
                        );

                        $transactionData = array(
                            'customer_id' => $customerId,
                            'sales_amount' => !empty($res['SalesPrice']) ? $res['SalesPrice'] : 0,
                            'loan_number' => !empty($res['Loans'][0]['LoanNumber']) ? $res['Loans'][0]['LoanNumber'] : 0,
                            'loan_amount' => !empty($res['Loans'][0]['LoanAmount']) ? $res['Loans'][0]['LoanAmount'] : 0,
                            'transaction_type' => $res['TransactionProductType']['TransactionTypeID'],
                            'purchase_type' => $res['TransactionProductType']['ProductTypeID'],
                            'sales_representative' => !empty($salesResult) ? $salesResult['id'] : 0,
                            'status' => 1,
                        );

                        $primary_owner = ($res['Buyers'][0]['Primary']['First'] && $res['Buyers'][0]['Primary']['First']) ? $res['Buyers'][0]['Primary']['First'] : '';
                        $primary_owner .= ($res['Buyers'][0]['Primary']['Middle'] && $res['Buyers'][0]['Primary']['Middle']) ? " " . $res['Buyers'][0]['Primary']['Middle'] : '';
                        $primary_owner .= ($res['Buyers'][0]['Primary']['Last'] && $res['Buyers'][0]['Primary']['Last']) ? " " . $res['Buyers'][0]['Primary']['Last'] : '';
                        $secondary_owner = ($res['Buyers'][0]['Secondary']['First'] && $res['Buyers'][0]['Secondary']['First']) ? $res['Buyers'][0]['Secondary']['First'] : '';
                        $secondary_owner .= ($res['Buyers'][0]['Secondary']['Middle'] && $res['Buyers'][0]['Secondary']['Middle']) ? $res['Buyers'][0]['Secondary']['Middle'] : '';
                        $secondary_owner .= ($res['Buyers'][0]['Secondary']['Last'] && $res['Buyers'][0]['Secondary']['Last']) ? " " . $res['Buyers'][0]['Secondary']['Last'] : '';
                        $ProductTypeTxt = $res['TransactionProductType']['ProductType'];

                        if (strpos($ProductTypeTxt, 'Loan') !== false) {
                            $propertyData['primary_owner'] = $primary_owner;
                            $propertyData['secondary_owner'] = $secondary_owner;
                        } elseif (strpos($ProductTypeTxt, 'Sale') !== false) {
                            $transactionData['borrower'] = $primary_owner;
                            $transactionData['secondary_borrower'] = $secondary_owner;
                            $propertyData['primary_owner'] = isset($property_info['primary_owner']) && !empty($property_info['primary_owner']) ? $property_info['primary_owner'] : '';
                            $propertyData['secondary_owner'] = isset($property_info['secondary_owner']) && !empty($property_info['secondary_owner']) ? $property_info['secondary_owner'] : '';
                        }

                        $propertyId = $this->home_model->insert($propertyData, 'property_details');
                        $transactionId = $this->home_model->insert($transactionData, 'transaction_details');
                        $time = round((int) (str_replace("-0000)/", "", str_replace("/Date(", "", $res['Dates']['OpenedDate']))) / 1000);
                        $created_date = date('Y-m-d H:i:s', $time);
                        $randomString = $this->order->randomPassword();

                        $randomString = md5($randomString);

                        if ($orderInfo['file_number'] == 10231167 || $orderInfo['file_number'] == 10232453 || $orderInfo['file_number'] == 10232646) {
                            $escrow_officer_id = 318384;
                        } else {
                            $escrow_officer_id = 304961;
                        }

                        $orderData = array(
                            'customer_id' => $customerId,
                            'file_id' => $res['FileID'],
                            'file_number' => $res['FileNumber'],
                            'property_id' => $propertyId,
                            'transaction_id' => $transactionId,
                            'created_at' => $created_date,
                            'status' => 1,
                            'is_imported' => 1,
                            'is_sales_rep_order' => 0,
                            'escrow_officer_id' => $escrow_officer_id,
                            'random_number' => $randomString,
                            'resware_status' => strtolower($res['Status']['Name']),
                        );

                        $this->home_model->insert($orderData, 'order_details');
                    }
                }
            } else {
                if ($orderInfo['file_number'] == 10231167 || $orderInfo['file_number'] == 10232453 || $orderInfo['file_number'] == 10232646) {
                    $escrow_officer_id = 318384;
                } else {
                    $escrow_officer_id = 304961;
                }

                $condition = array(
                    'file_number' => $orderInfo['FileNumber'],
                );

                $orderData = array(
                    'escrow_officer_id' => $escrow_officer_id,
                );

                $this->home_model->update($orderData, $condition, 'order_details');

                $transCondition = array(
                    'id' => $resultorders['transaction_id'],
                );

                $transData = array(
                    'sales_representative' => !empty($salesResult) ? $salesResult['id'] : 0,
                );

                $this->home_model->update($transData, $transCondition, 'transaction_details');
            }
        }
        echo "All orders imported successfully.";exit;
    }

    public function passwordUpdateAll()
    {
        $this->load->model('order/apiLogs');
        $this->load->library('order/resware');
        $this->load->library('order/order');
        $this->db->select('*');
        $this->db->from('customer_basic_details');
        $this->db->where('(is_password_updated = 0 and random_password != "")');
        $query = $this->db->get();
        $result = $query->result_array();

        if (!empty($result)) {
            foreach ($result as $customerData) {
                $endPoint = 'admin/partners/' . $customerData['partner_id'] . '/employees/' . $customerData['resware_user_id'];
                $method = 'PUT';
                $apiType = 'update_user';

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

                $userdata['admin_api'] = 1;
                $newUserData = json_encode($newUserData);
                $logid = $this->apiLogs->syncLogs(0, 'resware', $apiType, env('RESWARE_ORDER_API') . $endPoint, $newUserData, array(), 0, 0);
                $res = $this->make_request($method, $endPoint, $newUserData, $userdata);

                $this->apiLogs->syncLogs(0, 'resware', $apiType, env('RESWARE_ORDER_API') . $endPoint, $newUserData, $res, 0, $logid);

                if (isset($res) && !empty($res)) {
                    $response = json_decode($res, true);
                    if (isset($response['Employee']) && !empty($response['Employee'])) {
                        $res = array(
                            'resware_user_id' => $response['Employee']['UserID'],
                            'msg' => !empty($customerData['resware_user_id']) ? 'User created successfully on Resware Side' : 'User updated successfully on Resware Side',
                            'success' => true,
                        );
                        $customerDataUpdate = array();
                        $customerDataUpdate['resware_user_id'] = $response['Employee']['UserID'];
                        $customerDataUpdate['random_password'] = $this->order->randomPassword();
                        $reswareUpdatePwdData = array(
                            'user_name' => $customerData['email_address'],
                            'password' => 'Pacific1',
                            'new_password' => $customerDataUpdate['random_password'],
                        );
                        $logid = $this->apiLogs->syncLogs($userdata['id'], 'resware', 'change_password', env('RESWARE_UPDATE_PWD_API'), $reswareUpdatePwdData, array(), 0, 0);
                        $updatePwdResult = $this->updatePasswordResware($reswareUpdatePwdData);
                        $this->apiLogs->syncLogs($userdata['id'], 'resware', 'change_password', env('RESWARE_UPDATE_PWD_API'), $reswareUpdatePwdData, $updatePwdResult, 0, $logid);
                        $responsePwd = json_decode($updatePwdResult, true);

                        if (!empty($responsePwd['message'])) {
                            $customerDataUpdate['resware_error_msg'] = $responsePwd['message'];
                        } else {
                            $customerDataUpdate['is_password_updated'] = 1;
                        }
                        $updateCondition = array(
                            'id' => $customerData['id'],
                        );
                        $this->home_model->update($customerDataUpdate, $updateCondition, 'customer_basic_details');
                    }
                }
            }
        }
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

    public function updateAllOrderStatus()
    {
        echo date('Y-m-d H:i:s') . "<br>";
        $sftp = new SFTP(env('SFTP_HOST'));
        $username = env('SFTP_USERNAME');
        $password = env('SFTP_PASSWORD');

        if (!$sftp->login($username, $password)) {
            exit('Login Failed');
        }

        if (!($files = $sftp->nlist('/' . env('SFTP_FOLDER') . '/order-status/', true))) {
            die("Cannot read directory contents");
        }

        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && $file != '.protected') {
                if (!is_dir('uploads/order-status')) {
                    mkdir('./uploads/order-status', 0777, true);
                }
                $ext = pathinfo($file, PATHINFO_EXTENSION);
                if (!empty($ext)) {
                    $sftp->get(env("SFTP_FOLDER") . '/order-status/' . $file, FCPATH . 'uploads/order-status/' . trim($file));
                    chmod(FCPATH . 'uploads/order-status/' . $file, 0755);
                } else {
                    $sftp->get(env("SFTP_FOLDER") . '/order-status/' . $file, FCPATH . 'uploads/order-status/' . trim($file) . '.csv');
                    chmod(FCPATH . 'uploads/order-status/' . trim($file) . '.csv', 0755);
                }
                $sftp->delete(env("SFTP_FOLDER") . '/order-status/' . $file);
            }
        }

        /** get all sales reps */
        $this->db->select("id, LOWER(CONCAT_WS(' ', first_name, last_name)) AS sales_name, LOWER(email_address) as email");
        $this->db->from('customer_basic_details');
        $this->db->where('is_sales_rep', 1);
        $query = $this->db->get();
        $salesUsers = $query->result_array();
        /** End get all sales reps */
        $files = glob("uploads/order-status/*csv", GLOB_NOSORT);

        $closedFileNumbers = array();
        if (is_array($files) && count($files) > 0) {
            foreach ($files as $filePath) {
                $row = 1;
                $headerColumns = array();
                $updateArray = array();
                $duplicationUpdateArray = array();
                if (($handle = fopen($filePath, "r")) !== false) {
                    while (($data = fgetcsv($handle, 1000, ",", '"')) !== false) {
                        $num = count($data);
                        if ($row == 1) {
                            for ($c = 0; $c < $num; $c++) {
                                $headerColumns[] = trim($data[$c]);
                            }
                        }

                        $file_number = '';
                        $fileStatus = '';
                        $closedDate = '';
                        $loan_amount = '';
                        $sales_amount = '';
                        $prodType = '';
                        $salesRepId = 0;

                        if (in_array('File Number', $headerColumns)) {
                            $fileKey = array_search("File Number", $headerColumns);
                            $file_number = $data[$fileKey];
                        }

                        if (in_array('File Status', $headerColumns)) {
                            $fileStatusKey = array_search("File Status", $headerColumns);
                            $fileStatus = $data[$fileStatusKey];
                        }

                        if (in_array('Closed Date', $headerColumns)) {
                            $closedDateKey = array_search("Closed Date", $headerColumns);
                            $closedDate = $data[$closedDateKey];
                        }

                        if (in_array('Loan Amount', $headerColumns)) {
                            $loanAmountKey = array_search("Loan Amount", $headerColumns);
                            $loan_amount = $data[$loanAmountKey];
                        }

                        if (in_array('Sales Price', $headerColumns)) {
                            $salePriceKey = array_search("Sales Price", $headerColumns);
                            $sales_amount = $data[$salePriceKey];
                        }

                        if (in_array('Prod Type', $headerColumns)) {
                            $prodkey = array_search("Prod Type", $headerColumns);
                            $prodType = $data[$prodkey];
                        }

                        /** Check and update sales rep logic */
                        if (in_array('Email', $headerColumns)) {
                            $emailkey = array_search("Email", $headerColumns);
                            $sales_email = strtolower(trim($data[$emailkey]));
                            if (!empty($sales_email)) {
                                $saleUserKey = array_search($sales_email, array_column($salesUsers, 'email'));
                                if (isset($saleUserKey) && !empty($saleUserKey)) {
                                    $salesRepId = $salesUsers[$saleUserKey]['id'];
                                }
                            }
                        }

                        if ($salesRepId == 0) {
                            $salesRepName = '';
                            if (in_array('Sales Rep', $headerColumns)) {
                                $saleskey = array_search("Sales Rep", $headerColumns);
                                $salesRepName = strtolower(trim(preg_replace('/\s+/', ' ', $data[$saleskey])));
                                if (!empty($salesRepName)) {
                                    $saleUserKey = array_search($salesRepName, array_column($salesUsers, 'sales_name'));
                                    if (isset($saleUserKey) && !empty($saleUserKey)) {
                                        $salesRepId = $salesUsers[$saleUserKey]['id'];
                                    }
                                }
                            }
                        }
                        /** End Check and update sales rep logic */

                        if ($row != 1) {
                            if (1 === preg_match('~[0-9]~', $file_number)) {
                                $completed_date = null;
                                if (!empty($closedDate)) {
                                    $myDateTime = DateTime::createFromFormat('M d, Y', $closedDate);
                                    $completed_date = $myDateTime->format('Y-m-d H:i:s');
                                }
                                if (strtolower($fileStatus) == 'closed') {
                                    if (isset($completed_date) && (date('Y', strtotime($completed_date)) == date('Y')) && (date('m', strtotime($completed_date)) == date('m'))) {
                                        if (strtolower($prodType) == 'sale' || strtolower($prodType) == 'loan') {
                                            if (!in_array($file_number, $closedFileNumbers)) {
                                                $closedFileNumbers[] = (int) $file_number;
                                            }
                                        }
                                    }
                                }

                                $updateArray[] = array(
                                    'file_number' => (int) $file_number,
                                    'resware_status' => strtolower($fileStatus),
                                    'resware_closed_status_date' => strtolower($fileStatus) == 'closed' ? $completed_date : null,
                                    'loan_amount' => (int) $loan_amount,
                                    'sales_amount' => (int) $sales_amount,
                                    'updated_at' => date('Y-m-d H:i:s'),
                                );

                                $this->db->from('order_details as o');
                                $this->db->select('o.file_number, o.transaction_id, transaction_details.sales_representative');
                                $this->db->join('transaction_details', 'o.transaction_id = transaction_details.id', 'inner');
                                $this->db->where('o.file_number', $file_number);
                                $query = $this->db->get();
                                $salesDetails = $query->row_array();
                                if ($salesDetails['sales_representative'] != $salesRepId) {
                                    $updateData = array('sales_representative' => $salesRepId);

                                    $this->db->set($updateData);
                                    $this->db->where('id', $salesDetails['transaction_id']);
                                    $this->db->update('transaction_details');
                                    /** Save user Activity */
                                    $activity = 'Sales rep changed from update order status cron from :- ' . $salesDetails['sales_representative'] . ' to :- ' . $salesRepId . ' For order number: ' . $file_number;
                                    $this->order->logAdminActivity($activity);
                                    /** End Save user activity */
                                }
                            }
                        }
                        $row++;
                    }
                    fclose($handle);

                    if (!empty($updateArray)) {
                        $chunk1 = array_chunk($updateArray, 100);
                        for ($i = 0; $i < count($chunk1); $i++) {
                            $this->db->update_batch('order_details', $chunk1[$i], 'file_number') . "<br>";
                        }
                    }
                }

                $updateData = array('resware_status' => 'open');
                $this->db->set($updateData);
                $this->db->where('lp_file_number IS NOT NULL');
                $this->db->where('file_number', 0);
                $this->db->update('order_details');

                $documentName = pathinfo($filePath);
                $fileName = date('YmdHis') . "_" . $documentName['basename'];
                rename(FCPATH . "/uploads/order-status/" . $documentName['basename'], FCPATH . "/uploads/order-status/" . $fileName);
                $this->order->uploadDocumentOnAwsS3($fileName, 'order-status', 1);

                if (!empty($closedFileNumbers)) {
                    // $param = $closedFileNumbers;
                    // $command = "php ".FCPATH."index.php frontend/order/cron sendThankYouEmailForClosedOrder $param";
                    // if (substr(php_uname(), 0, 7) == "Windows"){
                    //     pclose(popen("start /B ". $command, "r"));
                    // }
                    // else {
                    //     exec($command . " > /dev/null &");
                    // }

                    /** Commented this function to avoid duplicate email suggested by Jerry on 10/05/2024 */
                    // $this->sendThankYouEmailForClosedOrder($closedFileNumbers);

                    $this->sendEmailForClosedOrder($closedFileNumbers);

                }
                echo "All orders status updated successfully" . "<br>";
                $this->updateAllowDuplicationFlag();
                echo date('Y-m-d H:i:s');exit;
            }
        } else {
            echo "No files found";exit;
        }
    }

    public function removeDocServer()
    {
        $this->load->library('order/order');
        $folders = array();
        $path = "uploads/";
        $sub_folder = scandir($path);
        $num = count($sub_folder);
        $countSyncFiles = 0;
        $countUnlinkFiles = 0;
        for ($i = 2; $i < $num; $i++) {
            if (is_file($path . $sub_folder[$i])) {
                $fileExist = $this->order->fileExistOrNotOnS3($sub_folder[$i]);
                if ($fileExist) {
                    chmod($path . $sub_folder[$i], 0644);
                    gc_collect_cycles();
                    unlink($path . $sub_folder[$i]);
                    $countUnlinkFiles++;
                } else {
                    $this->order->uploadDocumentOnAwsS3($sub_folder[$i]);
                    $countSyncFiles++;
                }
            } else {
                if ($sub_folder[$i] != 'orders') {
                    $folders[] = $sub_folder[$i];
                }
            }
        }

        foreach ($folders as $folder) {
            $fileSystemIterator = new FilesystemIterator("uploads/" . $folder . "/");
            foreach ($fileSystemIterator as $fileInfo) {
                if ($folder == 'sales-rep' && $fileInfo->getFilename() == 'default.jpg') {
                    continue;
                }
                $fileExist = $this->order->fileExistOrNotOnS3($folder . "/" . $fileInfo->getFilename());
                if ($fileExist) {
                    chmod($path . $folder . "/" . $fileInfo->getFilename(), 0644);
                    gc_collect_cycles();
                    unlink($path . $folder . "/" . $fileInfo->getFilename());
                    $countUnlinkFiles++;
                } else {
                    $this->order->uploadDocumentOnAwsS3($fileInfo->getFilename(), $folder);
                    $countSyncFiles++;
                }
            }
        }
        echo $countSyncFiles . " files synced successfully on S3 <br/>";
        echo $countUnlinkFiles . " files unlinked successfully from server <br/>";
        exit;
    }

    public function sendMessageRecordingConfirmation()
    {
        $this->load->model('order/twilioMessage');
        $this->load->library('order/twilio');
        $sftp = new SFTP(env('SFTP_HOST'));
        $username = env('SFTP_USERNAME');
        $password = env('SFTP_PASSWORD');

        if (!$sftp->login($username, $password)) {
            exit('Login Failed');
        }

        if (!($files = $sftp->nlist('/' . env('SFTP_FOLDER') . '/recording-confirmation/', true))) {
            die("Cannot read directory contents");
        }

        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && $file != '.protected') {
                if (!is_dir('uploads/recording-confirmation')) {
                    mkdir('./uploads/recording-confirmation', 0777, true);
                }
                $sftp->get(env("SFTP_FOLDER") . '/recording-confirmation/' . $file, FCPATH . 'uploads/recording-confirmation/' . trim($file) . '.csv');
                chmod(FCPATH . 'uploads/recording-confirmation/' . $file . '.csv', 0755);
                $sftp->delete(env("SFTP_FOLDER") . '/recording-confirmation/' . $file);
            }
        }
        $files = glob("uploads/recording-confirmation/*csv", GLOB_NOSORT);

        if (is_array($files) && count($files) > 0) {
            foreach ($files as $filePath) {
                $row = 1;
                $headerColumns = array();
                if (($handle = fopen($filePath, "r")) !== false) {
                    while (($data = fgetcsv($handle, 1000, ",", '"')) !== false) {
                        $num = count($data);
                        if ($row == 1) {
                            for ($c = 0; $c < $num; $c++) {
                                $headerColumns[] = trim($data[$c]);
                            }
                        }

                        $file_number = '';
                        $salesRepName = '';
                        $message = '';

                        if (in_array('File Number', $headerColumns)) {
                            $fileKey = array_search("File Number", $headerColumns);
                            $file_number = $data[$fileKey];
                        }

                        $salesRepName = '';
                        if (in_array('Sales Rep', $headerColumns)) {
                            $saleskey = array_search("Sales Rep", $headerColumns);
                            $salesRepName = $data[$saleskey];
                            $salesRepName = str_replace(' ', '-', $salesRepName);
                            $salesRepName = preg_replace('/[^A-Za-z0-9\-]/', '', $salesRepName);
                            $salesRepName = str_replace('-', ' ', $salesRepName);
                        }

                        if (in_array('Body', $headerColumns)) {
                            $messageKey = array_search("Body", $headerColumns);
                            $message = $data[$messageKey];
                        }

                        if ($row != 1) {
                            // echo $file_number."---".$salesRepName."--------".$salesRepName."---";exit;
                            $condition = array(
                                'where' => array(
                                    'file_number' => $file_number,
                                ),
                            );
                            $order = $this->order->get_order($condition);
                            if (!empty($order)) {
                                $orderDetails = $this->order->get_order_details($order[0]['file_id']);
                                $resultSales = array();
                                if (!empty($salesRepName) && empty($orderDetails['sales_representative'])) {
                                    $this->db->select('*');
                                    $this->db->from('customer_basic_details');
                                    $this->db->like("CONCAT_WS(' ', first_name, last_name)", $salesRepName);
                                    $this->db->where('is_sales_rep', 1);
                                    $query = $this->db->get();
                                    $resultSales = $query->row_array();
                                } else {
                                    $this->db->select('*');
                                    $this->db->from('customer_basic_details');
                                    $this->db->where("id", $orderDetails['sales_representative']);
                                    $this->db->where('is_sales_rep', 1);
                                    $query = $this->db->get();
                                    $resultSales = $query->row_array();
                                }
                                if (!empty($resultSales)) {
                                    if (isset($resultSales['telephone_no']) && !empty($resultSales['telephone_no'])) {
                                        $phoneNumber = $resultSales['telephone_no'];
                                        $sid = env('TWILIO_SID');
                                        $token = env('TWILIO_TOKEN');
                                        $from = env('TWILIO_FROM');
                                        $data = array(
                                            'message' => $message,
                                            'account_sid' => $sid,
                                            'token' => $token,
                                            'to' => $phoneNumber,
                                            'from' => $from,
                                        );

                                        $logid = $this->apiLogs->syncLogs('', 'twilio', 'send_message', '', $data, array(), 0, 0);

                                        /*try {
                                    $result = $this->twilio->message($phoneNumber, $message, '', array('from' => $from));
                                    $response = $result->toArray();
                                    $response['msg_status'] = 'success';
                                    } catch (Exception $e) {
                                    $response['sid'] = '';
                                    $response['to'] = $phoneNumber;
                                    $response['msg_status'] = 'error';
                                    $response['errorCode'] = $e->getCode();
                                    $response['errorMessage'] = $e->getMessage();
                                    } catch (\Twilio\Exceptions\RestException $e) {
                                    $response['sid'] = '';
                                    $response['to'] = $phoneNumber;
                                    $response['msg_status'] = 'error';
                                    $response['errorCode'] = $e->getCode();
                                    $response['errorMessage'] = $e->getMessage();
                                    }
                                    $this->apiLogs->syncLogs('', 'twilio', 'send_message', '', $data, $response, 0, $logid);

                                    if($response['msg_status'] == 'success') {

                                    $this->home_model->update(array('is_sent_recording_msg_sales_user' => 1), array('file_number' => $file_number), 'order_details');

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
                                    } */
                                    }
                                }
                            } else {
                                $data = json_encode(array('FileNumber' => $file_number));
                                $userData = array(
                                    'admin_api' => 1,
                                );
                                $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_order_information', env('RESWARE_ORDER_API') . 'files/search', $data, array(), 0, 0);
                                $res = $this->make_request('POST', 'files/search', $data, $userData);
                                $this->apiLogs->syncLogs(0, 'resware', 'get_order_information', env('RESWARE_ORDER_API') . 'files/search', $data, $res, 0, $logid);
                                $result = json_decode($res, true);

                                if (isset($result['Files']) && !empty($result['Files'])) {
                                    foreach ($result['Files'] as $res) {
                                        $partner_fname = $res['Partners'][0]['PrimaryEmployee']['FirstName'];
                                        $partner_lname = $res['Partners'][0]['PrimaryEmployee']['LastName'];
                                        $partner_name = $res['Partners'][0]['PartnerName'];
                                        $condition = array(
                                            'first_name' => $partner_fname,
                                            'last_name' => $partner_lname,
                                            'company_name' => $partner_name,
                                            'is_pass' => $partner_name,
                                        );
                                        $user_details = $this->home_model->get_user_by_name($condition);
                                        $customerId = 0;

                                        if (isset($user_details) && !empty($user_details)) {
                                            $customerId = $user_details['id'];
                                        }

                                        $FullProperty = $res['Properties'][0]['StreetNumber'] . " " . $res['Properties'][0]['StreetDirection'] . " " . $res['Properties'][0]['StreetName'] . " " . $res['Properties'][0]['StreetSuffix'] . ", " . $res['Properties'][0]['City'] . ", " . $res['Properties'][0]['State'] . ", " . $res['Properties'][0]['Zip'];
                                        $address = $res['Properties'][0]['StreetNumber'] . " " . $res['Properties'][0]['StreetDirection'] . " " . $res['Properties'][0]['StreetName'] . " " . $res['Properties'][0]['StreetSuffix'];
                                        $locale = $res['Properties'][0]['City'];

                                        if (($locale)) {
                                            if (!empty($res['Properties'][0]['State'])) {
                                                $locale .= ', ' . $res['Properties'][0]['State'];
                                            } else {
                                                $locale .= ', CA';
                                            }
                                        }

                                        $property_details = $this->getSearchResult($address, $locale);
                                        $property_type = isset($property_details['property_type']) && !empty($property_details['property_type']) ? $property_details['property_type'] : '';
                                        $LegalDescription = isset($property_details['legaldescription']) && !empty($property_details['legaldescription']) ? $property_details['legaldescription'] : '';
                                        $apn = isset($property_details['apn']) && !empty($property_details['apn']) ? $property_details['apn'] : '';
                                        $propertyData = array(
                                            'customer_id' => $customerId,
                                            'buyer_agent_id' => 0,
                                            'listing_agent_id' => 0,
                                            'escrow_lender_id' => 0,
                                            'parcel_id' => $res['Properties'][0]['ParcelID'],
                                            'address' => removeMultipleSpace($address),
                                            'city' => $res['Properties'][0]['City'],
                                            'state' => $res['Properties'][0]['State'],
                                            'zip' => $res['Properties'][0]['Zip'],
                                            'property_type' => $property_type,
                                            'full_address' => removeMultipleSpace($FullProperty),
                                            'apn' => $apn,
                                            'county' => $res['Properties'][0]['County'],
                                            'legal_description' => $LegalDescription,
                                            'status' => 1,
                                        );

                                        $resultSales = array();
                                        if (!empty($salesRepName)) {
                                            $this->db->select('*');
                                            $this->db->from('customer_basic_details');
                                            $this->db->like("CONCAT_WS(' ', first_name, last_name)", $salesRepName);
                                            $this->db->where('is_sales_rep', 1);
                                            $query = $this->db->get();
                                            $resultSales = $query->row_array();
                                        }

                                        $transactionData = array(
                                            'customer_id' => $customerId,
                                            'sales_amount' => !empty($res['SalesPrice']) ? $res['SalesPrice'] : 0,
                                            'loan_number' => !empty($res['Loans'][0]['LoanNumber']) ? $res['Loans'][0]['LoanNumber'] : 0,
                                            'loan_amount' => !empty($res['Loans'][0]['LoanAmount']) ? $res['Loans'][0]['LoanAmount'] : 0,
                                            'transaction_type' => $res['TransactionProductType']['TransactionTypeID'],
                                            'purchase_type' => $res['TransactionProductType']['ProductTypeID'],
                                            'sales_representative' => !empty($resultSales) ? $resultSales['id'] : 0,
                                            'status' => 1,
                                        );

                                        $primary_owner = ($res['Buyers'][0]['Primary']['First'] && $res['Buyers'][0]['Primary']['First']) ? $res['Buyers'][0]['Primary']['First'] : '';
                                        $primary_owner .= ($res['Buyers'][0]['Primary']['Middle'] && $res['Buyers'][0]['Primary']['Middle']) ? " " . $res['Buyers'][0]['Primary']['Middle'] : '';
                                        $primary_owner .= ($res['Buyers'][0]['Primary']['Last'] && $res['Buyers'][0]['Primary']['Last']) ? " " . $res['Buyers'][0]['Primary']['Last'] : '';
                                        $secondary_owner = ($res['Buyers'][0]['Secondary']['First'] && $res['Buyers'][0]['Secondary']['First']) ? $res['Buyers'][0]['Secondary']['First'] : '';
                                        $secondary_owner .= ($res['Buyers'][0]['Secondary']['Middle'] && $res['Buyers'][0]['Secondary']['Middle']) ? $res['Buyers'][0]['Secondary']['Middle'] : '';
                                        $secondary_owner .= ($res['Buyers'][0]['Secondary']['Last'] && $res['Buyers'][0]['Secondary']['Last']) ? " " . $res['Buyers'][0]['Secondary']['Last'] : '';
                                        $ProductTypeTxt = $res['TransactionProductType']['ProductType'];

                                        if (strpos($ProductTypeTxt, 'Loan') !== false) {
                                            $propertyData['primary_owner'] = $primary_owner;
                                            $propertyData['secondary_owner'] = $secondary_owner;
                                            $loanFlag = 1;
                                        } elseif (strpos($ProductTypeTxt, 'Sale') !== false) {
                                            $transactionData['borrower'] = $primary_owner;
                                            $transactionData['secondary_borrower'] = $secondary_owner;
                                            $propertyData['primary_owner'] = isset($property_info['primary_owner']) && !empty($property_info['primary_owner']) ? $property_info['primary_owner'] : '';
                                            $propertyData['secondary_owner'] = isset($property_info['secondary_owner']) && !empty($property_info['secondary_owner']) ? $property_info['secondary_owner'] : '';
                                            $loanFlag = 0;
                                        }

                                        $propertyId = $this->home_model->insert($propertyData, 'property_details');
                                        $transactionId = $this->home_model->insert($transactionData, 'transaction_details');
                                        $time = round((int) (str_replace("-0000)/", "", str_replace("/Date(", "", $res['Dates']['OpenedDate']))) / 1000);
                                        $created_date = date('Y-m-d H:i:s', $time);
                                        $randomString = $this->order->randomPassword();
                                        $randomString = md5($randomString);

                                        $completed_date = null;
                                        if (!empty($closedDate)) {
                                            $myDateTime = DateTime::createFromFormat('M d, Y', $closedDate);
                                            $completed_date = $myDateTime->format('Y-m-d H:i:s');
                                        } else {
                                            if (!empty($res['Dates']['FileCompletedDate'])) {
                                                $time = round((int) (str_replace("-0000)/", "", str_replace("/Date(", "", $res['Dates']['FileCompletedDate']))) / 1000);
                                                $completed_date = date('Y-m-d H:i:s', $time);
                                            }
                                        }

                                        $orderData = array(
                                            'customer_id' => $customerId,
                                            'file_id' => $res['FileID'],
                                            'file_number' => $res['FileNumber'],
                                            'property_id' => $propertyId,
                                            'transaction_id' => $transactionId,
                                            'created_at' => $created_date,
                                            'prod_type' => $loanFlag == 1 ? 'loan' : 'sale',
                                            'status' => 1,
                                            'is_imported' => 1,
                                            'is_sales_rep_order' => 1,
                                            'random_number' => $randomString,
                                            'resware_closed_status_date' => $completed_date,
                                            'resware_status' => strtolower($res['Status']['Name']),
                                            'sent_to_accounting_date' => $completed_date,
                                        );
                                        $this->home_model->insert($orderData, 'order_details');
                                        if (!empty($resultSales)) {
                                            if (isset($resultSales['telephone_no']) && !empty($resultSales['telephone_no'])) {
                                                $phoneNumber = $resultSales['telephone_no'];
                                                $sid = env('TWILIO_SID');
                                                $token = env('TWILIO_TOKEN');
                                                $from = env('TWILIO_FROM');
                                                $data = array(
                                                    'message' => $message,
                                                    'account_sid' => $sid,
                                                    'token' => $token,
                                                    'to' => $phoneNumber,
                                                    'from' => $from,
                                                );

                                                $logid = $this->apiLogs->syncLogs('', 'twilio', 'send_message', '', $data, array(), 0, 0);

                                                /*try {
                                            $result = $this->twilio->message($phoneNumber, $message, '', array('from' => $from));
                                            $response = $result->toArray();
                                            $response['msg_status'] = 'success';
                                            } catch (Exception $e) {
                                            $response['sid'] = '';
                                            $response['to'] = $phoneNumber;
                                            $response['msg_status'] = 'error';
                                            $response['errorCode'] = $e->getCode();
                                            $response['errorMessage'] = $e->getMessage();
                                            } catch (\Twilio\Exceptions\RestException $e) {
                                            $response['sid'] = '';
                                            $response['to'] = $phoneNumber;
                                            $response['msg_status'] = 'error';
                                            $response['errorCode'] = $e->getCode();
                                            $response['errorMessage'] = $e->getMessage();
                                            }
                                            $this->apiLogs->syncLogs('', 'twilio', 'send_message', '', $data, $response, 0, $logid);

                                            if($response['msg_status'] == 'success') {

                                            $this->home_model->update(array('is_sent_recording_msg_sales_user' => 1), array('file_number' => $file_number), 'order_details');

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
                                            } */
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $row++;
                    }
                    fclose($handle);
                }
                $documentName = pathinfo($filePath);
                $fileName = date('YmdHis') . "_" . $documentName['basename'];
                rename(FCPATH . "/uploads/recording-confirmation/" . $documentName['basename'], FCPATH . "/uploads/recording-confirmation/" . $fileName);
                $this->order->uploadDocumentOnAwsS3($fileName, 'recording-confirmation', 1);
            }
        } else {
            echo "No files found";exit;
        }
        echo "All messages sent to sales users successfully";exit;
    }

    public function syncPrelimData()
    {
        $this->db->select('*');
        $this->db->from('pct_order_api_logs');
        $this->db->where('request_type', 'get_prelim');
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $res) {
            $logSyncId = $this->apiLogs->syncLogs(0, 'local', 'sync_prelim_data', 'https://mypctrep.com/ReceiveSearchDataService.svc?wsdl', array('ReceiveSearchDataService' => true), array());
            $url = "http://app.pacificcoasttitle.com/resware-fetch-data";
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER,
                array("Content-type: application/json"));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $res['response_data']);
            $json_response = curl_exec($curl);
            $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $this->apiLogs->syncLogs(0, 'local', 'sync_prelim_data', 'https://mypctrep.com/ReceiveSearchDataService.svc?wsdl', array(), $json_response, 0, $logSyncId);
            curl_close($curl);
        }
        echo "All prelim data synced successfully";
    }

    public function sendSummaryMailSalesRepUsers()
    {
        $result = $this->order->sendSummaryMail(0);
        echo "Mails sent successfully to Sales Managers";exit;
    }

    public function addPartnerForOrders()
    {
        $this->db->select('*');
        $this->db->from('order_details');
        $this->db->where('created_at BETWEEN DATE_SUB(NOW(), INTERVAL 45 DAY) AND NOW()');
        $this->db->where('((order_details.resware_status != "closed" AND order_details.resware_status != "cancelled") OR order_details.resware_status IS NULL)');
        $query = $this->db->get();
        $orderDetails = $query->result_array();
        $userdata = array();

        if (!empty($orderDetails)) {
            foreach ($orderDetails as $orderDetail) {
                $partners = array(
                    'PartnerTypeID' => 10049,
                    'PartnerID' => 400023,
                    'PartnerType' => array(
                        'PartnerTypeID' => 10049,
                    ),
                );
                $userdata['email'] = 'admin@pct24.com';
                $partnerData = json_encode(array('Partners' => $partners));
                $endPoint = 'files/' . $orderDetail['file_id'] . '/partners';
                $logid = $this->apiLogs->syncLogs(0, 'resware', 'add_partner', env('RESWARE_ORDER_API') . $endPoint, $partnerData, array(), 0, 0);
                $resultPartner = $this->make_request('POST', $endPoint, $partnerData, $userdata);
                $this->apiLogs->syncLogs(0, 'resware', 'add_partner', env('RESWARE_ORDER_API') . $endPoint, $partnerData, $resultPartner, 0, $logid);
            }
            echo "Updated partner for last 45 days orders";exit;
        } else {
            echo "No orders found to update partner";exit;
        }
    }

    public function importDataForPayOff()
    {
        $sftp = new SFTP(env('SFTP_HOST'));
        $username = env('SFTP_USERNAME');
        $password = env('SFTP_PASSWORD');

        if (!$sftp->login($username, $password)) {
            exit('Login Failed');
        }

        if (!($files = $sftp->nlist('/' . env('SFTP_FOLDER') . '/payoff/', true))) {
            die("Cannot read directory contents");
        }

        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && $file != '.protected') {
                if (!is_dir('uploads/payoff')) {
                    mkdir('./uploads/payoff', 0777, true);
                }
                $sftp->get(env('SFTP_FOLDER') . '/payoff/' . $file, FCPATH . 'uploads/payoff/' . trim($file) . ".csv");
                chmod(FCPATH . 'uploads/payoff/' . $file . ".csv", 0755);
                $sftp->delete(env('SFTP_FOLDER') . '/payoff/' . $file);
            }
        }
        $files = glob("uploads/payoff/*csv");

        if (is_array($files) && count($files) > 0) {
            foreach ($files as $filePath) {
                $row = 1;
                $headerColumns = array();
                if (($handle = fopen($filePath, "r")) !== false) {
                    $documentName = pathinfo($filePath);
                    $file_numbers = array();
                    $salesRepNameArr = array();
                    $titleOfficerNameArr = array();
                    $i = 0;
                    $j = 0;
                    while (($data = fgetcsv($handle, 1000, ",", '"')) !== false) {
                        $num = count($data);
                        if ($row == 1) {
                            for ($c = 0; $c < $num; $c++) {
                                $headerColumns[] = trim($data[$c]);
                            }
                        }

                        $fileKey = '';
                        $saleskey = '';
                        $titleOfficerkey = '';
                        $salesRepId = 0;
                        $titleOfficerId = 0;

                        if (in_array('File Number', $headerColumns)) {
                            $fileKey = array_search("File Number", $headerColumns);
                            $file_number = $data[$fileKey];
                        }

                        $salesRepName = '';
                        if (in_array('Sales Rep', $headerColumns)) {
                            $saleskey = array_search("Sales Rep", $headerColumns);
                            $salesRepName = $data[$saleskey];
                            $salesRepName = str_replace(' ', '_', $salesRepName);
                            $salesRepName = preg_replace('/[^A-Za-z0-9\_-]/', '', $salesRepName);
                            $salesRepName = str_replace('_', ' ', $salesRepName);
                            $key = array_search($salesRepName, array_column($salesRepNameArr, 'name'));
                            if (isset($key) && !empty($key)) {
                                $salesRepId = $salesRepNameArr[$key]['id'];
                            } else {
                                $salesRepNameArr[$i]['name'] = $salesRepName;
                            }
                        }

                        $titleOfficerName = '';
                        if (in_array('Title Officer', $headerColumns)) {
                            $titleOfficerkey = array_search("Title Officer", $headerColumns);
                            $titleOfficerName = $data[$titleOfficerkey];
                            $titleOfficerName = str_replace(' ', '_', $titleOfficerName);
                            $titleOfficerName = preg_replace('/[^A-Za-z0-9\_-]/', '', $titleOfficerName);
                            $titleOfficerName = str_replace('_', ' ', $titleOfficerName);
                            $titleOfckey = array_search($titleOfficerName, array_column($titleOfficerNameArr, 'name'));
                            if (isset($titleOfckey) && !empty($titleOfckey)) {
                                $titleOfficerId = $titleOfficerNameArr[$titleOfckey]['id'];
                            } else {
                                $titleOfficerNameArr[$j]['name'] = $titleOfficerName;
                            }
                        }

                        if ($row != 1) {
                            //echo $file_number."---".$prodType."----".$premium."----".$salesRepName."---".$closedDate;exit;
                            $resultSales = array();
                            if (!empty($salesRepName)) {
                                if ($salesRepId == 0) {
                                    $this->db->select('*');
                                    $this->db->from('customer_basic_details');
                                    $this->db->like("CONCAT_WS(' ', first_name, last_name)", $salesRepName);
                                    $this->db->where('is_sales_rep', 1);
                                    $query = $this->db->get();
                                    $resultSales = $query->row_array();
                                    if (!empty($resultSales)) {
                                        $salesRepId = $resultSales['id'];
                                        $salesRepNameArr[$i]['id'] = $salesRepId;
                                        $i++;
                                    } else {
                                        if (!empty(trim($salesRepNameArr[$i]['name']))) {
                                            $salesRepNameArr[$i]['id'] = 0;
                                            $i++;
                                        }
                                    }
                                }
                            }

                            $resultTitleOfficer = array();
                            if (!empty($titleOfficerName)) {
                                if ($titleOfficerId == 0) {
                                    $this->db->select('*');
                                    $this->db->from('customer_basic_details');
                                    $this->db->like("CONCAT_WS(' ', first_name, last_name)", $titleOfficerName);
                                    $this->db->where('is_title_officer', 1);
                                    $query = $this->db->get();
                                    $resultTitleOfficer = $query->row_array();
                                    if (!empty($resultTitleOfficer)) {
                                        $titleOfficerId = $resultTitleOfficer['id'];
                                        $titleOfficerNameArr[$j]['id'] = $titleOfficerId;
                                        $j++;
                                    } else {
                                        if (!empty(trim($titleOfficerNameArr[$i]['name']))) {
                                            $titleOfficerNameArr[$j]['id'] = 0;
                                            $j++;
                                        }
                                    }
                                }
                            }

                            $condition = array(
                                'where' => array(
                                    'file_number' => $file_number,
                                ),
                            );
                            $order = $this->order->get_order($condition);
                            if (!empty($order)) {
                                $this->home_model->update(
                                    array(
                                        'is_payoff_order' => 1,
                                    ),
                                    array(
                                        'id' => $order[0]['id'],
                                    ),
                                    'order_details'
                                );
                                $orderDetails = $this->order->get_order_details($order[0]['file_id']);
                                if (!empty($salesRepId)) {
                                    $this->home_model->update(
                                        array(
                                            'sales_representative' => $salesRepId,
                                        ),
                                        array(
                                            'id' => $orderDetails['transaction_id'],
                                        ),
                                        'transaction_details'
                                    );
                                    $file_numbers[] = $file_number;
                                } else {
                                    $this->home_model->update(
                                        array(
                                            'sales_representative' => 0,
                                        ),
                                        array(
                                            'id' => $orderDetails['transaction_id'],
                                        ),
                                        'transaction_details'
                                    );
                                }
                                if (!empty($titleOfficerId)) {
                                    $this->home_model->update(
                                        array(
                                            'title_officer' => $titleOfficerId,
                                        ),
                                        array(
                                            'id' => $orderDetails['transaction_id'],
                                        ),
                                        'transaction_details'
                                    );
                                    $file_numbers[] = $file_number;
                                } else {
                                    $this->home_model->update(
                                        array(
                                            'title_officer' => 0,
                                        ),
                                        array(
                                            'id' => $orderDetails['transaction_id'],
                                        ),
                                        'transaction_details'
                                    );
                                }
                            } else {
                                $data = json_encode(array('FileNumber' => $file_number));
                                $userData = array(
                                    'admin_api' => 1,
                                );
                                $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_order_information', env('RESWARE_ORDER_API') . 'files/search', $data, array(), 0, 0);
                                $res = $this->make_request('POST', 'files/search', $data, $userData);
                                $this->apiLogs->syncLogs(0, 'resware', 'get_order_information', env('RESWARE_ORDER_API') . 'files/search', $data, $res, 0, $logid);
                                $result = json_decode($res, true);

                                if (isset($result['Files']) && !empty($result['Files'])) {
                                    foreach ($result['Files'] as $res) {
                                        if (count($result['Files']) > 1 && strtolower($res['Status']['Name']) == 'cancelled') {
                                            continue;
                                        }
                                        $partner_fname = $res['Partners'][0]['PrimaryEmployee']['FirstName'];
                                        $partner_lname = $res['Partners'][0]['PrimaryEmployee']['LastName'];
                                        $partner_name = $res['Partners'][0]['PartnerName'];
                                        $condition = array(
                                            'first_name' => $partner_fname,
                                            'last_name' => $partner_lname,
                                            'company_name' => $partner_name,
                                            'is_pass' => $partner_name,
                                        );
                                        $user_details = $this->home_model->get_user_by_name($condition);
                                        $customerId = 0;

                                        if (isset($user_details) && !empty($user_details)) {
                                            $customerId = $user_details['id'];
                                        }

                                        $FullProperty = $res['Properties'][0]['StreetNumber'] . " " . $res['Properties'][0]['StreetDirection'] . " " . $res['Properties'][0]['StreetName'] . " " . $res['Properties'][0]['StreetSuffix'] . ", " . $res['Properties'][0]['City'] . ", " . $res['Properties'][0]['State'] . ", " . $res['Properties'][0]['Zip'];
                                        $address = $res['Properties'][0]['StreetNumber'] . " " . $res['Properties'][0]['StreetDirection'] . " " . $res['Properties'][0]['StreetName'] . " " . $res['Properties'][0]['StreetSuffix'];
                                        $locale = $res['Properties'][0]['City'];

                                        if (($locale)) {
                                            if (!empty($res['Properties'][0]['State'])) {
                                                $locale .= ', ' . $res['Properties'][0]['State'];
                                            } else {
                                                $locale .= ', CA';
                                            }
                                        }

                                        $property_details = $this->getSearchResult($address, $locale);
                                        $property_type = isset($property_details['property_type']) && !empty($property_details['property_type']) ? $property_details['property_type'] : '';
                                        $LegalDescription = isset($property_details['legaldescription']) && !empty($property_details['legaldescription']) ? $property_details['legaldescription'] : '';
                                        $apn = isset($property_details['apn']) && !empty($property_details['apn']) ? $property_details['apn'] : '';
                                        $propertyData = array(
                                            'customer_id' => $customerId,
                                            'buyer_agent_id' => 0,
                                            'listing_agent_id' => 0,
                                            'escrow_lender_id' => 0,
                                            'parcel_id' => $res['Properties'][0]['ParcelID'],
                                            'address' => removeMultipleSpace($address),
                                            'city' => $res['Properties'][0]['City'],
                                            'state' => $res['Properties'][0]['State'],
                                            'zip' => $res['Properties'][0]['Zip'],
                                            'property_type' => $property_type,
                                            'full_address' => removeMultipleSpace($FullProperty),
                                            'apn' => $apn,
                                            'county' => $res['Properties'][0]['County'],
                                            'legal_description' => $LegalDescription,
                                            'status' => 1,
                                        );

                                        $transactionData = array(
                                            'customer_id' => $customerId,
                                            'sales_amount' => !empty($res['SalesPrice']) ? $res['SalesPrice'] : 0,
                                            'loan_number' => !empty($res['Loans'][0]['LoanNumber']) ? $res['Loans'][0]['LoanNumber'] : 0,
                                            'loan_amount' => !empty($res['Loans'][0]['LoanAmount']) ? $res['Loans'][0]['LoanAmount'] : 0,
                                            'transaction_type' => $res['TransactionProductType']['TransactionTypeID'],
                                            'purchase_type' => $res['TransactionProductType']['ProductTypeID'],
                                            'sales_representative' => $salesRepId,
                                            'title_officer' => $titleOfficerId,
                                            'status' => 1,
                                        );

                                        $primary_owner = ($res['Buyers'][0]['Primary']['First'] && $res['Buyers'][0]['Primary']['First']) ? $res['Buyers'][0]['Primary']['First'] : '';
                                        $primary_owner .= ($res['Buyers'][0]['Primary']['Middle'] && $res['Buyers'][0]['Primary']['Middle']) ? " " . $res['Buyers'][0]['Primary']['Middle'] : '';
                                        $primary_owner .= ($res['Buyers'][0]['Primary']['Last'] && $res['Buyers'][0]['Primary']['Last']) ? " " . $res['Buyers'][0]['Primary']['Last'] : '';
                                        $secondary_owner = ($res['Buyers'][0]['Secondary']['First'] && $res['Buyers'][0]['Secondary']['First']) ? $res['Buyers'][0]['Secondary']['First'] : '';
                                        $secondary_owner .= ($res['Buyers'][0]['Secondary']['Middle'] && $res['Buyers'][0]['Secondary']['Middle']) ? $res['Buyers'][0]['Secondary']['Middle'] : '';
                                        $secondary_owner .= ($res['Buyers'][0]['Secondary']['Last'] && $res['Buyers'][0]['Secondary']['Last']) ? " " . $res['Buyers'][0]['Secondary']['Last'] : '';
                                        $ProductTypeTxt = $res['TransactionProductType']['ProductType'];

                                        if (strpos($ProductTypeTxt, 'Loan') !== false) {
                                            $propertyData['primary_owner'] = $primary_owner;
                                            $propertyData['secondary_owner'] = $secondary_owner;
                                        } elseif (strpos($ProductTypeTxt, 'Sale') !== false) {
                                            $transactionData['borrower'] = $primary_owner;
                                            $transactionData['secondary_borrower'] = $secondary_owner;
                                            $propertyData['primary_owner'] = isset($property_info['primary_owner']) && !empty($property_info['primary_owner']) ? $property_info['primary_owner'] : '';
                                            $propertyData['secondary_owner'] = isset($property_info['secondary_owner']) && !empty($property_info['secondary_owner']) ? $property_info['secondary_owner'] : '';
                                        }

                                        $propertyId = $this->home_model->insert($propertyData, 'property_details');
                                        $transactionId = $this->home_model->insert($transactionData, 'transaction_details');
                                        $time = round((int) (str_replace("-0000)/", "", str_replace("/Date(", "", $res['Dates']['OpenedDate']))) / 1000);
                                        $created_date = date('Y-m-d H:i:s', $time);
                                        $randomString = $this->order->randomPassword();
                                        $randomString = md5($randomString);

                                        $completed_date = null;
                                        if (empty($closedDate)) {
                                            if (!empty($res['Dates']['FileCompletedDate'])) {
                                                $time = round((int) (str_replace("-0000)/", "", str_replace("/Date(", "", $res['Dates']['FileCompletedDate']))) / 1000);
                                                $completed_date = date('Y-m-d H:i:s', $time);
                                            }
                                        }

                                        $orderData = array(
                                            'customer_id' => $customerId,
                                            'file_id' => $res['FileID'],
                                            'file_number' => $res['FileNumber'],
                                            'property_id' => $propertyId,
                                            'transaction_id' => $transactionId,
                                            'created_at' => $created_date,
                                            'status' => 1,
                                            'is_imported' => 1,
                                            'is_payoff_order' => 1,
                                            'random_number' => $randomString,
                                            'resware_closed_status_date' => $completed_date,
                                            'resware_status' => strtolower($res['Status']['Name']),
                                            'sent_to_accounting_date' => $completed_date,
                                        );
                                        $this->home_model->insert($orderData, 'order_details');
                                    }
                                }
                            }
                        }
                        $row++;
                    }
                    fclose($handle);
                }
                $documentName = pathinfo($filePath);
                $fileName = date('YmdHis') . "_" . $documentName['basename'];
                rename(FCPATH . "/uploads/payoff/" . $documentName['basename'], FCPATH . "/uploads/payoff/" . $fileName);
                $this->order->uploadDocumentOnAwsS3($fileName, 'payoff', 1);
            }
        } else {
            echo "No files found";exit;
        }
        echo "All data imported successfully";exit;
    }

    // Sent Memo acknowledge mail to assigned User
    public function sendMemoMail($memo_assign_id)
    {
        //Get pending mails to be sent

        $this->db->select('pct_hr_assigned_memo_users.id,pct_hr_assigned_memo_users.user_id,pct_hr_users.email,pct_hr_users.first_name,pct_hr_users.last_name,,pct_hr_memos.subject,pct_hr_memos.description');
        $this->db->from('pct_hr_assigned_memo_users');
        $this->db->join('pct_hr_users', 'pct_hr_users.id = pct_hr_assigned_memo_users.user_id');
        $this->db->join('pct_hr_memos', 'pct_hr_memos.id = pct_hr_assigned_memo_users.memo_id');
        $this->db->where('pct_hr_assigned_memo_users.id', $memo_assign_id);

        $query = $this->db->get();
        $memo_mails = $query->result_array();
        $this->load->library('encryption');

        foreach ($memo_mails as $memo_mail) {
            // var_dump($memo_mail);

            $memoId = urlencode($this->encryption->encrypt($memo_mail['id']));
            $userId = urlencode($this->encryption->encrypt($memo_mail['user_id']));
            $data = array();
            $data['subject'] = $memo_mail['subject'];
            $data['description'] = $memo_mail['description'];
            $data['user_name'] = $memo_mail['first_name'] . ' ' . $memo_mail['last_name'];
            $data['botton_url'] = base_url('hr/acknowledge-memo/' . $memoId . '/' . $userId);
            $message = $this->load->view('emails/memo_assigned', $data, true);
            $from_name = 'Pacific Coast Title Company';
            $from_mail = env('FROM_EMAIL');
            $subject = 'Memo Created';
            $to = $memo_mail['email'];
            // $to = 'cs@pct.com';
            $cc = array();
            $this->load->helper('sendemail');
            $check_mail = send_email($from_mail, $from_name, $to, $subject, $message, $cc);
            if ($check_mail) {
                $update_data = array();
                $update_data['mail_sent'] = 1;
                $condition = array();
                $condition['id'] = $memo_mail['id'];
                $update = $this->home_model->update($update_data, $condition, 'pct_hr_assigned_memo_users');
            }
        }

        // $data['file_number'] = $file_number;
    }

    public function importEscrowFee()
    {
        $sftp = new SFTP(env('SFTP_HOST'));
        $username = env('SFTP_USERNAME');
        $password = env('SFTP_PASSWORD');

        if (!$sftp->login($username, $password)) {
            exit('Login Failed');
        }

        if (!($files = $sftp->nlist('/' . env('SFTP_FOLDER') . '/escrow-orders/', true))) {
            die("Cannot read directory contents");
        }

        foreach ($files as $file) {
            if ($file != '.' && $file != '..' && $file != '.protected') {
                if (!is_dir('uploads/escrow-orders')) {
                    mkdir('./uploads/escrow-orders', 0777, true);
                }
                $sftp->get(env('SFTP_FOLDER') . '/escrow-orders/' . $file, FCPATH . 'uploads/escrow-orders/' . trim($file) . ".csv");
                chmod(FCPATH . 'uploads/escrow-orders/' . $file . ".csv", 0755);
                $sftp->delete(env('SFTP_FOLDER') . '/escrow-orders/' . $file);
            }
        }
        $files = glob("uploads/escrow-orders/*csv");

        if (is_array($files) && count($files) > 0) {
            foreach ($files as $filePath) {
                $row = 1;
                $headerColumns = array();
                if (($handle = fopen($filePath, "r")) !== false) {
                    $documentName = pathinfo($filePath);
                    $file_numbers = array();
                    $salesRepNameArr = array();
                    $i = 0;

                    while (($data = fgetcsv($handle, 1000, ",", '"')) !== false) {
                        $num = count($data);
                        if ($row == 1) {
                            for ($c = 0; $c < $num; $c++) {
                                $headerColumns[] = trim($data[$c]);
                            }
                        }

                        $titleOfficerId = 0;
                        $fileKey = '';
                        $prodkey = '';
                        $escrowAmountKey = '';
                        $saleskey = '';
                        $closedDate = '';
                        $salesRepId = 0;
                        $sales_rep_img = '';
                        $salesRepColumnFlag = 0;

                        if (in_array('File Number', $headerColumns)) {
                            $fileKey = array_search("File Number", $headerColumns);
                            $file_number = $data[$fileKey];
                        }

                        if (in_array('Prod Type', $headerColumns)) {
                            $prodkey = array_search("Prod Type", $headerColumns);
                            $prodType = $data[$prodkey];
                        }

                        if (in_array('Amt', $headerColumns)) {
                            $escrowAmountKey = array_search("Amt", $headerColumns);
                            $escrowAmount = $data[$escrowAmountKey];
                            $escrowAmount = str_replace('$', '', $escrowAmount);
                            $escrowAmount = str_replace(',', '', $escrowAmount);
                        }

                        $salesRepName = '';
                        if (in_array('Sales Rep', $headerColumns)) {
                            $saleskey = array_search("Sales Rep", $headerColumns);
                            $salesRepName = $data[$saleskey];
                            $salesRepName = str_replace(' ', '_', $salesRepName);
                            $salesRepName = preg_replace('/[^A-Za-z0-9\_-]/', '', $salesRepName);
                            $salesRepName = str_replace('_', ' ', $salesRepName);
                            $key = array_search($salesRepName, array_column($salesRepNameArr, 'name'));
                            if (isset($key) && !empty($key)) {
                                $salesRepId = $salesRepNameArr[$key]['id'];
                                $sales_rep_img = $salesRepNameArr[$key]['sales_rep_img'];
                            } else {
                                $salesRepNameArr[$i]['name'] = $salesRepName;
                            }
                            $salesRepColumnFlag = 1;
                        }

                        if (in_array('Sent To External Accounting', $headerColumns)) {
                            $closedDatekey = array_search("Sent To External Accounting", $headerColumns);
                            $closedDate = $data[$closedDatekey];
                        }

                        if ($row != 1) {
                            //echo $file_number."---".$prodType."----".$premium."----".$salesRepName."---".$closedDate;exit;
                            $resultSales = array();
                            if (!empty($salesRepName)) {
                                if ($salesRepId == 0) {
                                    $this->db->select('*');
                                    $this->db->from('customer_basic_details');
                                    $this->db->like("CONCAT_WS(' ', first_name, last_name)", $salesRepName);
                                    $this->db->where('is_sales_rep', 1);
                                    $query = $this->db->get();
                                    $resultSales = $query->row_array();
                                    if (!empty($resultSales)) {
                                        $salesRepId = $resultSales['id'];
                                        $sales_rep_img = isset($resultSales["sales_rep_profile_img"]) && !empty($resultSales["sales_rep_profile_img"]) ? $resultSales["sales_rep_profile_img"] : '';
                                        if (!empty($sales_rep_img)) {
                                            $sales_rep_img = env('AWS_PATH') . str_replace('uploads/', '', $sales_rep_img);
                                        }
                                        $salesRepNameArr[$i]['id'] = $salesRepId;
                                        $salesRepNameArr[$i]['sales_rep_img'] = $sales_rep_img;
                                        $i++;
                                    } else {
                                        if (!empty(trim($salesRepNameArr[$i]['name']))) {
                                            $salesRepNameArr[$i]['id'] = 0;
                                            $i++;
                                        }
                                    }
                                }
                            }

                            $completed_date = null;
                            if (!empty($closedDate)) {
                                $myDateTime = DateTime::createFromFormat('M d, Y', $closedDate);
                                $completed_date = $myDateTime->format('Y-m-d H:i:s');
                            }

                            if (!empty($file_number)) {
                                $condition = array(
                                    'where' => array(
                                        'file_number' => $file_number,
                                    ),
                                );
                                $order = $this->order->get_order($condition);

                                if (!empty($order)) {
                                    if (in_array($file_number, $file_numbers)) {
                                        if (!empty($escrowAmount)) {
                                            $escrowAmount = (float) $escrowAmount + $order[0]['escrow_amount'];
                                        }
                                    }
                                    $file_numbers[] = $file_number;
                                    $orderData = array();

                                    if (!empty($prodType)) {
                                        $orderData['prod_type'] = strtolower($prodType);
                                    }

                                    if (!empty($escrowAmount)) {
                                        $orderData['escrow_amount'] = (float) $escrowAmount;
                                    }

                                    if (!empty($completed_date)) {
                                        $orderData['sent_to_accounting_date'] = $completed_date;
                                    }

                                    if (!empty($orderData)) {
                                        $this->home_model->update(
                                            $orderData,
                                            array(
                                                'id' => $order[0]['id'],
                                            ),
                                            'order_details'
                                        );
                                    }

                                    $orderDetails = $this->order->get_order_details($order[0]['file_id']);
                                    if (!empty($salesRepId)) {
                                        $this->home_model->update(
                                            array(
                                                'sales_representative' => $salesRepId,
                                            ),
                                            array(
                                                'id' => $orderDetails['transaction_id'],
                                            ),
                                            'transaction_details'
                                        );
                                    } else {
                                        if ($salesRepColumnFlag == 1) {
                                            $this->home_model->update(
                                                array(
                                                    'sales_representative' => 0,
                                                ),
                                                array(
                                                    'id' => $orderDetails['transaction_id'],
                                                ),
                                                'transaction_details'
                                            );
                                        }
                                    }
                                } else {
                                    $file_numbers[] = $file_number;
                                    $data = json_encode(array('FileNumber' => $file_number));
                                    $userData = array(
                                        'admin_api' => 1,
                                    );
                                    $logid = $this->apiLogs->syncLogs(0, 'resware', 'get_order_information', env('RESWARE_ORDER_API') . 'files/search', $data, array(), 0, 0);
                                    $res = $this->make_request('POST', 'files/search', $data, $userData);
                                    $this->apiLogs->syncLogs(0, 'resware', 'get_order_information', env('RESWARE_ORDER_API') . 'files/search', $data, $res, 0, $logid);
                                    $result = json_decode($res, true);

                                    if (isset($result['Files']) && !empty($result['Files'])) {
                                        foreach ($result['Files'] as $res) {
                                            if (count($result['Files']) > 1 && strtolower($res['Status']['Name']) == 'cancelled') {
                                                continue;
                                            }
                                            $partner_fname = $res['Partners'][0]['PrimaryEmployee']['FirstName'];
                                            $partner_lname = $res['Partners'][0]['PrimaryEmployee']['LastName'];
                                            $partner_name = $res['Partners'][0]['PartnerName'];
                                            $condition = array(
                                                'first_name' => $partner_fname,
                                                'last_name' => $partner_lname,
                                                'company_name' => $partner_name,
                                                'is_pass' => $partner_name,
                                            );
                                            $user_details = $this->home_model->get_user_by_name($condition);
                                            $customerId = 0;

                                            if (isset($user_details) && !empty($user_details)) {
                                                $customerId = $user_details['id'];
                                            }

                                            $FullProperty = $res['Properties'][0]['StreetNumber'] . " " . $res['Properties'][0]['StreetDirection'] . " " . $res['Properties'][0]['StreetName'] . " " . $res['Properties'][0]['StreetSuffix'] . ", " . $res['Properties'][0]['City'] . ", " . $res['Properties'][0]['State'] . ", " . $res['Properties'][0]['Zip'];
                                            $address = $res['Properties'][0]['StreetNumber'] . " " . $res['Properties'][0]['StreetDirection'] . " " . $res['Properties'][0]['StreetName'] . " " . $res['Properties'][0]['StreetSuffix'];
                                            $locale = $res['Properties'][0]['City'];

                                            if (($locale)) {
                                                if (!empty($res['Properties'][0]['State'])) {
                                                    $locale .= ', ' . $res['Properties'][0]['State'];
                                                } else {
                                                    $locale .= ', CA';
                                                }
                                            }

                                            $property_details = $this->getSearchResult($address, $locale);
                                            $property_type = isset($property_details['property_type']) && !empty($property_details['property_type']) ? $property_details['property_type'] : '';
                                            $LegalDescription = isset($property_details['legaldescription']) && !empty($property_details['legaldescription']) ? $property_details['legaldescription'] : '';
                                            $apn = isset($property_details['apn']) && !empty($property_details['apn']) ? $property_details['apn'] : '';
                                            $propertyData = array(
                                                'customer_id' => $customerId,
                                                'buyer_agent_id' => 0,
                                                'listing_agent_id' => 0,
                                                'escrow_lender_id' => 0,
                                                'parcel_id' => $res['Properties'][0]['ParcelID'],
                                                'address' => removeMultipleSpace($address),
                                                'city' => $res['Properties'][0]['City'],
                                                'state' => $res['Properties'][0]['State'],
                                                'zip' => $res['Properties'][0]['Zip'],
                                                'property_type' => $property_type,
                                                'full_address' => removeMultipleSpace($FullProperty),
                                                'apn' => $apn,
                                                'county' => $res['Properties'][0]['County'],
                                                'legal_description' => $LegalDescription,
                                                'status' => 1,
                                            );

                                            $transactionData = array(
                                                'customer_id' => $customerId,
                                                'sales_amount' => !empty($res['SalesPrice']) ? $res['SalesPrice'] : 0,
                                                'loan_number' => !empty($res['Loans'][0]['LoanNumber']) ? $res['Loans'][0]['LoanNumber'] : 0,
                                                'loan_amount' => !empty($res['Loans'][0]['LoanAmount']) ? $res['Loans'][0]['LoanAmount'] : 0,
                                                'transaction_type' => $res['TransactionProductType']['TransactionTypeID'],
                                                'purchase_type' => $res['TransactionProductType']['ProductTypeID'],
                                                'sales_representative' => $salesRepId,
                                                'title_officer' => $titleOfficerId,
                                                'status' => 1,
                                            );

                                            $primary_owner = ($res['Buyers'][0]['Primary']['First'] && $res['Buyers'][0]['Primary']['First']) ? $res['Buyers'][0]['Primary']['First'] : '';
                                            $primary_owner .= ($res['Buyers'][0]['Primary']['Middle'] && $res['Buyers'][0]['Primary']['Middle']) ? " " . $res['Buyers'][0]['Primary']['Middle'] : '';
                                            $primary_owner .= ($res['Buyers'][0]['Primary']['Last'] && $res['Buyers'][0]['Primary']['Last']) ? " " . $res['Buyers'][0]['Primary']['Last'] : '';
                                            $secondary_owner = ($res['Buyers'][0]['Secondary']['First'] && $res['Buyers'][0]['Secondary']['First']) ? $res['Buyers'][0]['Secondary']['First'] : '';
                                            $secondary_owner .= ($res['Buyers'][0]['Secondary']['Middle'] && $res['Buyers'][0]['Secondary']['Middle']) ? $res['Buyers'][0]['Secondary']['Middle'] : '';
                                            $secondary_owner .= ($res['Buyers'][0]['Secondary']['Last'] && $res['Buyers'][0]['Secondary']['Last']) ? " " . $res['Buyers'][0]['Secondary']['Last'] : '';
                                            $ProductTypeTxt = $res['TransactionProductType']['ProductType'];

                                            if (strpos($ProductTypeTxt, 'Loan') !== false) {
                                                $propertyData['primary_owner'] = $primary_owner;
                                                $propertyData['secondary_owner'] = $secondary_owner;
                                            } elseif (strpos($ProductTypeTxt, 'Sale') !== false) {
                                                $transactionData['borrower'] = $primary_owner;
                                                $transactionData['secondary_borrower'] = $secondary_owner;
                                                $propertyData['primary_owner'] = isset($property_info['primary_owner']) && !empty($property_info['primary_owner']) ? $property_info['primary_owner'] : '';
                                                $propertyData['secondary_owner'] = isset($property_info['secondary_owner']) && !empty($property_info['secondary_owner']) ? $property_info['secondary_owner'] : '';
                                            }

                                            $propertyId = $this->home_model->insert($propertyData, 'property_details');
                                            $transactionId = $this->home_model->insert($transactionData, 'transaction_details');
                                            $time = round((int) (str_replace("-0000)/", "", str_replace("/Date(", "", $res['Dates']['OpenedDate']))) / 1000);
                                            $created_date = date('Y-m-d H:i:s', $time);
                                            $randomString = $this->order->randomPassword();
                                            $randomString = md5($randomString);

                                            $resware_closed_status_date = null;
                                            if (empty($closedDate)) {
                                                if (!empty($res['Dates']['FileCompletedDate'])) {
                                                    $time = round((int) (str_replace("-0000)/", "", str_replace("/Date(", "", $res['Dates']['FileCompletedDate']))) / 1000);
                                                    $resware_closed_status_date = date('Y-m-d H:i:s', $time);
                                                }
                                            }

                                            $orderData = array(
                                                'customer_id' => $customerId,
                                                'file_id' => $res['FileID'],
                                                'file_number' => $res['FileNumber'],
                                                'property_id' => $propertyId,
                                                'transaction_id' => $transactionId,
                                                'created_at' => $created_date,
                                                'prod_type' => strtolower($prodType),
                                                'escrow_amount' => (float) $escrowAmount,
                                                'status' => 1,
                                                'is_imported' => 1,
                                                'is_sales_rep_order' => 1,
                                                'random_number' => $randomString,
                                                'resware_closed_status_date' => $resware_closed_status_date,
                                                'resware_status' => strtolower($res['Status']['Name']),
                                                'sent_to_accounting_date' => $completed_date,
                                            );
                                            $this->home_model->insert($orderData, 'order_details');
                                        }
                                    }
                                }
                            }
                        }
                        $row++;
                    }
                    fclose($handle);
                }
                $documentName = pathinfo($filePath);
                $fileName = date('YmdHis') . "_" . $documentName['basename'];
                rename(FCPATH . "/uploads/escrow-orders/" . $documentName['basename'], FCPATH . "/uploads/escrow-orders/" . $fileName);
                $this->order->uploadDocumentOnAwsS3($fileName, 'escrow-orders', 1);
            }
        } else {
            echo "No files found";exit;
        }
        echo "All data exported successfully";exit;
    }

    public function sendDataToHomeDocs($fileId)
    {
        $orderDetails = $this->order->get_order_details($fileId);
        $this->load->model('order/titlePointData');
        $condition = array(
            'where' => array(
                'file_id' => $fileId,
            ),
        );
        $titlePointDetails = $this->titlePointData->gettitlePointDetails($condition);
        $this->load->helper('homedocsapi_helper');
        $homedocs_array = [
            'order_token' => $orderDetails['random_number'],
            'apn' => $orderDetails['apn'],
            'fips' => $titlePointDetails[0]['fips'],
            'address' => $orderDetails['address'],
            'last_line' => $orderDetails['property_city'] . ', ' . $orderDetails['property_state'],
            'full_address' => $orderDetails['full_address'],
            'file_id' => $orderDetails['file_id'],
            'file_number' => $orderDetails['file_number'],
            'vesting_info' => $titlePointDetails[0]['vesting_information'],
            'first_installment' => $titlePointDetails[0]['first_installment'],
            'second_installment' => $titlePointDetails[0]['second_installment'],
            'borrower_email' => $orderDetails['borrower_email'],
            'borrower_name' => $orderDetails['primary_owner'],
        ];

        $logid = $this->apiLogs->syncLogs(0, 'homedocs', 'send_order_info', env('HOMEDOCS_URL') . 'api/store-property-detail', json_encode($homedocs_array, JSON_UNESCAPED_SLASHES), array(), $orderDetails['order_id'], 0);
        $result = send_order_data($homedocs_array);
        $this->apiLogs->syncLogs(0, 'homedocs', 'send_order_info', env('HOMEDOCS_URL') . 'api/store-property-detail', json_encode($homedocs_array, JSON_UNESCAPED_SLASHES), $result, $orderDetails['order_id'], $logid);
    }

    public function update_underwriters_data()
    {
        $table = 'order_details';
        $this->load->library('order/resware');

        $this->db->select('id,file_id');
        $this->db->from($table);
        $this->db->where('is_underwriter_updated', 0);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(500);
        $query = $this->db->get();
        $result = $query->result();
        $user_data = array();
        $user_data['admin_api'] = 1;
        foreach ($result as $record) {
            $file_id = $record->file_id;
            $endPoint = 'files/' . $file_id . '/partners';
            $resultPartners = $this->resware->make_request('GET', $endPoint, '', $user_data);
            $resPartners = json_decode($resultPartners, true);

            $underWriter = '';
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
            //Update Underwriter
            $order_details = [
                'underwriter' => $underWriter,
                'is_underwriter_updated' => 1,

            ];
            $condition = [
                'file_id' => $file_id,
            ];
            $this->db->update($table, $order_details, $condition);

        }
    }

    public function sendEmailForClosedOrder($fileNumbers)
    {
        $this->db->select('order_details.file_id,
            order_details.file_number,
            order_details.id as order_id,
            order_details.resware_status,
            order_details.resware_closed_status_date,
            order_details.prod_type,
            client.first_name,
            client.last_name,
            client.email_address as sales_email,
            client.sales_rep_profile_thank_you_img,
            property_details.full_address,
            property_details.escrow_lender_id,
            escrow_details.email_address as escrow_email,
            title_officer.email_address as title_officer_email,
            listing_agent.email_address as listing_agent_email,
            buyer_agent.email_address as buyer_agent_email,
            sales_details.email_address as sales_rep_email,
            pct_order_partner_company_info.email as escrow_officer_email,
            transaction_details.sales_representative');
        $this->db->from('order_details');
        $this->db->where_in('order_details.file_number', $fileNumbers);
        //$this->db->where('order_details.is_thank_you_email_sent', 0);
        $this->db->where('order_details.customer_id > 0');
        // $this->db->where('property_details.escrow_lender_id != ""');
        // $this->db->where('transaction_details.sales_representative != ""');
        $this->db->join('property_details', 'order_details.property_id = property_details.id', 'inner');
        $this->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id', 'inner');
        $this->db->join('customer_basic_details as client ', 'client.id = order_details.customer_id', 'inner');
        $this->db->join('customer_basic_details as sales_details ', 'sales_details.id = transaction_details.sales_representative', 'inner');
        $this->db->join('customer_basic_details as escrow_details', 'escrow_details.id = property_details.escrow_lender_id', 'left');
        $this->db->join('customer_basic_details as title_officer', 'title_officer.id = transaction_details.title_officer', 'left');
        $this->db->join('agents as buyer_agent', 'buyer_agent.id = property_details.buyer_agent_id', 'left');
        $this->db->join('agents as listing_agent', 'listing_agent.id = property_details.listing_agent_id', 'left');
        $this->db->join('pct_order_partner_company_info', 'pct_order_partner_company_info.partner_id = order_details.escrow_officer_id', 'left');
        // $this->db->order_by('transaction_details.sales_representative asc, property_details.escrow_lender_id asc');
        $query = $this->db->get();
        $result = $query->result_array();

        $configData = $this->order->getConfigData();
        $loanOrderEmailSendStatus = $configData['loan_order_closed_email_send_off']['is_enable'];
        $saleOrderEmailSendStatus = $configData['sale_order_closed_email_send_off']['is_enable'];
        $enableSurveyEmailFlag = $configData['enable_survey_email']['is_enable'];

        if (!empty($result)) {
            $checkFlag = 0;
            $data = array();
            $i = 0;
            foreach ($result as $res) {
                // echo 'res ===';
                if (((empty($loanOrderEmailSendStatus) || $loanOrderEmailSendStatus == 0) && $res['prod_type'] === 'loan') || ((empty($saleOrderEmailSendStatus) || $saleOrderEmailSendStatus == 0) && $res['prod_type'] === 'sale')) {
                    $sales_email = !empty($res['sales_rep_email']) ? $res['sales_rep_email'] : '';

                    $to = [];
                    if (!empty($res['escrow_email'])) {
                        array_push($to, $res['escrow_email']);
                    }
                    if (!empty($res['listing_agent_email'])) {
                        array_push($to, $res['listing_agent_email']);
                    }
                    if (!empty($res['buyer_agent_email'])) {
                        array_push($to, $res['buyer_agent_email']);
                    }
                    if (!empty($res['sales_email'])) {
                        array_push($to, $res['sales_email']);
                    }

                    // echo 'after if ===';
                    // print_r($res);
                    $message = $this->load->view('emails/close_order_email.php', $data, true);
                    $from_name = 'Pacific Coast Title Company';
                    $from_mail = env('FROM_EMAIL');
                    // $subject = 'Thank You!';
                    // $to = $escrow_email_address;

                    $cc = array('ghernandez@pct.com', $sales_email);

                    $from_name = 'Pacific Coast Title Company';
                    $from_mail = env('FROM_EMAIL');
                    $subject = 'Your Order ' . $res['file_number'] . ' has been closed';
                    // $to = $escrow_email_address;
                    // $cc = array('piyush.j@crestinfosystems.com', $sales_email);
                    // $to = array('piyush.j@crestinfosystems.com');
                    $mailParams = array(
                        'from_mail' => $from_mail,
                        'from_name' => $from_name,
                        'to' => $to,
                        'subject' => $subject,
                        'message' => json_encode($data),
                        'cc' => $cc,
                    );
                    $to = ['piyush.j@crestinfosystems.com', 'ghernandez@pct.com'];
                    // $cc = array();
                    $this->load->helper('sendemail');
                    $logid = $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_all_parties', '', $mailParams, array(), $res['order_id'], 0);
                    $escrow_mail_result = send_email($from_mail, $from_name, $to, $subject, $message, array(), $cc);
                    $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_all_parties', '', $mailParams, array('status' => $escrow_mail_result), $res['orderId'], $logid);
                    echo "Mails sent successfully for Order Number : " . $res['file_number'] . " To: " . implode(', ', $to) . "And In CC : " . implode(', ', $cc) . "<br/>";
                }
                if (($enableSurveyEmailFlag == 1) && !empty($res['escrow_officer_email'])) {
                    $this->sendSurvayEmail($res);
                }

            }

        }
    }

    public function sendSurvayEmail($data) {
        if (!empty($data['title_officer_email'])) {
            if ($data['title_officer_email'] == 'unit66@pct.com') { 
                $data['survey_link'] = 'https://www.surveymonkey.com/r/KR5G38W?order_id=' . $data['order_id']; // Clive - 143260
            } else if ($data['title_officer_email'] == 'jjean@pct.com') {
                $data['survey_link'] = 'https://www.surveymonkey.com/r/P3X7KX8?order_id=' . $data['order_id']; // Jim
            } else if ($data['title_officer_email'] == 'unit33@pct.com') {
                $data['survey_link'] = 'https://www.surveymonkey.com/r/PG7SJRG?order_id=' . $data['order_id']; // Eddie
            } else if ($data['title_officer_email'] == 'unit88@pct.com') {
                $data['survey_link'] = 'https://www.surveymonkey.com/r/6BJZ79Y?order_id=' . $data['order_id']; // Rachel
            } else {
                exit;
            }
        }
        $message = $this->load->view('emails/surveymonkey_email.php', $data, true);
        $from_name = 'Pacific Coast Title Company';
        $from_mail = env('FROM_EMAIL');
        // $subject = 'Thank You!';
        $to = $data['escrow_officer_email'];

        // $to = array('piyush.j@crestinfosystems.com', 'ghernandez@pct.com');
        $cc = array('piyush.j@crestinfosystems.com');

        $from_name = 'Pacific Coast Title Company';
        $from_mail = env('FROM_EMAIL');
        $subject = "We'd Love Your Feedback";
        // $to = $escrow_email_address;
        // $cc = array('piyush.j@crestinfosystems.com', $sales_email);
        // $cc = array('piyush.j@crestinfosystems.com');
        $mailParams = array(
            'from_mail' => $from_mail,
            'from_name' => $from_name,
            'to' => $to,
            'subject' => $subject,
            'message' => json_encode($data),
            'cc' => $cc,
        );
        // $to = ['piyush.j@crestinfosystems.net', 'ghernandez@pct.com'];
        // $cc = array();
        $this->load->helper('sendemail');
        $logid = $this->apiLogs->syncLogs(0, 'sendgrid', 'survay_email_sent_mail_to_escrow_officer', '', $mailParams, array(), $data['order_id'], 0);
        $mail_result = send_email($from_mail, $from_name, $to, $subject, $message, array(), $cc);
        $this->apiLogs->syncLogs(0, 'sendgrid', 'survay_email_sent_mail_to_escrow_officer', '', $mailParams, array('status' => $mail_result), $data['orderId'], $logid);
        echo "Survay Mails sent successfully for Order Number : " . $data['file_number'] . " To: " . implode(', ', $to) . "And In CC : " . implode(', ', $cc) . "<br/>";
    }

    public function sendThankYouEmailForClosedOrder($fileNumbers)
    {
        $this->db->select('order_details.file_id,
            order_details.file_number,
            order_details.id as order_id,
            order_details.resware_status,
            order_details.resware_closed_status_date,
            property_details.full_address,
            client.first_name,
            client.last_name,
            client.email_address as sales_email,
            client.sales_rep_profile_thank_you_img,
            property_details.escrow_lender_id,
            escrow_details.email_address,
            transaction_details.sales_representative');
        $this->db->from('order_details');
        $this->db->where_in('order_details.file_number', $fileNumbers);
        //$this->db->where('order_details.is_thank_you_email_sent', 0);
        $this->db->where('order_details.customer_id > 0');
        $this->db->where('property_details.escrow_lender_id != ""');
        $this->db->where('transaction_details.sales_representative != ""');
        $this->db->join('property_details', 'order_details.property_id = property_details.id', 'inner');
        $this->db->join('transaction_details', 'order_details.transaction_id = transaction_details.id', 'inner');
        $this->db->join('customer_basic_details as client ', 'client.id = order_details.customer_id', 'inner');
        $this->db->join('customer_basic_details as sales_details ', 'sales_details.id = transaction_details.sales_representative', 'inner');
        $this->db->join('customer_basic_details as escrow_details', 'escrow_details.id = property_details.escrow_lender_id', 'inner');
        $this->db->join('agents as buyer_agent', 'buyer_agent.id = property_details.buyer_agent_id', 'left');
        $this->db->join('agents as listing_agent', 'listing_agent.id = property_details.listing_agent_id', 'left');
        $this->db->order_by('transaction_details.sales_representative asc, property_details.escrow_lender_id asc');
        $query = $this->db->get();
        $result = $query->result_array();

        if (!empty($result)) {
            $checkFlag = 0;
            $data = array();
            $i = 0;
            foreach ($result as $res) {
                if ($checkFlag == 0) {
                    $sales_rep_user_id = $res['sales_representative'];
                    $escrow_user_id = $res['escrow_lender_id'];
                    $escrow_email_address = $res['email_address'];
                    $checkFlag = 1;
                }
                if ($res['sales_representative'] == $sales_rep_user_id && $res['escrow_lender_id'] == $escrow_user_id) {
                    $data['order_info'][$i]['order_number'] = $res['file_number'];
                    $data['order_info'][$i]['address'] = $res['full_address'];
                    $data['order_info'][$i]['resware_status'] = $res['resware_status'] ? $res['resware_status'] : 'closed';
                    $data['order_info'][$i]['closed_date'] = date("m/d/Y", strtotime($res['resware_closed_status_date']));
                    $data['sales_rep_profile_thank_you_img'] = !empty($res['sales_rep_profile_thank_you_img']) ? $res['sales_rep_profile_thank_you_img'] : '';
                    if (!empty($data['sales_rep_profile_thank_you_img'])) {
                        $data['sales_rep_profile_thank_you_img'] = env('AWS_PATH') . str_replace('uploads/', '', $data['sales_rep_profile_thank_you_img']);
                    }
                    $data['sales_email'] = !empty($res['sales_email']) ? $res['sales_email'] : '';
                    $i++;
                } else {
                    $message = $this->load->view('emails/thank_you_escrow.php', $data, true);
                    $from_name = 'Pacific Coast Title Company';
                    $from_mail = env('FROM_EMAIL');
                    $subject = 'Thank You!';
                    $to = $escrow_email_address;
                    $cc = array('ghernandez@pct.com', $data['sales_email']);
                    $mailParams = array(
                        'from_mail' => $from_mail,
                        'from_name' => $from_name,
                        'to' => $to,
                        'subject' => $subject,
                        'message' => json_encode($data),
                        'cc' => $data['sales_email'],
                    );
                    // $to = 'piyush.j@crestinfosystems.net';
                    $cc = array();
                    $this->load->helper('sendemail');
                    $logid = $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_escrow_user', '', $mailParams, array(), $res['order_id'], 0);
                    $escrow_mail_result = send_email($from_mail, $from_name, $to, $subject, $message, array(), $cc);
                    $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_escrow_user', '', $mailParams, array('status' => $escrow_mail_result), $res['orderId'], $logid);
                    $data = array();
                    $sales_rep_user_id = $res['sales_representative'];
                    $escrow_user_id = $res['escrow_lender_id'];
                    $escrow_email_address = $res['email_address'];
                    $data['order_info'][$i]['order_number'] = $res['file_number'];
                    $data['order_info'][$i]['address'] = $res['full_address'];
                    $data['order_info'][$i]['resware_status'] = $res['resware_status'] ? $res['resware_status'] : 'closed';
                    $data['order_info'][$i]['closed_date'] = date("m/d/Y", strtotime($res['resware_closed_status_date']));
                    $data['sales_rep_profile_thank_you_img'] = !empty($res['sales_rep_profile_thank_you_img']) ? $res['sales_rep_profile_thank_you_img'] : '';
                    if (!empty($data['sales_rep_profile_thank_you_img'])) {
                        $data['sales_rep_profile_thank_you_img'] = env('AWS_PATH') . str_replace('uploads/', '', $data['sales_rep_profile_thank_you_img']);
                    }
                    $data['sales_email'] = !empty($res['sales_email']) ? $res['sales_email'] : '';
                    $i++;
                }
                $sales_email = $res['sales_email'];
                $order_id = $res['order_id'];
            }
            if (!empty($data)) {
                $message = $this->load->view('emails/thank_you_escrow.php', $data, true);
                $from_name = 'Pacific Coast Title Company';
                $from_mail = env('FROM_EMAIL');
                $subject = 'Thank You!';
                $to = $escrow_email_address;

                $cc = array('ghernandez@pct.com', $sales_email);
                $this->load->helper('sendemail');
                $mailParams = array(
                    'from_mail' => $from_mail,
                    'from_name' => $from_name,
                    'to' => $to,
                    'subject' => $subject,
                    'message' => json_encode($data),
                    'cc' => $sales_email,
                );
                // $to = 'piyush.j@crestinfosystems.net';
                $cc = array();

                $logid = $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_escrow_user', '', $mailParams, array(), $order_id, 0);
                $escrow_mail_result = send_email($from_mail, $from_name, $to, $subject, $message, array(), $cc);
                $this->apiLogs->syncLogs(0, 'sendgrid', 'send_mail_to_escrow_user', '', $mailParams, array('status' => $escrow_mail_result), $order_id, $logid);
            }
            $order_details = [
                'is_thank_you_email_sent' => 1,
            ];
            // $this->db->where_in('order_details.file_number', $fileNumbers);
            // $this->db->update('order_details', $order_details);
            echo "Mails sent successfully to Escow user ";exit;
        }
    }

    public function getFileNumbers()
    {
        $fileNumbers = array();
        $this->db->select('*');
        $this->db->from('pct_order_api_logs');
        $this->db->where('request_type', 'get_prelim');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(200);
        $query = $this->db->get();
        $result = $query->result_array();
        foreach ($result as $res) {
            $data = json_decode($res['response_data'], true);
            if (isset($data['FileNumber']) && !in_array($data['FileNumber'], $fileNumbers)) {
                $fileNumbers[] = $data['FileNumber'];
            }
        }
        echo "<pre>";
        print_r($fileNumbers);
        echo "All prelim data synced successfully";
    }

    public function update_databackup_partner()
    {
        $table = 'order_details';
        $this->load->library('order/resware');
        $this->db->select('id,file_id');
        $this->db->from($table);
        $this->db->where('is_imported', 1);
        $this->db->where('is_added_databackup_partner', 0);
        $this->db->order_by('id', 'asc');
        $this->db->limit(10000);
        $query = $this->db->get();
        $result = $query->result();
        $user_data = array();
        $user_data['admin_api'] = 1;
        foreach ($result as $record) {
            $file_id = $record->file_id;
            $endPoint = 'files/' . $file_id . '/partners';
            $resultPartners = $this->resware->make_request('GET', $endPoint, '', $user_data);
            $resPartners = json_decode($resultPartners, true);
            $key = '';
            if (!empty($resPartners)) {
                $key = array_search(10049, array_column($resPartners['Partners'], 'PartnerTypeID'));
                if (isset($key) && strlen($key) > 0) {
                    $order_details = [
                        'is_added_databackup_partner' => 1,
                    ];
                    $condition = [
                        'file_id' => $file_id,
                    ];
                    $this->db->update($table, $order_details, $condition);
                } else {
                    $partners = array(
                        'PartnerTypeID' => 10049,
                        'PartnerID' => 400023,
                        'PartnerType' => array(
                            'PartnerTypeID' => 10049,
                        ),
                    );
                    $partnerData = json_encode(array('Partners' => $partners));
                    $endPoint = 'files/' . $file_id . '/partners';
                    $logid = $this->apiLogs->syncLogs(0, 'resware', 'add_partner', env('RESWARE_ORDER_API') . $endPoint, $partnerData, array(), 0, 0);
                    $resultPartner = $this->make_request('POST', $endPoint, $partnerData, $user_data);
                    $this->apiLogs->syncLogs(0, 'resware', 'add_partner', env('RESWARE_ORDER_API') . $endPoint, $partnerData, $resultPartner, 0, $logid);
                    if (empty($resultPartner)) {
                        $order_details = [
                            'is_added_databackup_partner' => 1,
                        ];
                        $condition = [
                            'file_id' => $file_id,
                        ];
                        $this->db->update($table, $order_details, $condition);
                    }
                }

            }
        }
    }

    public function sendDailyProductionReport()
    {
        $result = $this->order->sendDailyProductionReport(0);
        echo "Mails sent successfully to Sales Managers";exit;
    }

    public function sendLPReports()
    {
        $result = $this->order->sendLPReports(0);
        echo "Mails sent successfully to Sales Managers";exit;
    }

    public function generateAllDocumentFromTitlePoint($file_number)
    {
        $this->load->model('order/apiLogs');
        $titlePointInstrumentDetails = $this->titlePointData->getInstrumentDetails($file_number, 1);
        if (!empty($titlePointInstrumentDetails)) {
            foreach ($titlePointInstrumentDetails as $insDetail) {
                $fileExist = $this->order->fileExistOrNotOnS3("title-point/" . $insDetail['id'] . '.pdf');
                if ($fileExist) {
                    continue;
                }
                $recordedDate = $insDetail['recorded_date'];
                $docId = $insDetail['instrument'];
                $fips = $insDetail['fips'];
                $type = $insDetail['type'];
                $sub_type = $insDetail['sub_type'];
                $order_number = $insDetail['order_number'];

                if (isset($recordedDate) && !empty($recordedDate)) {
                    $time = strtotime($recordedDate);
                    $year = date('Y', $time);
                }

                if (!empty($order_number)) {
                    $parameters = 'FIPS=' . $fips . ',TYPE=' . $type . ',ORDER=' . $order_number . ',SUBTYPE=' . $sub_type . ',YEAR=' . $year . ',INST=' . $docId . '';
                } else {
                    $parameters = 'FIPS=' . $fips . ',TYPE=REC,SUBTYPE=ALL,YEAR=' . $year . ',INST=' . $docId . '';
                }

                $docId = (string) ((int) ($docId));
                $requestParams = array(
                    'parameters' => $parameters,
                    'username' => env('TP_USERNAME'),
                    'password' => env('TP_PASSWORD'),
                    'company' => '',
                    'department' => '',
                    'titleOfficer' => '',
                    'pages' => '',
                    'propertyOnly' => 'FALSE',
                    'maxPageCount' => 0,
                    'maxSizeInKB' => 0,
                    'additionalInfo' => '',
                    'customerRef' => '',
                    'fileType' => 'PDF',
                );

                $request = env('GRANT_DEED_ENDPOINT') . http_build_query($requestParams);

                $opts = array(
                    "ssl" => array(
                        "verify_peer" => false,
                        "verify_peer_name" => false,
                    ),
                );

                $context = stream_context_create($opts);
                $logid = $this->apiLogs->syncLogs(0, 'titlepoint', 'generate_instrument_document', $request, $requestParams, array(), $file_number, 0);
                $file = file_get_contents($request, false, $context);
                $xmlData = simplexml_load_string($file);
                $response = json_encode($xmlData);
                $result = json_decode($response, true);
                $this->apiLogs->syncLogs(0, 'titlepoint', 'generate_instrument_document', $request, $requestParams, $result, $file_number, $logid);
                $responseStatus = isset($result['Status']['Msg']) && !empty($result['Status']['Msg']) ? $result['Status']['Msg'] : '';
                $docStatus = isset($result['Documents']['DocumentResponse']['DocStatus']['Msg']) && !empty($result['Documents']['DocumentResponse']['DocStatus']['Msg']) ? $result['Documents']['DocumentResponse']['DocStatus']['Msg'] : '';
                $docStatus = strtolower($docStatus);

                if (isset($docStatus) && !empty($docStatus) && $docStatus == 'ok') {
                    $base64_data = isset($result['Documents']['DocumentResponse']['Document']['Body']['Body']) && !empty($result['Documents']['DocumentResponse']['Document']['Body']['Body']) ? $result['Documents']['DocumentResponse']['Document']['Body']['Body'] : '';

                    if (isset($base64_data) && !empty($base64_data)) {
                        $bin = base64_decode($base64_data, true);

                        if (!is_dir('uploads/title-point')) {
                            mkdir('./uploads/title-point', 0777, true);
                        }
                        $fileExist = $this->order->fileExistOrNotOnS3("title-point/" . $insDetail['id'] . '.pdf');
                        if ($fileExist) {

                        }
                        $pdfFilePath = './uploads/title-point/' . $insDetail['id'] . '.pdf';
                        file_put_contents($pdfFilePath, $bin);
                        $this->order->uploadDocumentOnAwsS3($insDetail['id'] . '.pdf', 'title-point');
                    }
                }
            }
        }
    }

    public function generateTaxDocument($requestId, $orderId, $fileNumber)
    {
        $userdata = $this->session->userdata('user');
        $requestParams = array(
            'username' => env('TP_USERNAME'),
            'password' => env('TP_PASSWORD'),
            'requestId' => $requestId,
        );
        // print_r($requestParams);die;
        $request = env('TP_GENERATE_IMAGE') . http_build_query($requestParams);
        $requestUrl = env('TP_GENERATE_IMAGE');
        $logid = $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'generate_tax_image_BG', $request, $requestParams, array(), $orderId, 0);
        $response = $this->order->curl_post($requestUrl, $requestParams);

        $result = json_decode($response, true);

        $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'generate_tax_image_BG', $request, $response, $result, $orderId, $logid);

        $imgReturnStatus = isset($result['ReturnStatus']) && !empty($result['ReturnStatus']) ? $result['ReturnStatus'] : '';
        $imgReturnStatus = strtolower($imgReturnStatus);

        $generateImgStatus = isset($result['Status']) && !empty($result['Status']) ? $result['Status'] : '';
        $generateImgStatus = strtolower($generateImgStatus);

        if ($imgReturnStatus == 'success') {
            if ($generateImgStatus == 'processing') {
                if ($this->taxcount <= 10) {
                    sleep(3);
                    $this->taxcount = $this->taxcount + 1;
                    return $this->generateTaxDocument($requestId, $orderId, $fileNumber);
                } else {
                    $generateImgStatus = 'failed';
                }
            } else if ($generateImgStatus == 'success') {
                $base64_data = isset($result['Data']) && !empty($result['Data']) ? $result['Data'] : '';

                if (isset($base64_data) && !empty($base64_data)) {
                    $bin = base64_decode($base64_data, true);

                    if (!is_dir('uploads/tax')) {
                        mkdir('./uploads/tax', 0777, true);
                    }

                    $pdfFilePath = './uploads/tax/' . $fileNumber . '.pdf';
                    file_put_contents($pdfFilePath, $bin);
                    $this->order->uploadDocumentOnAwsS3($fileNumber . '.pdf', 'tax');
                }
            }
        }

        $tpData = array(
            'tax_file_status' => $generateImgStatus,
        );

        $condition = array(
            'file_number' => $fileNumber,
        );
        $this->titlePointData->update($tpData, $condition);

        $condition = array(
            'where' => array(
                'file_number' => $fileNumber,
            ),
        );
        $titlePointDetails = $this->titlePointData->gettitlePointDetails($condition);
        $lvDocStatus = strtolower($titlePointDetails[0]['lv_file_status']);
        $taxDataStatus = strtolower($titlePointDetails[0]['tax_data_status']);
        $taxDocStatus = strtolower($titlePointDetails[0]['tax_file_status']);
        $emailSentFlag = strtolower($titlePointDetails[0]['email_sent_status']);
        $this->apiLogs->syncLogs(0, 'email-check-Tax', 'email-check-Tax', '', ['$emailSentFlag' => $emailSentFlag, '$taxDocStatus' => $taxDocStatus, 'tax_data_status' => $taxDataStatus, '$lvDocStatus' => $lvDocStatus], array(), 0, 0);
        // if ($emailSentFlag != 1  && ($taxDocStatus == 'success' || $taxDocStatus == 'failed' || $taxDocStatus == 'exception') && ($lvDocStatus == 'success' || $lvDocStatus == 'failed' || $lvDocStatus == 'exception'))
        if ($emailSentFlag != 1 && ($lvDocStatus == 'success' || $lvDocStatus == 'failed' || $lvDocStatus == 'exception') && ($taxDocStatus == 'success' || $taxDocStatus == 'failed' || $taxDocStatus == 'exception')) {
            $this->order->sendOrderEmail($fileNumber);
            $this->session->set_userdata('email_sent_flag', 1);
        }
    }

    public function generateLVDocument($requestId, $orderId, $fileNumber)
    {
        $userdata = $this->session->userdata('user');
        $requestParams = array(
            'username' => env('TP_USERNAME'),
            'password' => env('TP_PASSWORD'),
            'requestId' => $requestId,
        );

        $request = env('TP_GENERATE_IMAGE') . http_build_query($requestParams);
        $requestName = 'generate_lv_image';
        $requestUrl = env('TP_GENERATE_IMAGE');
        $logid = $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'generate_lv_image_BG', $request, $requestParams, array(), $orderId, 0);
        $response = $this->order->curl_post($requestUrl, $requestParams);

        $result = json_decode($response, true);
        $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'generate_lv_image_BG', $request, $requestParams, $result, $orderId, $logid);

        $imgReturnStatus = isset($result['ReturnStatus']) && !empty($result['ReturnStatus']) ? $result['ReturnStatus'] : '';
        $imgReturnStatus = strtolower($imgReturnStatus);

        $generateImgStatus = isset($result['Status']) && !empty($result['Status']) ? $result['Status'] : '';
        $generateImgStatus = strtolower($generateImgStatus);

        if ($imgReturnStatus == 'success') {
            if ($generateImgStatus == 'processing') {
                if ($this->lvcount <= 10) {
                    sleep(3);
                    $this->lvcount += 1;
                    return $this->generateLVDocument($requestId, $orderId, $fileNumber);
                } else {
                    $generateImgStatus = 'failed';
                }
            } else if ($generateImgStatus == 'success') {
                $base64_data = isset($result['Data']) && !empty($result['Data']) ? $result['Data'] : '';

                if (isset($base64_data) && !empty($base64_data)) {
                    $bin = base64_decode($base64_data, true);

                    if (!is_dir('uploads/legal-vesting')) {
                        mkdir('./uploads/legal-vesting', 0777, true);
                    }
                    $pdfFilePath = './uploads/legal-vesting/' . $fileNumber . '.pdf';
                    file_put_contents($pdfFilePath, $bin);
                    $this->order->uploadDocumentOnAwsS3($fileNumber . '.pdf', 'legal-vesting');
                }
            }
        }

        $tpData = array(
            'lv_file_status' => $generateImgStatus,
        );

        $condition = array(
            'file_number' => $fileNumber,
        );
        $this->titlePointData->update($tpData, $condition);

        $condition = array(
            'where' => array(
                'file_number' => $fileNumber,
            ),
        );
        $titlePointDetails = $this->titlePointData->gettitlePointDetails($condition);
        $lvDocStatus = strtolower($titlePointDetails[0]['lv_file_status']);
        $taxDataStatus = strtolower($titlePointDetails[0]['tax_data_status']);
        $taxDocStatus = strtolower($titlePointDetails[0]['tax_file_status']);
        $emailSentFlag = strtolower($titlePointDetails[0]['email_sent_status']);
        $this->apiLogs->syncLogs(0, 'email-check-LV', 'email-check-LV', '', ['$emailSentFlag' => $emailSentFlag, '$taxDocStatus' => $taxDocStatus, '$taxDataStatus' => $taxDataStatus, '$lvDocStatus' => $lvDocStatus], array(), 0, 0);
        // if ($emailSentFlag != 1  && ($taxDocStatus == 'success' || $taxDocStatus == 'failed' || $taxDocStatus == 'exception') && ($lvDocStatus == 'success' || $lvDocStatus == 'failed' || $lvDocStatus == 'exception'))
        if ($emailSentFlag != 1 && ($lvDocStatus == 'success' || $lvDocStatus == 'failed' || $lvDocStatus == 'exception') && ($taxDocStatus == 'success' || $taxDocStatus == 'failed' || $taxDocStatus == 'exception')) {
            $this->order->sendOrderEmail($fileNumber);
            $this->session->set_userdata('email_sent_flag', 1);
        }

    }

    public function updateAllowDuplicationFlag()
    {
        $this->db->select('order_details.property_id');
        $this->db->from('order_details');
        $this->db->where('order_details.resware_status = "closed" OR order_details.resware_status = "clear for policy"');
        $this->db->where('property_details.allow_duplication = 0');
        $this->db->join('property_details', 'order_details.property_id = property_details.id', 'inner');
        $this->db->order_by("order_details.id", "desc");
        $query = $this->db->get();
        $filesResult = $query->result_array();

        $duplicationUpdateArray = array();
        if (isset($filesResult) && !empty($filesResult)) {
            foreach ($filesResult as $file) {
                $duplicationUpdateArray[] = $file['property_id'];
            }
        }

        if (!empty($duplicationUpdateArray)) {
            $chunk = array_chunk($duplicationUpdateArray, 500);
            for ($i = 0; $i < count($chunk); $i++) {
                $updateDuplicationData = array('allow_duplication' => 1);
                $this->db->set($updateDuplicationData);
                $this->db->where_in("id", $chunk[$i]);
                $this->db->update('property_details');
            }
        }
    }

    public function updateLpReportStatus()
    {
        $this->db->select('*');
        $this->db->from('order_details');
        $this->db->where('lp_file_number IS NOT NULL');
        $this->db->where('file_number IS NOT NULL and file_number != 0');
        $query = $this->db->get();
        $result = $query->result_array();

        if (!empty($result)) {
            foreach ($result as $res) {
                $orderData = array(
                    'lp_report_status' => 'converted',
                );
                $condition = array(
                    'file_id' => $res['file_id'],
                );

                $this->home_model->update($orderData, $condition, 'order_details');
            }
        }

    }

    public function updateLpReportStatusForOldOrders()
    {
        $this->db->select('*');
        $this->db->from('order_details');
        $this->db->where('lp_file_number IS NOT NULL');
        $this->db->where('created_at <= (NOW() - INTERVAL 90 DAY)');
        $this->db->where('lp_report_status', 'pending');
        $query = $this->db->get();
        $result = $query->result_array();

        if (!empty($result)) {
            foreach ($result as $res) {
                $orderData = array(
                    'lp_report_status' => 'denied',
                );
                $condition = array(
                    'file_id' => $res['file_id'],
                );

                $this->home_model->update($orderData, $condition, 'order_details');
            }
        }
    }

    public function getIdealUsers()
    {
        $startDate = date('Y-m-d 00:00:00', strtotime('-97 days', strtotime(date('Y-m-d'))));
        $endDate = date('Y-m-d 23:59:59', strtotime('-7 days', strtotime(date('Y-m-d'))));
        $this->db->select('*')
            ->from('order_details')
            ->join('transaction_details', 'order_details.transaction_id = transaction_details.id');
        $this->db->where('order_details.lp_file_number is not null');
        $this->db->where('order_details.created_at BETWEEN "' . $startDate . '" and "' . $endDate . '"');
        $this->db->where_in('transaction_details.sales_representative', 11942);
        $this->db->where('order_details.`is_imported` = 0');
        $this->db->where('order_details.`file_number` != 0');
        $query = $this->db->get();
        echo $this->db->last_query();exit;
        $result = $query->result_array();
        return $result;
    }

    public function deleteOldLogs()
    {
        // Define the path to the logs directory
        $log_path = APPPATH . 'logs/';

        // Define the retention period (30 days)
        $retention_period = 5 * 24 * 60 * 60; // 30 days in seconds

        // Get the current time
        $current_time = time();

        // Open the logs directory
        if ($handle = opendir($log_path)) {
            while (false !== ($file = readdir($handle))) {
                // Skip current and parent directory links
                if ($file != "." && $file != "..") {
                    $file_path = $log_path . $file;

                    // Ensure it is a file and ends with .php
                    if (is_file($file_path) && pathinfo($file_path, PATHINFO_EXTENSION) == 'php') {
                        // Check if the file is older than the retention period
                        if (($current_time - filemtime($file_path)) > $retention_period) {
                            // Delete the file
                            unlink($file_path);
                            echo "Deleted old log file: $file\n";
                        }
                    }
                }
            }
            closedir($handle);
        }
    }
}
