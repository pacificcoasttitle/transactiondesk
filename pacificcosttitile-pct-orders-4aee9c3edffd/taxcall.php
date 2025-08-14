<html>
<body>    
<form action="" method="post">
  APN:  &nbsp;&nbsp;&nbsp;&nbsp;        <input type="text" name="apn"/><br/><br/>
  County:  &nbsp;&nbsp;&nbsp;&nbsp;     <input type="text" name="county"/><br/><br/>
  <input type="submit" name="SubmitButton"/><br/>
</form>    
</body>
</html>

<?php

// echo "<pre>";
// print_r($_GET);die;

if(isset($_POST['SubmitButton'])) {

    $post = [
        'apn'    => $_POST['apn'], // "445-013-05"
        'state'  => "CA",
        'county' => $_POST['county'], //Orange
    ];
    $result         = createService($post);
    $responseStatus = isset($result['ReturnStatus']) && !empty($result['ReturnStatus']) ? $result['ReturnStatus'] : '';
    if ($responseStatus != 'Success') {
        echo "Created service response failed";die;
    }
    $requestId = $result['RequestID'];
    
    // Get Summary
    $result         = getSummury($requestId);
    $responseStatus = isset($result['ReturnStatus']) && !empty($result['ReturnStatus']) ? $result['ReturnStatus'] : '';
    if ($responseStatus != 'Success') {
        echo "Get summary response failed";die;
    }
    
    $resultId  = isset($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail']['ID']) && !empty($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail']['ID']) ? $result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ThumbNails']['ResultThumbNail']['ID'] : '';
    $serviceId = isset($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID']) && !empty($result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID']) ? $result['RequestSummaries']['RequestSummary']['Order']['Services']['Service']['ID'] : '';
    
    // Create tax image request
    $requestParams = array(
        'username'   => "PCTXML01",
        'password'   => getenv('TP_PASSWORD') ?: $_ENV['TP_PASSWORD'] ?? '',
        'serviceId1' => $serviceId,
        'serviceId2' => '',
        'source'     => '',
        'clientKey1' => '',
        'clientKey2' => '',
        'sortOrder'  => '',
        'fileType'   => 'tiff',
    );
    $requestUrl = 'https://www.titlepoint.com/TitlePointServices/tpsgenerateimage.asmx/CreateRequest3?';
    $requestUrl = $requestUrl.http_build_query($requestParams);
    echo date('Y-m-d H:i:s') . ' <br/><b>Image Create Service Request Url: </b><br/>' . $requestUrl . '<br/><br/>';
    
    $response = curlPost($requestUrl, $requestParams);
    $result   = json_decode($response, true);
    echo date('Y-m-d H:i:s') . ' <br/><b>Response: </b><br/>' . $response . '<br/><br/><br/>';
    if (isset($result['RequestID']) && !empty($result['RequestID'])) {
        sleep(8);
        generateTaxImage($result['RequestID']);
    } else {
        echo ' Create tax image request failed';die;
    }
}
// Create Service

// Generate tax image request (Final response)
function generateTaxImage($requestId, $i = 1)
{
    // print_r($i);die;
    $requestParams = array(
        'username'  => 'PCTXML01',
        'password'  => getenv('TP_PASSWORD') ?: $_ENV['TP_PASSWORD'] ?? '',
        'requestId' => $requestId,
    );

    $requestUrl = "https://www.titlepoint.com/titlepointservices/TpsGenerateImage.asmx/GetGeneratedImage?";
	$requestUrl = $requestUrl.http_build_query($requestParams);
    echo "<b>Executed $i Times</b> -> ";
    echo date('Y-m-d H:i:s') . ' <br/><b>Generate Tax Image Request Url: </b><br/>' . $requestUrl . '<br/><br/>';
    $res        = curlPost($requestUrl, $requestParams);
    echo date('Y-m-d H:i:s') . ' <br/><b>Response: </b><br/>' . $res . '<br/><br/><br/>';
    $res        = json_decode($res, true);
    if ($res['Status'] == 'Processing' && $i < 10) {
        sleep(3);
        
        echo "<br>";
        $i++;
        generateTaxImage($requestId, $i);
    }
    // echo "<pre> Last API (Generate tax image) Response ==";
    // print_r($res);
}

function getSummury($requestId)
{
    $requestParams = array(
        'userID'         => 'PCTXML01',
        'password'       => getenv('TP_PASSWORD') ?: $_ENV['TP_PASSWORD'] ?? '',
        'company'        => '',
        'department'     => '',
        'titleOfficer'   => '',
        'requestId'      => $requestId,
        'maxWaitSeconds' => 20,
    );

    $requestUrl = 'https://www.titlepoint.com/TitlePointServices/TpsService.asmx/GetRequestSummaries?';
	$requestUrl = $requestUrl.http_build_query($requestParams);
    echo date('Y-m-d H:i:s') . ' <br/><b>Get Request Summary Request Url:</b> <br/>' . $requestUrl . '<br/><br/>';
    $res        = curlPost($requestUrl, $requestParams);
    $response = json_decode($res, true);
    echo date('Y-m-d H:i:s') . ' <br/><b>Response:</b> </br>' . $res . '<br/><br/><br/>';
    // echo '<pre>summury res ==';
    // print_r(json_decode($res));die;
    return $response;
}
function createService($post)
{
    $requestParams = array(
        'userID'         => 'PCTXML01',
        'password'       => getenv('TP_PASSWORD') ?: $_ENV['TP_PASSWORD'] ?? '',
        'customerRef'    => 567467456743213,
        'company'        => '',
        'orderNo'        => '',
        'department'     => '',
        'titleOfficer'   => '',
        'orderComment'   => '',
        'starterRemarks' => '',
    );
    $apn                          = isset($post['apn']) && !empty($post['apn']) ? $post['apn'] : '';
    $apn                          = str_replace('0000', '0-000', $apn);
    $state                        = isset($post['state']) && !empty($post['state']) ? $post['state'] : '';
    $county                       = isset($post['county']) && !empty($post['county']) ? $post['county'] : '';
    $requestParams['serviceType'] = 'TitlePoint.Geo.Tax';
    $requestParams['parameters']  = 'Tax.APN=' . $apn . ';General.AutoSearchTaxes=true;General.AutoSearchProperty=false';
    $requestParams['state']       = $state;
    $requestParams['county']      = $county;
    $requestUrl                   = 'https://www.titlepoint.com/TitlePointServices/TpsService.asmx/CreateService3?';
    $request_type                 = 'create_service_3';

    $requestUrl = $requestUrl.http_build_query($requestParams);
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
