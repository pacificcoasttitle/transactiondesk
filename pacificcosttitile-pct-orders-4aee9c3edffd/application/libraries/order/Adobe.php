<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Adobe {

	private $api_access_point,$web_access_point,$api_version,$token,$curl_reponse;

	public function __construct()
	{
		$this->api_access_point = 'https://api.na3.adobesign.com/';
		$this->web_access_point = 'https://secure.na3.adobesign.com/';
		$this->api_version = 'v6';
		$this->token = getenv('ADOBE_SIGN_TOKEN');
		$this->curl_reponse = [
			'status' => TRUE,
			'result' => NULL,
			'error' => 'Error Occured whiile sending document please try again'
		];
	}

	public function send_request($params)
	{
		$ch = curl_init();
		$url = $this->api_access_point.$params['url'];
		$curl_headers = [
			'Authorization: Bearer '.$this->token
		];
		curl_setopt($ch, CURLOPT_URL,$url);
		if (strtoupper($params['request_type']) == 'POST') {
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($params['post_data']));
			if (strtoupper($params['data_type']) == 'JSON') {
				array_push($curl_headers,'Content-Type: application/json');
			}
		}
		curl_setopt($ch, CURLOPT_HTTPHEADER, $curl_headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$server_output = curl_exec($ch);
		$error = curl_error($ch);
		curl_close ($ch);
		if ($server_output) {
			$this->curl_reponse['result'] = $server_output;
			$this->curl_reponse['error'] = '';
		} else {
			$this->curl_reponse['status'] = FALSE;
			if ($error) {
				$this->curl_reponse['error'] = $error;
			}
		}
		return $this->curl_reponse;
	}
}