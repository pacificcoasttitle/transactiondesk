<html>
<body>
<form action="" method="post">
  APN:  &nbsp;&nbsp;&nbsp;&nbsp;        <input type="text" name="apn"/><br/><br/>
  County:  &nbsp;&nbsp;&nbsp;&nbsp;     <input type="text" name="county"/><br/><br/>
  Fips:  &nbsp;&nbsp;&nbsp;&nbsp;     <input type="text" name="fips"/><br/><br/>
  <input type="submit" name="SubmitButton"/><br/>
</form>
</body>
</html>

<?php

// echo "<pre>";
// print_r($_GET);die;
// $this->CI->load->library('order/titlepoint');

if (isset($_POST['SubmitButton'])) {

    $post = [
        'apn' => $_POST['apn'], // "445-013-05"
        'state' => "CA",
        'county' => $_POST['county'], //Orange
    ];
    $result = createService($post);
    echo '-----------------------------------------------------------------------';
    echo '<br/><br/><br/>';

    // print_r($result);
    // echo '-----------------------------------------------------------------------';
    // echo '<br/><br/><br/>';
    $responseStatus = isset($result['ReturnStatus']) && !empty($result['ReturnStatus']) ? $result['ReturnStatus'] : '';
    if ($responseStatus != 'Success') {
        echo "Created service response failed";die;
    }
    $requestId = $result['RequestID'];
    $fips = $_POST['fips'];
    // Get Summary
    $result = getSummury($requestId);
    echo '------------------------getSummury-----------------------------------------------';
    echo '<br/><br/><br/>';

    // print_r($result);
    // echo '-----------------------------------------------------------------------';
    echo '<br/><br/><br/>';
    $responseStatus = isset($result['ReturnStatus']) && !empty($result['ReturnStatus']) ? $result['ReturnStatus'] : '';
    // print_r($responseStatus);die;
    if ($responseStatus != 'Success') {
        echo "Get summary response failed";die;
    }

    $resultId = isset($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail']['ID']) && !empty($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail']['ID']) ? $result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail']['ID'] : '';
    $serviceId = isset($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID']) && !empty($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID']) ? $result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID'] : '';
    $requestSummary = $result['RequestSummaries']['RequestSummary']['Order']['Services']['Service'];
    $count = 0;
    if (count($requestSummary) == 2) {
        foreach ($requestSummary as $service) {
            //echo "here";
            //print_r($service);
            $thumbnail = $service['ThumbNails']['ResultThumbNail'];
            //print_r($thumbnail);exit;
            $lineNum = $thumbnail['Highlights']['string'][2];
            $lineNumArr = explode("=", $lineNum);
            if ($lineNumArr[1] >= $count) {
                $count = $lineNumArr[1];
                $resultId = $thumbnail['ID'];
                $serviceId = $service['ID'];
            }
        }
    } else {
        $thumbnail = $requestSummary['ThumbNails']['ResultThumbNail'];
        //print_r($thumbnail);exit;
        $lineNum = $thumbnail['Highlights']['string'][2];
        $lineNumArr = explode("=", $lineNum);
        if ($lineNumArr[1] > $count) {
            $count = $lineNumArr[1];
        } else {

        }
        $resultId = $thumbnail['ID'];
        $serviceId = $requestSummary['ID'];
    }

    echo 'resultid --------';
    print_r($resultId);
    echo 'serviceId --------';
    print_r($serviceId);
    generateGeoDocument($resultId, $fips);

}
// Create Service

function generateGeoDocument($resultId, $fips)
{
    $requestParams = array(
        'userID' => 'PCTXML01',
        'password' => "AlphaOmega637#",
        'company' => '',
        'department' => '',
        'titleOfficer' => '',
        'requestingTPXML' => "true",
        'resultID' => $resultId,
    );

    $requestUrl = "https://www.titlepoint.com/TitlePointServices/TpsService.asmx/GetResultByID3?";
    $requestUrl = $requestUrl . http_build_query($requestParams);
    echo '------------------------get result by id-----------------------------------------------';
    echo date('Y-m-d H:i:s') . ' <br/><b>GenerateGeoDocument Url: </b><br/>' . $requestUrl . '<br/><br/>';
    $res = curlPost($requestUrl, $requestParams);
    $result = json_decode($res, true);

    echo "<pre>";
    echo '<br/><br/><br/>';

    /** Save document records here Start*/
    $recordArray = [];
    $i = 0;
    $j = 0;
    $k = 0;
    if ((strtolower($result['ReturnStatus']) == 'success')) {
        $result = $result['Result']['DocumentList'];
        $addressIds = isset($result['Addresses']['Address']) ? array_column($result['Addresses']['Address'], 'Id') : [];
        $documentIdentifications = $result['DocumentIdentifications']['DocumentIdentification'];
        $images = $result['Images']['Item'];
        $items = $result['Items'];

        if (isset($items['Item'])) {
            /** Start All instrument number details fetched */
            foreach ($items['Item'] as $key => $val) {

                $id = $val['DocumentIdentification'];
                if (isset($id['@attributes']['Id'])) {
                    $docId = $id['@attributes']['Id'];
                    $key = array_search($docId, array_column($documentIdentifications, 'Id'));
                    $filterNoticeExistKey = '';
                    $filterExistKey = '';
                    if (isset($documentIdentifications[$key]) && (!empty($documentIdentifications[$key]['InstrumentNumber']) || strtolower($val['DocumentType']) == 'tdd')) {
                        $recordArray[$i]['instrument'] = isset($documentIdentifications[$key]['InstrumentNumber']) ? $documentIdentifications[$key]['InstrumentNumber'] : null;
                        $recordArray[$i]['recorded_date'] = $documentIdentifications[$key]['RecordingDate'];
                        $recordArray[$i]['type'] = $images[$key]['Type'];
                        $recordArray[$i]['sub_type'] = $images[$key]['SubType'];
                        $recordArray[$i]['order_number'] = isset($images[$key]['OrderNumber']) ? $images[$key]['OrderNumber'] : null;
                        $recordArray[$i]['document_name'] = $val['DocumentFullName'];
                        $recordArray[$i]['document_type'] = $val['DocumentType'];
                        $recordArray[$i]['document_sub_type'] = null;
                        $recordArray[$i]['coupling'] = isset($val['CouplingIndicatorAll']) ? $val['CouplingIndicatorAll'] : null;
                        $recordArray[$i]['remarks'] = isset($val['PropertyRemark']) ? $val['PropertyRemark'] : null;
                        $recordArray[$i]['color_coding'] = isset($val['ColorCoding']) ? $val['ColorCoding'] : null;
                        $recordArray[$i]['icon_text'] = isset($val['ChainIconName']) ? $val['ChainIconName'] : null;
                        $recordArray[$i]['loan_amount'] = isset($val['LoanAmount']) ? $val['LoanAmount'] : null;
                        $recordArray[$i]['created_at'] = date("Y-m-d H:i:s");
                        $i++;
                    }
                }
            }
            usort($recordArray, 'compareDates');
            echo "<pre>";
            // print_r($recordArray[0]);die;
            // print_r($recordArray);die;
        }
    }
    /** Save document records here end*/
    // $this->CI->apiLogs->syncLogs($userdata['id'], 'titlepoint', 'generate_geo_document', $request, $requestParams, $result, $orderId, $logid);

    // return $response;
    echo 'Latest Instrument and recorded date: ' . $recordArray[0]['instrument'] . ' ----- Date: ' . $recordArray[0]['recorded_date'];
    echo '<br/> Document Name: ' . $recordArray[0]['document_name'] . ' ------------ Document type: ' . $recordArray[0]['document_type'];
    generateGrantDeed($recordArray[1]['instrument'], $recordArray[1]['recorded_date'], $fips);
    print_r($recordArray);die;

}

function generateGrantDeed($docId, $recordedDate, $fips)
{
    if (isset($recordedDate) && !empty($recordedDate)) {
        $time = strtotime($recordedDate);
        $year = date('Y', $time);
    }
    // if (isset($instrumentNumber) && !empty($instrumentNumber)) {
    //     if (isset($recordedDate) && !empty($recordedDate)) {
    //         $time = strtotime($recordedDate);
    //         $year = date('Y', $time);
    //     }
    //     $count = substr_count($instrumentNumber, '-');

    //     if (isset($count) && !empty($count)) {
    //         $detailDocInfo = explode('-', $instrumentNumber);

    //         $docId = isset($detailDocInfo['1']) && !empty($detailDocInfo['1']) ? $detailDocInfo['1'] : '';
    //     } else {
    //         $docId = str_replace($year, '', $instrumentNumber);
    //     }

    //     $docId = (string) ((int) ($docId));
    // }

    $requestParams = array(
        'parameters' => 'FIPS=' . $fips . ',TYPE=REC,SUBTYPE=ALL,YEAR=' . $year . ',INST=' . $docId . '',
        'username' => 'PCTXML01',
        'password' => "AlphaOmega637#",
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

    $requestUrl = 'https://www.titlepoint.com/titlepointservices/TpsImage.asmx/GetDocumentsByParameters3?' . http_build_query($requestParams);

    // $opts = array(
    //     "ssl" => array(
    //         "verify_peer" => false,
    //         "verify_peer_name" => false,
    //     ),
    // );

    // $context = stream_context_create($opts);

    // $requestUrl = env('GRANT_DEED_ENDPOINT');
    $response = curlPost($requestUrl, $requestParams);
    $result = json_decode($response, true);
    echo "<br/> <pre>";
    print_r($result);
    // die;
    $responseStatus = isset($result['Status']['Msg']) && !empty($result['Status']['Msg']) ? $result['Status']['Msg'] : '';

    $docStatus = isset($result['Documents']['DocumentResponse']['DocStatus']['Msg']) && !empty($result['Documents']['DocumentResponse']['DocStatus']['Msg']) ? $result['Documents']['DocumentResponse']['DocStatus']['Msg'] : '';
    $docStatus = strtolower($docStatus);
    if (isset($docStatus) && !empty($docStatus) && $docStatus == 'ok') {
        $base64_data = isset($result['Documents']['DocumentResponse']['Document']['Body']['Body']) && !empty($result['Documents']['DocumentResponse']['Document']['Body']['Body']) ? $result['Documents']['DocumentResponse']['Document']['Body']['Body'] : '';

    }

}

// Generate tax image request (Final response)
function compareDates($a, $b)
{
    $dateA = strtotime($a['recorded_date']);
    $dateB = strtotime($b['recorded_date']);

    if ($dateA == $dateB) {
        return 0;
    } elseif ($dateA > $dateB) {
        return -1;
    } else {
        return 1;
    }
}

function getSummury($requestId)
{
    $requestParams = array(
        'userID' => 'PCTXML01',
        'password' => 'AlphaOmega637#',
        'company' => '',
        'department' => '',
        'titleOfficer' => '',
        'requestId' => $requestId,
        'maxWaitSeconds' => 20,
    );

    $requestUrl = 'https://www.titlepoint.com/TitlePointServices/TpsService.asmx/GetRequestSummaries?';
    $requestUrl = $requestUrl . http_build_query($requestParams);
    echo date('Y-m-d H:i:s') . ' <br/><b>Get Request Summary Request Url:</b> <br/>' . $requestUrl . '<br/><br/>';
    $res = curlPost($requestUrl, $requestParams);
    $response = json_decode($res, true);
    // echo date('Y-m-d H:i:s') . ' <br/><b>Response:</b> </br>' . $res . '<br/><br/><br/>';

    return $response;
}
function createService($post)
{
    $requestParams = array(
        'userID' => 'PCTXML01',
        'password' => 'AlphaOmega637#',
        'department' => '',
        'orderNo' => '',
        'customerRef' => '',
        'company' => '',
        'titleOfficer' => '',
        'orderComment' => '',
        'starterRemarks' => '',
    );
    $apn = isset($post['apn']) && !empty($post['apn']) ? $post['apn'] : '';
    $apn = str_replace('0000', '0-000', $apn);
    $state = isset($post['state']) && !empty($post['state']) ? $post['state'] : 'CA';
    $county = isset($post['county']) && !empty($post['county']) ? $post['county'] : '';
    $requestParams['serviceType'] = 'TitlePoint.Geo.Address';
    // $requestParams['parameters'] = 'Tax.APN=' . $apn . ';General.AutoSearchTaxes=true;General.AutoSearchProperty=false';
    $requestParams['parameters'] = 'Tax.APN=' . $apn . ';IncludeReferenceDocs=True;General.AutoSearchProperty=True;General.AutoSearchTaxes=True;Property.IntelligentPropertyGrouping=True;Property.IncludeReferenceDocs=TrueGeneral.AutoSearchTaxes=True;Tax.CurrentYearTaxesOnly=True;';

    $requestParams['state'] = $state;
    $requestParams['county'] = $county;
    $requestUrl = 'https://www.titlepoint.com/TitlePointServices/TpsService.asmx/CreateService3?';
    // $request_type = 'create_service_3';

    $requestUrl = $requestUrl . http_build_query($requestParams);
    echo date('Y-m-d H:i:s') . ' <br/><b>Create Service Request Url: </b><br/>' . $requestUrl . '<br/><br/>';
    $res = curlPost($requestUrl, $requestParams);
    $response = json_decode($res, true);
    echo date('Y-m-d H:i:s') . ' <br/><b> Response: </b>' . $res . '<br/><br/><br/>';
    return $response;

}

function curlPost($end_point, $requestParams)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $end_point,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
    ));
    $response = curl_exec($curl);
    // print_r('$response ===', $response);
    if (curl_errno($curl)) {
        echo $error_msg = curl_error($curl);exit;
    }
    curl_close($curl);
    $xmlData = simplexml_load_string($response);
    return json_encode($xmlData);
    //echo $response;
}
