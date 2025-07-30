<?php

// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => '',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2TLS,
//   CURLOPT_CUSTOMREQUEST => 'POST',
//   CURLOPT_POSTFIELDS =>'{"clientId":"a7884552-0fb5-4638-a35c-e3a19ac4d242","secretKey":"e3b962f8-9eec-4ac0-a39f-d3600a4bb292"}',
//   CURLOPT_HTTPHEADER => array(
//     'Content-Type: application/json',
//     'Cookie: ApplicationGatewayAffinity=fad5af1966bfb1a40ad389b2926bd556569b7a1e9a8686cc312d530d855e278a; ApplicationGatewayAffinityCORS=fad5af1966bfb1a40ad389b2926bd556569b7a1e9a8686cc312d530d855e278a'
//   ),
// ));

// $response = curl_exec($curl);
// if (curl_errno($curl)) {
//   echo $error_msg = curl_error($curl);exit;
// }
// curl_close($curl);
// echo $response;

// $ch = curl_init('https://authtr.crestdemo.ml/api/FnfAuthIdentityProvider/GetToken');                                    
//         curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');                        
//         curl_setopt($ch, CURLOPT_POSTFIELDS, '{"clientId":"a7884552-0fb5-4638-a35c-e3a19ac4d242","secretKey":"e3b962f8-9eec-4ac0-a39f-d3600a4bb292"}');                   
//         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//         curl_setopt($ch, CURLOPT_TIMEOUT,0); 
//         curl_setopt($ch, CURLOPT_POST, true);
//         curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//           'Content-Type: application/json',
//           'Cookie: ApplicationGatewayAffinity=fad5af1966bfb1a40ad389b2926bd556569b7a1e9a8686cc312d530d855e278a; ApplicationGatewayAffinityCORS=fad5af1966bfb1a40ad389b2926bd556569b7a1e9a8686cc312d530d855e278a'
//         ));
//         $error_msg = curl_error($ch);
//         $result = curl_exec($ch);
//         if (curl_errno($ch)) {
//              echo $error_msg = curl_errno($ch)."---".curl_error($ch);exit;
//          }
//         echo $result;exit;

//         <?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://www.titlepoint.com/TitlePointServices/TpsService.asmx/CreateService3?userID=PCTXML01&password=AlphaOmega637%2523&orderNo=&customerRef=&company=&department=&titleOfficer=&orderComment=&starterRemarks=&serviceType=TitlePoint.Geo.Tax&parameters=Tax.APN%253D533-363-11-00%253BGeneral.AutoSearchTaxes%253Dtrue%253BGeneral.AutoSearchProperty%253Dfalse&state=CA&county=San+Diego',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'GET',
  CURLOPT_HTTPHEADER => array(
    'Cookie: TPPRDWebCookie=!4nSLjEEPd9dDz8bFv8D9noHjewGHvylN14gxouiiNwGy4ntuzh/CEqYVGbcwk2SnDsaBS7UqN8KHA0ZWxgy1juPIljOp04ahrmb4gnPI'
  ),
));

$error_msg = curl_error($curl);
$response = curl_exec($curl);
if (curl_errno($curl)) {
  echo $error_msg = "dsd--".curl_errno($curl)."---".curl_error($curl);
}
curl_close($curl);
echo $response."dfdf";


$requestParams = array(
  'userID' => 'PCTXML01',
  'password' => 'AlphaOmega637#',
  'orderNo' =>  '',
  'customerRef'=>  567467456743213,
  'company'=>  '',
  'department'=>  '',
  'titleOfficer'=>  '',
  'orderComment'=>  '',
  'starterRemarks'=>  '',
);

$requestParams['serviceType'] = 'TitlePoint.Geo.Tax';
			$requestParams['parameters'] = 'Tax.APN=533-363-11-00;General.AutoSearchTaxes=true;General.AutoSearchProperty=false';
			$requestParams['state'] = 'CA';
			$requestParams['county'] = 'San Diego';
			$requestUrl= 'https://www.titlepoint.com/TitlePointServices/TpsService.asmx/CreateService3?';
			$request_type= 'create_service_3';




      $request = $requestUrl.http_build_query($requestParams);

      
      $opts = array(
        "ssl"=>array(
              "verify_peer"=>false,
              "verify_peer_name"=>false,
          ),
      );
      $context = stream_context_create($opts);
      $file = file_get_contents($request,false,$context);
  
      $xmlData = simplexml_load_string($file);
      $response = json_encode($xmlData);
      $result = json_decode($response,TRUE);
      print_r($result);