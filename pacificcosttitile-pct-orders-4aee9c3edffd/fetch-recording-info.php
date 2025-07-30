<?php

	$api_token = 'testPCTGtoken';
	$url = 'http://50.28.52.207/api/order/recorded?api_token='.$api_token.'&date=2019-11-19';

	$curl = curl_init();

    $curl_options =[
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "cache-control: no-cache",
            "Content-Type: application/json",
        ],
    ];
            
    curl_setopt_array($curl, $curl_options);

    $response = curl_exec($curl);
    $error = curl_error($curl);

    curl_close($curl);
	if ($error) 
	{
        echo json_encode(['result'=>'fail', 'error'=>$error]); exit;
    }
    else
    {
    	$response = json_decode($response,true);
    	$records = array();

    	if(isset($response) && !empty($response))
    	{
    		foreach ($response as $key => $value) 
    		{
    			if(isset($value['documents']) && !empty($value['documents']))
    			{
    				foreach ($value['documents'] as $k => $v) 
    				{
    					$date = strtotime($v['recordingTime']);
						$recording_date = date('m/d/Y H:i:s', $date);
    					$records[] = array('date'=>$recording_date,'instrument_no'=>$v['instrumentNumber'], 'order_no'=>$value['orderNumber']);
    				}
    			}
    		}
    	}
    	echo json_encode(['result'=>'success', 'data'=>$records]); exit;
    }
?>