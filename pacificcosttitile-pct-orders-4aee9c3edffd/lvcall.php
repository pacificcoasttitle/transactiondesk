<html>
<body>
<form action="" method="post">
  APN:  &nbsp;&nbsp;&nbsp;&nbsp;        <input type="text" name="apn" placeholder="7012-017-018" /><br/><br/>
  City:  &nbsp;&nbsp;&nbsp;&nbsp;     <input type="text" name="city" placeholder="Cerritos" /><br/><br/>
  County:  &nbsp;&nbsp;&nbsp;&nbsp;     <input type="text" name="county" placeholder="Los Angeles" /><br/><br/>
  fips:  &nbsp;&nbsp;&nbsp;&nbsp;     <input type="text" name="fips" placeholder="06037" /><br/><br/>

  <input type="submit" name="SubmitButton"/><br/>
</form>
</body>
</html>

<?php

// echo "<pre>";
// print_r($_GET);die;

if (isset($_POST['SubmitButton'])) {

    $post = [
        'apn' => $_POST['apn'], // "445-013-05"
        'state' => "CA",
        'city' => $_POST['city'], //Orange
        'fips' => $_POST['fips'], //Orange
        'county' => $_POST['county'], //Orange
    ];
    $fips = $_POST['fips'];
    $result = createService($post);
    $responseStatus = isset($result['ReturnStatus']) && !empty($result['ReturnStatus']) ? $result['ReturnStatus'] : '';
    if ($responseStatus != 'Success') {
        echo "Created service response failed";die;
    }
    $requestId = $result['RequestID'];

    // Get Summary
    $result = getSummury($requestId);
    $responseStatus = isset($result['ReturnStatus']) && !empty($result['ReturnStatus']) ? $result['ReturnStatus'] : '';
    if ($responseStatus != 'Success') {
        echo "Get summary response failed";die;
    }
    echo '----------------------------------------------- <br/><pre>';
    print_r($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail']);
    echo '-----------------------------------------------';
    $resultId = isset($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail'][0]['ID']) && !empty($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail'][0]['ID']) ? $result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail'][0]['ID'] : '';
    $serviceId = isset($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID']) && !empty($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID']) ? $result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID'] : '';
    echo '------------- result id :' . $resultId[0];
    echo '<br/>';
    generateLVImage($resultId, $fips);
    // Create tax image request
    // $requestParams = array(
    //     'username' => "PCTXML01",
    //     'password' => "AlphaOmega637#",
    //     'serviceId1' => $serviceId,
    //     'serviceId2' => '',
    //     'source' => '',
    //     'clientKey1' => '',
    //     'clientKey2' => '',
    //     'sortOrder' => '',
    //     'fileType' => 'tiff',
    // );
    // $requestUrl = 'https://www.titlepoint.com/TitlePointServices/tpsgenerateimage.asmx/CreateRequest4?';
    // $requestUrl = $requestUrl . http_build_query($requestParams);
    // echo date('Y-m-d H:i:s') . ' <br/><b>Image Create Service Request Url: </b><br/>' . $requestUrl . '<br/><br/>';

    // $response = curlPost($requestUrl, $requestParams);
    // $result = json_decode($response, true);
    // echo date('Y-m-d H:i:s') . ' <br/><b>Response: </b><br/>' . $response . '<br/><br/><br/>';
    // sleep(8);
} else {
    if (isset($result['RequestID']) && !empty($result['RequestID'])) {
        echo ' Create tax image request failed';die;
    }
}
// Create Service

// Generate tax image request (Final response)
function generateLVImage($resultId, $fips)
{
    $requestParams = array(
        'userID' => 'PCTXML01',
        'password' => "AlphaOmega637#",
        'company' => '',
        'department' => '',
        'titleOfficer' => '',
        'resultID' => $resultId,
    );

    $requestUrl = "https://www.titlepoint.com/TitlePointServices/TpsService.asmx/GetResultByID?";
    $requestUrl = $requestUrl . http_build_query($requestParams);
    echo "<b>Executed 1 Times</b> -> ";
    echo date('Y-m-d H:i:s') . ' <br/><b>Generate LV Request Url: </b><br/>' . $requestUrl . '<br/><br/>';
    $res = curlPost($requestUrl, $requestParams);
    $result = json_decode($res, true);
    echo date('Y-m-d H:i:s') . ' <br/><b>Get Result By id Response: </b><br/><pre>';
    print_r($res);
    print_r($result);
    echo '<br/><br/><br/>';

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
            $instruNumber = isset($value['InstrumentNumber']) && !empty($value['InstrumentNumber']) ? $value['InstrumentNumber'] : '';
            if (!empty($instruNumber) && ($docType == 'grant deed' || $docType == 'intrafamily transfer & dissolution' || $docType == 'quit claim deed' || $docType == 'intra-family transfer or dissolution')) {
                $instrumentNumber = isset($value['InstrumentNumber']) && !empty($value['InstrumentNumber']) ? $value['InstrumentNumber'] : '';
                $recordedDate = isset($value['RecordedDate']) && !empty($value['RecordedDate']) ? $value['RecordedDate'] : '';
                break;
            }
        }
    }
    echo ' <br/><b>Recorded Date: </b><br/>' . $recordedDate . '<br/><br/><br/>';
    echo ' <br/><b>Instrument: </b><br/>' . $instrumentNumber . '<br/><br/><br/>';
    generateGrantDeed($instrumentNumber, $recordedDate, $fips);
}

function generateGrantDeed($instrumentNumber, $recordedDate, $fips)
{
    // if (isset($recordedDate) && !empty($recordedDate)) {
    //     $time = strtotime($recordedDate);
    //     $year = date('Y', $time);
    // }
    if (isset($instrumentNumber) && !empty($instrumentNumber)) {
        if (isset($recordedDate) && !empty($recordedDate)) {
            $time = strtotime($recordedDate);
            $year = date('Y', $time);
        }
        $count = substr_count($instrumentNumber, '-');

        if (isset($count) && !empty($count)) {
            $detailDocInfo = explode('-', $instrumentNumber);

            $docId = isset($detailDocInfo['1']) && !empty($detailDocInfo['1']) ? $detailDocInfo['1'] : '';
        } else {
            $docId = str_replace($year, '', $instrumentNumber);
        }

        $docId = (string) ((int) ($docId));
    }

    echo '<br /> Instrument number: ' . $docId . ' Year: ' . $year;
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
    echo date('Y-m-d H:i:s') . ' <br/><b>Response:</b> </br>' . $res . '<br/><br/><br/>';
    // echo '<pre>summury res ==';
    // print_r(json_decode($res));die;
    return $response;
}

function createService($post)
{
    $unitinfo = '';
    $city = '';
    $address = '';

    $requestParams = array(
        'userID' => 'PCTXML01',
        'password' => 'AlphaOmega637#',
        'orderNo' => '',
        'customerRef' => rand(),
        'company' => '',
        'department' => '',
        'titleOfficer' => '',
        'orderComment' => '',
        'starterRemarks' => '',
        'serviceType' => 'TitlePoint.LegalAndVesting2',
    );
    $apn = isset($post['apn']) && !empty($post['apn']) ? $post['apn'] : '';
    $apn = str_replace('0000', '0-000', $apn);
    $state = isset($post['state']) && !empty($post['state']) ? $post['state'] : '';
    $city = isset($post['city']) && !empty($post['city']) ? $post['city'] : '';
    $county = isset($post['county']) && !empty($post['county']) ? $post['county'] : '';
    $fipsCode = isset($post['fips']) && !empty($post['fips']) ? $post['fips'] : '';
    // $requestParams['serviceType'] = ;
    // $requestParams['parameters'] = 'Tax.APN=' . $apn . ';General.AutoSearchTaxes=true;General.AutoSearchProperty=false';
    $requestParams['parameters'] = 'Pin=' . $apn . ';LvLookup=Address;LvLookupValue=' . $address . ', ' . $unitinfo . $city . ';LvReportFormat=LV;IncludeTaxAssessor=true';

    $requestParams['state'] = $state;
    $requestParams['county'] = $county;
    $requestParams['fipsCode'] = $fipsCode;
    $requestUrl = 'https://www.titlepoint.com/TitlePointServices/TpsService.asmx/CreateService4?';
    $request_type = 'create_service_4';

    $requestUrl = $requestUrl . http_build_query($requestParams);
    echo date('Y-m-d H:i:s') . ' <br/><b>Create Service Request Url: </b><br/>' . $requestUrl . '<br/><br/>';
    $res = curlPost($requestUrl, $requestParams);
    $response = json_decode($res, true);
    echo date('Y-m-d H:i:s') . ' <br/><b> Response: </b>' . $res . '<br/><br/><br/>';
    return $response;
    // echo "<pre>";
    // print_r(json_decode($res));die;

}

function curlPost($end_point, $requestParams)
{
    /*$post_array_string = '';
    foreach ($requestParams as $key => $value) {
    $post_array_string .= $key . '=' . $value . '&';
    }
    $ch = curl_init($end_point);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_array_string);
    $res = curl_exec($ch);
    if (curl_errno($ch)) {
    $error_msg = curl_error($ch);
    echo 'Error message ==' . $error_msg;die;
    }
    curl_close($ch);
    $xmlData = simplexml_load_string($res);
    return json_encode($xmlData);*/

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

    if (curl_errno($curl)) {
        echo $error_msg = curl_error($curl);exit;
    }
    curl_close($curl);
    $xmlData = simplexml_load_string($response);
    return json_encode($xmlData);
    //echo $response;
}

// function curlGet($url, $requestParams)
// {
//     $post_array_string = '';
//     $i                 = 0;
//     foreach ($requestParams as $key => $value) {
//         $i++;
//         $post_array_string .= $key . '=' . $value;
//         if ($i != (count($requestParams))) {
//             $post_array_string .= '&';
//         }
//     }
//     $endUrl = $url . $post_array_string;
//     print_r($endUrl);
//     echo "<br>";
//     $endUrl = "https://www.titlepoint.com/TitlePointServices/TpsService.asmx/CreateService3?userID=PCTXML01&password=AlphaOmega637#&orderNo=&customerRef=&company=&department=&titleOfficer=&orderComment=&starterRemarks=&serviceType=TitlePoint.Geo.Address&parameters=Address.FullAddress=1358 5th St;General.AutoSearchTaxes=False;General.AutoSearchProperty=True&state=CA&county=Los Angeles";
//     print_r($endUrl);
//     $crl = curl_init();
//     curl_setopt($crl, CURLOPT_URL, $endUrl);
//     curl_setopt($crl, CURLOPT_FRESH_CONNECT, true);
//     curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

//     $response = curl_exec($crl);

//     if (curl_errno($crl)) {
//         $error_msg = curl_error($crl);
//         echo 'Eror ==';
//         print_r($error_msg);die;
//     }
//     echo '<br>response ===';
//     print_r($response);die;
//     curl_close($crl);
//     $xmlData = simplexml_load_string($response);
//     print_r($xmlData);die;
//     return json_encode($xmlData);
//     // $result = json_decode($response,TRUE);
//     // return $result;
// }
