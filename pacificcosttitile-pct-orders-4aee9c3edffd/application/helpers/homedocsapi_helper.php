<?php
if(!function_exists('call_homedocs_api')){
    function call_homedocs_api($data) {
        $api_base_url = 'http://dev.homedocs.io/';
        if(!empty(env('HOMEDOCS_URL'))) {
            $api_base_url = env('HOMEDOCS_URL');
        }
        $url = $api_base_url.'api/users';

        $ch = curl_init( $url );
        # Setup request to send json via POST.
        $payload = json_encode($data);
        // echo $payload;die;
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $payload );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Accept:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        if (curl_errno($ch)) { 
           return false; 
        } 

        // var_dump($result);die;
        curl_close($ch);
        
        return true;

    }

	function send_order_data($data) 
    {
		$api_base_url = 'http://dev.homedocs.io/';
		$api_token = 'ubya07bfi4agd4uih0qs';
        if(!empty(env('HOMEDOCS_URL'))) {
            $api_base_url = env('HOMEDOCS_URL');
        }
		if(!empty(env('HOMEDOCS_TOKEN'))) {
            $api_token = env('HOMEDOCS_TOKEN');
        }

        $url = $api_base_url.'api/store-property-detail';

        $ch = curl_init( $url );
        # Setup request to sendvia POST.
        
		$payload = "";
		foreach( $data as $key => $val ) {
			$payload .=$key."=".$val."&";
		}
		$payload = rtrim($payload, "&");

		$authorization = "Authorization: Bearer $api_token";
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Accept:application/json',$authorization));
        curl_setopt( $ch, CURLOPT_POSTFIELDS,  $payload );
        curl_setopt( $ch, CURLOPT_POST,1);
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        if (curl_errno($ch)) { 
           return false; 
        } 
        curl_close($ch);
		return ($result);
	}

	
}
