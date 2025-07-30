<?php

(defined('BASEPATH')) or exit('No direct script access allowed');

class TitlePoint extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('order/apiLogs');
        $this->load->model('order/titlePointData');
        $this->load->library('order/order');
        // $this->order->is_user();
    }

    public function createService()
    {
        $userdata = $this->session->userdata('user');

        $methodId = isset($_POST['methodId']) && !empty($_POST['methodId']) ? $_POST['methodId'] : '';

        $random_number = isset($_POST['random_number']) && !empty($_POST['random_number']) ? $_POST['random_number'] : '';

        if (empty($random_number)) {
            $response = array('status' => 'error', 'message' => 'Empty random number');
            echo json_encode($response);exit;
        }
        /* Insert into table */
        if (!$this->session->userdata('tp_api_id_' . $random_number)) {
            if ($random_number) {
                $tpData = array(
                    'session_id' => 'tp_api_id_' . $random_number,
                );
                $tpId = $this->titlePointData->insert($tpData);

                $this->session->set_userdata('tp_api_id_' . $random_number, 1);
            }
        }
        /* Insert into table */

        $requestParams = array(
            'userID' => env('TP_USERNAME'),
            'password' => env('TP_PASSWORD'),
            'orderNo' => '',
            'customerRef' => rand(),
            'company' => '',
            'department' => '',
            'titleOfficer' => '',
            'orderComment' => '',
            'starterRemarks' => '',
        );

        if ($methodId == 3) {
            $apn = isset($_POST['apn']) && !empty($_POST['apn']) ? $_POST['apn'] : '';
            $apn = str_replace('0000', '0-000', $apn);
            $state = isset($_POST['state']) && !empty($_POST['state']) ? $_POST['state'] : '';
            $county = isset($_POST['county']) && !empty($_POST['county']) ? $_POST['county'] : '';
            $requestParams['serviceType'] = env('TAX_SEARCH_SERVICE_TYPE');
            $requestParams['parameters'] = 'Tax.APN=' . $apn . ';General.AutoSearchTaxes=true;General.AutoSearchProperty=false';
            $requestParams['state'] = $state;
            $requestParams['county'] = $county;
            $requestUrl = env('TP_TAX_INSTRUMENT_CREATE_SERVICE_ENDPOINT');
            $request_type = 'create_service_3';
        } else if ($methodId == 4) {
            $fipsCode = isset($_POST['fipCode']) && !empty($_POST['fipCode']) ? $_POST['fipCode'] : '';
            $address = isset($_POST['address']) && !empty($_POST['address']) ? $_POST['address'] : '';
            $city = isset($_POST['city']) && !empty($_POST['city']) ? $_POST['city'] : '';
            $unit_no = isset($_POST['unit_no']) && !empty($_POST['unit_no']) ? $_POST['unit_no'] : '';
            $apn = isset($_POST['apn']) && !empty($_POST['apn']) ? $_POST['apn'] : '';
            $bedRooms = isset($_POST['bedRooms']) ? $_POST['bedRooms'] : '';
            $baths = isset($_POST['baths']) ? $_POST['baths'] : '';
            $lotSize = isset($_POST['lotSize']) && !empty($_POST['lotSize']) ? $_POST['lotSize'] : '';
            $zoning = isset($_POST['zoning']) && !empty($_POST['zoning']) ? $_POST['zoning'] : '';
            $buildingArea = isset($_POST['buildingArea']) && !empty($_POST['buildingArea']) ? $_POST['buildingArea'] : '';
            if ($unit_no) {
                $unitinfo = 'UnitNumber ' . $unit_no . ', ';
            }
            $configData = $this->order->getConfigData();
            $enableLvWithAddressApn = $configData['enable_lv_with_address_apn']['is_enable'];
            $requestParams['serviceType'] = env('SERVICE_TYPE');
            if (empty($enableLvWithAddressApn) || $enableLvWithAddressApn == 0) {
                $requestParams['parameters'] = 'Pin=' . $apn . ';LvLookup=Address;LvLookupValue=' . $address . ', ' . $unitinfo . $city . ';LvReportFormat=LV;IncludeTaxAssessor=true';
            } else {
                $requestParams['parameters'] = 'Address1=' . $address . ';City=' . $city . ';Pin=' . $apn . ';LvLookup=Address;LvLookupValue=' . $address . ', ' . $unitinfo . $city . ';LvReportFormat=LV;IncludeTaxAssessor=true';
            }
            // $requestParams['parameters'] = 'Address1=' . $address . ';City=' . $city . ';LvLookup=Address;LvLookupValue=' . $address . ', ' . $unitinfo . $city . ';LvReportFormat=LV;IncludeTaxAssessor=true';

            $requestParams['fipsCode'] = $fipsCode;
            $requestUrl = env('TP_CREATE_SERVICE_ENDPOINT');
            $request_type = 'create_service_4';
        }
        $request = $requestUrl . http_build_query($requestParams);

        $logid = $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', $request_type, $request, $requestParams, array(), $random_number, 0);

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

        $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', $request_type, $request, $requestParams, $result, $random_number, $logid);

        if (isset($result) && empty($result)) {
            $tpData = array(
                'cs4_message' => 'Failed',
            );

            if ($this->session->has_userdata('tp_api_id_' . $random_number)) {
                $session_id = 'tp_api_id_' . $random_number;
                $condition = array(
                    'session_id' => $session_id,
                );
                $this->titlePointData->update($tpData, $condition);
            } else {
                $tpData['session_id'] = 'tp_api_id_' . $random_number;

                $tpId = $this->titlePointData->insert($tpData);

                $this->session->set_userdata('tp_api_id_' . $random_number, 1);

            }
        } else {
            $responseStatus = isset($result['ReturnStatus']) && !empty($result['ReturnStatus']) ? $result['ReturnStatus'] : '';

            if ($methodId == 4) {
                if ($responseStatus == 'Success') {
                    $requestId = isset($result['RequestID']) && !empty($result['RequestID']) ? $result['RequestID'] : '';
                    $tpData = array(
                        'cs4_request_id' => $requestId,
                        'property_bedroom' => $bedRooms,
                        'property_bathroom' => $baths,
                        'property_lotsize' => $lotSize,
                        'property_zoning' => $zoning,
                        'property_squarefeet' => $buildingArea,
                    );

                    if ($this->session->has_userdata('tp_api_id_' . $random_number)) {
                        $session_id = 'tp_api_id_' . $random_number;
                        $condition = array(
                            'session_id' => $session_id,
                        );
                        $this->titlePointData->update($tpData, $condition);
                    } else {
                        $tpData['session_id'] = 'tp_api_id_' . $random_number;

                        $tpId = $this->titlePointData->insert($tpData);

                        $this->session->set_userdata('tp_api_id_' . $random_number, 1);

                    }
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

                    if ($this->session->has_userdata('tp_api_id_' . $random_number)) {
                        $session_id = 'tp_api_id_' . $random_number;
                        $condition = array(
                            'session_id' => $session_id,
                        );
                        $this->titlePointData->update($tpData, $condition);
                    } else {
                        $tpData['session_id'] = 'tp_api_id_' . $random_number;

                        $tpId = $this->titlePointData->insert($tpData);

                        $this->session->set_userdata('tp_api_id_' . $random_number, 1);

                    }
                } else {
                    $error = isset($result['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($result['ReturnErrors']['ReturnError']['ErrorDescription']) ? $result['ReturnErrors']['ReturnError']['ErrorDescription'] : '';
                    $this->addLogs($methodId, $responseStatus, '', $error, $random_number);
                }
            }

            echo trim($file);
        }

    }

    public function getRequestSummaries()
    {
        $userdata = $this->session->userdata('user');
        $requestId = isset($_POST['requestId']) && !empty($_POST['requestId']) ? $_POST['requestId'] : '';
        $methodId = isset($_POST['methodId']) && !empty($_POST['methodId']) ? $_POST['methodId'] : '';
        $apn = isset($_POST['apn']) && !empty($_POST['apn']) ? $_POST['apn'] : '';
        $random_number = isset($_POST['random_number']) && !empty($_POST['random_number']) ? $_POST['random_number'] : '';
        $random_number = isset($_POST['random_number']) && !empty($_POST['random_number']) ? $_POST['random_number'] : '';

        if (empty($random_number)) {
            $response = array('status' => 'error', 'message' => 'Empty random number');
            echo json_encode($response);exit;
        }
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

        $logid = $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'get_request_summary_' . $methodId, $request, $requestParams, array(), $random_number, 0);

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

        $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'get_request_summary_' . $methodId, $request, $requestParams, $result, $random_number, $logid);

        if (isset($result) && empty($result)) {
            $tpData = array(
                'cs4_message' => 'Failed',
            );

            if ($this->session->has_userdata('tp_api_id_' . $random_number)) {
                $session_id = 'tp_api_id_' . $random_number;
                $condition = array(
                    'session_id' => $session_id,
                );
                $this->titlePointData->update($tpData, $condition);
            } else {
                $tpData['session_id'] = 'tp_api_id_' . $random_number;

                $tpId = $this->titlePointData->insert($tpData);

                $this->session->set_userdata('tp_api_id_' . $random_number, 1);

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

                    if ($this->session->has_userdata('tp_api_id_' . $random_number)) {
                        $session_id = 'tp_api_id_' . $random_number;
                        $condition = array(
                            'session_id' => $session_id,
                        );
                        $this->titlePointData->update($tpData, $condition);
                    } else {
                        $tpData['session_id'] = 'tp_api_id_' . $random_number;

                        $tpId = $this->titlePointData->insert($tpData);

                        $this->session->set_userdata('tp_api_id_' . $random_number, 1);

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

                    if ($this->session->has_userdata('tp_api_id_' . $random_number)) {
                        $session_id = 'tp_api_id_' . $random_number;
                        $condition = array(
                            'session_id' => $session_id,
                        );
                        $this->titlePointData->update($tpData, $condition);
                    } else {
                        $tpData['session_id'] = 'tp_api_id_' . $random_number;

                        $tpId = $this->titlePointData->insert($tpData);

                        $this->session->set_userdata('tp_api_id_' . $random_number, 1);

                    }
                } else {
                    $error = isset($result['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($result['ReturnErrors']['ReturnError']['ErrorDescription']) ? $result['ReturnErrors']['ReturnError']['ErrorDescription'] : '';
                    $this->addLogs($methodId, $responseStatus, '', $error, $random_number);
                }
            }

            echo trim($file);
        }

    }

    public function getResultById()
    {
        $userdata = $this->session->userdata('user');
        $resultId = isset($_POST['resultId']) && !empty($_POST['resultId']) ? $_POST['resultId'] : '';
        $methodId = isset($_POST['methodId']) && !empty($_POST['methodId']) ? $_POST['methodId'] : '';
        $apn = isset($_POST['apn']) && !empty($_POST['apn']) ? $_POST['apn'] : '';
        $random_number = isset($_POST['random_number']) && !empty($_POST['random_number']) ? $_POST['random_number'] : '';
        if (empty($random_number)) {
            $response = array('status' => 'error', 'message' => 'Empty random number');
            echo json_encode($response);exit;
        }
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

        $logid = $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'get_result_by_id_' . $methodId, $request, $requestParams, array(), $random_number, 0);

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

        $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'get_result_by_id_' . $methodId, $request, $requestParams, $result, $random_number, $logid);

        if (isset($result) && empty($result)) {
            $tpData = array(
                'cs4_message' => 'Failed',
            );

            if ($this->session->has_userdata('tp_api_id_' . $random_number)) {
                $session_id = 'tp_api_id_' . $random_number;
                $condition = array(
                    'session_id' => $session_id,
                );
                $this->titlePointData->update($tpData, $condition);
            } else {
                $tpData['session_id'] = 'tp_api_id_' . $random_number;

                $tpId = $this->titlePointData->insert($tpData);

                $this->session->set_userdata('tp_api_id_' . $random_number, 1);

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

                    $configData = $this->order->getConfigData();
                    $enableVestingDocumentTypeFilter = $configData['enable_vesting_document_type_filter']['is_enable'];

                    if (count($legal_vesting_info) == count($legal_vesting_info, COUNT_RECURSIVE)) {
                        $docType = isset($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['DocType']) && !empty($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['DocType']) ? $result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['DocType'] : '';
                        $docType = strtolower($docType);

                        // if (($docType == 'grant deed' || $docType == 'intrafamily transfer & dissolution' || $docType == 'quit claim deed' || $docType == 'intra-family transfer or dissolution')) {
                        if (($enableVestingDocumentTypeFilter == 0) || ($docType == 'grant deed' || $docType == 'intrafamily transfer & dissolution' || $docType == 'quit claim deed' || $docType == 'intra-family transfer or dissolution')) {
                            $instrumentNumber = isset($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['InstrumentNumber']) && !empty($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['InstrumentNumber']) ? $result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['InstrumentNumber'] : '';
                            $recordedDate = isset($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['RecordedDate']) && !empty($result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['RecordedDate']) ? $result['Result']['LvDeeds']['LegalAndVesting2DeedInfo']['RecordedDate'] : '';
                        }

                    } else {
                        foreach ($legal_vesting_info as $key => $value) {
                            $docType = isset($value['DocType']) && !empty($value['DocType']) ? $value['DocType'] : '';
                            $docType = strtolower($docType);
                            $instruNumber = isset($value['InstrumentNumber']) && !empty($value['InstrumentNumber']) ? $value['InstrumentNumber'] : '';
                            // if (!empty($instruNumber) && (($docType == 'grant deed' || $docType == 'intrafamily transfer & dissolution' || $docType == 'quit claim deed' || $docType == 'intra-family transfer or dissolution'))) {
                            if (!empty($instruNumber) && (($enableVestingDocumentTypeFilter == 0) || ($docType == 'grant deed' || $docType == 'intrafamily transfer & dissolution' || $docType == 'quit claim deed' || $docType == 'intra-family transfer or dissolution'))) {
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

                    if ($this->session->has_userdata('tp_api_id_' . $random_number)) {
                        $session_id = 'tp_api_id_' . $random_number;
                        $condition = array(
                            'session_id' => $session_id,
                        );
                        $this->titlePointData->update($tpData, $condition);
                    } else {
                        $tpData['session_id'] = 'tp_api_id_' . $random_number;

                        $tpId = $this->titlePointData->insert($tpData);

                        $this->session->set_userdata('tp_api_id_' . $random_number, 1);

                    }
                    $this->addLogs($methodId, $responseStatus, $status, $error, $random_number);
                } else {
                    $error = isset($result['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($result['ReturnErrors']['ReturnError']['ErrorDescription']) ? $result['ReturnErrors']['ReturnError']['ErrorDescription'] : '';
                    $this->addLogs($methodId, $responseStatus, '', $error, $random_number);
                }
            }
            $taxDataStatus = 'Failed';
            if ($methodId == 3) {
                if ($responseStatus == 'Success') {
                    $firstInstallment = $secondInstallment = array();

                    if (isset($result['Result']['TaxReport']['Installments']['Item'][0]) && !empty($result['Result']['TaxReport']['Installments']['Item'][0])) {
                        $firstInstallment = $result['Result']['TaxReport']['Installments']['Item'][0];
                        $taxDataStatus = 'Success';
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
                        'tax_rate_area' => isset($result['Result']['TaxReport']['TaxRateArea']) && !empty($result['Result']['TaxReport']['TaxRateArea']) ? $result['Result']['TaxReport']['TaxRateArea'] : '',
                        'use_code' => isset($result['Result']['TaxReport']['UseCode']) && !empty($result['Result']['TaxReport']['UseCode']) ? $result['Result']['TaxReport']['UseCode'] : '',
                        'region_code' => isset($result['Result']['TaxReport']['RegionCode']) && !empty($result['Result']['TaxReport']['RegionCode']) ? $result['Result']['TaxReport']['RegionCode'] : '',
                        'flood_zone' => isset($result['Result']['TaxReport']['FloodZone']) && !empty($result['Result']['TaxReport']['FloodZone']) ? $result['Result']['TaxReport']['FloodZone'] : '',
                        'zoning_code' => isset($result['Result']['TaxReport']['ZoningCode']) && !empty($result['Result']['TaxReport']['ZoningCode']) ? $result['Result']['TaxReport']['ZoningCode'] : '',
                        'taxability_code' => isset($result['Result']['TaxReport']['TaxabilityCode']) && !empty($result['Result']['TaxReport']['TaxabilityCode']) ? $result['Result']['TaxReport']['TaxabilityCode'] : '',
                        'tax_rate' => isset($result['Result']['TaxReport']['TaxRate']) && !empty($result['Result']['TaxReport']['TaxRate']) ? $result['Result']['TaxReport']['TaxRate'] : '',
                        'issue_date' => isset($result['Result']['TaxReport']['IssueDate']) && !empty($result['Result']['TaxReport']['IssueDate']) ? $result['Result']['TaxReport']['IssueDate'] : '',
                        'land' => isset($result['Result']['TaxReport']['LandValuation']) && !empty($result['Result']['TaxReport']['LandValuation']) ? $result['Result']['TaxReport']['LandValuation'] : '',
                        'improvements' => isset($result['Result']['TaxReport']['ImprovementsValuation']) && !empty($result['Result']['TaxReport']['ImprovementsValuation']) ? $result['Result']['TaxReport']['ImprovementsValuation'] : '',
                        'tax_data_status' => $taxDataStatus,
                    );

                    if ($this->session->has_userdata('tp_api_id_' . $random_number)) {
                        $session_id = 'tp_api_id_' . $random_number;
                        $condition = array(
                            'session_id' => $session_id,
                        );
                        $this->titlePointData->update($tpData, $condition);
                    } else {
                        $tpData['session_id'] = 'tp_api_id_' . $random_number;

                        $tpId = $this->titlePointData->insert($tpData);

                        $this->session->set_userdata('tp_api_id_' . $random_number, 1);

                    }
                    $this->addLogs($methodId, $responseStatus, $message, $error, $random_number);
                    /** Start: Save Tax data xml response in S3 */
                    if (!is_dir('uploads/tax-data-xml')) {
                        mkdir('./uploads/tax-data-xml', 0777, true);
                    }
                    $pdfFilePath = './uploads/tax-data-xml/tp_api_id_' . $random_number . '.xml';

                    // print_r($pdfFilePath);die;
                    file_put_contents($pdfFilePath, $file);
                    $this->order->uploadDocumentOnAwsS3('tp_api_id_' . $random_number . '.xml', 'tax-data-xml');
                    /** End: Save Tax data xml response in S3 */

                    // $data = array();
                    // $data['apn'] = isset($result['Result']['TaxReport']['APN']) && !empty($result['Result']['TaxReport']['APN']) ? $result['Result']['TaxReport']['APN'] : '';
                    // $data['description'] = isset($result['Result']['TaxReport']['Description']) && !empty($result['Result']['TaxReport']['Description']) ? $result['Result']['TaxReport']['Description'] : '';
                    // $data['property_address'] = isset($result['Result']['TaxReport']['PropertyAddress']) && !empty($result['Result']['TaxReport']['PropertyAddress']) ? $result['Result']['TaxReport']['PropertyAddress'] : '';
                    // $data['billing_address'] = isset($result['Result']['TaxReport']['BillingAddress']) && !empty($result['Result']['TaxReport']['BillingAddress']) ? $result['Result']['TaxReport']['BillingAddress'] : '';
                    // $data['city_county'] = '';
                    // if (!empty($result['Result']['TaxReport']['AssessedOwners']['Item'])) {
                    //     $data['assessed_owners'] = '';
                    //     foreach($result['Result']['TaxReport']['AssessedOwners']['Item'] as $assessedOwner) {
                    //         $data['assessed_owners'] .= $assessedOwner.";";
                    //     }
                    // } else {
                    //     $data['assessed_owners'] = '';
                    // }

                    // if (!empty($result['Result']['TaxReport']['SearchAsPointers']['Item'])) {
                    //     $data['SearchAsPointers'] = $result['Result']['TaxReport']['SearchAsPointers']['Item'];
                    // } else {
                    //     $data['SearchAsPointers'] = '';
                    // }
                    // $data['city'] = isset($result['Result']['TaxReport']['City']) && !empty($result['Result']['TaxReport']['City']) ? $result['Result']['TaxReport']['City'] : '';
                    // $data['tax_year'] = isset($result['Result']['TaxReport']['TaxYear']) && !empty($result['Result']['TaxReport']['TaxYear']) ? $result['Result']['TaxReport']['TaxYear'] : '';
                    // $data['TaxRateArea'] = isset($result['Result']['TaxReport']['TaxRateArea']) && !empty($result['Result']['TaxReport']['TaxRateArea']) ? $result['Result']['TaxReport']['TaxRateArea'] : '';
                    // $data['UseCode'] = isset($result['Result']['TaxReport']['UseCode']) && !empty($result['Result']['TaxReport']['UseCode']) ? $result['Result']['TaxReport']['UseCode'] : '';
                    // $data['UseDescription'] = isset($result['Result']['TaxReport']['UseDescription']) && !empty($result['Result']['TaxReport']['UseDescription']) ? $result['Result']['TaxReport']['UseDescription'] : '';
                    // $data['RegionCode'] = isset($result['Result']['TaxReport']['RegionCode']) && !empty($result['Result']['TaxReport']['RegionCode']) ? $result['Result']['TaxReport']['RegionCode'] : '';
                    // $data['ZoningCode'] = isset($result['Result']['TaxReport']['ZoningCode']) && !empty($result['Result']['TaxReport']['ZoningCode']) ? $result['Result']['TaxReport']['ZoningCode'] : '';
                    // $data['FloodZone'] = isset($result['Result']['TaxReport']['FloodZone']) && !empty($result['Result']['TaxReport']['FloodZone']) ? $result['Result']['TaxReport']['FloodZone'] : '';
                    // $data['TaxabilityCode'] = isset($result['Result']['TaxReport']['TaxabilityCode']) && !empty($result['Result']['TaxReport']['TaxabilityCode']) ? $result['Result']['TaxReport']['TaxabilityCode'] : '';
                    // $data['TaxRate'] = isset($result['Result']['TaxReport']['TaxRate']) && !empty($result['Result']['TaxReport']['TaxRate']) ? $result['Result']['TaxReport']['TaxRate'] : '';
                    // $data['IssueDate'] = isset($result['Result']['TaxReport']['IssueDate']) && !empty($result['Result']['TaxReport']['IssueDate']) ? $result['Result']['TaxReport']['IssueDate'] : '';
                    // $data['LandValuation'] = isset($result['Result']['TaxReport']['LandValuation']) && !empty($result['Result']['TaxReport']['LandValuation']) ? $result['Result']['TaxReport']['LandValuation'] : '';
                    // $data['ImprovementsValuation'] = isset($result['Result']['TaxReport']['ImprovementsValuation']) && !empty($result['Result']['TaxReport']['ImprovementsValuation']) ? $result['Result']['TaxReport']['ImprovementsValuation'] : '';
                    // $data['NetTaxableValue'] = isset($result['Result']['TaxReport']['NetTaxableValue']) && !empty($result['Result']['TaxReport']['NetTaxableValue']) ? $result['Result']['TaxReport']['NetTaxableValue'] : '';
                    // $data['YearBuilt'] = isset($result['Result']['TaxReport']['YearBuilt']) && !empty($result['Result']['TaxReport']['YearBuilt']) ? $result['Result']['TaxReport']['YearBuilt'] : '';
                    // $data['YearLastModified'] = isset($result['Result']['TaxReport']['YearLastModified']) && !empty($result['Result']['TaxReport']['YearLastModified']) ? $result['Result']['TaxReport']['YearLastModified'] : '';
                    // $data['ImprovementsSqFootage'] = isset($result['Result']['TaxReport']['ImprovementsSqFootage']) && !empty($result['Result']['TaxReport']['ImprovementsSqFootage']) ? $result['Result']['TaxReport']['ImprovementsSqFootage'] : '';
                    // $data['TotalTax'] = isset($result['Result']['TaxReport']['TotalTax']) && !empty($result['Result']['TaxReport']['TotalTax']) ? $result['Result']['TaxReport']['TotalTax'] : '';
                    // $data['TotalBalanceTaxInstallment'] = isset($result['Result']['TaxReport']['TotalBalanceTaxInstallment']) && !empty($result['Result']['TaxReport']['TotalBalanceTaxInstallment']) ? number_format($result['Result']['TaxReport']['TotalBalanceTaxInstallment'],2) : '';
                    // $data['HomeOwnerExemption'] = isset($result['Result']['TaxReport']['HomeOwnerExemption']) && !empty($result['Result']['TaxReport']['HomeOwnerExemption']) ? number_format($result['Result']['TaxReport']['HomeOwnerExemption'],2) : '';
                    // $data['ConveyanceDate'] = isset($result['Result']['TaxReport']['ConveyanceDate']) && !empty($result['Result']['TaxReport']['ConveyanceDate']) ? $result['Result']['TaxReport']['ConveyanceDate'] : '';
                    // $data['ConveyingInstrument'] = isset($result['Result']['TaxReport']['ConveyingInstrument']) && !empty($result['Result']['TaxReport']['ConveyingInstrument']) ? $result['Result']['TaxReport']['ConveyingInstrument'] : '';

                    // $data['first_balance'] = isset($result['Result']['TaxReport']['Installments']['Item'][0]['Balance']) && !empty($result['Result']['TaxReport']['Installments']['Item'][0]['Balance']) ? number_format($result['Result']['TaxReport']['Installments']['Item'][0]['Balance'],2) : '';
                    // $data['first_amount'] = isset($result['Result']['TaxReport']['Installments']['Item'][0]['Amount']) && !empty($result['Result']['TaxReport']['Installments']['Item'][0]['Amount']) ? number_format($result['Result']['TaxReport']['Installments']['Item'][0]['Amount'],2) : '';
                    // $data['first_due_date'] = isset($result['Result']['TaxReport']['Installments']['Item'][0]['DueDate']) && !empty($result['Result']['TaxReport']['Installments']['Item'][0]['DueDate']) ? $result['Result']['TaxReport']['Installments']['Item'][0]['DueDate'] : '';
                    // $data['first_number'] = isset($result['Result']['TaxReport']['Installments']['Item'][0]['Number']) && !empty($result['Result']['TaxReport']['Installments']['Item'][0]['Number']) ? $result['Result']['TaxReport']['Installments']['Item'][0]['Number'] : '';
                    // $data['first_penalty'] = isset($result['Result']['TaxReport']['Installments']['Item'][0]['Penalty']) && !empty($result['Result']['TaxReport']['Installments']['Item'][0]['Penalty']) ? $result['Result']['TaxReport']['Installments']['Item'][0]['Penalty'] : '';
                    // $data['first_status'] = isset($result['Result']['TaxReport']['Installments']['Item'][0]['Status']) && !empty($result['Result']['TaxReport']['Installments']['Item'][0]['Status']) ? $result['Result']['TaxReport']['Installments']['Item'][0]['Status'] : '';
                    // $data['first_amount_paid'] = isset($result['Result']['TaxReport']['Installments']['Item'][0]['AmountPaid']) && !empty($result['Result']['TaxReport']['Installments']['Item'][0]['AmountPaid']) ? $result['Result']['TaxReport']['Installments']['Item'][0]['AmountPaid'] : '';
                    // $data['first_tax_year'] = isset($result['Result']['TaxReport']['Installments']['Item'][0]['TaxYear']) && !empty($result['Result']['TaxReport']['Installments']['Item'][0]['TaxYear']) ? $result['Result']['TaxReport']['Installments']['Item'][0]['TaxYear'] : '';
                    // $data['first_interest_amount'] = isset($result['Result']['TaxReport']['Installments']['Item'][0]['InterestAmount']) && !empty($result['Result']['TaxReport']['Installments']['Item'][0]['InterestAmount']) ? $result['Result']['TaxReport']['Installments']['Item'][0]['InterestAmount'] : '';

                    // $data['second_balance'] = isset($result['Result']['TaxReport']['Installments']['Item'][1]['Balance']) && !empty($result['Result']['TaxReport']['Installments']['Item'][1]['Balance']) ? number_format($result['Result']['TaxReport']['Installments']['Item'][1]['Balance'],2) : '';
                    // $data['second_amount'] = isset($result['Result']['TaxReport']['Installments']['Item'][1]['Amount']) && !empty($result['Result']['TaxReport']['Installments']['Item'][1]['Amount']) ? number_format($result['Result']['TaxReport']['Installments']['Item'][1]['Amount'],2) : '';
                    // $data['second_due_date'] = isset($result['Result']['TaxReport']['Installments']['Item'][1]['DueDate']) && !empty($result['Result']['TaxReport']['Installments']['Item'][1]['DueDate']) ? $result['Result']['TaxReport']['Installments']['Item'][1]['DueDate'] : '';
                    // $data['second_number'] = isset($result['Result']['TaxReport']['Installments']['Item'][1]['Number']) && !empty($result['Result']['TaxReport']['Installments']['Item'][1]['Number']) ? $result['Result']['TaxReport']['Installments']['Item'][1]['Number'] : '';
                    // $data['second_penalty'] = isset($result['Result']['TaxReport']['Installments']['Item'][1]['Penalty']) && !empty($result['Result']['TaxReport']['Installments']['Item'][1]['Penalty']) ? $result['Result']['TaxReport']['Installments']['Item'][1]['Penalty'] : '';
                    // $data['second_status'] = isset($result['Result']['TaxReport']['Installments']['Item'][1]['Status']) && !empty($result['Result']['TaxReport']['Installments']['Item'][1]['Status']) ? $result['Result']['TaxReport']['Installments']['Item'][1]['Status'] : '';
                    // $data['second_amount_paid'] = isset($result['Result']['TaxReport']['Installments']['Item'][1]['AmountPaid']) && !empty($result['Result']['TaxReport']['Installments']['Item'][1]['AmountPaid']) ? $result['Result']['TaxReport']['Installments']['Item'][1]['AmountPaid'] : '';
                    // $data['second_tax_year'] = isset($result['Result']['TaxReport']['Installments']['Item'][1]['TaxYear']) && !empty($result['Result']['TaxReport']['Installments']['Item'][1]['TaxYear']) ? $result['Result']['TaxReport']['Installments']['Item'][1]['TaxYear'] : '';
                    // $data['second_interest_amount'] = isset($result['Result']['TaxReport']['Installments']['Item'][1]['InterestAmount']) && !empty($result['Result']['TaxReport']['Installments']['Item'][1]['InterestAmount']) ? $result['Result']['TaxReport']['Installments']['Item'][1]['InterestAmount'] : '';
                    // $data['Liens'] = isset($result['Result']['TaxReport']['Liens']['Item']) && !empty($result['Result']['TaxReport']['Liens']['Item']) ? $result['Result']['TaxReport']['Liens']['Item'] : '';

                    // $html = $this->load->view('order/tax/taxes', $data, true);
                    // $this->load->library('snappy_pdf');
                    // $this->snappy_pdf->pdf->setOption('page-size', 'Letter');
                    // $this->snappy_pdf->pdf->setOption('zoom', '1.1');

                    // if (!is_dir('uploads/tax')) {
                    //     mkdir('./uploads/tax', 0777, TRUE);
                    // }
                    //$pdfFilePath = './uploads/tax/tp_api_id_' . $random_number.'.pdf';
                    //$pdfFilePath = str_replace('\\', '/', $pdfFilePath);
                    //$this->snappy_pdf->pdf->generateFromHtml($html, $pdfFilePath);
                } else {
                    $error = isset($result['ReturnErrors']['ReturnError']['ErrorDescription']) && !empty($result['ReturnErrors']['ReturnError']['ErrorDescription']) ? $result['ReturnErrors']['ReturnError']['ErrorDescription'] : '';
                    $this->addLogs($methodId, $responseStatus, '', $error, $random_number);
                }
            }
            echo trim($file);
        }

    }

    public function imageCreateRequest()
    {
        $userdata = $this->session->userdata('user');
        $serviceId = isset($_POST['serviceId']) && !empty($_POST['serviceId']) ? $_POST['serviceId'] : '';

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
        }

        $request = $requestUrl . http_build_query($requestParams);

        $logid = $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'create_image_request', $request, $requestParams, array(), 0, 0);

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

        $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'create_image_request', $request, $requestParams, $result, 0, $logid);
        echo trim($file);
    }

    public function getRequestStatus()
    {
        $userdata = $this->session->userdata('user');
        $requestId = isset($_POST['requestId']) && !empty($_POST['requestId']) ? $_POST['requestId'] : '';

        $requestParams = array(
            'username' => env('TP_USERNAME'),
            'password' => env('TP_PASSWORD'),
            'requestId' => $requestId,
        );
        $request = env('TP_IMAGE_REQUEST_STATUS') . http_build_query($requestParams);

        $logid = $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'image_request_status', $request, $requestParams, array(), 0, 0);

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

        $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'image_request_status', $request, $requestParams, $result, 0, $logid);
        echo trim($file);
    }

    public function generateImage()
    {
        $userdata = $this->session->userdata('user');
        $requestId = isset($_POST['requestId']) && !empty($_POST['requestId']) ? $_POST['requestId'] : '';
        $methodId = isset($_POST['methodId']) && !empty($_POST['methodId']) ? $_POST['methodId'] : '';
        $fileNumber = isset($_POST['fileNumber']) && !empty($_POST['fileNumber']) ? $_POST['fileNumber'] : '';

        $requestParams = array(
            'username' => env('TP_USERNAME'),
            'password' => env('TP_PASSWORD'),
            'requestId' => $requestId,
        );

        $request = env('TP_GENERATE_IMAGE') . http_build_query($requestParams);

        $logid = $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'generate_image', $request, $requestParams, array(), 0, 0);

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

        $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'generate_image', $request, $requestParams, $result, 0, $logid);

        $responseStatus = isset($result['ReturnStatus']) && !empty($result['ReturnStatus']) ? $result['ReturnStatus'] : '';
        if ($responseStatus == 'Success') {
            $base64_data = isset($result['Data']) && !empty($result['Data']) ? $result['Data'] : '';
            $bin = base64_decode($base64_data, true);

            if ($this->session->has_userdata('tp_api_id')) {
                $fileNumber = $this->session->userdata('tp_api_id');
            }
            if ($methodId == 4) {
                if (!is_dir('uploads/legal-vesting')) {
                    mkdir('./uploads/legal-vesting', 0777, true);
                }
                $pdfFilePath = './uploads/legal-vesting/' . $fileNumber . '.pdf';
                file_put_contents($pdfFilePath, $bin);
                $this->order->uploadDocumentOnAwsS3($fileNumber . '.pdf', 'legal-vesting');
            }
            if ($methodId == 3) {
                if (!is_dir('uploads/tax')) {
                    mkdir('./uploads/tax', 0777, true);
                }
                $pdfFilePath = './uploads/tax/' . $fileNumber . '.pdf';
                file_put_contents($pdfFilePath, $bin);
                $this->order->uploadDocumentOnAwsS3($fileNumber . '.pdf', 'tax');
            }
        }
        echo trim($file);
    }

    public function instrumentService()
    {
        $state = isset($_POST['state']) && !empty($_POST['state']) ? $_POST['state'] : '';
        $county = isset($_POST['county']) && !empty($_POST['county']) ? $_POST['county'] : '';
        $docId = isset($_POST['docId']) && !empty($_POST['docId']) ? $_POST['docId'] : '';
        $recDate = isset($_POST['recDate']) && !empty($_POST['recDate']) ? $_POST['recDate'] : '';

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
            'serviceType' => env('INSTRUMENT_SEARCH_SERVICE_TYPE'),
            'parameters' => 'Document.SearchType=Instrument;Document.RecordDate=' . $recDate . '; Document.InstrumentNumber=' . $docId . '',
            'state' => $state,
            'county' => $county,
        );

        $request = env('TP_TAX_INSTRUMENT_CREATE_SERVICE_ENDPOINT') . http_build_query($requestParams);

        $opts = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );
        $context = stream_context_create($opts);
        $file = file_get_contents($request, false, $context);

        echo trim($file);
    }

    public function generateGrantDeed()
    {
        $userdata = $this->session->userdata('user');
        $fips = isset($_POST['fips']) && !empty($_POST['fips']) ? $_POST['fips'] : '';
        $year = isset($_POST['year']) && !empty($_POST['year']) ? $_POST['year'] : '';
        $docId = isset($_POST['docId']) && !empty($_POST['docId']) ? $_POST['docId'] : '';
        $fileNumber = isset($_POST['fileNumber']) && !empty($_POST['fileNumber']) ? $_POST['fileNumber'] : '';

        $requestParams = array(
            'parameters' => 'FIPS=' . $fips . ',TYPE=REC,SUBTYPE=ALL,YEAR=' . $year . ',INST=' . $docId . '',
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
        $logid = $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'generate_grant_deed', $request, $requestParams, array(), 0, 0);

        $file = file_get_contents($request, false, $context);
        $xmlData = simplexml_load_string($file);
        $response = json_encode($xmlData);
        $result = json_decode($response, true);

        $this->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'generate_grant_deed', $request, $requestParams, $result, 0, $logid);

        $responseStatus = isset($result['Status']['Msg']) && !empty($result['Status']['Msg']) ? $result['Status']['Msg'] : '';
        $docStatus = isset($result['Documents']['DocumentResponse']['DocStatus']['Msg']) && !empty($result['Documents']['DocumentResponse']['DocStatus']['Msg']) ? $result['Documents']['DocumentResponse']['DocStatus']['Msg'] : '';
        $docStatus = strtolower($docStatus);

        if (isset($docStatus) && !empty($docStatus) && $docStatus == 'ok') {
            $base64_data = isset($result['Documents']['DocumentResponse']['Document']['Body']['Body']) && !empty($result['Documents']['DocumentResponse']['Document']['Body']['Body']) ? $result['Documents']['DocumentResponse']['Document']['Body']['Body'] : '';

            if (isset($base64_data) && !empty($base64_data)) {
                $bin = base64_decode($base64_data, true);

                if (!is_dir('uploads/grant-deed')) {
                    mkdir('./uploads/grant-deed', 0777, true);
                }
                $pdfFilePath = './uploads/grant-deed/' . $fileNumber . '.pdf';
                file_put_contents($pdfFilePath, $bin);
                $this->order->uploadDocumentOnAwsS3($fileNumber . '.pdf', 'grant-deed');
                $tpData = array(
                    'grant_deed_status' => $docStatus,
                    'grant_deed_message' => 'success',
                );
            } else {
                $tpData = array(
                    'grant_deed_status' => 'failed',
                    'grant_deed_message' => 'failed',
                );
            }

        } else {
            $tpData = array(
                'grant_deed_status' => 'Failed',
                'grant_deed_message' => $docStatus,
            );
        }

        if ($this->session->has_userdata('tp_api_id')) {
            $id = $this->session->userdata('tp_api_id');
            $condition = array(
                'id' => $id,
            );
            $this->titlePointData->update($tpData, $condition);

        } else {
            $tpId = $this->titlePointData->insert($tpData);

            if ($tpId) {
                $this->session->set_userdata('tp_api_id', $tpId);
            }
        }
        echo trim($file);
    }

    public function addLogs($methodId, $returnStatus, $status = '', $error, $random_number)
    {
        if ($returnStatus == 'Failed') {
            if ($methodId == 4) {
                $tpData = array(
                    'cs4_message' => $error,
                    'lv_file_status' => 'failed',
                );
            } elseif ($methodId == 3) {
                $tpData = array(
                    'cs3_message' => $error,
                    'tax_file_status' => 'failed',
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

        if ($this->session->has_userdata('tp_api_id_' . $random_number)) {
            $session_id = 'tp_api_id_' . $random_number;
            $condition = array(
                'session_id' => $session_id,
            );
            $this->titlePointData->update($tpData, $condition);
        } else {
            $tpData['session_id'] = 'tp_api_id_' . $random_number;

            $tpId = $this->titlePointData->insert($tpData);

            $this->session->set_userdata('tp_api_id_' . $random_number, 1);

        }

    }
}
